<?php
session_start();
require_once './dbconnect.php';
require_once './functions.php';
require_once './classes/UserLogic.php';

$login_user = $_SESSION['login_user'];

var_dump($_POST);
$thankYou = $_POST['thankYou'];
$bookmark = $_POST['bookmark'];
// recipe_idの取得が必要
    $str_recipe_id = $_POST['recipe_id'];
    // str型の$str_recipe_idをint型に変換
    $recipe_id = intval($str_recipe_id);

// $dataArrを作成する
$dataArr = [
    'user_id' => $login_user['id'],
    'recipe_id' => $recipe_id,
    'thankyou'  => $thankYou,
    'bookmark'  => $bookmark
];

// 「ごちそうさま」をDBに保存する
if ($dataArr['thankyou']) {
    $result = FALSE;

    $sql = "INSERT INTO thankyous
        (user_id, recipe_id)
        VALUE
        (?, ?)";

    try {
        $stmt = connect()->prepare($sql);
        $stmt->bindValue(1, $dataArr['user_id']);
        $stmt->bindValue(2, $dataArr['recipe_id']);
        $result = $stmt->execute();
    } catch (\Exception $e) {
        echo $e->getMessage();
    }

    if ($result === FALSE) {
        echo 'データベースへの保存が失敗しました！';
    } else {
        echo 'データベースに保存しました！';
    }
}

// 「ブックマーク」をDBに保存する
if ($dataArr['bookmark']) {
    $result = FALSE;

    $sql = "INSERT INTO bookmarks
        (user_id, recipe_id)
        VALUE
        (?, ?)";

    try {
        $stmt = connect()->prepare($sql);
        $stmt->bindValue(1, $dataArr['user_id']);
        $stmt->bindValue(2, $dataArr['recipe_id']);
        $result = $stmt->execute();
    } catch (\Exception $e) {
        echo $e->getMessage();
    }

    if ($result === FALSE) {
        echo 'データベースへの保存が失敗しました！';
    } else {
        echo 'データベースに保存しました！';
    }
}

header("Location: general_top.php");
?>