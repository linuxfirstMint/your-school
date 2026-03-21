<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /classrooms/index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$db = Database::getConnection();
$stmt = $db->prepare('DELETE FROM classes WHERE id = :id');
$stmt->execute([':id' => $id]);

header('Location: /classrooms/index.php');
exit;
