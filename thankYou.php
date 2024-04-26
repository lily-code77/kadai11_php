<?php
session_start();
require_once './dbconnect.php';
require_once './functions.php';
require_once './classes/UserLogic.php';

$login_user = $_SESSION['login_user'];

// var_dump($_POST);
$thankYou = $_POST['thankYou'];
$bookmark = $_POST['bookmark'];
// recipe_idの取得が必要

// $dataArrを作成する





// 「ごちそうさま」をDBに保存する
$result = FALSE;

$sql = "INSERT INTO thankyous
        (recipe_id, user_id)
        VALUE
        (?, ?)";

try {
    $stmt = connect()->prepare($sql);
    $stmt->bindValue(1, $dataArr['login_user']);
    $stmt->bindValue(2, $dataArr['login_user']);
    $result = $stmt->execute();
} catch (\Exception $e) {
    echo $e->getMessage();
}

if ($result === FALSE) {
    echo 'データベースへの保存が失敗しました！';
} else {
    echo 'データベースに保存しました！';
}

// header("Location: general_top.php");
?>