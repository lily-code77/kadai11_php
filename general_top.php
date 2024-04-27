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

$pdo = connect();
// パートナーのprofile_idを引っ張ってくる
$sql = "SELECT profile_id FROM partners WHERE user_id=$login_user[id]";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$partners_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($partners_id);

$partners_idArr = array();
foreach ($partners_id as $partner_id) {
    array_push($partners_idArr, $partner_id["profile_id"]);
}
// var_dump($partners_idArr);

// パートナーのfile_pathをusersテーブルから引っ張ってくる
$sql = "SELECT id, file_path FROM users WHERE id IN (" . implode(",", $partners_idArr) . ");";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$partner_photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($partner_photos);

// パートナーのnameとkeywordsをprofilesテーブルから引っ張ってくる
$sql = "SELECT user_id, name, keywords FROM profiles WHERE user_id IN (" . implode(",", $partners_idArr) . ");";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$partner_descripts = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($partner_descripts);

// 「ごちそうさま」を表示する
$sql = "SELECT * FROM recipes
        JOIN thankyous
        ON recipes.id = thankyous.recipe_id;";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$thk_recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($thk_recipes);

// 「ごちそうさま」を表示する
$sql = "SELECT * FROM recipes
        JOIN bookmarks
        ON recipes.id = bookmarks.recipe_id;";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($bookmarks);

//  レシピの作者を引っ張ってくる
$sql = "SELECT recipes.id, profiles.name 
        FROM profiles JOIN recipes
        ON profiles.user_id = recipes.user_id";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$producer_names = $stmt->fetchAll(PDO::FETCH_ASSOC);

