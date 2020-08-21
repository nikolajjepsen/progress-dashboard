<?php
namespace Progress\Content\Template;
require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use Progress\Db\Database;
use \PDO;

class Templates
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

	public function getTemplates($status) {
		try {
			$this->sth = $this->dbh->prepare("SELECT * FROM `content_templates` ORDER BY name ASC");
			$this->sth->execute();
		} catch (Exception $e) {
			Debugger::log($e);
		}

		if ($blocks = $this->sth->fetchAll(PDO::FETCH_OBJ)) {
			return $blocks;
		} else {
			Debugger::log('Unable to fetch all landers. Reference \Content\Template\Templates.php @ getTemplates');
		}
	}

	public function getTemplate($id) {
		try {
			$this->sth = $this->dbh->prepare("SELECT * FROM `content_templates` WHERE `id` = :id LIMIT 1");
			$this->sth->execute([
				':id' => $id
			]);
		} catch (Exception $e) {
			Debugger::log($e);
		}

		if ($block = $this->sth->fetch(PDO::FETCH_OBJ)) {
			return $block;
		} else {
			Debugger::log('Unable to fetch lander. Reference \Content\Template\Templates.php @ getTemplate');
		}
	}

	public function getTemplateContent($id) {
		$templateFolder = __DIR__ . '/../../../fragments/templates/' . $id . '/';
		if (!file_exists($templateFolder)) {
			Debugger::log('Failed fetching template content @ ' . $templateFolder . '. Reference \Content\Template\Templates.php @ getTemplateContent');
			return false;
		}

		$content = [];
		if (file_exists($templateFolder . 'javascript.js')) {
			$content['javascript'] = file_get_contents($templateFolder . 'javascript.js');
		} else {
			$content['javascript'] = '';
		}
		if (file_exists($templateFolder . 'markup.html')) {
			$content['markup'] = file_get_contents($templateFolder . 'markup.html');
		} else {
			$content['markup'] = '';
		}
		if (file_exists($templateFolder . 'stylesheet.css')) {
			$content['stylesheet'] = file_get_contents($templateFolder . 'stylesheet.css');
		} else {
			$content['stylesheet'] = '';
		}

		return $content;
	}
}    