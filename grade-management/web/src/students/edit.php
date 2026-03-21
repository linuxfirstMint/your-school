<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\StudentRepository;
use App\Database;

$id   = (int) ($_GET['id'] ?? 0);
$repo = new StudentRepository();
$student = $repo->findById($id);

if ($student === false) {
    header('Location: /students/index.php');
    exit;
}

$db = Database::getConnection();
$classes = $db->query('SELECT id, grade, class_name FROM classes ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>生徒編集 | 成績管理</title>
</head>
<body>
  <h1>生徒編集</h1>
  <form method="post" action="/students/update.php">
    <input type="hidden" name="id" value="<?= $student['id'] ?>">
    <div>
      <label>クラス：
        <select name="class_id" required>
          <?php foreach ($classes as $class): ?>
          <option value="<?= $class['id'] ?>" <?= $student['class_id'] == $class['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($class['grade'] . $class['class_name']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
    <div>
      <label>出席番号：
        <input type="number" name="number" value="<?= htmlspecialchars($student['number']) ?>" required min="1" max="99">
      </label>
    </div>
    <div>
      <label>氏名：
        <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required maxlength="50">
      </label>
    </div>
    <div style="margin-top:1em;">
      <button type="submit">更新</button>
      <a href="/students/index.php">キャンセル</a>
    </div>
  </form>
</body>
</html>
