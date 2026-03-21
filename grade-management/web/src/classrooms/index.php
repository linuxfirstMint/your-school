<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

$db = Database::getConnection();
$classes = $db->query('SELECT * FROM classes ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);

$notice = $_GET['notice'] ?? '';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>クラス一覧 | 成績管理</title>
</head>
<body>
  <h1>クラス一覧</h1>

  <?php if ($notice === 'no_classes'): ?>
  <p style="color:red;">先にクラスを登録してください。</p>
  <?php endif; ?>

  <a href="/classrooms/create.php">＋ クラスを登録</a>

  <table border="1" cellpadding="6" style="margin-top:1em;border-collapse:collapse;">
    <thead>
      <tr><th>ID</th><th>学年</th><th>組</th><th>操作</th></tr>
    </thead>
    <tbody>
      <?php foreach ($classes as $class): ?>
      <tr>
        <td><?= htmlspecialchars($class['id']) ?></td>
        <td><?= htmlspecialchars($class['grade']) ?></td>
        <td><?= htmlspecialchars($class['class_name']) ?></td>
        <td>
          <a href="/classrooms/edit.php?id=<?= $class['id'] ?>">編集</a>
          <a href="/classrooms/delete.php?id=<?= $class['id'] ?>">削除</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="/">← トップへ</a></p>
</body>
</html>
