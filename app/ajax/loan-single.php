<?php
require __DIR__ . '/../../vendor/autoload.php';
$leapConfig = new Progress\Api\LeapFrontend\Config;
if ($_GET['task'] == 'updateConfigEntry' && isset($_POST) && isset($_POST['name']) && isset($_POST['value'])) {
    if ($response = $leapConfig->updateEntry($_POST['id'], $_POST['name'], $_POST['value'])) {
        echo 'ok';
    } else {
        echo 'error';
    }
}
?>