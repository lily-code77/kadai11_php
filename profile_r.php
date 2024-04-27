<?php
session_start();
require_once './classes/UserLogic.php';
require_once './functions.php';
require_once './dbconnect.php';

// ログインしているか判定し、していなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if (!$result) {
    $_SESSION['login_err'] = 'ユーザを登録してログインしてください！';
    header('Location: signup_form.php');
    return;
}

$login_user = $_SESSION['login_user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Old+Mincho&display=swap" rel="stylesheet">

    <title>紡くっく人 | レシピ登録</title>
</head>

<body>
    <h1 class="logo">
        <a href="general_top.php"><img src="./hp_img/logo.png" alt="紡くっくのロゴ"></a>
    </h1>
    <p>You are：<?php echo h($login_user['name']) ?></p>
    <form class="logout" action="logout.php" method="POST">
        <input class="b" type="submit" name="logout" value="ログアウト">
    </form>
    <h1>プロフィールを登録する</h1>

    <form action="p_upload.php" method="post" enctype="multipart/form-data">
        <div class="content">
            表示名：<br><input type="text" name="name" class="input"><br>
            キーワード(例：#米粉マイスター)：<br><textarea name="keywords" id="textarea" cols="50"></textarea><br>
            アピール：<br><textarea name="pr" class="input big" cols="70" rows="10"></textarea><br>
        </div>
        <button class="b" type="submit">作成</button>
    </form>
</body>

</html>