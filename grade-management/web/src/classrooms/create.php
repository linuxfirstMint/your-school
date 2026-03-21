<?php
require_once __DIR__ . '/../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>クラス登録 | 成績管理</title>
</head>
<body>
  <h1>クラス登録</h1>
  <form method="post" action="/classrooms/save.php">
    <div>
      <label>学年：
        <input type="text" name="grade" required maxlength="10" placeholder="例：1年">
      </label>
    </div>
    <div>
      <label>組：
        <input type="text" name="class_name" required maxlength="10" placeholder="例：A組">
      </label>
    </div>
    <div style="margin-top:1em;">
      <button type="submit">登録</button>
      <a href="/classrooms/index.php">キャンセル</a>
    </div>
  </form>
</body>
</html>
