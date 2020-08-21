<?php

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use \Progress\Contacts\Provider\Providers;
use Tracy\Debugger;
Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::enable();
$providers = new Providers;

if (!isset($_GET['method'])) {
	die;
}

if ($_GET['method'] == 'list-providers') {
	if ($list = $providers->listProviders()) {
		echo json_encode($list);
	}
}
?>