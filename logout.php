<?php
session_start();
require_once './classes/UserLogic.php';

if (!$logout = filter_input(INPUT_POST, 'logout')) {
    exit('不正なリクエストです。');
}

// ログインしているか判定し、セッションが切れていたらログインしてくださいとメッセージを出す。
$result = UserLogic::checkLogin();

if (!$result) {
    exit('セッションが切れましたので、ログインし直してください。'); //セッション期限のデフォルトは24分。24分間、マイページ上で何もしないとセッションが切れてしまう。
}

// ログアウトする
UserLogic::logout();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Old+Mincho&display=swap" rel="stylesheet">

    <title>紡くっく | ログアウト</title>
</head>

<body>
    <h2>ログアウト完了</h2>
    <p>ログアウトしました。</p>
    <a href="login_form.php">ログイン画面へ</a>
</body>

</html>