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
// var_dump($login_user);

$pdo = connect();

// プロフィールをDBから取ってくる
$sql = "SELECT * FROM profiles WHERE user_id=$login_user[id]";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$profile = $stmt->fetch(PDO::FETCH_ASSOC);
// echo $profile['name'];

// プロフィール写真をDBから取ってくる
$sql = "SELECT file_path FROM users WHERE id=$login_user[id]";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$p_photo = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($p_photo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>紡くっく人 | プロフィール</title>
</head>

<body>
    <p><a href="profile_r.php">プロフィールを登録する</a></p>

    <div class="profile">
        <h1>プロフィール</h1>
        <ul>
            <li><img src="<?php echo "{$p_photo['file_path']}"; ?>" alt="プロフィール写真"></li>
            <li><?php echo "{$profile['name']}"; ?></li>
            <li><?php echo "{$profile['keywords']}"; ?></li>
            <li><?php echo "{$profile['pr']}"; ?></li>
        </ul>
    </div>
</body>

</html>