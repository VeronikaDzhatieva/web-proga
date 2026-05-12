<?php

class Student
{
    private $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * Регистрация студента
     */
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

        // 5. Форма обучения (радио-кнопка)
        $allowedForms = ['очная', 'заочная', 'очно-заочная'];
        if (!in_array($data['study_form'], $allowedForms)) {
            return "Ошибка: Неверная форма обучения.";
        }

        // Если бы была реальная БД, здесь был бы запрос через $this->pdo
        
        return "Студент {$data['name']} успешно зарегистрирован!";
    }
}