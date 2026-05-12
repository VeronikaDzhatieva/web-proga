<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

// Подключаем наш класс
require_once __DIR__ . '/../www/Student.php';

class StudentTest extends TestCase
{
    private Student $student;

    protected function setUp(): void
    {
        // Создаем мок PDO, чтобы изолировать тесты от реальной БД
        $mockPdo = $this->createMock(PDO::class);
        $this->student = new Student($mockPdo);
    }

    // --- UNIT TESTS ---

    // Тест 1: Успешная регистрация
    public function testRegisterSuccess(): void
    {
        $data = [
            'name' => 'Иван Иванов',
            'age' => 20,
            'faculty' => 'ФИТ',
            'agreed' => true,
            'study_form' => 'очная'
        ];

        $result = $this->student->register($data);
        $this->assertEquals("Студент Иван Иванов успешно зарегистрирован!", $result);
    }

    // Тест 2: Ошибка валидации (нет согласия)
    public function testRegisterNoAgreement(): void
    {
        $data = [
            'name' => 'Петр Петров',
            'age' => 19,
            'faculty' => 'Эконом',
            'agreed' => false, // Важно!
            'study_form' => 'заочная'
        ];

        $result = $this->student->register($data);
        $this->assertStringContainsString("согласиться", $result);
    }

    // --- MOCK TEST ---

    // Тест 3: Проверка взаимодействия с зависимостью (PDO)
    public function testRegisterWithMockedPDO(): void
    {
        // Создаем новый экземпляр с моком, который ожидает вызова
        $mockPdo = $this->createMock(PDO::class);
        
        // Если бы в методе register был вызов $this->pdo->prepare(), 
        // мы могли бы проверить это здесь. 
        // Пока просто проверяем, что объект принимает мок и работает.
        $studentWithMock = new Student($mockPdo);
        
        $data = [
            'name' => 'Анна Сидорова',
            'age' => 21,
            'faculty' => 'Мед',
            'agreed' => true,
            'study_form' => 'очная'
        ];
        
        $result = $studentWithMock->register($data);
        $this->assertNotEmpty($result);
    }

    // --- GUZZLE HTTP TEST ---

    // Тест 4: HTTP запрос через Guzzle (используем MockHandler, чтобы не нужен был реальный сервер)
    public function testHttpRequestViaGuzzle(): void
    {
        // Создаем мок-ответ, который вернет нам Guzzle
        $mock = new MockHandler([
            new Response(200, [], json_encode(['status' => 'ok', 'message' => 'Registered']))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // Делаем запрос
        $response = $client->post('http://example.com/api/register', [
            'json' => [
                'name' => 'Test User',
                'age' => 20,
                'faculty' => 'Test Fac',
                'agreed' => true,
                'study_form' => 'очная'
            ]
        ]);

        // Проверяем ответ
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody(), true);
        $this->assertEquals('ok', $body['status']);
    }
}