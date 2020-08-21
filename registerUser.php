<?php

require_once (__DIR__ . '/vendor/autoload.php');
use \Progress\Application\Settings;
use \Progress\Application\Auth;

$auth = new Auth;
$auth->register('nj@codefighter.dk', 'balalaika');
?>