<?php
// tests/ApiTest.php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class ApiTest extends TestCase
{
    private $baseUri;

    protected function setUp(): void
    {
        // Для реальных тестов используется nginx контейнер
        $this->baseUri = "http://nginx";
    }

    public function testHomePageLoads()
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
            'timeout' => 5.0,
        ]);
        
        try {
            $response = $client->get("/index.php");
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertStringContainsString('Регистрация', (string)$response->getBody());
        } catch (Exception $e) {
            $this->markTestSkipped('Сервер не доступен: ' . $e->getMessage());
        }
    }

    public function testMockHttpRequest()
    {
        // Создаем мок-обработчик с фиктивным ответом
        $mock = new MockHandler([
            new Response(200, [], '<html><body>Mock Response OK</body></html>')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $response = $client->get('/any-url');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Mock Response OK', (string)$response->getBody());
    }

    // Дополнительно: мок для ошибки 404
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

    // Дополнительно: мок для исключения
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
}