<?php
// www/worker.php
require 'vendor/autoload.php';
require 'QueueManager.php';
require 'db.php'; 

$q = new QueueManager();

echo "====================================\n";
echo "RabbitMQ Worker запущен...\n";
echo "Ожидание сообщений в очереди...\n";
echo "====================================\n";

try {
    if (isset($pdo)) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS queue_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            email VARCHAR(255),
            message TEXT,
            data JSON,
            processed_at DATETIME
        )");
    }
} catch (Exception $e) {
    echo "База данных не подключена\n";
}

$q->consume(function($data) {
    echo "Получено сообщение: " . date('H:i:s') . "\n";
    echo "   Данные: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
    
    echo "  Обработка...\n";
    sleep(2);
    
    file_put_contents('processed_rabbit.log', json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
    
    global $pdo;
    if (isset($pdo)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO queue_messages (name, email, message, data, processed_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([
                $data['name'] ?? '',
                $data['email'] ?? '',
                $data['message'] ?? '',
                json_encode($data, JSON_UNESCAPED_UNICODE)
            ]);
            echo "   Сохранено в БД\n";
        } catch (Exception $e) {
            echo "   Ошибка БД: " . $e->getMessage() . "\n";
        }
    }
    
    echo "Обработано! " . date('H:i:s') . "\n";
    echo "------------------------------------\n";
});