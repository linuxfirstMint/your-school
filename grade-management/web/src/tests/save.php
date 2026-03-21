<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\TestRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /tests/index.php');
    exit;
}

$year = (int) ($_POST['year'] ?? 0);
$name = $_POST['name'] ?? '';

$repo = new TestRepository();
$repo->create($year, $name);

header('Location: /tests/index.php');
exit;
