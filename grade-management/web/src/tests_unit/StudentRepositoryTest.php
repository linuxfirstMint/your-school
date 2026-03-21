<?php

namespace Tests;

use App\Database;
use App\StudentRepository;
use PHPUnit\Framework\TestCase;

class StudentRepositoryTest extends TestCase
{
    private StudentRepository $repo;
    private int $classId;

    protected function setUp(): void
    {
        $db = Database::getConnection();
        $db->exec('DELETE FROM students');
        $db->exec('DELETE FROM classes');

        $stmt = $db->prepare('INSERT INTO classes (grade, class_name) VALUES (:grade, :class_name)');
        $stmt->execute([':grade' => '1年', ':class_name' => 'A組']);
        $this->classId = (int) $db->lastInsertId();

        $this->repo = new StudentRepository();
    }

    protected function tearDown(): void
    {
        $db = Database::getConnection();
        $db->exec('DELETE FROM students');
        $db->exec('DELETE FROM classes');
    }

    public function test生徒を登録できる(): void
    {
        $id = $this->repo->create($this->classId, 1, '山田 太郎');
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
    }

    public function test全件取得できる(): void
    {
        $this->repo->create($this->classId, 1, '山田 太郎');
        $this->repo->create($this->classId, 2, '鈴木 花子');

        $students = $this->repo->findAll();
        $this->assertCount(2, $students);
    }

    public function testIDで1件取得できる(): void
    {
        $id = $this->repo->create($this->classId, 3, '佐藤 次郎');

        $student = $this->repo->findById($id);
        $this->assertSame($this->classId, (int) $student['class_id']);
        $this->assertSame(3, (int) $student['number']);
        $this->assertSame('佐藤 次郎', $student['name']);
    }

    public function test生徒情報を更新できる(): void
    {
        $id = $this->repo->create($this->classId, 1, '山田 太郎');
        $this->repo->update($id, $this->classId, 10, '山田 一郎');

        $student = $this->repo->findById($id);
        $this->assertSame(10, (int) $student['number']);
        $this->assertSame('山田 一郎', $student['name']);
    }

    public function test生徒を削除できる(): void
    {
        $id = $this->repo->create($this->classId, 1, '山田 太郎');
        $this->repo->delete($id);

        $student = $this->repo->findById($id);
        $this->assertFalse($student);
    }
}
