<?php
// www/Student.php

class Student {
    private $pdo;
    
    public function __construct($pdo = null) {
        $this->pdo = $pdo;
    }
    
    public function add($name) {
        return "Student $name added";
    }
    
    public function addRegistration($data) {
        if (!$this->pdo) return false;
        
        $sql = "INSERT INTO conference_registrations (name, birth_year, section, need_certificate, participation_type) 
                VALUES (:name, :birth_year, :section, :need_certificate, :participation_type)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':birth_year' => $data['birthYear'],
            ':section' => $data['section'],
            ':need_certificate' => $data['certificate'],
            ':participation_type' => $data['participation']
        ]);
    }
    
    public function getCount() {
        if (!$this->pdo) return 0;
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM conference_registrations");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['count'] : 0;
    }
    
    public function getAll() {
        if (!$this->pdo) return [];
        $stmt = $this->pdo->query("SELECT * FROM conference_registrations ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOlderThan($year) {
        if (!$this->pdo) return [];
        $stmt = $this->pdo->prepare("SELECT * FROM conference_registrations WHERE birth_year < :year");
        $stmt->execute([':year' => $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}