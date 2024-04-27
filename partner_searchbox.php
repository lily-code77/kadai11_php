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
if (!isset($_POST['pWord'])) {
    header("Location: login_form.php");
    exit();
}

// $_POST['pWord']で入力値を取得 文字前後の空白除去&エスケープ処理
$pWord = trim(htmlspecialchars($_POST['pWord'], ENT_QUOTES));
// 文字列の中の「　」(全角空白)を「」(何もなし)に変換
$pWord = str_replace("　", "", $pWord);
// 対象文字列が何もなかったらキーワード指定なしとする
if ($pWord === "") {
    $pWord = "キーワード指定なし";
}

$pdo = connect();

// データ登録SQL作成（プロフィール）
$sql = "SELECT * FROM profiles 
        WHERE name LIKE :pWord 
        OR keywords LIKE :pWord2
        OR pr LIKE :pWord3";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':pWord', '%' . $pWord . '%', PDO::PARAM_STR);
$stmt->bindValue(':pWord2', '%' . $pWord . '%', PDO::PARAM_STR);
$stmt->bindValue(':pWord3', '%' . $pWord . '%', PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

// 全データ取得
$producers = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($producers);

// 紡くっく人のプロフィール写真
$sql = "SELECT id,file_path FROM users WHERE admin_flg=2";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

$p_photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($p_photos);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Old+Mincho&display=swap" rel="stylesheet">

    <title>紡くっく人 | 検索結果</title>
</head>

<body>
    <h3>検索結果</h3>
    <div>
        <?php foreach ($producers as $producer) { ?>
            <?php foreach ($p_photos as $p_photo) { ?>
                <?php if ($producer['user_id'] == $p_photo['id']) : ?>
                    <img src="<?php echo "{$p_photo['file_path']}"; ?>" alt="紡くっく人のアイコン" width="200px">
                <?php endif; ?>
            <?php } ?>
            <p>紡くっく人：<?php echo "{$producer['name']}"; ?></p>
            <p><?php echo "{$producer['keywords']}"; ?></p>
            <p><?php echo "{$producer['pr']}"; ?></p>
            <form method="post" action="partners_upload.php">
                <input type="radio" name="selected" value=<?= $producer['user_id'] ?>>選択
                <input type="submit" name="submit" value="確定" id="search">
            </form>
        <?php } ?>
    </div>

</body>

</html>