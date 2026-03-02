<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все данные</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Все сохранённые данные:</h2>
        
        <?php
        if(file_exists("data.txt")){
            $lines = file("data.txt", FILE_IGNORE_NEW_LINES);
            $lines = array_filter($lines);
            
            if(!empty($lines)) {
                echo "<ul>";
                foreach($lines as $line){
                    $data = explode(";", $line);
                    $data = array_pad($data, 5, '');
                    
                    $section_text = '';
                    switch($data[2] ?? '') {
                        case 'it': $section_text = 'IT и программирование'; break;
                        case 'medicine': $section_text = 'Медицина'; break;
                        case 'business': $section_text = 'Бизнес'; break;
                        case 'science': $section_text = 'Наука'; break;
                        default: $section_text = $data[2] ?? '';
                    }
                    
                    $participation_text = ($data[4] == 'online') ? 'Онлайн' : 'Очно';
                    
                    echo "<li>" . htmlspecialchars($data[0] ?? '') . " (" . 
                         htmlspecialchars($data[1] ?? '') . ") - " . 
                         $section_text . ", " . 
                         ($data[3] ?? '') . ", " . 
                         $participation_text . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Файл пуст</p>";
            }
        } else {
            echo "<p>Данных нет</p>";
        }
        ?>
        
        <p><a href="index.php">На главную</a></p>
    </div>
</body>
</html>