// JSONに値を渡す
$thk_recipes_json = json_encode($thk_recipes, JSON_UNESCAPED_UNICODE);
$bookmarks_json = json_encode($bookmarks, JSON_UNESCAPED_UNICODE);
$proName_json = json_encode($producer_names, JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/general_top.css">
    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Old+Mincho&display=swap" rel="stylesheet">

    <title>紡くっく | トップページ</title>
</head>

<body>
    <h1>マイページ</h1>
    <p>You are：<?php echo h($login_user['name']) ?></p>
    <form class="logout" action="logout.php" method="POST">
        <input class="b" type="submit" name="logout" value="ログアウト">
    </form>

    <h2>あなたの食卓パートナー</h2>
    <!-- パートナーの写真を表示 ＋　削除機能-->
    <div class="selected_partner">
        <?php foreach ($partner_descripts as $partner_descript) { ?>
            <div class="partner">
                <?php foreach ($partner_photos as $partner_photo) { ?>
                    <?php if ($partner_descript['user_id'] == $partner_photo['id']) : ?>
                        <img src="<?php echo "{$partner_photo['file_path']}"; ?>" alt="パートナーのアイコン">
                    <?php endif; ?>
                <?php } ?>
                <ul>
                    <li><?php echo "{$partner_descript['name']}" ?></li>
                    <li><?php echo "{$partner_descript['keywords']}" ?></li>
                </ul>
            </div>
        <?php } ?>
    </div>

    <h2>食卓パートナー検索</h2>
    <div class="pSearch">
        <form method="post" action="partner_searchbox.php">
            <label for="pWord">
                <input type="text" name="pWord" value="" placeholder="食卓パートナー名 / キーワード">
            </label>
            <input type="submit" name="submit" value="検索" id="search">
        </form>
    </div>

    <h2>レシピ検索</h2>
    <section id="searchbox">
        <form method="post" action="searchbox.php">
            <div class="label-title2">
                <p>下記、選択がない場合は全てのレシピから検索されます。</p>
                <input type="radio" name="from" value="fromPartner">パートナーのレシピから<input type="radio" name="from" value="plusB">パートナー＋ブックマークのレシピから<br>
            </div>
            <div class="mainSearch">
                <label for="word">
                    <input type="text" name="word" value="" placeholder="材料 × ジャンル × 調理時間 × キーワード">
                </label>
            </div>
            <div class="subSearch">
                <div class="element">
                    <label for="genre">
                        <div class="label-title">
                            <p>ジャンル</p>
                        </div>
                        <select name="genre" id="genre-select">
                            <option value="">選択しない</option>
                            <option value="ごはん">ごはん</option>
                            <option value="汁もの">汁もの</option>
                            <option value="おかず">おかず</option>
                            <option value="時短やつくりおき">時短やつくりおき</option>
                            <option value="ごはんのお供や保存食">ごはんのお供や保存食</option>
                            <option value="おやつ">おやつ</option>
                        </select>
                    </label>
                </div>
                <div class="element">
                    <label for="preference">
                        <div class="label-title">
                            <p>こだわり</p>
                        </div>
                        <select class="s" name="preference" id="preference-select">
                            <option value="">選択しない</option>
                            <option value="米粉">米粉</option>
                            <option value="発酵調味料">発酵調味料</option>
                            <option value="ヴィーガン">ヴィーガン</option>
                            <option value="乳製品不使用">乳製品不使用</option>
                            <option value="砂糖不使用">砂糖不使用</option>
                            <option value="卵不使用">卵不使用</option>
                        </select>
                    </label>
                </div>
                <div class="element">
                    <label for="time">
                        <div class="label-title">
                            <p>調理時間</p>
                        </div>
                        <select name="time" id="time">
                            <option value="">選択しない</option>
                            <option value="5分">5分</option>
                            <option value="10分">10分</option>
                            <option value="20分">20分</option>
                            <option value="30分">30分</option>
                            <option value="30~60分">30~60分</option>
                            <option value="60分以上">60分以上</option>
                        </select>
                    </label>
                </div>
            </div>
            <input type="submit" name="submit" value="検索" id="search">
        </form>
    </section>

    <h2>ごちそうさまでした</h2>
    <!-- 自分が作った料理の写真とコメントを表示 -->
    <ul class="messages"></ul>

    <h2>ブックマーク</h2>
    <!-- 自分がブックマークしているレシピ -->
    <ul class="bookmarks"></ul>

    <h2>記事一覧</h2>
    <!-- パートナーの記事一覧をTwitter風に見れる（いいね機能とブックマーク機能） -->
    <div class="posts"></div>

    <script>
        // 「ごちそうさま」
        //JSON受け取り
        $rec_thkRecipes_json = '<?= $thk_recipes_json ?>';
        const thkRecipeArr = JSON.parse($rec_thkRecipes_json);
        console.log({
            thkRecipeArr
        });

        $rec_proName_json = '<?= $proName_json ?>';
        const proNameArr = JSON.parse($rec_proName_json);
        console.log({
            proNameArr
        });

        for (const recipe of thkRecipeArr) {
            for (const proName of proNameArr) {
                // console.log({proName});
                // console.log(typeof proName);
                if (proName.id == recipe.id) {
                    // console.log(proName['id']);
                    recipe.madeBy = proName['name'];
                    // console.log({recipe});
                }
            }
        }
        console.log({
            thkRecipeArr
        });

        for (let i = 0; i < thkRecipeArr.length; i++) {
            const output =
                '<li class="list">' +
                '<p class="img">' +
                '<img src="' + thkRecipeArr[i]['file_path'] + '" width="300px">' +
                '<summary class="title">' +
                thkRecipeArr[i]['recipe_name'] +
                '<br>' +
                'By :' +
                thkRecipeArr[i]['madeBy'] +
                '</li>';

            $('.messages').append(output);
        }

        // 「ブックマーク」
        //JSON受け取り
        $rec_bookmarks_json = '<?= $bookmarks_json ?>';
        const bookmarksArr = JSON.parse($rec_bookmarks_json);
        console.log({
            bookmarksArr
        });

        for (const recipe of bookmarksArr) {
            for (const proName of proNameArr) {
                // console.log({proName});
                // console.log(typeof proName);
                if (proName.id == recipe.id) {
                    // console.log(proName['id']);
                    recipe.madeBy = proName['name'];
                    // console.log({recipe});
                }
            }
        }
        console.log({
            bookmarksArr
        });

        for (let i = 0; i < bookmarksArr.length; i++) {
            const output =
                '<li class="list">' +
                '<p class="img">' +
                '<img src="' + bookmarksArr[i]['file_path'] + '" width="300px">' +
                '<summary class="title">' +
                bookmarksArr[i]['recipe_name'] +
                '<br>' +
                'By :' +
                bookmarksArr[i]['madeBy'] +
                '</li>';

            $('.bookmarks').append(output);
        }
    </script>

</body>

</html>