<?php

namespace App;

class TestRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM tests ORDER BY id');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM tests WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(int $year, string $name): int
    {
        $stmt = $this->db->prepare('INSERT INTO tests (year, name) VALUES (:year, :name)');
        $stmt->execute([':year' => $year, ':name' => $name]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $year, string $name): void
    {
        $stmt = $this->db->prepare('UPDATE tests SET year = :year, name = :name WHERE id = :id');
        $stmt->execute([':year' => $year, ':name' => $name, ':id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM tests WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
