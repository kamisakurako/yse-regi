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

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>売上一覧</title>
    <style>
        body {
            font-family: 'Helvetica Neue', sans-serif;
            background-color: #f4f6f9;
            padding: 50px 20px;
            color: #333;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        .filter-form {
            margin-bottom: 30px;
            text-align: center;
        }
        select, button {
            padding: 10px 15px;
            font-size: 16px;
            margin: 0 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #2d98da;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #2274b5;
        }
        table {
            width: 90%;
            max-width: 1000px;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 14px 20px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }
        th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .summary {
            text-align: center;
            font-size: 20px;
            color: #27ae60;
            margin: 30px 0 20px;
        }
        .link-button {
            display: inline-block;
            margin-top: 40px;
            background: #34495e;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
        }
        .link-button:hover {
            background: #2c3e50;
        }
    </style>
</head>
<body>

    <h1>📊 <?= $year ?>年 <?= $month ?>月の売上一覧</h1>

    <form class="filter-form" method="get">
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
    </form>

    <div class="summary">💰 総売上：<?= number_format($total) ?> 円</div>

    <table>
        <tr>
            <th>請求書番号</th>
            <th>売上日時</th>
            <th>金額</th>
        </tr>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td>#<?= $row['id'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= number_format($row['amount']) ?> 円</td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div style="text-align:center;">
        <a href="../index.php" class="link-button">⬅ レジに戻る</a>
    </div>

</body>
</html>
