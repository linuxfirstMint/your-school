<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /classrooms/index.php');
    exit;
}

$grade     = $_POST['grade'] ?? '';
$className = $_POST['class_name'] ?? '';

$db = Database::getConnection();
$stmt = $db->prepare('INSERT INTO classes (grade, class_name) VALUES (:grade, :class_name)');
$stmt->execute([':grade' => $grade, ':class_name' => $className]);

header('Location: /classrooms/index.php');
exit;
