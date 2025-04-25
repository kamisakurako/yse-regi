<?php
$mysqli = new mysqli("localhost", "root", "", "yse_register");
if ($mysqli->connect_error) {
    die("DB接続失敗: " . $mysqli->connect_error);
}

$amount = intval($_POST['amount']);
$success = false;
if ($amount > 0) {
    $stmt = $mysqli->prepare("INSERT INTO sales (amount, created_at) VALUES (?, NOW())");
    $stmt->bind_param("i", $amount);
    $stmt->execute();
    $stmt->close();
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>売上計上完了</title>
    <style>
        body {
            font-family: 'Helvetica Neue', sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 400px;
        }
        .success {
            color: #2ecc71;
            font-size: 28px;
            margin-bottom: 15px;
        }
        .error {
            color: #e74c3c;
            font-size: 24px;
        }
        .link-button {
            display: inline-block;
            margin-top: 30px;
            background: #3498db;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        .link-button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="card">
        <?php if ($success): ?>
            <div class="success">✅ <?= number_format($amount) ?> 円を計上しました！</div>
        <?php else: ?>
            <div class="error">⚠ 有効な金額を入力してください</div>
        <?php endif; ?>
        <a href="index.php" class="link-button">⬅ レジに戻る</a>
    </div>
</body>
</html>
