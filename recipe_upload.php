<?php
session_start();
require_once './dbconnect.php';
require_once './functions.php';
require_once './classes/UserLogic.php';

$login_user = $_SESSION['login_user'];

// ファイル関連の取得
$file          = $_FILES['img'];
$filename      = basename($file['name']);
$tmp_path      = $file['tmp_name'];
$file_err      = $file['error'];
$filesize      = $file['size'];
$upload_dir    = 'recipe_images/';
$save_filename = date('YmdHis') . $filename;
$err_msgs      = array();
$save_path     = $upload_dir . $save_filename;


// var_dump($_POST);

// それ以外のinputの取得

$dataArr = [
    'login_user'   => $login_user['id'],
    'genre'        => $_POST['genre'],
    'preference'   => $_POST['preference'],
    'recipe_name'  => $_POST['recipe_name'],
    'cooking_time' => $_POST['time'],
    'ingredients'  => $_POST['ingredients'],
    'instructions' => $_POST['instructions'],
    'episode'      => $_POST['episode'],
    'keywords'     => $_POST['keywords'],
    'yesNo'        => $_POST['yesNo']
];
var_dump($dataArr);

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
            $result = fileSave($dataArr, $filename, $save_path);

            if ($result) {
                echo 'データベースに保存しました！';
            } else {
                echo 'データベースへの保存が失敗しました！';
            }
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

header("Location: producer_top.php");
?>