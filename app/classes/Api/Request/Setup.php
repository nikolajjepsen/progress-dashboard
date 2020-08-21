<?php

namespace Progress\Api\Request;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;

use \Progress\Db\Database;
use \PDO;

class Client
{
    protected $api;

    public function __construct($baseUri, $headers)
    {
        $this->api = new GuzzleHttp\Client(
            [
                'base_uri' => $baseUri,
                'headers' => $headers,
                'http_errors' => false
            ]
        );
    }
    public function request()
    {
        return $this->api;
    }
}
