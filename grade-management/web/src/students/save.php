<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\StudentRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /students/index.php');
    exit;
}

$classId = (int) ($_POST['class_id'] ?? 0);
$number  = (int) ($_POST['number'] ?? 0);
$name    = $_POST['name'] ?? '';

$repo = new StudentRepository();
$repo->create($classId, $number, $name);

header('Location: /students/index.php');
exit;
