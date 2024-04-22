<?php

/**
 * XSS対策：エスケープ処理
 * 
 * @param string $ste 対象の文字列
 * @return string 処理された文字列
 */

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF対策
 * @param void
 * @return string $csrf_token
 */
function setToken()
{
    // トークンを生成
    // フォームからそのトークンを送信
    // 送信後の画面でそのトークンを照合
    // トークンを削除

    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;

    return $csrf_token;
}

/**
 * ファイルデータを保存
 * @param string $login_user ログイン中のユーザー名
 * @param string $recipe_name レシピ名
 * @param string $filename ファイル名
 * @param string $save_path 保存先のパス
 * @param string $ingredients 材料
 * @param string $instructions 作り方
 * @param string $episode レシピのエピソード
 * @return bool $result
 */
function fileSave($login_user, $recipe_name, $filename, $save_path, $ingredients, $instructions, $episode)
{
    $result = False;

    $sql = "INSERT INTO recipe_registration (user_id, recipe_name, file_name, file_path, ingredients, instructions, episode) VALUE (?, ?, ?, ?, ?, ?, ?)";

    try {
        $stmt = connect()->prepare($sql);
        $stmt->bindValue(1, $login_user);
        $stmt->bindValue(2, $recipe_name);
        $stmt->bindValue(3, $filename);
        $stmt->bindValue(4, $save_path);
        $stmt->bindValue(5, $ingredients);
        $stmt->bindValue(6, $instructions);
        $stmt->bindValue(7, $episode);
        $result = $stmt->execute();
        return $result;
    } catch (\Exception $e) {
        echo $e->getMessage();
        return $result;
    }
}

/**
 * ファイルデータを取得
 * @return array $fileData
 */
function getAllFile()
{
    $sql = "SELECT * FROM recipe_registration";

    $fileData = connect()->query($sql);

    return $fileData;
}

//SQLエラー関数：sql_error($stmt)
function sql_error($stmt)
{
    $error = $stmt->errorInfo();
    exit("SQLError:" . $error[2]);
}
