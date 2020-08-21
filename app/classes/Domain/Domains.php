<?php
namespace Progress\Domain;
require_once(__DIR__ . '/../../../vendor/autoload.php');
use Tracy\Debugger;
use Progress\Db\Database;
use \PDO;

class Domains
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
		Debugger::enable(Debugger::DETECT, __DIR__ . '/../../log');
	}

	private function selectDomains() {
		try {
			$this->sth = $this->dbh->prepare("SELECT `id`, `domain`, `type`, `flags`, `last_check` FROM `domains` ORDER BY id DESC");
			$this->sth->execute();

			if ($domains = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
				return $domains;
			}
		} catch (Exception $e) {
			return false;
		}

		return false;
	}

	private function selectDomain($id) {
		try {
			$this->sth = $this->dbh->prepare("SELECT `id`, `domain`, `type`, `flags`, `last_check` FROM `domains` WHERE `id` = :id LIMIT 1");
			$this->sth->execute([
				':id' => $id
			]);

			if ($domain = $this->sth->fetch(PDO::FETCH_OBJ)) {
				return $domain;
			}

			return false;
		} catch (Exception $e) {
			return false;
		}
	}

	public function getDomains() {
		if ($domains = $this->selectDomains()) {
			return $domains;
		}
		return false;
	}

	public function getDomain($id) {
		if ($domain = $this->selectDomain($id)) {
			return $domain;
		}

		return false;
	}

	public function deleteDomain($id) {
		if ($this->selectDomain($id)) {
			try {
				$this->sth = $this->dbh->prepare("DELETE FROM `domains` WHERE `id` = :id LIMIT 1");
				$this->sth->execute([
					':id' => $id
				]);

				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		return false;
	}

	public function isFlagged($id) {
		return false;
	}

}