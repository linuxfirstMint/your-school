<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\TestRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /tests/index.php');
    exit;
}

$id   = (int) ($_POST['id'] ?? 0);
$year = (int) ($_POST['year'] ?? 0);
$name = $_POST['name'] ?? '';

$repo = new TestRepository();
$repo->update($id, $year, $name);

header('Location: /tests/index.php');
exit;
