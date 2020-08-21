<?php
namespace Progress\Contacts\Utility;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use \PDO;
use \Progress\Db\Database;
use \Progress\Utils\Country\Countries;
use ParseCsv\Csv;
use Tracy\Debugger;

class Import
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

    private $batch = [];
    private $buffer = [];
    private $batchSize;
    private $country;

    private $insertedRows = [];

    // ImportQueue class object
    private $queue;

    public function __construct()
    {
        $this->dbh = Database::get();
        $this->queue = new ImportQueue;

        $this->batchSize = 49;
        Debugger::$logSeverity = E_NOTICE | E_WARNING;
        Debugger::enable(Debugger::DETECT, __DIR__ . '/../../../log');
    }

    private function mapLine($mapping, $line)
    {
        foreach ($mapping as $key => $map) {
            if ($map == 'ignore') {
                unset($line[$key]);
            } else {
                $line[$map] = $line[$key];
                unset($line[$key]);
            }
        }
        return $line;
    }

    private function filterLine($manipulation, $line)
    {
        foreach ($line as $key => $value) {
            if (($key == 'firstname' || $key == 'lastname') && isset($manipulation->names) && $manipulation->names !== 0) {
                switch ($manipulation->names) {
                    case 'ucf':
                        $value = ucfirst($value);
                        break;
                    case 'lc':
                        $value = strtolower($value);
                        break;
                    case 'uc':
                        $value = strtoupper($value);
                        break;
                    default:;
                }
            } elseif ($key == 'email' && isset($manipulation->email) && $manipulation->email !== 0) {
                switch ($manipulation->email) {
                    case 'lc':
                        $value = strtolower($value);
                        break;
                    case 'uc':
                        $value = strtoupper($value);
                        break;
                    default:;
                }
            } elseif ($key == 'mobile' && isset($manipulation->mobile) && $manipulation->mobile !== 0) {
                $countryCC = $this->country->phonecode;
                $templates = [
                    '00',
                    '0',
                    '+',
                    ''
                ];
                foreach ($templates as $template) {
                    $startingMatch = $template . $countryCC;
                    $startingLength = strlen($startingMatch);
                    if (substr($value, 0, $startingLength) === $startingMatch) {
                        $value = str_replace($startingMatch, '', $value);
                    }
                }
            }

            $line[$key] = $value;
        }
        // Tracy\Debugger::dump($line);
        return $line;
    }
    private function addToBatch($values)
    {
        $this->batch[] = $values;

        foreach ($values as $value) {
            $this->buffer[] = $value;
        }
    }
    private function resetBatch()
    {
        $this->batch = [];
        $this->buffer = [];
    }

    private function buildQuery()
    {
        if (empty($this->batch)) {
            return false;
        }

        $fields = array_keys($this->batch[0]);
        $fields = implode(', ', $fields);

        $placeholders = implode(', ', array_fill(0, count($this->batch[0]), '?'));
        $query = 'INSERT INTO `data_contacts` (' . $fields . ') VALUES (' . $placeholders . ')';
        $query .= str_repeat(', (' . $placeholders . ')', count($this->batch) - 1);
        $query .= 'ON DUPLICATE KEY UPDATE pid=values(pid), collection_year=values(collection_year)';
        

        return $query;
    }

    private function processBatch()
    {
        
        $this->insertedRows['runs']++;
        $this->insertedRows['totals'] += count($this->batch);
        $query = $this->buildQuery();
        if (!$query) {
            return false;
        }

        try {
            $this->dbh->beginTransaction();
            $this->sth = $this->dbh->prepare($query);
            $this->sth->execute($this->buffer);
            $this->dbh->commit();
            $this->queue->increaseOffset($this->processingId, count($this->batch));
        } catch (Exception $e) {
            $this->dbh->rollback();
            return false;
        }
        $this->resetBatch();

    }

    public function lastBatch() {
        $this->processBatch();
        $this->queue->updateStatus($this->processingId, 'processed');

        return true;
    }


    public function process()
    {
        $scriptStart = microtime(true);
        // Get the next import task in queue
        // Will return either the task currently being processed but stopped, or the next queued task.
        if (!$process = $this->queue->getNextInQueue()) {
            return false;
        }
        \Tracy\Debugger::dump($process);

        $this->processingId = $process->id;
        $options = json_decode($process->options);

        $countries = new Countries;
        // Storing the country information in a class property to have access throughout the class.
        $this->country = $countries->getCountry($options->collectionCountryId);

        $this->queue->updateStatus($this->processingId, 'processing');


        $mapping = $options->mapping;
        $manipulation = $options->manipulation;

        $fileDirectory = __DIR__ . '/../../../files/import/';
        $fileAbsolutePath = $fileDirectory . $process->file;

        if (!file_exists($fileAbsolutePath) || !$handle = fopen($fileAbsolutePath, 'r')) {
            return false;
        }

        $increment = 0;
        while (($line = fgetcsv($handle, 10000, $options->delimiter)) !== false) {
            // \Tracy\Debugger::dump($line);
            // skip first row - usually headers
            if ($increment == 0) {
                $this->queue->increaseOffset($this->processingId, 1);
                $increment++;
                continue;
            }

            // skip if increment is below offset (restarted import)
            if ($process->offset > 0 && $increment <= $process->offset) {
                $increment++;
                continue;
            }
            // Replace line index-based keys with correct database column names for easier management.
            $line = $this->mapLine($mapping, $line);

            // Filter the line based on manipulation rules upon creation of import task.
            $line = $this->filterLine($manipulation, $line);

            /*if (isset($line['mobile']) || isset($line['email']) && !empty($line['mobile']) || !empty($line['email'])) {
                if ($this->userExist($line['mobile'], $line['email'])) {
                    $this->increaseDuplicate(1);
                    $this->increaseOffset(1);
                    $increment++;
                    continue;
                }
            }*/

            // Add meta-data about the data to the import
            $line['cid'] = $this->country->id;
            $line['pid'] = $options->collectionProviderId;
            $line['collection_year'] = $options->collectionYear;
            $line['import_ts'] = time();

            $this->addToBatch($line);
            $batchContentCount = count($this->batch);

            if ($batchContentCount == $this->batchSize) {
                $this->processBatch();
            }
            
            $increment++;

            if (($increment >= $process->total) || ($process->offset >= $process->total)) {
                $this->lastBatch();
                //$this->removeFile();
                $scriptEnd = microtime(true);
                $scriptTotal = $scriptEnd - $scriptStart;
                echo 'Script ran for ' . $scriptTotal . ' and inserted ' . $this->insertedRows . ' rows<br><br>';
                return true;
            }
        }
    }
}
