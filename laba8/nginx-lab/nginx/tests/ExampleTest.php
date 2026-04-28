<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testTrueIsTrue()
    {
        $this->assertTrue(true);
    }
    
    public function testFalseIsFalse()
    {
        $this->assertFalse(false);
    }
    
    public function testStringEquals()
    {
        $this->assertEquals("Hello", "Hello");
    }
    
    public function testArrayHasKey()
    {
        $array = ['name' => 'Ivan', 'age' => 20];
        $this->assertArrayHasKey('name', $array);
    }
}