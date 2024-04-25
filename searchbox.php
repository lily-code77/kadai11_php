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

// 直接アクセスされたらリダイレクト
if (!isset($_POST['word'])) {
    header("Location: login_form.php");
    exit();
}

// $_POST['pWord']で入力値を取得 文字前後の空白除去&エスケープ処理
$word = trim(htmlspecialchars($_POST['word'], ENT_QUOTES));
// 文字列の中の「　」(全角空白)を「」(何もなし)に変換
$word = str_replace("　", "", $word);
// 対象文字列が何もなかったらキーワード指定なしとする
if ($word === "") {
    $word = "キーワード指定なし";
}
// var_dump($_POST);
// それ以外のinputの取得
$genre = $_POST['genre'];
$preference = $_POST['preference'];
$time = $_POST['time'];

$pdo = connect();

// データ登録SQL作成（プロフィール）
$sql = "SELECT * FROM recipes 
        WHERE recipe_name LIKE :word 
        OR ing LIKE :word2
        OR episode LIKE :word3
        OR keywords LIKE :word4
        AND genre=:genre
        AND preference=:preference
        AND cooking_time=:cooking_time";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':word', '%' . $word . '%', PDO::PARAM_STR);
$stmt->bindValue(':word2', '%' . $word . '%', PDO::PARAM_STR);
$stmt->bindValue(':word3', '%' . $word . '%', PDO::PARAM_STR);
$stmt->bindValue(':word4', '%' . $word . '%', PDO::PARAM_STR);
$stmt->bindValue(':genre', $genre, PDO::PARAM_STR);
$stmt->bindValue(':preference', $preference, PDO::PARAM_STR);
$stmt->bindValue(':cooking_time', $time, PDO::PARAM_STR);

$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

// 全データ取得
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($recipes);

// partnerのprofile_id(=user_id partnerテーブルでprofile_idと命名したのが誤解を招いてしまっている)を引っ張ってくる
$sql = "SELECT profile_id FROM partners WHERE user_id=$login_user[id]";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$partners_id = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レシピ検索の結果</title>
</head>

<body>
    <h3>検索結果</h3>
    <div>
        <?php foreach ($recipes as $recipe) { ?>
            <p><?php echo "{$recipe['recipe_name']}"; ?></p>
        <?php } ?>
    </div>

</body>

</html>