<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\ExamRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /exams/index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

$toNullable = fn(string $key): ?int =>
    isset($_POST[$key]) && $_POST[$key] !== '' ? (int) $_POST[$key] : null;

$kokugo = $toNullable('kokugo');
$sugaku = $toNullable('sugaku');
$eigo   = $toNullable('eigo');
$rika   = $toNullable('rika');
$shakai = $toNullable('shakai');

$repo = new ExamRepository();
$repo->updateScores($id, $kokugo, $sugaku, $eigo, $rika, $shakai);

$exam = $repo->findById($id);
header('Location: /exams/index.php?test_id=' . ($exam['test_id'] ?? ''));
exit;
