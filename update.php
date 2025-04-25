<?php
$mysqli = new mysqli("localhost", "root", "", "yse_regi");
if ($mysqli->connect_error) {
    die("DB接続失敗: " . $mysqli->connect_error);
}

$amount = intval($_POST['amount']);
$success = false;
$receipt_no = '';

if ($amount > 0) {
    // 領収書番号生成（例: R-20250425-001）
    $today = date('Ymd');
    $prefix = "R-$today";

    // 同日の件数をカウントし、番号付け
    $stmt = $mysqli->prepare("SELECT COUNT(*) AS cnt FROM sales WHERE DATE(sales_at) = CURDATE()");
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    $seq = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    $receipt_no = "$prefix-$seq";

    // INSERT文
    $stmt = $mysqli->prepare("
        INSERT INTO sales (sales_at, amount, receipt_no, created_at, updated_at)
        VALUES (NOW(), ?, ?, NOW(), NOW())
    ");
    $stmt->bind_param("is", $amount, $receipt_no);
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
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            text-align: center;
            width: 400px;
        }
        .success { color: #27ae60; font-size: 24px; }
        .error { color: #e74c3c; font-size: 20px; }
        .receipt { margin-top: 10px; color: #555; font-size: 16px; }
        .link-button {
            display: inline-block; margin-top: 30px;
            background: #3498db; color: white; text-decoration: none;
            padding: 12px 24px; border-radius: 6px;
        }
        .link-button:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="card">
        <?php if ($success): ?>
            <div class="success">✅ <?= number_format($amount) ?> 円を計上しました！</div>
            <div class="receipt">領収書番号：<strong><?= $receipt_no ?></strong></div>
        <?php else: ?>
            <div class="error">⚠ 有効な金額を入力してください</div>
        <?php endif; ?>
        <a href="index.php" class="link-button">⬅ レジに戻る</a>
    </div>
</body>
</html>

