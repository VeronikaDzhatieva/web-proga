<?php
// tests/Integration/IntegrationTest.php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../www/Student.php';

class IntegrationTest extends TestCase
{
    private $pdo;
    private $student;
    
    protected function setUp(): void
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbname = getenv('DB_NAME') ?: 'test_db';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        
        // Пропускаем тесты, если нет реальной БД
        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $user,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Создаем тестовую таблицу
            $this->pdo->exec("DROP TABLE IF EXISTS conference_registrations");
            $this->pdo->exec("CREATE TABLE conference_registrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                birth_year INT NOT NULL,
                section VARCHAR(100) NOT NULL,
                need_certificate VARCHAR(3) NOT NULL,
                participation_type VARCHAR(20) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            $this->student = new Student($this->pdo);
        } catch (PDOException $e) {
            $this->markTestSkipped('Тестовая БД не доступна: ' . $e->getMessage());
        }
    }
    
    protected function tearDown(): void
    {
        if ($this->pdo) {
            $this->pdo->exec("DROP TABLE IF EXISTS conference_registrations");
        }
    }
    
    public function testAddRegistrationToDatabase()
    {
        $data = [
            'name' => 'Тестовый Пользователь',
            'birthYear' => 2000,
            'section' => 'it',
            'certificate' => 'Да',
            'participation' => 'online'
        ];
        
        $result = $this->student->addRegistration($data);
        $this->assertTrue($result);
        
        $count = $this->student->getCount();
        $this->assertEquals(1, $count);
    }
    
    public function testCheckRecordCount()
    {
        // Добавляем 3 записи
        for ($i = 1; $i <= 3; $i++) {
            $data = [
                'name' => "User $i",
                'birthYear' => 1990 + $i,
                'section' => 'science',
                'certificate' => $i % 2 == 0 ? 'Да' : 'Нет',
                'participation' => $i % 2 == 0 ? 'online' : 'offline'
            ];
            $this->student->addRegistration($data);
        }
        
        $count = $this->student->getCount();
        $this->assertEquals(3, $count);
    }
    
    public function testErrorWithInvalidData()
    {
        $this->expectException(PDOException::class);
        
        $data = [
            'name' => '', // Пустое имя - NOT NULL constraint
            'birthYear' => 2000,
            'section' => 'it',
            'certificate' => 'Да',
            'participation' => 'online'
        ];
        
        $this->student->addRegistration($data);
    }
    
    public function testGetAllRecords()
    {
        $data = [
            'name' => 'Тест Получение',
            'birthYear' => 1995,
            'section' => 'business',
            'certificate' => 'Да',
            'participation' => 'offline'
        ];
        $this->student->addRegistration($data);
        
        $records = $this->student->getAll();
        $this->assertIsArray($records);
        $this->assertCount(1, $records);
        $this->assertEquals('Тест Получение', $records[0]['name']);
    }
    
    public function testFilterOlderThan()
    {
        $young = ['name' => 'Молодой', 'birthYear' => 2010, 'section' => 'it', 'certificate' => 'Нет', 'participation' => 'online'];
        $old = ['name' => 'Взрослый', 'birthYear' => 1990, 'section' => 'it', 'certificate' => 'Нет', 'participation' => 'online'];
        
        $this->student->addRegistration($young);
        $this->student->addRegistration($old);
        
        $olderThan2005 = $this->student->getOlderThan(2005);
        
        $this->assertCount(1, $olderThan2005);
        $this->assertEquals('Взрослый', $olderThan2005[0]['name']);
    }
}