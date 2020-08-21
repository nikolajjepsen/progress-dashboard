<?php
require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use \Progress\Application\Settings;

$settings = new Settings;

Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::enable();

if (isset($_POST) && isset($_POST['primaryDomain'])) {
	if ($settings->set('tracker_main_domain_value', $_POST['primaryDomain'])) {
		echo json_encode(['status' => 'success', 'new_value' => $_POST['primaryDomain']]);
	}
}