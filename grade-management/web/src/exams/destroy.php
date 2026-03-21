<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\ExamRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /exams/index.php');
    exit;
}

$id     = (int) ($_POST['id'] ?? 0);
$testId = (int) ($_POST['test_id'] ?? 0);

$repo = new ExamRepository();
$repo->delete($id);

header('Location: /exams/index.php?test_id=' . $testId);
exit;
