<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\CsvExporter;
use App\Database;

$db = Database::getConnection();

$testId  = isset($_GET['test_id']) ? (int) $_GET['test_id'] : 0;
$sortMap = [
    'student_id' => 'e.student_id ASC',
    'class'      => 'c.grade ASC, c.class_name ASC, s.number ASC',
    'number'     => 's.number ASC',
    'name'       => 's.name ASC',
    'kokugo'     => 'e.kokugo DESC',
    'sugaku'     => 'e.sugaku DESC',
    'eigo'       => 'e.eigo DESC',
    'rika'       => 'e.rika DESC',
    'shakai'     => 'e.shakai DESC',
    'goukei'     => 'e.goukei DESC',
];
$sortKey = $_GET['sort'] ?? 'class';
if (!array_key_exists($sortKey, $sortMap)) {
    $sortKey = 'class';
}

if ($testId <= 0) {
    header('Location: /exams/index.php');
    exit;
}

$stmt = $db->prepare(
    'SELECT e.*, s.name AS student_name, s.number AS student_number, c.grade, c.class_name
     FROM exams e
     JOIN students s ON s.id = e.student_id
     JOIN classes c ON c.id = s.class_id
     WHERE e.test_id = :test_id
     ORDER BY ' . $sortMap[$sortKey]
);
$stmt->execute([':test_id' => $testId]);
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $db->prepare('SELECT year, name FROM tests WHERE id = :id');
$stmt2->execute([':id' => $testId]);
$test = $stmt2->fetch(PDO::FETCH_ASSOC);

$filename = $test
    ? $test['year'] . '年度_' . $test['name'] . '.csv'
    : 'exams.csv';

$csv = CsvExporter::generate($exams);

// UTF-8 BOM 付きで出力（Excel での文字化け防止）
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"');
header('Cache-Control: no-cache');

echo "\xEF\xBB\xBF" . $csv;
exit;
