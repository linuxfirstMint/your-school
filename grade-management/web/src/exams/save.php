<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\ExamRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /exams/index.php');
    exit;
}

$testId  = (int) ($_POST['test_id'] ?? 0);
$classId = (int) ($_POST['class_id'] ?? 0);

$db   = Database::getConnection();
$repo = new ExamRepository();

// クラスの生徒を取得
$stmt = $db->prepare('SELECT id FROM students WHERE class_id = :class_id ORDER BY number');
$stmt->execute([':class_id' => $classId]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// すでに登録済みの student_id を取得（重複回避）
$stmt2 = $db->prepare('SELECT student_id FROM exams WHERE test_id = :test_id');
$stmt2->execute([':test_id' => $testId]);
$existing = array_column($stmt2->fetchAll(PDO::FETCH_ASSOC), 'student_id');

foreach ($students as $student) {
    if (!in_array($student['id'], $existing, false)) {
        $repo->create($testId, (int) $student['id']);
    }
}

header('Location: /exams/index.php?test_id=' . $testId);
exit;
