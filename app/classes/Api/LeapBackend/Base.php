<?php

namespace Progress\Api\LeapBackend;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;

use \Progress\Db\Database;
use \PDO;
use GuzzleHttp\Client;

class Base {

    protected $api;

    public function __construct() {
        $this->api = new Client(
            [
                'base_uri' => 'https://loan.progressmedia.dev/',
                'headers' => [
                    'console_user' => 'nj@codefighter.dk',
                    'console_pass' => '2I!6iak*bxHewMLN%m1rPU',
                ],
                'http_errors' => false
            ]
        );
    }

    public function request()
    {
        return $this->api;
    }
    
    public function handleError($response, $statusCode)
    {
        /*if (!json_decode($response)) {
            throw new \Exception('406: Invalid response');
        }*/

        switch ($statusCode) {
            case 404:
                throw new \Exception('404: Ressource not found');
                break;
            case 401:
                throw new \Exception('401: Unauthorized');
                break;
            case 400:
                throw new \Exception('400: Invalid arguments');
                break;
            default:
                return true;
                break;
        }
    }      

}