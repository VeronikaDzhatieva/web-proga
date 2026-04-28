<?php
// www/ApiClient.php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiClient {
    private $client;
    
    public function __construct() {
        $this->client = new Client([
            'timeout' => 10.0,
            'verify' => false
        ]);
    }
    
    public function request($url) {
        try {
            $response = $this->client->get($url);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    public function post($url, $data) {
        try {
            $response = $this->client->post($url, [
                'json' => $data
            ]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}