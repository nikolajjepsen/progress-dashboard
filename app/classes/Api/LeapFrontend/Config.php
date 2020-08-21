<?php

namespace Progress\Api\LeapFrontend;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;

use \Progress\Db\Database;
use \PDO;
use GuzzleHttp\Client;


class Config extends Base
{
    public function list($id = null)
    {
        try {
            $response = $this->request()->get('https://leapkreditt.com/app/api/config/');
            $content = $response->getBody()->getContents();
            $this->handleError($content, $response->getStatusCode());
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Debugger::log($e->getMessage());
            return false;
        } catch (\Exception $e) {
            Debugger::log($e->getMessage());
            return false;
        }
        if (isset($response) && $response->getStatusCode() == 200) {
            return json_decode($content);
        }
        Debugger::log('Unable to decode response @ Progress\Api\LeapFrontend\Config:list: ' . $response);
        return false;
    }

    public function updateEntry($id, $name, $value) {
        Debugger::$logSeverity = E_NOTICE | E_WARNING;
        Debugger::enable(Debugger::DETECT, __DIR__ . '/../../../log');

        try {
            $response = $this->request()->put('https://leapkreditt.com/app/api/config/' . $id, [
                'form_params' => [
                    'name' => $name,
                    'value' => $value
                ]
            ]);
            $content = (string) $response->getBody();
            $this->handleError($content, $response->getStatusCode());
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Debugger::log($e->getMessage());
            return false;
        } catch (\Exception $e) {
            Debugger::log($e->getMessage());
            return false;
        }
        if ($response->getStatusCode() == 204) {
            return true;
        }
        Debugger::log('Unable to decode response @ Progress\Api\LeapFrontend\Config:updateEntry: ' . $response->getBody()->getContents());
        return false;

    }
}
