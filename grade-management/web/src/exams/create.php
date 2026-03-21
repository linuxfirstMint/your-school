<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

$db = Database::getConnection();
$tests   = $db->query('SELECT * FROM tests ORDER BY year, id')->fetchAll(PDO::FETCH_ASSOC);
$classes = $db->query('SELECT * FROM classes ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);

if (empty($classes)) {
    header('Location: /classrooms/index.php?notice=no_classes');
    exit;
}
if (empty($tests)) {
    header('Location: /tests/index.php');
    exit;
}

$selectedTestId = isset($_GET['test_id']) ? (int) $_GET['test_id'] : 0;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>成績一括登録 | 成績管理</title>
</head>
<body>
  <h1>成績一括登録</h1>
  <p>テストとクラスを選択すると、クラスの全生徒分の成績レコードを作成します。</p>
  <form method="post" action="/exams/save.php">
    <div>
      <label>テスト：
        <select name="test_id" required>
          <?php foreach ($tests as $test): ?>
          <option value="<?= $test['id'] ?>" <?= $selectedTestId === (int)$test['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($test['year'] . '年度 ' . $test['name']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
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
    <div style="margin-top:1em;">
      <button type="submit">登録</button>
      <a href="/exams/index.php">キャンセル</a>
    </div>
  </form>
</body>
</html>
