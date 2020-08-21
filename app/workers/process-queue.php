<?php

require_once(__DIR__ . '/../../vendor/autoload.php');
use Tracy\Debugger;
use \Progress\Contacts\Utility\Import;

$import = new Import;

Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/../log/');


$import->process();