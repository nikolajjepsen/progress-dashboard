<?php

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use \Progress\Utils\Country\Countries;
use Tracy\Debugger;
Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::enable();
$countries = new Countries;

if (!isset($_GET['method'])) {
	die;
}

if ($_GET['method'] == 'list-countries') {
	if ($list = $countries->listCountries()) {
		echo json_encode($list);
	}
}
?>