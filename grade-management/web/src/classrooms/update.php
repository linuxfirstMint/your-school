<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /classrooms/index.php');
    exit;
}

$id        = (int) ($_POST['id'] ?? 0);
$grade     = $_POST['grade'] ?? '';
$className = $_POST['class_name'] ?? '';

$db = Database::getConnection();
$stmt = $db->prepare('UPDATE classes SET grade = :grade, class_name = :class_name WHERE id = :id');
$stmt->execute([':grade' => $grade, ':class_name' => $className, ':id' => $id]);

header('Location: /classrooms/index.php');
exit;
