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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>テスト削除確認 | 成績管理</title>
</head>
<body>
  <h1>テスト削除確認</h1>
  <p>以下のテストを削除します。よろしいですか？</p>
  <ul>
    <li>年度：<?= htmlspecialchars($test['year']) ?></li>
    <li>テスト名：<?= htmlspecialchars($test['name']) ?></li>
  </ul>
  <form method="post" action="/tests/destroy.php">
    <input type="hidden" name="id" value="<?= $test['id'] ?>">
    <button type="submit">削除する</button>
    <a href="/tests/index.php">キャンセル</a>
  </form>
</body>
</html>
