<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\TestRepository;

$id = (int) ($_GET['id'] ?? 0);
$repo = new TestRepository();
$test = $repo->findById($id);

if ($test === false) {
    header('Location: /tests/index.php');
    exit;
}

$testNames = ['前期中間テスト', '前期期末テスト', '後期中間テスト', '後期期末テスト'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>テスト編集 | 成績管理</title>
</head>
<body>
  <h1>テスト編集</h1>
  <form method="post" action="/tests/update.php">
    <input type="hidden" name="id" value="<?= $test['id'] ?>">
    <div>
      <label>年度：
        <input type="number" name="year" value="<?= htmlspecialchars($test['year']) ?>" required min="2000" max="2099">
      </label>
    </div>
    <div>
      <label>テスト名：
        <select name="name" required>
          <?php foreach ($testNames as $name): ?>
          <option value="<?= htmlspecialchars($name) ?>" <?= $test['name'] === $name ? 'selected' : '' ?>>
            <?= htmlspecialchars($name) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
    <div style="margin-top:1em;">
      <button type="submit">更新</button>
      <a href="/tests/index.php">キャンセル</a>
    </div>
  </form>
</body>
</html>
