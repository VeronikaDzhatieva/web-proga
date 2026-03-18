<?php
// Student.php - класс для работы с таблицей conference (4 вариант)
class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Создание таблицы, если её нет
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS conference (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            birth_year INT,
            section VARCHAR(50),
            need_certificate TINYINT(1),
            participation_type VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    // Добавление записи
    public function add($name, $birthYear, $section, $needCertificate, $participation) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO conference (name, birth_year, section, need_certificate, participation_type) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $birthYear, $section, $needCertificate, $participation]);
    }

    // Получение всех записей
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM conference ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Фильтр по возрасту (старше определенного года)
    public function getOlderThan($year) {
        $stmt = $this->pdo->prepare("SELECT * FROM conference WHERE birth_year < ? ORDER BY created_at DESC");
        $stmt->execute([$year]);
        return $stmt->fetchAll();
    }

    // Подсчет количества записей
    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM conference");
        $result = $stmt->fetch();
        return $result['count'];
    }

    // Удаление записи
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM conference WHERE id=?");
        $stmt->execute([$id]);
    }
}
?>