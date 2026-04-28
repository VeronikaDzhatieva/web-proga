<?php
// www/send.php
require 'vendor/autoload.php';
require 'QueueManager.php';

session_start();

$q = new QueueManager();

// Получаем данные из формы (адаптируйте под вашу форму)
$name = $_POST['name'] ?? $_GET['name'] ?? 'Тестовый пользователь';
$data = [
    'name' => $name,
    'email' => $_POST['email'] ?? $_GET['email'] ?? 'test@example.com',
    'message' => $_POST['message'] ?? $_GET['message'] ?? 'Тестовое сообщение',
    'timestamp' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];

// Отправляем в очередь
$q->publish($data);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Отправка в очередь</title>
    <link rel='stylesheet' href='style.css'>
</head>
<body>
    <div class='container'>
        <h1>✅ Сообщение отправлено в очередь RabbitMQ</h1>
        <div style='background: #f5f5f5; padding: 20px; border-radius: 5px;'>
            <h3>Данные:</h3>
            <pre>" . print_r($data, true) . "</pre>
        </div>
        <p><a href='form.html' class='btn'>Назад к форме</a></p>
        <p><a href='check_queue.php' class='btn'>Проверить статус очереди</a></p>
    </div>
</body>
</html>";