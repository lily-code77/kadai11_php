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

// $_POST['word']で入力値を取得 文字前後の空白除去&エスケープ処理
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

// partnerのprofile_id(=user_id partnerテーブルでprofile_idと命名したのが誤解を招いてしまっている)を引っ張ってくる
$sql = "SELECT recipes.id, profiles.name 
        FROM profiles JOIN recipes
        ON profiles.user_id = recipes.user_id";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$producer_names = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($producer_names);

// JSONに値を渡す
$recipe_json = json_encode($recipes, JSON_UNESCAPED_UNICODE);
$proName_json = json_encode($producer_names, JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/searchbox.css">
    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Old+Mincho&display=swap" rel="stylesheet">

    <title>レシピ検索の結果</title>
</head>

<body>
    <h3>検索結果</h3>
    <ul class="results"></ul>

    <script>
        //JSON受け取り
        $rec_recipe_json = '<?= $recipe_json ?>';
        const recipeArr = JSON.parse($rec_recipe_json);
        // console.log({recipeArr});

        $rec_proName_json = '<?= $proName_json ?>';
        const proNameArr = JSON.parse($rec_proName_json);
        // console.log({proNameArr});

        // console.log(typeof recipeArr);
        // console.log(typeof proNameArr);

        // recipeArrにmadeByのkey＆valueを入れる
        for (const recipe of recipeArr) {
            for (const proName of proNameArr) {
                // console.log({proName});
                // console.log(typeof proName);
                // console.log(proName['id']);
                if (proName.id == recipe.id) {
                    recipe.madeBy = proName['name'];
                    // console.log({recipe});
                }
            }
        }
        console.log({
            recipeArr
        });

        for (let i = 0; i < recipeArr.length; i++) {
            const output =
                '<li class="list">' +
                '<p class="img">' +
                '<img src="' + recipeArr[i]['file_path'] + '" width="300px">' +
                '<details>' +
                '<summary class="title">' +
                recipeArr[i]['recipe_name'] +
                '<br>' +
                'By :' +
                recipeArr[i]['madeBy'] +
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
                '</details>' +
                '<p class="keywords">' +
                recipeArr[i]['keywords'] +
                '</p>' +
                '<form action="thankYou.php" method="post">' +
                '<input type="hidden" name="recipe_id" value="' + recipeArr[i]['id'] + '" />' +
                '<input type="checkbox" name="thankYou" value="thankYou">' +
                '<label for="thankYou">ごちそうさまです</label>' +
                '<br>' +
                '<input type="checkbox" name="bookmark" value="bookmark">' +
                '<label for="bookmark">ブックマーク</label>' +
                '<button class="b" type="submit">送信</button>' +
                '</form>' +
                '</li>';

            $('.results').append(output);
        }
    </script>

</body>

</html>