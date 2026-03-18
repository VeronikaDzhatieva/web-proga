<?php
namespace App;

use Predis\Client;

class RedisExample
{
    private $client;

    public function __construct()
    {
        $this->client = new Client('tcp://redis:6379');
    }

    public function setValue($key, $value)
    {
        $this->client->set($key, $value);
    }

    public function getValue($key)
    {
        return $this->client->get($key);
    }
}