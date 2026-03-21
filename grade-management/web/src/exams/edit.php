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
    'SELECT s.name, s.number, c.grade, c.class_name, t.year, t.name AS test_name
     FROM students s
     JOIN classes c ON c.id = s.class_id
     JOIN exams e ON e.student_id = s.id
     JOIN tests t ON t.id = e.test_id
     WHERE e.id = :id'
);
$stmt->execute([':id' => $id]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>点数入力 | 成績管理</title>
</head>
<body>
  <h1>点数入力</h1>
  <p>
    <?= htmlspecialchars($info['year'] . '年度 ' . $info['test_name']) ?> ／
    <?= htmlspecialchars($info['grade'] . $info['class_name']) ?>
    <?= htmlspecialchars($info['number']) ?>番
    <?= htmlspecialchars($info['name']) ?>
  </p>
  <form method="post" action="/exams/update.php">
    <input type="hidden" name="id" value="<?= $exam['id'] ?>">
    <table border="1" cellpadding="6" style="border-collapse:collapse;">
      <tr>
        <th>教科</th><th>点数 (0〜100)</th>
      </tr>
      <?php
      $subjects = ['kokugo' => '国語', 'sugaku' => '数学', 'eigo' => '英語', 'rika' => '理科', 'shakai' => '社会'];
      foreach ($subjects as $key => $label):
      ?>
      <tr>
        <td><?= $label ?></td>
        <td>
          <input type="number" name="<?= $key ?>" id="<?= $key ?>"
                 value="<?= $exam[$key] ?? '' ?>" min="0" max="100"
                 oninput="calcTotal()">
        </td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <td>合計</td>
        <td id="total_display">―</td>
      </tr>
    </table>
    <div style="margin-top:1em;">
      <button type="submit">保存</button>
      <a href="/exams/index.php?test_id=<?= $exam['test_id'] ?>">キャンセル</a>
    </div>
  </form>
  <script>
    function calcTotal() {
      const keys = ['kokugo', 'sugaku', 'eigo', 'rika', 'shakai'];
      let total = 0;
      let hasNull = false;
      for (const key of keys) {
        const val = document.getElementById(key).value;
        if (val === '') { hasNull = true; break; }
        total += parseInt(val, 10);
      }
      document.getElementById('total_display').textContent = hasNull ? '―' : total;
    }
    calcTotal();
  </script>
</body>
</html>
