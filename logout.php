<?php
session_start();

require_once (__DIR__ . '/vendor/autoload.php');
use \Progress\Application\Settings;
use \Progress\Application\Auth;

$settings = new Settings;
$basePath = $settings->get('site_path');

unset($_SESSION['userId']);
session_destroy();

header("Location: " . $basePath . "login");

?>