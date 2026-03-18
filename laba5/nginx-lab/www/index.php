<?php 
session_start(); 
require 'db.php';
require 'Student.php';

$student = new Student($pdo);
// Создаем таблицу, если её нет
$student->createTable();

// Получаем все записи
$allRecords = $student->getAll();
$totalCount = $student->getCount();

// Фильтр по возрасту
$filteredRecords = null;
if(isset($_GET['filter']) && $_GET['filter'] == 'older') {
    $filteredRecords = $student->getOlderThan(2005); // Старше 18 лет (родились до 2005)
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация на конференцию</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .stats {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .records {
            margin-top: 30px;
        }
        .record-item {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        .record-item small {
            color: #666;
            font-size: 0.8em;
        }
        .filters {
            margin: 20px 0;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .filter-link {
            display: inline-block;
            padding: 8px 15px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .filter-link:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Регистрация на конференцию</h1>
        
        <?php if(isset($_SESSION['errors'])): ?>
            <ul style="color:red;">    
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['form_data'])): ?>
            <?php 
            $data = $_SESSION['form_data'];
            
            $section_text = '';
            switch($data['section']) {
                case 'it': $section_text = 'IT и программирование'; break;
                case 'medicine': $section_text = 'Медицина'; break;
                case 'business': $section_text = 'Бизнес'; break;
                case 'science': $section_text = 'Наука'; break;
                default: $section_text = $data['section'];
            }
            
            $participation_text = ($data['participation'] == 'online') ? 'Онлайн' : 'Очно';
            ?>
            
            <div class="stats">
                <h3>✅ Данные успешно сохранены!</h3>
                <p><strong>Имя:</strong> <?= $data['name'] ?></p>
                <p><strong>Год рождения:</strong> <?= $data['birthYear'] ?></p>
                <p><strong>Секция:</strong> <?= $section_text ?></p>
                <p><strong>Сертификат:</strong> <?= $data['certificate'] ?></p>
                <p><strong>Форма участия:</strong> <?= $participation_text ?></p>
            </div>
            <?php unset($_SESSION['form_data']); ?>
        <?php endif; ?>

        <!-- Статистика -->
        <div class="stats">
            <h3>📊 Статистика</h3>
            <p>Всего записей в базе: <strong><?= $totalCount ?></strong></p>
        </div>

        <!-- Фильтры -->
        <div class="filters">
            <h3>🔍 Фильтры:</h3>
            <a href="index.php" class="filter-link">Все записи</a>
            <a href="index.php?filter=older" class="filter-link">Участники старше 18 лет</a>
        </div>

        <!-- Записи из БД -->
        <div class="records">
            <h3>📋 Сохранённые данные из БД:</h3>
            
            <?php 
            $recordsToShow = $filteredRecords ? $filteredRecords : $allRecords;
            
            if(empty($recordsToShow)): ?>
                <p>Пока нет записей. <a href="form.html">Заполните форму</a></p>
            <?php else: ?>
                <?php foreach($recordsToShow as $row): 
                    $section_text = '';
                    switch($row['section']) {
                        case 'it': $section_text = 'IT и программирование'; break;
                        case 'medicine': $section_text = 'Медицина'; break;
                        case 'business': $section_text = 'Бизнес'; break;
                        case 'science': $section_text = 'Наука'; break;
                        default: $section_text = $row['section'];
                    }
                    
                    $participation_text = ($row['participation_type'] == 'online') ? 'Онлайн' : 'Очно';
                ?>
                    <div class="record-item">
                        <strong><?= htmlspecialchars($row['name']) ?></strong> 
                        (<?= $row['birth_year'] ?> г.р.)<br>
                        Секция: <?= $section_text ?><br>
                        Сертификат: <?= $row['need_certificate'] ? 'Да' : 'Нет' ?><br>
                        Форма участия: <?= $participation_text ?><br>
                        <small>📅 Дата регистрации: <?= $row['created_at'] ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <p style="margin-top: 20px;">
            <a href="form.html">Заполнить форму</a>
        </p>
    </div>
</body>
</html>