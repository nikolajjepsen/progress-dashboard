<?php
namespace Progress\Contacts\Utility;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use \PDO;
use \Progress\Db\Database;
use Tracy\Debugger;

class ImportQueue
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

	public function __construct()
	{
		$this->dbh = Database::get();
		$this->batchSize = 49;
		Debugger::$logSeverity = E_NOTICE | E_WARNING;
		Debugger::enable(Debugger::DETECT, __DIR__ . '/../../../log');
	}

	public function getById($id)
	{
		$this->sth = $this->dbh->prepare("SELECT * FROM `data_import` WHERE `id` = :id LIMIT 1");
		$this->sth->execute([
			':id' => $id
		]);

		if ($import = $this->sth->fetch(PDO::FETCH_OBJ)) {
			return $import;
		}

		return false;
	}

	public function getQueue()
	{
		$this->sth = $this->dbh->prepare("SELECT * FROM `data_import` ORDER BY `id` DESC");
		$this->sth->execute();

		if ($queue = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
			return $queue;
		}

		return false;
	}

	public function getNextInQueue()
	{
		$this->sth = $this->dbh->prepare("SELECT id FROM `data_import` WHERE `status` = :status OR `status` = :status_alt ORDER BY status, id ASC LIMIT 1");
		$this->sth->execute([
			':status' => 'processing',
			':status_alt' => 'queued'
		]);

		if ($import = $this->sth->fetch(PDO::FETCH_OBJ)) {
			return $this->getById($import->id);
		}

		return false;
	}

	public function addFile($originalName, $fileName, $rows)
	{
		try {
			$this->sth = $this->dbh->prepare("INSERT INTO `data_import` (`file`, `original_name`, `total`, `created`, `options`, `status`) VALUES (:file, :original_name, :total, :created, :options, :status)");
			$this->sth->execute([
				':file'             => $fileName,
				':original_name'    => $originalName,
				':total'            => $rows,
				':created'          => time(),
				':options'			=> '',
				':status'           => 'file_upload'
			]);

			return $this->dbh->lastInsertId();
		} catch (Exception $e) {
			return false;
		}
	}

	public function removeFile($queueId) {
		if (!$queue = $this->getById($queueId)) {
			echo 'File not found.';
			return false;
		}
		/*clearstatcache();
		$fileDirectory = __DIR__ . '/../../../files/import/';
        $fileAbsolutePath = $fileDirectory . $queue->file;

        if (file_exists($fileAbsolutePath)) {
            unlink($fileAbsolutePath);
            echo 'Unlinking file';
            return true;
        }
		echo $fileAbsolutePath;*/
		return false;
	}

	public function queueImport($id, $options)
	{
		/*$options = [
			'delimiter' => ',',
			'collectionCountryId' => 1,
			'collectionProviderId' => 1,
			'collectionYear' => 2010,
			'mapping' => [
				'firstname',
				'ignore',
				'mobile'
			],
			'manipulation' => [
				'email' => 'lc',
				'names' => 'ucf',
				'mobile' => 1
			]
		];*/

		try {

			$this->sth = $this->dbh->prepare("UPDATE `data_import` SET `options` = :options WHERE `id` = :id");
			$this->sth->execute([
				':options'   => json_encode($options),
				':id'        => $id
			]);
            $this->updateStatus($id, 'queued');

			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function updateStatus($id, $status)
	{
		if (! $this->getById($id)) {
			return false;
		}
		
		try {
			$this->sth = $this->dbh->prepare("UPDATE `data_import` SET `status` = :status WHERE `id` = :id LIMIT 1");
			$this->sth->execute([
				':status' => $status,
				':id' => $id
			]);

			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function increaseOffset($id, $offset) {
		if (! $this->getById($id)) {
			return false;
		}
				
		try {
			$this->sth = $this->dbh->prepare("UPDATE `data_import` SET `offset` = `offset` + :offset WHERE `id` = :id LIMIT 1");
			$this->sth->execute([
				':offset' => $offset,
				':id' => $id
			]);

			return true;
		} catch (Exception $e) {
			return false;
		}

	}
}
