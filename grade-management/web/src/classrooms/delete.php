<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

$id = (int) ($_GET['id'] ?? 0);
$db = Database::getConnection();
$stmt = $db->prepare('SELECT * FROM classes WHERE id = :id');
$stmt->execute([':id' => $id]);
$class = $stmt->fetch(PDO::FETCH_ASSOC);

if ($class === false) {
    header('Location: /classrooms/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>クラス削除確認 | 成績管理</title>
</head>
<body>
  <h1>クラス削除確認</h1>
  <p>以下のクラスを削除します。よろしいですか？</p>
  <ul>
    <li><?= htmlspecialchars($class['grade'] . $class['class_name']) ?></li>
  </ul>
  <form method="post" action="/classrooms/destroy.php">
    <input type="hidden" name="id" value="<?= $class['id'] ?>">
    <button type="submit">削除する</button>
    <a href="/classrooms/index.php">キャンセル</a>
  </form>
</body>
</html>
