<?php
// www/check_queue.php
require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

echo "<!DOCTYPE html>
<html>
<head>
    <title>Статус очереди RabbitMQ</title>
    <link rel='stylesheet' href='style.css'>
</head>
<body>
    <div class='container'>";

try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    
    list($queue, $messageCount, $consumerCount) = $channel->queue_declare('lab7_queue', true);
    
    echo "<h1>📊 Статус очереди RabbitMQ</h1>";
    echo "<div style='background: #f5f5f5; padding: 20px; border-radius: 5px;'>";
    echo "<p><strong>Очередь:</strong> lab7_queue</p>";
    echo "<p><strong>Сообщений в очереди:</strong> " . $messageCount . "</p>";
    echo "<p><strong>Активных потребителей:</strong> " . $consumerCount . "</p>";
    
    // Показываем последние обработанные сообщения
    if (file_exists('processed_rabbit.log')) {
        $lines = file('processed_rabbit.log', FILE_IGNORE_NEW_LINES);
        $lastMessages = array_slice($lines, -5);
        
        echo "<h3>Последние 5 обработанных сообщений:</h3>";
        echo "<pre>";
        foreach ($lastMessages as $msg) {
            echo $msg . "\n\n";
        }
        echo "</pre>";
    }
    
    echo "</div>";
    
    $channel->close();
    $connection->close();
    
} catch (Exception $e) {
    echo "<h1>❌ Ошибка подключения к RabbitMQ</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}

echo "<p><a href='form.html' class='btn'>Назад к форме</a></p>";
echo "</div></body></html>";