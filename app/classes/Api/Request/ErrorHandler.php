<?php
namespace Progress\Api\Request;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;

use \Progress\Db\Database;

class ErrorHandler
{
    protected $api;

    public function __construct()
    {
    }
        
    public function handle($response, $statusCode)
    {
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

        /* 
        TODO:    
            [ ] Add content specific error handlers
            [ ] Add database storage of errors
        */
    }
}
