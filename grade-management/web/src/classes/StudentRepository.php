<?php

namespace App;

class StudentRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM students ORDER BY id');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(int $classId, int $number, string $name): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO students (class_id, number, name) VALUES (:class_id, :number, :name)'
        );
        $stmt->execute([':class_id' => $classId, ':number' => $number, ':name' => $name]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $classId, int $number, string $name): void
    {
        $stmt = $this->db->prepare(
            'UPDATE students SET class_id = :class_id, number = :number, name = :name WHERE id = :id'
        );
        $stmt->execute([':class_id' => $classId, ':number' => $number, ':name' => $name, ':id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM students WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
