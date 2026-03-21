<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\ExamRepository;
use App\Database;

$id   = (int) ($_GET['id'] ?? 0);
$repo = new ExamRepository();
$exam = $repo->findById($id);

if ($exam === false) {
    header('Location: /exams/index.php');
    exit;
}

$db = Database::getConnection();
$stmt = $db->prepare(
    'SELECT s.name, s.number FROM students s
     JOIN exams e ON e.student_id = s.id WHERE e.id = :id'
);
$stmt->execute([':id' => $id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>成績削除確認 | 成績管理</title>
</head>
<body>
  <h1>成績削除確認</h1>
  <p>以下の成績レコードを削除します。よろしいですか？</p>
  <ul>
    <li>生徒：<?= htmlspecialchars($student['number'] . '番 ' . $student['name']) ?></li>
  </ul>
  <form method="post" action="/exams/destroy.php">
    <input type="hidden" name="id" value="<?= $exam['id'] ?>">
    <input type="hidden" name="test_id" value="<?= $exam['test_id'] ?>">
    <button type="submit">削除する</button>
    <a href="/exams/index.php?test_id=<?= $exam['test_id'] ?>">キャンセル</a>
  </form>
</body>
</html>
