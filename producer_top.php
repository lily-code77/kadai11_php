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

// $files = getAllFile();

// foreach($files as $file) {
//     var_dump($file);
// }

$pdo = connect();

$sql = "SELECT * FROM recipes";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// JSONに値を渡す
$json = json_encode($recipes, JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>紡くっく人 | トップページ</title>
</head>

<body>
    <h1 class="logo">
        <a href="general_top.php"><img src="./hp_img/logo.png" alt="紡くっくのロゴ"></a>
    </h1>
    <h1>わたしの台所</h1>
    <p>You are：<?php echo h($login_user['name']) ?></p>
    <ul>
        <li><a href="recipe_registration.php">レシピを登録する</a></li>
        <li>教室の管理</li>
        <li>ECサイト</li>
    </ul>

    <h2>あなたが紡いだレシピ</h2>
    <div class="myRecipe">
        <ul class="item"></ul>
    </div>

    <h2>作成中のレシピ</h2>
    <div class="unfinished">
        <ul class="unfinishedItem"></ul>
    </div>

    <script>
        // JSON受け取り
        $received_json = '<?=$json?>';
        const obj = JSON.parse($received_json);
        console.log(obj);


    </script>

</body>

</html>