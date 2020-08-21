<?php
namespace Progress\Application;

require_once(__DIR__ . '/../../../vendor/autoload.php');
use Tracy\Debugger;

use \Progress\Db\Database;
use \PDO;

class Settings
{

	/**
	 * The database handler
	 * @var object
	 */
	protected $dbh;

	/**
	 * The statement handler
	 * @var object
	 */
	protected $sth;

	public function __construct()
	{
		$this->dbh = Database::get();
		Debugger::$logSeverity = E_NOTICE | E_WARNING;
		Debugger::enable(Debugger::DETECT, __DIR__ . '/../../log');
	}

	public function get($settingName)
	{
		$this->sth = $this->dbh->prepare("SELECT * FROM `settings` WHERE `name` = :name LIMIT 1");
		$this->sth->execute([
				':name' => $settingName
		]);

		if ($setting = $this->sth->fetch(PDO::FETCH_OBJ)) {
			return $setting->value;
		}

		return false;
	}

	public function set($settingName, $settingValue) {
		
		if (!$this->get($settingName))
			return false;

		try {
			$this->sth = $this->dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = :name");
			$this->sth->execute([
				':value' => $settingValue,
				':name' => $settingName
			]);

			return true;
		} catch (Exception $e) {
			return false;
		}
		
		return false;
	}
}
