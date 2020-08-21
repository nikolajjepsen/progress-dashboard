<?php
/*session_start();

if (!isset($_SESSION['logged'])) {
	header("Location: login.php");
}
*/
$filename = __DIR__ . '/../../app/exports/' . $_GET['file'] . '.zip';

if (file_exists($filename)) {
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename="'.basename($filename).'"');
	header('Content-Length: ' . filesize($filename));

	flush();
	readfile($filename);
}