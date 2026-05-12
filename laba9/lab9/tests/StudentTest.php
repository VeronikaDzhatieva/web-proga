<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../code/Student.php';

class StudentTest extends TestCase
{
    public function testSuccessfulRegistration(): void
    {
        $student = new Student();
        $data = [
            'name' => 'Иванов Иван',
            'age' => 20,
            'faculty' => 'IT',
            'agreed' => true,
            'study_form' => 'очная'
        ];

        $result = $student->register($data);
        $this->assertStringContainsString('успешно', $result);
    }

    public function testFailEmptyName(): void
    {
        $student = new Student();
        $data = [
            'name' => '', // Пустое имя
            'age' => 20,
            'faculty' => 'IT',
            'agreed' => true,
            'study_form' => 'очная'
        ];

        $result = $student->register($data);
        $this->assertStringContainsString('Ошибка', $result);
    }

    public function testFailYoungAge(): void
    {
        $student = new Student();
        $data = [
            'name' => 'Петров Петр',
            'age' => 10, // Меньше 16
            'faculty' => 'IT',
            'agreed' => true,
            'study_form' => 'очная'
        ];

        $result = $student->register($data);
        $this->assertStringContainsString('Возраст', $result);
    }
}