<?php

namespace Tests;

use App\Database;
use App\ExamRepository;
use PHPUnit\Framework\TestCase;

class ExamRepositoryTest extends TestCase
{
    private ExamRepository $repo;
    private int $testId;
    private int $studentId;

    protected function setUp(): void
    {
        $db = Database::getConnection();
        $db->exec('DELETE FROM exams');
        $db->exec('DELETE FROM students');
        $db->exec('DELETE FROM classes');
        $db->exec('DELETE FROM tests');

        $stmt = $db->prepare('INSERT INTO classes (grade, class_name) VALUES (:grade, :class_name)');
        $stmt->execute([':grade' => '1年', ':class_name' => 'A組']);
        $classId = (int) $db->lastInsertId();

        $stmt = $db->prepare('INSERT INTO students (class_id, number, name) VALUES (:class_id, :number, :name)');
        $stmt->execute([':class_id' => $classId, ':number' => 1, ':name' => '山田 太郎']);
        $this->studentId = (int) $db->lastInsertId();

        $stmt = $db->prepare('INSERT INTO tests (year, name) VALUES (:year, :name)');
        $stmt->execute([':year' => 2024, ':name' => '前期中間テスト']);
        $this->testId = (int) $db->lastInsertId();

        $this->repo = new ExamRepository();
    }

    protected function tearDown(): void
    {
        $db = Database::getConnection();
        $db->exec('DELETE FROM exams');
        $db->exec('DELETE FROM students');
        $db->exec('DELETE FROM classes');
        $db->exec('DELETE FROM tests');
    }

    public function test成績を登録できる(): void
    {
        $id = $this->repo->create($this->testId, $this->studentId);
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
    }

    public function test登録直後は全教科がnullである(): void
    {
        $id = $this->repo->create($this->testId, $this->studentId);

        $exam = $this->repo->findById($id);
        $this->assertNull($exam['kokugo']);
        $this->assertNull($exam['sugaku']);
        $this->assertNull($exam['eigo']);
        $this->assertNull($exam['rika']);
        $this->assertNull($exam['shakai']);
        $this->assertNull($exam['goukei']);
    }

    public function testIDで1件取得できる(): void
    {
        $id = $this->repo->create($this->testId, $this->studentId);

        $exam = $this->repo->findById($id);
        $this->assertSame($this->testId, (int) $exam['test_id']);
        $this->assertSame($this->studentId, (int) $exam['student_id']);
    }

    public function testテストIDで一覧取得できる(): void
    {
        $this->repo->create($this->testId, $this->studentId);

        $exams = $this->repo->findByTestId($this->testId);
        $this->assertCount(1, $exams);
    }

    public function test点数を更新すると合計が自動計算される(): void
    {
        $id = $this->repo->create($this->testId, $this->studentId);
        $this->repo->updateScores($id, 90, 80, 70, 60, 50);

        $exam = $this->repo->findById($id);
        $this->assertSame(90, (int) $exam['kokugo']);
        $this->assertSame(80, (int) $exam['sugaku']);
        $this->assertSame(350, (int) $exam['goukei']);
    }

    public function test未入力の教科はnullのまま合計もnullになる(): void
    {
        $id = $this->repo->create($this->testId, $this->studentId);
        $this->repo->updateScores($id, 90, null, null, null, null);

        $exam = $this->repo->findById($id);
        $this->assertSame(90, (int) $exam['kokugo']);
        $this->assertNull($exam['sugaku']);
        $this->assertNull($exam['goukei']);
    }

    public function test成績を削除できる(): void
    {
        $id = $this->repo->create($this->testId, $this->studentId);
        $this->repo->delete($id);

        $exam = $this->repo->findById($id);
        $this->assertFalse($exam);
    }
}
