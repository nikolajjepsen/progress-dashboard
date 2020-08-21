<?php
namespace Progress\Contacts\Utility;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use \PDO;
use \Progress\Db\Database;
use ParseCsv\Csv;
use Tracy\Debugger;

class ExportQueue
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
		Debugger::$logSeverity = E_NOTICE | E_WARNING;
		Debugger::enable(Debugger::DETECT, __DIR__ . '/../../../log');
	}


    public function getById($id)
	{
		$this->sth = $this->dbh->prepare("SELECT * FROM `data_export` WHERE `id` = :id LIMIT 1");
		$this->sth->execute([
			':id' => $id
		]);

		if ($queue = $this->sth->fetch(PDO::FETCH_OBJ)) {
			return $queue;
		}

		return false;
	}

	public function getQueue()
	{
		$this->sth = $this->dbh->prepare("SELECT * FROM `data_export` ORDER BY `id` DESC");
		$this->sth->execute();

		if ($queue = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
			return $queue;
		}

		return false;
	}

	public function getNextInQueue()
	{
		$this->sth = $this->dbh->prepare("SELECT id FROM `data_export` WHERE `status` = :status OR `status` = :status_alt ORDER BY status, id ASC LIMIT 1");
		$this->sth->execute([
			':status' => 'processing',
			':status_alt' => 'queued'
		]);

		if ($queue = $this->sth->fetch(PDO::FETCH_OBJ)) {
			return $this->getById($queue->id);
		}

		return false;
    }
    
	public function queueExport($recordCount, $options)
	{
		try {
            $this->sth = $this->dbh->prepare("INSERT INTO `data_export` (`total`, `file`, `created`, `options`) VALUES (:total, :file, :created, :options)");
			$this->sth->execute([
                ':total'     => $recordCount,
                ':file'      => uniqid('exp_'),
                ':created'   => time(),
				':options'   => json_encode($options)
			]);

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
			$this->sth = $this->dbh->prepare("UPDATE `data_export` SET `status` = :status WHERE `id` = :id LIMIT 1");
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
			$this->sth = $this->dbh->prepare("UPDATE `data_export` SET `offset` = `offset` + :offset WHERE `id` = :id LIMIT 1");
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