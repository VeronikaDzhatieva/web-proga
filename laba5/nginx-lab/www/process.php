<?php
// process.php
session_start();
require 'db.php';
require 'Student.php';

// Создаем объект класса
$student = new Student($pdo);

// Создаем таблицу при первом запуске
$student->createTable();

// Получаем все данные из формы
$name = $_POST['name'] ?? '';
$birthYear = $_POST['birthYear'] ?? '';
$section = $_POST['section'] ?? '';
$certificate = isset($_POST['certificate']) ? 1 : 0;
$participation = $_POST['participation'] ?? 'online';

// Массив для ошибок
$errors = [];

// Проверяем имя
if(empty($name)) {
    $errors[] = "Имя не может быть пустым";
}

// Проверяем год рождения
if(empty($birthYear)) {
    $errors[] = "Год рождения не может быть пустым";
} elseif(!is_numeric($birthYear) || $birthYear < 1920 || $birthYear > 2026) {
    $errors[] = "Год рождения должен быть от 1920 до 2026";
}

// Проверяем секцию
if(empty($section)) {
    $errors[] = "Выберите секцию";
}

// Если есть ошибки
if(!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: form.html");
    exit();
}

// Сохраняем в БД
$student->add($name, $birthYear, $section, $certificate, $participation);

// Сохраняем в сессию для отображения
$_SESSION['form_data'] = [
    'name' => htmlspecialchars($name),
    'birthYear' => htmlspecialchars($birthYear),
    'section' => htmlspecialchars($section),
    'certificate' => $certificate ? 'Да' : 'Нет',
    'participation' => htmlspecialchars($participation)
];

// Перенаправляем на index.php
header("Location: index.php");
exit();
?>