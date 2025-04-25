<?php
$mysqli = new mysqli("localhost", "root", "", "yse_register");
if ($mysqli->connect_error) {
    die("DB接続失敗: " . $mysqli->connect_error);
}

$amount = intval($_POST['amount']);
$message = '';
if ($amount > 0) {
    $stmt = $mysqli->prepare("INSERT INTO sales (amount, created_at) VALUES (?, NOW())");
    $stmt->bind_param("i", $amount);
    $stmt->execute();
    $stmt->close();
    $message = "<h1 class='success'>✅ {$amount} 円を計上しました</h1>";
} else {
    $message = "<h1 class='error'>⚠ 有効な金額を入力してください</h1>";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>売上計上完了</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: 'Segoe UI', sans-serif;
            text-align: center;
            margin-top: 100px;
        }
        h1.success {
            color: #4CAF50;
            font-size: 32px;
        }
        h1.error {
            color: #f44336;
            font-size: 28px;
        }
        .link-button {
            display: inline-block;
            background: #2196F3;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 30px;
            font-size: 16px;
        }
        .link-button:hover {
            background: #0b7dda;
        }
    </style>
</head>
<body>
    <?= $message ?>
    <br>
    <a href="index.php" class="link-button">⬅ レジに戻る</a>
</body>
</html>

