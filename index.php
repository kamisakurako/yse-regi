<?php
session_start();
if (!isset($_SESSION['display'])) {
    $_SESSION['display'] = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['input'])) {
        $_SESSION['display'] .= $_POST['input'];
    } elseif (isset($_POST['ac'])) {
        $_SESSION['display'] = '';
    } elseif (isset($_POST['equal'])) {
        try {
            $_SESSION['display'] = eval('return ' . $_SESSION['display'] . ';');
        } catch (Exception $e) {
            $_SESSION['display'] = 'Error';
        }
    } elseif (isset($_POST['tax'])) {
        $_SESSION['display'] = round($_SESSION['display'] * 1.10);
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>YSEレジシステム（電卓UI）</title>
    <style>
        body { font-family: 'Arial'; text-align: center; margin-top: 50px; }
        .calculator { display: inline-block; background: #222; padding: 20px; border-radius: 15px; box-shadow: 0 0 10px #000; }
        .display { width: 100%; height: 50px; font-size: 24px; text-align: right; margin-bottom: 10px; padding: 10px; background: #fff; border: none; border-radius: 5px; }
        .button-row { display: flex; justify-content: space-between; margin: 5px 0; }
        button { width: 50px; height: 50px; font-size: 18px; margin: 2px; border: none; border-radius: 5px; background: #444; color: white; cursor: pointer; }
        button:hover { background: #666; }
        .wide-btn { width: 108px; }
        a.link-button {
            display: inline-block; background: #2196F3; color: white; padding: 10px 20px;
            margin: 5px auto; text-decoration: none; border-radius: 5px;
        }
        a.link-button:hover { background: #0b7dda; }
    </style>
</head>
<body>
    <h1>YSEレジシステム</h1>

    <!-- メッセージ欄（必要に応じて） -->
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color:green;"><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="calculator">
        <form method="post">
            <input class="display" type="text" readonly value="<?= htmlspecialchars($_SESSION['display']) ?>">
            <?php
            $buttons = [['7','8','9'], ['4','5','6'], ['1','2','3'], ['0','+','×']];
            foreach ($buttons as $row) {
                echo '<div class="button-row">';
                foreach ($row as $btn) {
                    $val = $btn === '×' ? '*' : $btn;
                    echo "<button name='input' value='{$val}'>{$btn}</button>";
                }
                echo '</div>';
            }
            ?>
            <div class="button-row">
                <button name="equal">＝</button>
                <button name="tax">税込</button>
                <button name="ac">AC</button>
            </div>
        </form>

        <!-- 売上計上フォーム（POST送信） -->
        <form action="update.php" method="post">
            <input type="hidden" name="amount" value="<?= htmlspecialchars($_SESSION['display']) ?>">
            <button class="wide-btn" type="submit">計上</button>
        </form>

        <!-- 売上表示ページへ -->
        <a class="link-button" href="sales/index.php">▶ 売上表示</a>
    </div>
</body>
</html>