<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\TestRepository;

$repo = new TestRepository();
$tests = $repo->findAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>テスト一覧 | 成績管理</title>
</head>
<body>
  <h1>テスト一覧</h1>
  <a href="/tests/create.php">＋ テストを登録</a>
  <table border="1" cellpadding="6" style="margin-top:1em;border-collapse:collapse;">
    <thead>
      <tr>
        <th>ID</th><th>年度</th><th>テスト名</th><th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tests as $test): ?>
      <tr>
        <td><?= htmlspecialchars($test['id']) ?></td>
        <td><?= htmlspecialchars($test['year']) ?></td>
        <td><?= htmlspecialchars($test['name']) ?></td>
        <td>
          <a href="/exams/index.php?test_id=<?= $test['id'] ?>">成績を見る</a>
          <a href="/tests/edit.php?id=<?= $test['id'] ?>">編集</a>
          <a href="/tests/delete.php?id=<?= $test['id'] ?>">削除</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="/">← トップへ</a></p>
</body>
</html>
