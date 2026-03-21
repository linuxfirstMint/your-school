-- ============================================================
-- シードデータ（開発・動作確認用）
-- 実行: make seed
-- ============================================================

-- 既存データをリセット（FK順に削除）
DELETE FROM exams;
DELETE FROM students;
DELETE FROM tests;
DELETE FROM classes;

-- オートインクリメントをリセット
ALTER TABLE exams    AUTO_INCREMENT = 1;
ALTER TABLE students AUTO_INCREMENT = 1;
ALTER TABLE tests    AUTO_INCREMENT = 1;
ALTER TABLE classes  AUTO_INCREMENT = 1;

-- ----------------------------------------
-- クラス
-- ----------------------------------------
INSERT INTO classes (grade, class_name) VALUES
  ('1年', 'A組'),
  ('1年', 'B組'),
  ('2年', 'A組');

-- ----------------------------------------
-- 生徒 (class_id: 1=1年A組, 2=1年B組, 3=2年A組)
-- ----------------------------------------
INSERT INTO students (class_id, number, name) VALUES
  (1, 1, '山田 太郎'),
  (1, 2, '鈴木 花子'),
  (1, 3, '佐藤 次郎'),
  (1, 4, '田中 美咲'),
  (2, 1, '伊藤 健太'),
  (2, 2, '渡辺 さくら'),
  (3, 1, '中村 大輔'),
  (3, 2, '小林 ゆい');

-- ----------------------------------------
-- テスト
-- ----------------------------------------
INSERT INTO tests (year, name) VALUES
  (2024, '前期中間テスト'),
  (2024, '前期期末テスト');

-- ----------------------------------------
-- 成績 (test_id=1: 前期中間, test_id=2: 前期期末)
-- student_id: 1=山田, 2=鈴木, 3=佐藤, 4=田中, 5=伊藤, 6=渡辺, 7=中村, 8=小林
-- ----------------------------------------

-- 前期中間テスト（1年A組 + 1年B組 の一部）
INSERT INTO exams (test_id, student_id, kokugo, sugaku, eigo, rika, shakai, goukei) VALUES
  (1, 1, 85, 90, 78, 88, 92, 433),
  (1, 2, 72, 65, 80, 70, 75, 362),
  (1, 3, 91, 88, 95, 85, 89, 448),
  (1, 4, 60, 55, 68, 72, 64, 319),
  (1, 5, 78, 82, 74, 79, 81, 394),
  (1, 6, 95, 92, 98, 90, 94, 469);

-- 前期期末テスト（1年A組のみ、一部 NULL で未入力を再現）
INSERT INTO exams (test_id, student_id, kokugo, sugaku, eigo, rika, shakai, goukei) VALUES
  (2, 1, 88, 92, 80, 90, 94, 444),
  (2, 2, 75, 68, 82, 73, 77, 375),
  (2, 3, NULL, NULL, NULL, NULL, NULL, NULL),  -- 未入力
  (2, 4, 62, 58, 70, 74, 66, 330);
