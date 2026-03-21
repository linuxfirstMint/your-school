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
  <title>クラス編集 | 成績管理</title>
</head>
<body>
  <h1>クラス編集</h1>
  <form method="post" action="/classrooms/update.php">
    <input type="hidden" name="id" value="<?= $class['id'] ?>">
    <div>
      <label>学年：
        <input type="text" name="grade" value="<?= htmlspecialchars($class['grade']) ?>" required maxlength="10">
      </label>
    </div>
    <div>
      <label>組：
        <input type="text" name="class_name" value="<?= htmlspecialchars($class['class_name']) ?>" required maxlength="10">
      </label>
    </div>
    <div style="margin-top:1em;">
      <button type="submit">更新</button>
      <a href="/classrooms/index.php">キャンセル</a>
    </div>
  </form>
</body>
</html>
