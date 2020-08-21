<?php

require_once(__DIR__ . '/../../vendor/autoload.php');
use Tracy\Debugger;
use \Progress\Contacts\Utility\Export;


$export = new Export;

Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/../log/');
Debugger::$showBar = false;

$export->process();