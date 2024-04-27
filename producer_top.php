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

$sql = "SELECT * FROM recipes";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($recipes);

$user_recipes = array();
foreach ($recipes as $recipe) {
    if ($recipe['user_id'] == $login_user['id']) {
        array_push($user_recipes, $recipe);
    }
}

// JSONに値を渡す
$json = json_encode($user_recipes, JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/producer_top.css">
    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Old+Mincho&display=swap" rel="stylesheet">

    <title>紡くっく人 | トップページ</title>
</head>

<body>
    <h1 class="logo">
        <a href="general_top.php"><img src="./hp_img/logo.png" alt="紡くっくのロゴ"></a>
    </h1>
    <h1>わたしの台所</h1>
    <p>You are：<?php echo h($login_user['name']) ?></p>
    <form class="logout" action="logout.php" method="POST">
        <input class="b" type="submit" name="logout" value="ログアウト">
    </form>
    <ul>
        <li><a href="profile.php">プロフィール</a></li>
        <li><a href="recipe_registration.php">レシピを登録する</a></li>
        <li>記事を投稿する</li>
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
        $received_json = '<?= $json ?>';
        const recipeArr = JSON.parse($received_json);
        console.log(recipeArr);
        console.log(recipeArr.length);

        for (let i = 0; i < recipeArr.length; i++) {
            const output =
                '<li class="list">' +
                '<p class="img">' +
                '<img src="' + recipeArr[i]['file_path'] + '" width="300px">' +
                '<details>' +
                '<summary class="title">' +
                recipeArr[i]['recipe_name'] +
                '<br>' +
                recipeArr[i]['genre'] +
                '<br>' +
                recipeArr[i]['preference'] +
                '<br>' +
                "調理時間：" +
                recipeArr[i]['cooking_time'] +
                '</summary>' +
                '<p class="ing">' +
                "材料：" +
                recipeArr[i]['ing'] +
                '</p>' +
                '<p class="ins">' +
                "作り方：" +
                recipeArr[i]['ins'] +
                '</p>' +
                '<p class="memo">' +
                "レシピ背景：" +
                recipeArr[i]['episode'] +
                '</p>' +
                '</details>' +
                '<p class="keywords">' +
                recipeArr[i]['keywords'] +
                '</p>' +
                '</li>';

            if (recipeArr[i]['done'] === "yes") {
                $('.item').append(output);
            } else {
                $('.unfinishedItem').append(output);
            }
        }
    </script>

</body>

</html>