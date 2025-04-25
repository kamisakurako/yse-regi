<?php
$mysqli = new mysqli("localhost", "root", "", "yse_regi");
if ($mysqli->connect_error) {
    die("DB接続失敗: " . $mysqli->connect_error);
}

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('m');
$start = "$year-$month-01";
$end = date("Y-m-t", strtotime($start));

$stmt = $mysqli->prepare("
    SELECT id, sales_at, amount, receipt_no, created_at, updated_at
    FROM sales
    WHERE sales_at BETWEEN ? AND ?
    ORDER BY sales_at DESC
");
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
            padding: 40px;
            color: #333;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        select, button {
            padding: 8px 12px;
            font-size: 16px;
            margin: 0 4px;
        }
        table {
            width: 95%;
            max-width: 1000px;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }
        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }
        th {
            background-color: #f8f9fa;
        }
        .summary {
            text-align: center;
            font-size: 20px;
            color: #27ae60;
            margin: 20px 0;
        }
        .link-button {
            display: inline-block;
            background: #34495e;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
        .link-button:hover {
            background: #2c3e50;
        }
    </style>
</head>
<body>

    <h1>📊 <?= $year ?>年 <?= $month ?>月の売上一覧</h1>

    <form method="get">
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
            <th>ID</th>
            <th>領収書番号</th>
            <th>売上日時</th>
            <th>金額</th>
            <th>作成日</th>
            <th>修正日</th>
        </tr>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['receipt_no']) ?></td>
                <td><?= $row['sales_at'] ?></td>
                <td><?= number_format($row['amount']) ?> 円</td>
                <td><?= $row['created_at'] ?></td>
                <td><?= $row['updated_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div style="text-align:center; margin-top:30px;">
        <a href="../index.php" class="link-button">⬅ レジに戻る</a>
    </div>

</body>
</html>

