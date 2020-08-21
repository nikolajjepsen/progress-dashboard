<?php

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use \Progress\Db\Database;
use \Progress\Contacts\Utility\ExportQueue;
use \Progress\Contacts\Utility\Export;

$queue = new ExportQueue;
$export = new Export;
$dbh = new Database;

Debugger::$logSeverity = E_NOTICE | E_WARNING;
Debugger::enable(Debugger::DEVELOPMENT);

if (isset($_GET['method']) && $_GET['method'] == 'queue-export') {

    
    $providerId = $_POST['data_provider'] ?? false;
    $providerYear = $_POST['data_year'] ?? false;
    $providerCountryId = $_POST['data_country'] ?? false;
    
    $fileSeparator = $_POST['file_separator'] ?? false;
    $fileChunckSize = $_POST['file_chunck_size'] ?? false;
    $fileSize = $_POST['file_size'] ?? false;

    $dataCountryCodeStructure = $_POST['cc_template'] ?? false;
    $dataMobileValidationLevel = $_POST['mobile_status'] ?? false; // TODO skipped for now 09042020
    $dataRequireMobile = $_POST['require_mobile'] ?? false;
    $dataRequireEmail = $_POST['require_email'] ?? false;

    $dataFields = $_POST['field'];

    if (! $providerId) {
        $response[] = [
            'status' => 'error',
            'field' => 'providerId',
            'message' => 'providerId not set.'
		];
    }
    if (! $providerYear) {
        $response[] = [
            'status' => 'error',
            'field' => 'providerYear',
            'message' => 'providerYear not set.'
		];
    }
    if (! $providerCountryId) {
        $response[] = [
            'status' => 'error',
            'field' => 'providerCountryId',
            'message' => 'providerCountryId not set.'
		];
    }
    if (! $fileSeparator) {
        $response[] = [
            'status' => 'error',
            'field' => 'fileSeparator',
            'message' => 'fileSeparator not set.'
		];
    }
    if (! $fileChunckSize) {
        $response[] = [
            'status' => 'error',
            'field' => 'fileChunckSize',
            'message' => 'fileChunckSize not set.'
		];
    }
    if (! $fileSize) {
        $response[] = [
            'status' => 'error',
            'field' => 'fileSize',
            'message' => 'fileSize not set.'
		];
    }
    if (! $dataCountryCodeStructure) {
        $response[] = [
            'status' => 'error',
            'field' => 'dataCountryCodeStructure',
            'message' => 'dataCountryCodeStructure not set.'
		];
    }
    if (! $dataMobileValidationLevel) {
        $response[] = [
            'status' => 'error',
            'field' => 'dataMobileValidationLevel',
            'message' => 'dataMobileValidationLevel not set.'
		];
    }
    if (! $dataRequireMobile) {
        $response[] = [
            'status' => 'error',
            'field' => 'dataRequireMobile',
            'message' => ' not set.'
		];
    }
    if (! $dataRequireEmail) {
        $response[] = [
            'status' => 'error',
            'field' => 'dataRequireEmail',
            'message' => 'dataRequireEmail not set.'
		];
    }

    $options = [
        'file' => [
            'delimiter' => $fileSeparator,
            'chunck_size' => $fileChunckSize,
            'file_size' => $fileSize
        ],
        'provider' => [
            'id' => $providerId,
            'year' => $providerYear,
            'cid' => $providerCountryId
        ],
        'data' => [
            'validation_level' => $dataMobileValidationLevel,
            'require_mobile' => $dataRequireMobile,
            'cc_structure' => $dataCountryCodeStructure,
            'require_email' => $dataRequireEmail,
            'fields' => $dataFields
        ]
    ];
    if ($countQuery = $export->generateQueryFromOptions($options, 'COUNT', $limit)) {
        if ($countQuery < $fileSize) {
            $numberOfRecourds = $countQuery;
        } else {
            $numberOfRecourds = $fileSize;
        }
        $queue->queueExport($numberOfRecourds, $options);
    }
}