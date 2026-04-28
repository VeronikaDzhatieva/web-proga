<?php
require 'vendor/autoload.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

echo "<h1>Лабораторная работа №6 - Вариант 4 (Товары - Elasticsearch)</h1>";

// Redis
echo "<h2>Redis Example</h2>";
$redis = new RedisExample();
$redis->setValue('framework', 'predis');
echo "Redis value: " . $redis->getValue('framework') . "<br><br>";

// Elasticsearch
echo "<h2>Elasticsearch Example (Товары - вариант 4)</h2>";
$elastic = new ElasticExample();

// Индексируем товары
echo $elastic->indexDocument('products', 1, [
    'name' => 'Ноутбук ASUS',
    'category' => 'электроника',
    'price' => 120000
]);
echo "<br>";

echo $elastic->indexDocument('products', 2, [
    'name' => 'Смартфон Samsung',
    'category' => 'электроника',
    'price' => 80000
]);
echo "<br>";

echo $elastic->indexDocument('products', 3, [
    'name' => 'Книга "1984"',
    'category' => 'книги',
    'price' => 500
]);
echo "<br><br>";

// Поиск товаров
echo "<h3>Поиск товаров по категории 'электроника':</h3>";
echo $elastic->search('products', ['category' => 'электроника']);
echo "<br><br>";

// ClickHouse
echo "<h2>Clickhouse Example</h2>";
$click = new ClickhouseExample();
echo $click->query('SELECT count() FROM system.tables');
echo "<br>";