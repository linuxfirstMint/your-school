<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\TestRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /tests/index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

$repo = new TestRepository();
$repo->delete($id);

header('Location: /tests/index.php');
exit;
