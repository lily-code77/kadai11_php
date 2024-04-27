<?php
session_start();
require_once './classes/UserLogic.php';
require_once './functions.php';
// require_once './dbconnect.php';

// エラーメッセージ
$err = [];

$token = filter_input(INPUT_POST, 'csrf_token');
// トークンがない、もしくは一致しない場合、処理を中止
if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
    exit('不正なリクエスト');
}

unset($_SESSION['csrf_token']);

// バリデーション
if (!$username = filter_input(INPUT_POST, 'username')) {
    $err[] = 'ユーザー名を入力してください。';
}
if (!$email = filter_input(INPUT_POST, 'email')) {
    $err[] = 'メールアドレスを入力してください。';
}
$password = filter_input(INPUT_POST, 'password');
// 正規表現
if (!preg_match("/\A[a-z\d]{6,100}+\z/i", $password)) {
    $err[] = 'パスワードは英数字6文字以上100文字以下にしてください。';
}
$password_conf = filter_input(INPUT_POST, 'password_conf');
if ($password !== $password_conf) {
    $err[] = '確認用パスワードと異なっています。';
}

// ファイル関連の取得
$file          = $_FILES['img'];
$filename      = basename($file['name']);
$tmp_path      = $file['tmp_name'];
$file_err      = $file['error'];
$filesize      = $file['size'];
// $upload_dir    = '/Applications/XAMPP/xamppfiles/htdocs/gs/kadai08_db1/public/images/';
$upload_dir    = 'profile_images/';
$save_filename = date('YmdHis') . $filename;
$err_msgs      = array();
$save_path     = $upload_dir . $save_filename;

// ファイルのバリデーション
// ファイルのサイズが1MG未満か
if ($filesize > 1048576 || $file_err == 2) {
    array_push($err_msgs, 'ファイルサイズは1MB未満にしてください。');
}

// 拡張は画像形式か
$allow_ext = array('jpg', 'jpeg', 'png');
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);

if (!in_array(strtolower($file_ext), $allow_ext)) {
    array_push($err_msgs, '画像ファイルを添付してください。');
}

if (count($err_msgs) === 0) {
    // ファイルはあるかどうか？
    if (is_uploaded_file($tmp_path)) {
        if (move_uploaded_file($tmp_path, $save_path)) {
            echo $filename . 'を' . $upload_dir . 'をアップしました。';
            //DBに保存（ファイル名、ファイルパス、キャプション）
            // $result = fileSave($login_user, $recipe_name, $filename, $save_path, $ingredients, $instructions, $episode);

            // if ($result) {
            //     echo 'データベースに保存しました！';
            // } else {
            //     echo 'データベースへの保存が失敗しました！';
            // }
        } else {
            echo 'ファイルが保存できませんでした。';
        }
    } else {
        echo 'ファイルが選択されていません。';
        echo '<br>';
    }
} else {
    foreach ($err_msgs as $msg) {
        echo $msg;
        echo '<br>';
    }
}


if (count($err) === 0) {
    // ユーザーを登録する処理
    // var_dump($_POST);
    $hasCreated = UserLogic::createUser($_POST, $filename, $save_path);

    if (!$hasCreated) {
        $err[] = '登録に失敗しました';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Old+Mincho&display=swap" rel="stylesheet">

    <title>紡くっく | ユーザー登録完了画面</title>
</head>

<body>
    <?php if (count($err) > 0) : ?>
        <?php foreach ($err as $e) : ?>
            <p><?php echo $e ?></p>
        <?php endforeach ?>
    <?php else : ?>
        <p>ユーザー登録が完了しました。</p>
    <?php endif ?>
    <a href="./signup_form.php">戻る</a>

</body>

</html>