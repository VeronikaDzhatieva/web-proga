<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результат регистрации</title>
    <link rel="stylesheet" href="style.css">
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
            
            <p>Данные из сессии:</p>
            <ul>
                <li>Имя: <?= $data['name'] ?></li>
                <li>Год рождения: <?= $data['birthYear'] ?></li>
                <li>Секция: <?= $section_text ?></li>
                <li>Сертификат: <?= $data['certificate'] ?></li>
                <li>Форма участия: <?= $participation_text ?></li>
            </ul>
            <?php unset($_SESSION['form_data']); ?>
        <?php else: ?>
            <p>Данных пока нет.</p>
        <?php endif; ?>
        
        <p>
            <a href="form.html">Заполнить форму</a> |
            <a href="view.php">Посмотреть все данные</a>
        </p>
    </div>
</body>
</html>