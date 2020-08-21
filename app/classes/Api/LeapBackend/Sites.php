<?php

namespace Progress\Api\LeapBackend;

require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;

class Sites extends Base {
    
    public function getAll() {
        try {
            $response = $this->request()->get('/backend/api/v1/sites/');
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
        Debugger::log('Unable to decode response @ Progress\Api\LeapBackend\Sites:getAll: ' . $response);
        return false;
    }

    public function getSummary($id) {
        try {
            $response = $this->request()->get('/backend/api/v1/sites/' . $id . '/summary');
            $content = $response->getBody()->getContents();
            //echo $content;
            $this->handleError($content, $response->getStatusCode());
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Debugger::log($e);
            return false;
        } catch (\Exception $e) {
            Debugger::log($e);
            return false;
        }

        if (isset($response) && $response->getStatusCode() == 200) {
            return json_decode($content);
        }
        Debugger::log('Unable to decode response @ Progress\Api\LeapBackend\Sites:getSummary: ' . $response);
        return false;
    }

    public function getById($id) {
        try {
            $response = $this->request()->get('/backend/api/v1/sites/' . $id);
            $content = $response->getBody()->getContents();
            //echo $content;
            $this->handleError($content, $response->getStatusCode());
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Debugger::log($e);
            return false;
        } catch (\Exception $e) {
            Debugger::log($e);
            return false;
        }

        if (isset($response) && $response->getStatusCode() == 200) {
            return json_decode($content);
        }
        Debugger::log('Unable to decode response @ Progress\Api\LeapBackend\Sites:getSummary: ' . $response);
        return false;

    }
}