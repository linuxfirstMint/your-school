<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\StudentRepository;

$id   = (int) ($_GET['id'] ?? 0);
$repo = new StudentRepository();
$student = $repo->findById($id);

if ($student === false) {
    header('Location: /students/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>生徒削除確認 | 成績管理</title>
</head>
<body>
  <h1>生徒削除確認</h1>
  <p>以下の生徒を削除します。よろしいですか？</p>
  <ul>
    <li>氏名：<?= htmlspecialchars($student['name']) ?></li>
    <li>出席番号：<?= htmlspecialchars($student['number']) ?></li>
  </ul>
  <form method="post" action="/students/destroy.php">
    <input type="hidden" name="id" value="<?= $student['id'] ?>">
    <button type="submit">削除する</button>
    <a href="/students/index.php">キャンセル</a>
  </form>
</body>
</html>
