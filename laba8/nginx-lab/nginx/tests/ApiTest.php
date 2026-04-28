<?php
// tests/ApiTest.php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

require_once __DIR__ . '/../www/ApiClient.php';

class ApiTest extends TestCase
{
    private $apiClient;
    
    protected function setUp(): void
    {
        $this->apiClient = new ApiClient();
    }
    
    public function testRealHttpRequestToApi()
    {
        $client = new Client();
        
        try {
            $response = $client->get('https://jsonplaceholder.typicode.com/posts/1');
            $this->assertEquals(200, $response->getStatusCode());
            
            $data = json_decode($response->getBody(), true);
            $this->assertArrayHasKey('id', $data);
            $this->assertArrayHasKey('title', $data);
        } catch (Exception $e) {
            $this->markTestSkipped('API не доступен: ' . $e->getMessage());
        }
    }
    
    // Тест HTTP через ApiClient
    public function testApiClientRequest()
    {
        $result = $this->apiClient->request('https://jsonplaceholder.typicode.com/posts/1');
        
        if (!isset($result['error'])) {
            $this->assertArrayHasKey('id', $result);
            $this->assertEquals(1, $result['id']);
        } else {
            $this->markTestSkipped('API не доступен: ' . $result['error']);
        }
    }
    
    public function testMockHttpRequest()
    {
        // Создаем мок-обработчик
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 1, 'title' => 'Mock Title']))
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        
        $response = $client->get('/test');
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('Mock Title', $data['title']);
    }
    
    public function testMockHttpNotFound()
    {
        $mock = new MockHandler([
            new Response(404, [], 'Not Found')
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        
        $response = $client->get('/not-exists');
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testMockHttpException()
    {
        $mock = new MockHandler([
            new RequestException('Ошибка соединения', new Request('GET', '/test'))
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        
        $this->expectException(RequestException::class);
        $client->get('/test');
    }
    
    public function testMultipleMockResponses()
    {
        $mock = new MockHandler([
            new Response(200, [], 'First'),
            new Response(200, [], 'Second'),
            new Response(404, [], 'Not Found')
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        
        $response1 = $client->get('/first');
        $response2 = $client->get('/second');
        $response3 = $client->get('/third');
        
        $this->assertEquals(200, $response1->getStatusCode());
        $this->assertEquals(200, $response2->getStatusCode());
        $this->assertEquals(404, $response3->getStatusCode());
        $this->assertEquals('First', (string)$response1->getBody());
    }
}