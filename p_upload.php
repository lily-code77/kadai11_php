<?php
session_start();
require_once './dbconnect.php';
require_once './functions.php';
require_once './classes/UserLogic.php';

$login_user = $_SESSION['login_user'];

$dataArr = [
    'login_user' => $login_user['id'],
    'name'       => $_POST['name'],
    'keywords'   => $_POST['keywords'],
    'pr'         => $_POST['pr']
];
// var_dump($dataArr);

// プロフィールをDBに保存する
$result = FALSE;

$sql = "INSERT INTO profiles
        (user_id, name, keywords, pr)
        VALUE
        (?, ?, ?, ?)";

try {
    $stmt = connect()->prepare($sql);
    $stmt->bindValue(1, $dataArr['login_user']);
    $stmt->bindValue(2, $dataArr['name']);
    $stmt->bindValue(3, $dataArr['keywords']);
    $stmt->bindValue(4, $dataArr['pr']);
    $result = $stmt->execute();
} catch (\Exception $e) {
    echo $e->getMessage();
}

if ($result === FALSE) {
    echo 'データベースへの保存が失敗しました！';
} else {
    echo 'データベースに保存しました！';
}

header("Location: producer_top.php");
?>