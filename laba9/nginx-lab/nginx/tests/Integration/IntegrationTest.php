<?php
// tests/integration/IntegrationTest.php

use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    private $pdo;
    private $student;
    
    protected function setUp(): void
    {
        $host = getenv('DB_HOST') ?: 'db';
        $dbname = getenv('DB_NAME') ?: 'test_db';
        $user = getenv('DB_USER') ?: 'test_user';
        $password = getenv('DB_PASSWORD') ?: 'test_pass';
        
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
            
            require_once __DIR__ . '/../../www/Student.php';
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
    
    // Тест добавления данных
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
    
    // Проверка количества записей
    public function testCheckRecordCount()
    {
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
    
    // Тест на ошибку (неверные данные)
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
}