<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\StudentRepository;
use App\Database;

$repo = new StudentRepository();
$students = $repo->findAll();

// class_id → class_name の対応表
$db = Database::getConnection();
$classes = $db->query('SELECT id, grade, class_name FROM classes ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
$classMap = array_column($classes, null, 'id');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>生徒一覧 | 成績管理</title>
</head>
<body>
  <h1>生徒一覧</h1>

  <?php if (($_GET['notice'] ?? '') === 'no_students'): ?>
  <p style="color:red;">選択したクラスに生徒が登録されていません。先に生徒を登録してください。</p>
  <?php endif; ?>

  <a href="/students/create.php">＋ 生徒を登録</a>
  <table border="1" cellpadding="6" style="margin-top:1em;border-collapse:collapse;">
    <thead>
      <tr>
        <th>ID</th><th>クラス</th><th>出席番号</th><th>氏名</th><th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($students as $student): ?>
      <?php $class = $classMap[$student['class_id']] ?? null; ?>
      <tr>
        <td><?= htmlspecialchars($student['id']) ?></td>
        <td><?= $class ? htmlspecialchars($class['grade'] . $class['class_name']) : '?' ?></td>
        <td><?= htmlspecialchars($student['number']) ?></td>
        <td><?= htmlspecialchars($student['name']) ?></td>
        <td>
          <a href="/students/edit.php?id=<?= $student['id'] ?>">編集</a>
          <a href="/students/delete.php?id=<?= $student['id'] ?>">削除</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="/">← トップへ</a></p>
</body>
</html>
