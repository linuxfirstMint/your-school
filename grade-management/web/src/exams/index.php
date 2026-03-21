<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\ExamRepository;

$db = Database::getConnection();

$tests = $db->query('SELECT * FROM tests ORDER BY year, id')->fetchAll(PDO::FETCH_ASSOC);

$selectedTestId = isset($_GET['test_id']) ? (int) $_GET['test_id'] : 0;
$sortKey = $_GET['sort'] ?? 'number';
$allowedSorts = ['number', 'goukei'];
if (!in_array($sortKey, $allowedSorts, true)) {
    $sortKey = 'number';
}

$exams = [];
$selectedTest = null;
if ($selectedTestId > 0) {
    $stmt = $db->prepare(
        'SELECT e.*, s.name AS student_name, s.number AS student_number, c.grade, c.class_name
         FROM exams e
         JOIN students s ON s.id = e.student_id
         JOIN classes c ON c.id = s.class_id
         WHERE e.test_id = :test_id
         ORDER BY ' . ($sortKey === 'goukei' ? 'e.goukei DESC' : 's.number ASC')
    );
    $stmt->execute([':test_id' => $selectedTestId]);
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $db->prepare('SELECT * FROM tests WHERE id = :id');
    $stmt2->execute([':id' => $selectedTestId]);
    $selectedTest = $stmt2->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>成績一覧 | 成績管理</title>
</head>
<body>
  <h1>成績一覧</h1>

  <form method="get">
    <label>テストを選択：
      <select name="test_id" onchange="this.form.submit()">
        <option value="">-- 選択してください --</option>
        <?php foreach ($tests as $test): ?>
        <option value="<?= $test['id'] ?>" <?= $selectedTestId === (int)$test['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($test['year'] . '年度 ' . $test['name']) ?>
        </option>
        <?php endforeach; ?>
      </select>
    </label>
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sortKey) ?>">
  </form>

  <?php if ($selectedTest): ?>
  <h2><?= htmlspecialchars($selectedTest['year'] . '年度 ' . $selectedTest['name']) ?></h2>

  <p>
    並び替え：
    <a href="?test_id=<?= $selectedTestId ?>&sort=number">出席番号順</a>
    ｜
    <a href="?test_id=<?= $selectedTestId ?>&sort=goukei">合計点順</a>
  </p>

  <a href="/exams/create.php?test_id=<?= $selectedTestId ?>">＋ このテストの成績を一括登録</a>

  <table border="1" cellpadding="6" style="margin-top:1em;border-collapse:collapse;">
    <thead>
      <tr>
        <th>クラス</th><th>出席番号</th><th>氏名</th>
        <th>国語</th><th>数学</th><th>英語</th><th>理科</th><th>社会</th><th>合計</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($exams as $exam): ?>
      <tr>
        <td><?= htmlspecialchars($exam['grade'] . $exam['class_name']) ?></td>
        <td><?= htmlspecialchars($exam['student_number']) ?></td>
        <td><?= htmlspecialchars($exam['student_name']) ?></td>
        <td><?= $exam['kokugo'] ?? '―' ?></td>
        <td><?= $exam['sugaku'] ?? '―' ?></td>
        <td><?= $exam['eigo'] ?? '―' ?></td>
        <td><?= $exam['rika'] ?? '―' ?></td>
        <td><?= $exam['shakai'] ?? '―' ?></td>
        <td><?= $exam['goukei'] ?? '―' ?></td>
        <td>
          <a href="/exams/edit.php?id=<?= $exam['id'] ?>">点数入力</a>
          <a href="/exams/delete.php?id=<?= $exam['id'] ?>">削除</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>

  <p><a href="/">← トップへ</a></p>
</body>
</html>
