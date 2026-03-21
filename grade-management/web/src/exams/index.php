<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

$db = Database::getConnection();

$tests = $db->query('SELECT * FROM tests ORDER BY year, id')->fetchAll(PDO::FETCH_ASSOC);

$selectedTestId = isset($_GET['test_id']) ? (int) $_GET['test_id'] : 0;

// 許可するソートキーと対応する ORDER BY 句
$sortMap = [
  'student_id' => 'e.student_id ASC',
  'class'      => 'c.grade ASC, c.class_name ASC, s.number ASC',
  'number'     => 's.number ASC',
  'name'    => 's.name ASC',
  'kokugo'  => 'e.kokugo DESC',
  'sugaku'  => 'e.sugaku DESC',
  'eigo'    => 'e.eigo DESC',
  'rika'    => 'e.rika DESC',
  'shakai'  => 'e.shakai DESC',
  'goukei'  => 'e.goukei DESC',
];
$sortKey = $_GET['sort'] ?? 'class';
if (!array_key_exists($sortKey, $sortMap)) {
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
         ORDER BY ' . $sortMap[$sortKey]
  );
  $stmt->execute([':test_id' => $selectedTestId]);
  $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmt2 = $db->prepare('SELECT * FROM tests WHERE id = :id');
  $stmt2->execute([':id' => $selectedTestId]);
  $selectedTest = $stmt2->fetch(PDO::FETCH_ASSOC);
}

// ソートリンクを生成するヘルパー
function sortLink(string $key, string $label, string $currentSort, int $testId): string
{
  $indicator = $currentSort === $key ? '▲' : '▽';
  $style = $currentSort === $key ? 'font-weight:bold;' : '';
  $url = '?test_id=' . $testId . '&sort=' . $key;
  return "<a href=\"{$url}\" style=\"{$style}text-decoration:none;color:inherit;\">{$label} {$indicator}</a>";
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

  <p>
    <?php foreach ($tests as $i => $test): ?>
      <?php if ($i > 0): ?> / <?php endif; ?>
      <?php if ($selectedTestId === (int)$test['id']): ?>
        <strong><?= htmlspecialchars($test['name']) ?></strong>
      <?php else: ?>
        <a href="?test_id=<?= $test['id'] ?>&sort=<?= htmlspecialchars($sortKey) ?>">
          <?= htmlspecialchars($test['name']) ?>
        </a>
      <?php endif; ?>
    <?php endforeach; ?>
  </p>





  <?php if ($selectedTest): ?>
    <h2><?= htmlspecialchars($selectedTest['year'] . '年度 ' . $selectedTest['name']) ?></h2>

    <a href="/exams/create.php?test_id=<?= $selectedTestId ?>">＋ このテストの成績を一括登録</a>

    <table border="1" cellpadding="6" style="margin-top:1em;border-collapse:collapse;">
      <thead>
        <tr>
          <th><?= sortLink('student_id', '学生番号', $sortKey, $selectedTestId) ?></th>
          <th><?= sortLink('class',  'クラス',   $sortKey, $selectedTestId) ?></th>
          <th><?= sortLink('number', '出席番号', $sortKey, $selectedTestId) ?></th>
          <th><?= sortLink('name',   '氏名',     $sortKey, $selectedTestId) ?></th>
          <th><?= sortLink('kokugo', '国語',     $sortKey, $selectedTestId) ?></th>
          <th><?= sortLink('sugaku', '数学',     $sortKey, $selectedTestId) ?></th>
          <th><?= sortLink('eigo',   '英語',     $sortKey, $selectedTestId) ?></th>
          <th><?= sortLink('rika',   '理科',     $sortKey, $selectedTestId) ?></th>
          <th><?= sortLink('shakai', '社会',     $sortKey, $selectedTestId) ?></th>
          <th><?= sortLink('goukei', '合計',     $sortKey, $selectedTestId) ?></th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($exams as $exam): ?>
          <tr>
            <td><?= htmlspecialchars($exam['student_id']) ?></td>
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