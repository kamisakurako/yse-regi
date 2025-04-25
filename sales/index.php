<?php
$mysqli = new mysqli("localhost", "root", "", "yse_register");
if ($mysqli->connect_error) {
    die("DB接続失敗: " . $mysqli->connect_error);
}

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('m');
$start = "{$year}-{$month}-01";
$end = date("Y-m-t", strtotime($start));

$stmt = $mysqli->prepare("SELECT id, amount, created_at FROM sales WHERE created_at BETWEEN ? AND ?");
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
    $total += $row['amount'];
}
$stmt->close();
?>

<h1>📅 <?= $year ?>年 <?= $month ?>月 の売上一覧</h1>

<form method="get" style="text-align:center;">
    <select name="year">
        <?php for ($y = 2023; $y <= date('Y'); $y++): ?>
            <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?>年</option>
        <?php endfor; ?>
    </select>
    <select name="month">
        <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $m == $month ? 'selected' : '' ?>><?= $m ?>月</option>
        <?php endfor; ?>
    </select>
    <button type="submit">検索</button>
    <a href="index.php">クリア</a>
</form>

<h2>💰 総売上：<?= number_format($total) ?> 円</h2>

<table border="1" style="margin:auto;">
    <tr><th>請求書番号</th><th>売上日時</th><th>金額</th></tr>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td><?= number_format($row['amount']) ?> 円</td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="../index.php" class="link-button">⬅ 戻る</a>

