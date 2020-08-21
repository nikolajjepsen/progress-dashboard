<?php
namespace Progress\Content\Boilerplate;
require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use Progress\Db\Database;
use \PDO;

class Boilerplates
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

	private $country;
	private $partials;

	public function __construct()
	{
		$this->dbh = Database::get();
		$this->partials = new \Progress\Content\Partial\Partials();
		Debugger::$logSeverity = E_NOTICE | E_WARNING;
		Debugger::enable(Debugger::DETECT, __DIR__ . '/../../../log');
	}

	public function getBoilerplates($status) {
		try {
			$this->sth = $this->dbh->prepare("SELECT * FROM `content_boilerplates` ORDER BY name ASC");
			$this->sth->execute();
		} catch (Exception $e) {
			Debugger::log($e);
		}

		if ($blocks = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
			return $blocks;
		} else {
			Debugger::log('Unable to fetch all landers. Reference \Content\Boilerplate\Boilerplates.php @ getLanders');
		}
	}

	public function getBoilerplate($id) {
		try {
			$this->sth = $this->dbh->prepare("SELECT * FROM `content_boilerplates` WHERE `id` = :id LIMIT 1");
			$this->sth->execute([
				':id' => $id
			]);
		} catch (Exception $e) {
			Debugger::log($e);
		}

		if ($block = $this->sth->fetch(PDO::FETCH_OBJ)) {
			return $block;
		} else {
			Debugger::log('Unable to fetch lander. Reference \Content\Boilerplate\Boilerplates.php @ getLander');
		}
	}

	public function getRelatedPartials($id) {
		try {
			$this->sth = $this->dbh->prepare("SELECT `pid` FROM `content_boilerplates_relations` WHERE `lid` = :lid");
			$this->sth->execute([
				':lid' => $id
			]);
		} catch (Exception $e) {
			Debugger::log($e);
		}

		if ($partialRelations = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
			$partials = [];
			foreach ($partialRelations as $partial) {
				if ($partialInfo = $this->partials->getPartial($partial->pid)) {
					$partials[] = $partialInfo;
				}
			}

			return $partials;
		}
	}
}    