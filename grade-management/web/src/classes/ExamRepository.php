<?php

namespace App;

class ExamRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM exams ORDER BY id');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM exams WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findByTestId(int $testId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM exams WHERE test_id = :test_id ORDER BY id');
        $stmt->execute([':test_id' => $testId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(int $testId, int $studentId): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO exams (test_id, student_id) VALUES (:test_id, :student_id)'
        );
        $stmt->execute([':test_id' => $testId, ':student_id' => $studentId]);
        return (int) $this->db->lastInsertId();
    }

    public function updateScores(
        int $id,
        ?int $kokugo,
        ?int $sugaku,
        ?int $eigo,
        ?int $rika,
        ?int $shakai
    ): void {
        $scores = [$kokugo, $sugaku, $eigo, $rika, $shakai];
        $goukei = in_array(null, $scores, true)
            ? null
            : array_sum($scores);

        $stmt = $this->db->prepare(
            'UPDATE exams
             SET kokugo = :kokugo, sugaku = :sugaku, eigo = :eigo,
                 rika = :rika, shakai = :shakai, goukei = :goukei
             WHERE id = :id'
        );
        $stmt->execute([
            ':kokugo'  => $kokugo,
            ':sugaku'  => $sugaku,
            ':eigo'    => $eigo,
            ':rika'    => $rika,
            ':shakai'  => $shakai,
            ':goukei'  => $goukei,
            ':id'      => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM exams WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
