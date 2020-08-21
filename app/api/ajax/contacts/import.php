<?php

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use \Progress\Contacts\Utility\Import;
use \Progress\Contacts\Utility\ImportQueue;

$import = new Import;
$queue = new ImportQueue;

Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::enable();


if (isset($_GET['method']) && $_GET['method'] == 'upload-file') {
	$storage = __DIR__ . '/../../../files/import/';
	$file = $_FILES['file'];

	$fileName = $file['name'];
	$fileTmpName = $file['tmp_name'];
	$fileExtension = strtolower(end(explode('.', $fileName)));

	$allowedExtensions = ['csv'];
	$fileNewName = uniqid() . '.' . $fileExtension;

	$path = $storage . $fileNewName;

	if (in_array($fileExtension, $allowedExtensions)) {
		$uploadComplete = move_uploaded_file($fileTmpName, $path);
		if ($uploadComplete) {
			$response = [
				'status' => 'success',
				'file_link' => $fileNewName
			];
			$fileDetail = new SplFileObject($storage . $fileNewName);
			$fileDetail->seek(PHP_INT_MAX);
			$rowCount = $fileDetail->key() + 1;
			if ($queueId = $queue->addFile($fileName, $fileNewName, $rowCount)) {
				$response['queue_id'] = $queueId;
			}

		} else {
			$response = [
				'status' => 'error',
				'message' => 'File unable to be moved'
			];
		}
	} else {
		$response = [
			'status' => 'error',
			'message' => 'The file extension mismatches allowed extensions'
		];
	}

	echo json_encode($response);
} elseif (isset($_GET['method']) && $_GET['method'] == 'queue-file') {
	$queueId = $_GET['file_id'] ?? 'NaN';
	if (isset($_POST)) {
		if (! isset($_POST['data_provider']) || $_POST['data_provider'] == 0) {
			$response[] = [
				'status' => 'error',
				'field' => 'data_provider',
				'message' => 'Data provider not set.'
			];
		}

		if (! isset($_POST['data_year']) || $_POST['data_year'] == 0) {
			$response[] = [
				'status' => 'error',
				'field' => 'data_year',
				'message' => 'Data year not set.'
			];
		}

		if (! isset($_POST['data_country']) || $_POST['data_country'] == 0) {
			$response[] = [
				'status' => 'error',
				'field' => 'data_country',
				'message' => 'Data country not set.'
			];
		}

		if (! isset($_POST['header']) || ! is_array($_POST['header'])) {
			$response[] = [
				'status' => 'error',
				'field' => 'header',
				'message' => 'Data mapping not set or incorrectly set.'
			];
		}

		var_dump($queueId);
		
		$delimiter = $_POST['delimiter'];

		$collectionProviderId = $_POST['data_provider'];
		$collectionYear = $_POST['data_year'];
		$collectionCountryId = $_POST['data_country'];

		$mapping = $_POST['header'];

		$emailManipulation = $_POST['email_manipulation'];
		$mobileManipulation = $_POST['number_manipulation'];
		$nameManipulation = $_POST['name_manipulation'];

		$options = [
			'delimiter' => $delimiter,
			'collectionProviderId' => $collectionProviderId,
			'collectionCountryId' => $collectionCountryId,
			'collectionYear' => $collectionYear,
			'mapping' => $mapping,
			'manipulation' => [
				'email' => $emailManipulation,
				'names' => $nameManipulation,
				'mobile' => $mobileManipulation
			]
		];
		$queue->queueImport($queueId, $options);

		var_dump($options);
	}
}