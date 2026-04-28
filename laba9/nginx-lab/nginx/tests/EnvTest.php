<?php
// tests/EnvTest.php

use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    protected function setUp(): void
    {
        // Загружаем переменные из .env.test
        $envFile = __DIR__ . '/../.env.test';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
    }

    public function testDatabaseEnvVariables()
    {
        $this->assertEquals('db', getenv('DB_HOST'));
        $this->assertEquals('test_db', getenv('DB_NAME'));
        $this->assertEquals('test_user', getenv('DB_USER'));
    }

    public function testTestModeEnabled()
    {
        $this->assertEquals('true', getenv('TEST_MODE'));
    }
}