<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\StudentRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /students/index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

$repo = new StudentRepository();
$repo->delete($id);

header('Location: /students/index.php');
exit;
