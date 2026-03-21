<?php
require_once __DIR__ . '/../vendor/autoload.php';

$testNames = ['前期中間テスト', '前期期末テスト', '後期中間テスト', '後期期末テスト'];
$currentYear = (int) date('Y');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>テスト登録 | 成績管理</title>
</head>
<body>
  <h1>テスト登録</h1>
  <form method="post" action="/tests/save.php">
    <div>
      <label>年度：
        <input type="number" name="year" value="<?= $currentYear ?>" required min="2000" max="2099">
      </label>
    </div>
    <div>
      <label>テスト名：
        <select name="name" required>
          <?php foreach ($testNames as $name): ?>
          <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
    <div style="margin-top:1em;">
      <button type="submit">登録</button>
      <a href="/tests/index.php">キャンセル</a>
    </div>
  </form>
</body>
</html>
