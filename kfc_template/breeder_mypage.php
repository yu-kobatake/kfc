<?php
// titleで読み込むページ名
$pagetitle = "ブリーダーマイページ"
?>
<?php include('parts/header.php');
// セッション開始
if (!isset($_SESSION)) {
session_start();
}
require_once("./lib/util.php");
// エンコードチェック
if (!cken($_POST)) {
$encoding = mb_internal_encoding();
$err = "encoding Err! {$encoding}";
exit($err);
}
// エスケープ処理
$_POST = es($_POST);

//ログインページに戻って表示させるエラー文を入れる配列の初期化
$errors = [];
// 動物登録・変更関係のセッション削除
$_SESSION['animal'] = [];
?>

<?php

//  不正アクセスチェック

// 未ログイン状態で（$_SESSION['user_id']がない）遷移してきた場合のトークンチェック
if (empty($_SESSION['user_id'])) {
    // トークンチェック
    if (isset($_POST['token']) && isset($_SESSION['token'])) {
        if ($_POST['token'] !== $_SESSION['token']) {
            echo "不正なアクセスです。err:1";
            echo "<a class ='error' href='login.php'>戻る</a>";
            exit();
        }
    } else {
        echo "不正なアクセスです。err:2";
        echo "<a class ='error' href='login.php'>戻る</a>";
        exit();
    }
}
//  DB接続 基本情報
  // データベース接続
  $user = 'shotohlcd31_kfc';
  $password = 'KFCpassword';
  $dbName = 'shotohlcd31_kfc';
  $host = 'localhost';
  $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";

// DB接続
// ログインページからのページ遷移の場合
//  ログイン時入力したIDとパスワードがuserテーブルに登録されているかチェック
if (!empty($_POST['login_send'])) {

    // POSTされたユーザーIDとパスワードのバリデーション
    // 未入力→$errorsにエラー文を追加
    // 入力済→前後の空白を削除して$user_idと$passに代入する
    if (empty($_POST['user_id'])) {
        $errors[] = "ユーザーIDが入力されていません";
    } else {
        $user_id = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['user_id']);
    }
    if (empty($_POST['password'])) {
        $errors[] = "パスワードが入力されていません";
    } else {
        $pass = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['password']);
    }
    // DB接続
    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // sql文：userテーブルに$user_idと$passが一致するユーザーが存在するか
        $sql = "SELECT EXISTS(SELECT * FROM user WHERE kind = 'ブリーダー' AND user_id = :user_id AND password = :password)";

        $stm = $pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_STR);
        $stm->bindValue(":password", $pass, PDO::PARAM_STR);
        $stm->execute();
        //userテーブルに該当するユーザーがいなかった時$resultにfalseが入る 
        $result = $stm->fetch(PDO::FETCH_NUM);

        //userテーブルに該当するユーザーがいなかった場合$errorsにエラーメッセージを追加
        if (!$result[0]) {
            $errors[] = "入力内容が間違っています。";
        } else {

            $_SESSION['user_id'] = $user_id;
        }
    } catch (Exception $e) {
        $e->getMessage();
        echo "エラーが発生しました。1";
        echo "<a class ='error' href='login.php'>戻る</a>";
    }

    // エラーがあった場合ログインページへ戻る
    // $_SESSION['error']に$errors[]を代入
    if (count($errors) > 0) {
        $_SESSION['error'] = $errors;
        header("Location:login.php");
        exit();
    }
}
?>

<div id="container" class="c1">
    <main>
        <h2>マイページ</h2>

        <div class="mypage_set">
            <?php


// 会員情報の表示
//  DB接続 userテーブルから会員情報を表示

        // ログイン済み（$_SESSION['user_id']がある）ユーザーが訪れた場合、$user_idに$_SESSION['user_id']を代入する
        if (!empty($_SESSION['user_id'])) $user_id = $_SESSION['user_id'];

        // DB接続
        try {
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // sql文：userテーブルから$user_idに該当するユーザー情報を取得
            $sql = "SELECT * FROM user WHERE kind = 'ブリーダー' AND user_id = :user_id";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(":user_id", $user_id, PDO::PARAM_STR);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            // var_dump($result);
            // $resultにはユーザー情報が入っている
            // エスケープ処理
            $result = es($result);

            //  該当するユーザーの情報を表示
            echo <<<EOL
            <div>
            <h3>会員情報</h3>
            <table class= "ta1">
            <tbody>
            <tr><th>会員ID</th><td>{$result['user_id']}</td></tr>
            <tr><th>里親orブリーダー</th><td>{$result['kind']}</td></tr>
            <tr><th>ユーザー名</th><td>{$result['user_name']}</td></tr>
            <tr><th>氏名</th><td>{$result['name']}</td></tr>
            <tr><th>フリガナ</th><td>{$result['furigana']}</td></tr>
            <tr><th>性別</th><td>{$result['gender']}</td></tr>
            <tr><th>メールアドレス</th><td>{$result['email']}</td></tr>
            <tr><th>郵便番号</th><td>{$result['zip']}</td></tr>
            <tr><th>住所</th><td>{$result['address']}</td></tr>
            <tr><th>生年月日</th><td>{$result['birth']}</td></tr>
            <tr><th>職業</th><td>{$result['job']}</td></tr>
            </tbody>
            </table>
    
            <form method="POST" action="mypage_change.php">
            <input type="submit" value="会員情報の変更" class="btn_one martop10">
            <input type="hidden" name="user_id" value="{$user_id}">
            </form>
            </div>
            EOL;
            // // ブリーダーのユーザーIDをセッション$_SESSION['user_id']に代入する
            $_SESSION['user_id'] = $result['user_id'];
            // var_dump($_SESSION);
        } catch (Exception $e) {
            $e->getMessage();
            echo "エラーが発生しました。2";
            // エラーの場合はログインページに
            echo "<a class ='error' href='login.php'>戻る</a>";
        }
        ?>
            <?php


//  犬猫登録・管理画面/メッセージエリア
        ?>
            <div>
                <h3>登録ペット</h3>
                <a href="animal.php" class="animal_mana_btn_add">犬猫新規登録</a>
                <a href="animal_manage.php" class="animal_mana_btn_con">犬猫管理画面</a>
                <h3 class="martop10">メッセージ</h3>
                <p>犬猫についてのメッセージのやり取りを確認・送信したい場合はこちら。</p>
                <button type="button" class="btn_one martop10"
                    onclick="location.href='message_top.php'">メッセージ一覧へ</button>
            </div>

        </div><!-- mypage_set -->

        <?php

        
//  退会
        ?>
        <h3 class=" martop50">退会</h3>
        <form method="POST" action="delete.php">
            <input type="submit" value="退会ページへ" class="btn_back_mini">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
        </form>

    </main>
</div>

<?php include('parts/footer.php'); ?>