<?php
$mysqli = new mysqli("localhost", "root", "", "yse_register");
if ($mysqli->connect_error) {
    die("DB接続失敗: " . $mysqli->connect_error);
}

$amount = intval($_POST['amount']);
if ($amount > 0) {
    $stmt = $mysqli->prepare("INSERT INTO sales (amount, created_at) VALUES (?, NOW())");
    $stmt->bind_param("i", $amount);
    $stmt->execute();
    $stmt->close();
    echo "<h1>✅ {$amount} 円を計上しました</h1>";
} else {
    echo "<h1>⚠ 有効な金額を入力してください</h1>";
}
?>
<a href="index.php" class="link-button">⬅ 戻る</a>

