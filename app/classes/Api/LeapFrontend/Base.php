<?php

namespace Progress\Api\LeapFrontend;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;

use \Progress\Db\Database;
use \PDO;
use GuzzleHttp\Client;

class Base
{
    protected $api;

    public function __construct()
    {
        $this->api = new Client(
            [
                'headers' => [
                    'pm_user' => 'nj@codefighter.dk',
                    'pm_pass' => 'b_jF5TPgQgUZ7Ff2BJS98&SLscHWEEg',
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
