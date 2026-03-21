<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

$db = Database::getConnection();
$classes = $db->query('SELECT id, grade, class_name FROM classes ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);

if (empty($classes)) {
    header('Location: /classrooms/index.php?notice=no_classes');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>生徒登録 | 成績管理</title>
</head>
<body>
  <h1>生徒登録</h1>
  <form method="post" action="/students/save.php">
    <div>
      <label>クラス：
        <select name="class_id" required>
          <?php foreach ($classes as $class): ?>
          <option value="<?= $class['id'] ?>">
            <?= htmlspecialchars($class['grade'] . $class['class_name']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
    <div>
      <label>出席番号：
        <input type="number" name="number" required min="1" max="99">
      </label>
    </div>
    <div>
      <label>氏名：
        <input type="text" name="name" required maxlength="50">
      </label>
    </div>
    <div style="margin-top:1em;">
      <button type="submit">登録</button>
      <a href="/students/index.php">キャンセル</a>
    </div>
  </form>
</body>
</html>
