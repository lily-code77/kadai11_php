<?php
session_start();

require_once './classes/UserLogic.php';

// エラーメッセージ
$err = [];

// バリデーション
if (!$email = filter_input(INPUT_POST, 'email')) {
    $err['email'] = 'メールアドレスを入力してください。';
}
if (!$password = filter_input(INPUT_POST, 'password')) {
    $err['password'] = 'パスワードを入力してください。';
};


if (count($err) > 0) {
    // エラーがあった場合は戻す
    $_SESSION = $err;
    header('Location: login_form.php');
    return;
}

// ログイン成功時の処理
$result = UserLogic::login($email, $password);
// ログイン失敗時の処理
if (!$result) {
    header('Location: login_form.php');
    return;
}

$login_id = $_SESSION['login_user']['id'];
$login_user = $_SESSION['login_user']['name'];
// var_dump($_SESSION);
// var_dump($login_id);

// ユーザーのadmin_flgの確認
$pdo = connect();

$sql = "SELECT id, admin_flg FROM users";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$admin_flgs = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($admin_flgs as $admin_flg) {
    if ($login_id == $admin_flg['id']) {
        if ($admin_flg['admin_flg'] == 1) {
            header("Location: admin_top.php");
        } else if ($admin_flg['admin_flg'] == 2) {
            header("Location: producer_top.php");
        } else {
            header("Location: general_top.php");
        }
    }
}

