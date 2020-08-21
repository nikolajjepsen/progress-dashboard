<?php
namespace Progress\Content\Partial;
require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use Progress\Db\Database;
use \PDO;

class Partials
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

	public function getPartials($status) {
		try {
			$this->sth = $this->dbh->prepare("SELECT * FROM `content_partials` ORDER BY name ASC");
			$this->sth->execute();
		} catch (Exception $e) {
			Debugger::log($e);
		}

		if ($partials = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
			return $partials;
		} else {
			Debugger::log('Unable to fetch all content partials. Reference \Content\Partial\Partials.php @ getPartials');
		}
	}

	public function getPartial($id) {
		try {
			$this->sth = $this->dbh->prepare("SELECT * FROM `content_partials` WHERE `id` = :id LIMIT 1");
			$this->sth->execute([
				':id' => $id
			]);
		} catch (Exception $e) {
			Debugger::log($e);
		}

		if ($partial = $this->sth->fetch(PDO::FETCH_OBJ)) {
			return $partial;
		} else {
			Debugger::log('Unable to fetch content block. Reference \Content\Partial\Partials.php @ getPartial');
		}
	}

	public function getPartialContent($id) {
		$partialFolder = __DIR__ . '/../../../fragments/partials/' . $id . '/';
		if (!file_exists($partialFolder)) {
			Debugger::log('Failed fetching partial content @ ' . $partialFolder . '. Reference \Content\Partial\Partials.php @ getPartialContent');
			return false;
		}

		$content = [];
		if (file_exists($partialFolder . 'javascript.js')) {
			$content['javascript'] = file_get_contents($partialFolder . 'javascript.js');
		} else {
			$content['javascript'] = '';
		}
		if (file_exists($partialFolder . 'markup.html')) {
			$content['markup'] = file_get_contents($partialFolder . 'markup.html');
		} else {
			$content['markup'] = '';
		}
		if (file_exists($partialFolder . 'stylesheet.css')) {
			$content['stylesheet'] = file_get_contents($partialFolder . 'stylesheet.css');
		} else {
			$content['stylesheet'] = '';
		}

		return $content;
	}
}    