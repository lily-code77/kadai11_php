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
    <!-- <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css"> -->
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
    <form class="logout" action="logout.php" method="POST">
        <input class="b" type="submit" name="logout" value="ログアウト">
    </form>
    <h1>レシピを登録する</h1>
    <p>You are：<?php echo h($login_user['name']) ?></p>

    <h2>マイレシピ登録</h2>
    <form action="recipe_upload.php" method="post" enctype="multipart/form-data">
        <div class="content">
            料理名：<br><input type="text" name="recipe_name" class="input"><br>
            ジャンル：<br><select name="genre">
                <option value="">選択しない</option>
                <option value="ごはん">ごはん</option>
                <option value="汁もの">汁もの</option>
                <option value="おかず">おかず</option>
                <option value="時短やつくりおき">時短やつくりおき</option>
                <option value="ごはんのお供や保存食">ごはんのお供や保存食</option>
                <option value="おやつ">おやつ</option>
            </select><br>
            こだわり：<br><select name="preference">
                <option value="">選択しない</option>
                <option value="米粉">米粉</option>
                <option value="発酵調味料">発酵調味料</option>
                <option value="ヴィーガン">ヴィーガン</option>
                <option value="乳製品不使用">乳製品不使用</option>
                <option value="砂糖不使用">砂糖不使用</option>
                <option value="卵不使用">卵不使用</option>
            </select><br>
            写真(.png、.jpg、.gifのみ対応)：<br>
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" name="img" accept="recipe_images/*"><br>
            調理時間：<br><select name="time">
                <option value="">選択しない</option>
                <option value="5分">5分</option>
                <option value="10分">10分</option>
                <option value="20分">20分</option>
                <option value="30分">30分</option>
                <option value="30~60分">30~60分</option>
                <option value="60分以上">60分以上</option>
            </select>
            </select><br>
            材料：<br><textarea name="ingredients" class="input big" cols="70" rows="10"></textarea><br>
            作り方：<br><textarea name="instructions" class="input big" cols="70" rows="10"></textarea><br>
            レシピのエピソード：<br><textarea name="episode" id="textarea" cols="70" rows="10"></textarea><br>
            キーワード(例：#元気が出る)：<br><textarea name="keywords" id="textarea" cols="50"></textarea><br>
            完成：<input type="radio" name="yesNo" value="yes">YES<input type="radio" name="yesNo" value="no">NO<br>
        </div>
        <button class="b" type="submit">作成</button>
    </form>

    <a href="./producer_top.php">戻る</a>
</body>

</html>