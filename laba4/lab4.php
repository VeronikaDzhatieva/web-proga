<?php
// process.php
session_start();

// Получаем все данные из формы
$name = $_POST['name'] ?? '';
$birthYear = $_POST['birthYear'] ?? '';
$section = $_POST['section'] ?? '';
$certificate = isset($_POST['certificate']) ? 'Да' : 'Нет';
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

// Если ошибок нет - сохраняем данные в сессию
$_SESSION['form_data'] = [
    'name' => htmlspecialchars($name),
    'birthYear' => htmlspecialchars($birthYear),
    'section' => htmlspecialchars($section),
    'certificate' => $certificate,
    'participation' => htmlspecialchars($participation)
];

// Сохраняем в файл data.txt
$line = $name . ';' . $birthYear . ';' . $section . ';' . $certificate . ';' . $participation . "\n";
file_put_contents('data.txt', $line, FILE_APPEND);

// Устанавливаем куки
setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

// Получаем данные из API
require_once 'ApiClient.php';
$api = new ApiClient();

// API для стран (4 вариант)
$url = 'https://restcountries.com/v3.1/all';
$apiData = $api->request($url);

if (!isset($apiData['error'])) {
    // Берем только первые 10 стран для отображения
    $countries = array_slice($apiData, 0, 10);
    $_SESSION['api_data'] = $countries;
} else {
    $_SESSION['api_error'] = $apiData['error'];
}

// Перенаправляем на index.php
header("Location: index.php");
exit();