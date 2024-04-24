<?php
session_start();
require_once './dbconnect.php';
require_once './functions.php';
require_once './classes/UserLogic.php';

$login_user = $_SESSION['login_user'];

var_dump($_POST);
$dataArr = [
    'user_id'    => $login_user['id'],
    'profile_id' => $_POST['selected']
];

// 選択された、紡くっく人をDBに保存する
$result = FALSE;

$sql = "INSERT INTO partners
        (user_id, profile_id)
        VALUE
        (?, ?)";

try {
    $stmt = connect()->prepare($sql);
    $stmt->bindValue(1, $dataArr['user_id']);
    $stmt->bindValue(2, $dataArr['profile_id']);
    $result = $stmt->execute();
} catch (\Exception $e) {
    echo $e->getMessage();
}

if ($result === FALSE) {
    echo 'データベースへの保存が失敗しました！';
} else {
    echo 'データベースに保存しました！';
}

header("Location: general_top.php");