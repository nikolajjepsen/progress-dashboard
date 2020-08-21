<?php
namespace Progress\Contacts\Utility;

require_once(__DIR__ . '/../../../../vendor/autoload.php');

use \PDO;
use \Progress\Db\Database;

use \ZipArchive;

use ParseCsv\Csv;
use Tracy\Debugger;

class Export
{

        /**
     * The database handler
     * @var object
     */
    private $dbh;

    /**
     * The statement handler
     * @var object
     */
    private $sth;

	private $processingId;

    // ExportQueue class object
    private $queue;

    public function __construct()
    {
        $this->dbh = Database::get();
		$this->queue = new ExportQueue;

        Debugger::$logSeverity = E_NOTICE | E_WARNING;
        Debugger::enable(Debugger::DETECT, __DIR__ . '/../../../log');
	}

	public function generateQueryFromOptions($options, $type = 'SELECT', $limit = false) {
		// Generation of where clauses. 
		// TODO: Make it possible to select if any field is required during export.
		$queryWhereClausesAppend = [];
		if ($options['data']['require_email'] == 'yes') {
			$queryWhereClausesAppend[] = 'AND `email` IS NOT NULL';
		}

		if ($options['data']['require_mobile'] == 'yes') {
			$queryWhereClausesAppend[] = 'AND `mobile` IS NOT NULL';
		}
		// Generation of where clauses based on mobile number validation level if selected.
		if ($options['data']['validation_level'] != 1) {
			if ($options['data']['validation_level'] == 2) {
				$queryWhereClausesAppend[] = 'AND `status` = \'syntax\' OR `status` = \'hlr\'';
			} elseif ($options['data']['validation_level'] == 3) {
				$queryWhereClausesAppend[] = 'AND `status` = \'hlr\'';
			} elseif ($options['data']['validation_level'] == 4) {
				$queryWhereClausesAppend[] = 'AND `status` = \'syntax\'';
			} elseif ($options['data']['validation_level'] == 5) {
				$queryWhereClausesAppend[] = 'AND `status` = \'invalid\'';
			}
		}

		// Determine type of query. 
		if ($type == 'SELECT') {
			$querySelectClauses = implode(', ', $options['data']['fields']);
		} else {
			$querySelectClauses = 'COUNT(`id`) as `recordsCount`';
		}

		// Append query limits based on chunck size during export.
		if ($limit) {
			$queryLimit = ' LIMIT ' . $limit['offset'] . ', ' . $limit['size'];
		}

		try {
			$this->sth = $this->dbh->prepare("SELECT ". $querySelectClauses ."
				FROM 
					`data_contacts` 
				WHERE 
					`pid` = :providerId AND
					`collection_year` = :providerYear AND
					`cid` = :providerCountryId
					" . implode(' ', $queryWhereClausesAppend) . $queryLimit);
			$this->sth->execute([
				':providerId' => $options['provider']['id'],
				':providerYear' => $options['provider']['year'],
				':providerCountryId' => $options['provider']['cid']
			]);

			if ($type == 'SELECT') {
				if ($results = $this->sth->fetchAll(PDO::FETCH_ASSOC)) {
					return $results;
				}
			} else {
				if ($count = $this->sth->fetch(PDO::FETCH_OBJ)->recordsCount) {
					return $count;
				}
			}

			return false;

		} catch (Exception $e) {
			echo $e->getMessage();
			return false;
		}
		
		return false;
	}

	public function addRecordToFile($file, $record, $delimiter) {
		$fp = fopen($file, 'a');
		fputcsv($fp, $record, $delimiter);
		fclose($fp);
	}

	public function process() {
		if (!$processing = $this->queue->getNextInQueue()) {
			return false;
		}

		$options = json_decode($processing->options, true);
		$fileOptions = $options['file'];

		$this->processingId = $processing->id;

		$relativeBaseFilePath = __DIR__ . '/../../../files/export/';

		$totalRecords = $processing->total;

		if ($fileOptions['chunck_size'] == 0 || empty($fileOptions['chunck_size'])) {
			$fileOptions['chunck_size'] = $totalRecords;
		}

		for ($offset = 1; ($offset <= $totalRecords / $fileOptions['chunck_size']) || $offset == 250; $offset++) {
			$currentFile = $relativeBaseFilePath . $processing->file . '_' . $offset . '.csv';

			$contacts = $this->generateQueryFromOptions($options, 'SELECT', 
				[
					'offset' => ($offset == 1) ? 0 : $offset * $fileOptions['chunck_size'],
					'size' => $fileOptions['chunck_size']
				]
			);
			// Create a file pointer (and file if not existing) and append to file.
			$filePointer = fopen($currentFile, 'a');

			// Add headers to file
			fputcsv($filePointer, array_keys($contacts[0]), $fileOptions['delimiter']);

			// Loop through the contacts add append the contact to the export file.
			foreach ($contacts as $contact) {
				fputcsv($filePointer, $contact, $fileOptions['delimiter']);
			}

			// Close the file at the end
			fclose($filePointer);

			// Create and populate ZIP archive
			$ZipPointer = new \ZipArchive;
			if ($ZipPointer->open($relativeBaseFilePath . $processing->file . '.zip', ZipArchive::CREATE) === true) {
				$ZipPointer->addFile($relativeBaseFilePath . $processing->file . '_' . $offset . '.csv', $processing->file . '_' . $offset . '.csv');
				$ZipPointer->close();
			}

			unlink($relativeBaseFilePath . $processing->file . '_' . $offset . '.csv');
		}

		return true;
	}
}