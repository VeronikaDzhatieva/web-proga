<?php
// tests/Unit/StudentTest.php (добавить в конец)

// Этот тест всегда проходит
public function testWorkingTest()
{
    $this->assertTrue(true);
}

// ЭТОТ ТЕСТ МОЖНО СЛОМАТЬ ДЛЯ ПРОВЕРКИ CI
// Раскомментируйте для проверки падения CI
/*
public function testBrokenForCI()
{
    $this->assertEquals(2, 1 + 2); // Ожидается 2, но 1+2=3 -> тест упадет
}
*/