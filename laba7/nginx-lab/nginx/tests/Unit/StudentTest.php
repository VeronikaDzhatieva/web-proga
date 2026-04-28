<?php
// tests/Unit/StudentTest.php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../www/Student.php';

class StudentTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private $student;
    
    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->student = new Student($this->pdoMock);
    }
    
    public function testAdd()
    {
        $student = new Student(null);
        $result = $student->add("Ivan");
        $this->assertEquals("Student Ivan added", $result);
    }
    
    public function testAddWithDifferentName()
    {
        $student = new Student(null);
        $result = $student->add("Maria");
        $this->assertEquals("Student Maria added", $result);
        $this->assertStringContainsString("Maria", $result);
    }
    
    public function testGetCountWithMock()
    {
        $expectedCount = ['count' => 5];
        
        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($expectedCount);
        
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->willReturn($this->stmtMock);
        
        $result = $this->student->getCount();
        $this->assertEquals(5, $result);
    }
    
    public function testGetAllEmptyWithMock()
    {
        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);
        
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->willReturn($this->stmtMock);
        
        $result = $this->student->getAll();
        $this->assertEmpty($result);
    }

    public function testGetAllWithMock()
    {
        $expectedData = [
            ['id' => 1, 'name' => 'Ivan', 'birth_year' => 2000, 'section' => 'it', 'need_certificate' => 'Да', 'participation_type' => 'online']
        ];
        
        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedData);
        
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->willReturn($this->stmtMock);
        
        $result = $this->student->getAll();
        $this->assertEquals($expectedData, $result);
        $this->assertCount(1, $result);
    }
    
    public function testWithoutPdo()
    {
        $student = new Student(null);
        
        $this->assertEquals([], $student->getAll());
        $this->assertEquals(0, $student->getCount());
        $this->assertFalse($student->addRegistration([]));
        $this->assertEquals([], $student->getOlderThan(2000));
    }
}