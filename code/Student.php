<?php
class Student
{
    private $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function register(array $data): string
    {
        // 1. Валидация имени
        if (empty($data['name']) || !is_string($data['name'])) {
            return "Ошибка: Имя обязательно.";
        }

        // 2. Валидация возраста
        if (!isset($data['age']) || !is_numeric($data['age']) || $data['age'] < 16) {
            return "Ошибка: Возраст должен быть >= 16.";
        }

        // 3. Валидация факультета
        if (empty($data['faculty'])) {
            return "Ошибка: Факультет обязателен.";
        }

        // 4. Чекбокс согласия
        if (empty($data['agreed'])) {
            return "Ошибка: Вы должны согласиться с правилами.";
        }

        // 5. Форма обучения
        $allowedForms = ['очная', 'заочная', 'очно-заочная'];
        if (!in_array($data['study_form'], $allowedForms)) {
            return "Ошибка: Неверная форма обучения.";
        }

        // Если нет БД (для тестов), просто возвращаем успех
        if ($this->pdo === null) {
            return "Студент {$data['name']} успешно зарегистрирован!";
        }
        
        // Здесь был бы код сохранения в БД
        return "Студент {$data['name']} успешно зарегистрирован в БД!";
    }
}