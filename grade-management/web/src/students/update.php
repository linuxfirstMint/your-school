<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\StudentRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /students/index.php');
    exit;
}

$id      = (int) ($_POST['id'] ?? 0);
$classId = (int) ($_POST['class_id'] ?? 0);
$number  = (int) ($_POST['number'] ?? 0);
$name    = $_POST['name'] ?? '';

$repo = new StudentRepository();
$repo->update($id, $classId, $number, $name);

header('Location: /students/index.php');
exit;
