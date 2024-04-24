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
var_dump($partners_id);

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
var_dump($partner_photos);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>紡くっく | トップページ</title>
</head>

<body>
    <h1>マイページ</h1>
    <p>You are：<?php echo h($login_user['name']) ?></p>

    <h2>あなたの食卓パートナー</h2>
    <!-- パートナーの写真を表示 ＋　削除機能-->
    <div class="selected_partner">
        <?php foreach ($partner_photos as $partner_photo) { ?>
            <img src="<?php echo "{$partner_photo['file_path']}"; ?>" alt="パートナーのアイコン">
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
            <div class="label-title">
                <p>下記、選択がない場合は全てのレシピから検索されます。</p>
                <input type="radio" name="from" value="partner">パートナーのレシピから<input type="radio" name="from" value="plusB">パートナー＋ブックマークのレシピから<br>
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
                        <select name="preference" id="preference-select">
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
    <div class="messages"></div>

    <h2>ブックマーク</h2>
    <!-- 自分がブックマークしているレシピ -->
    <div class="bookmarks"></div>

    <h2>記事一覧</h2>
    <!-- パートナーの記事一覧をTwitter風に見れる（いいね機能とブックマーク機能） -->
    <div class="posts"></div>



</body>

</html>