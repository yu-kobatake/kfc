<?php
session_start();
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
?>

<?php
/*************************************************************
 DB接続 基本情報
 ************************************************************/
// $user = "shotohlcd31_kfc";
$user = "testuser";
$password = "pw4testuser";
$dbName = "shotohlcd31_kfc";
// $host = "sv14471.xserver.jp";
$host = "localhost";
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

/*************************************************************
不正アクセスチェック
 ************************************************************/

// 未ログイン状態で（$_SESSION['user_id']がない）遷移してきた場合で、トークンチェック（POSTによる遷移かどうか）
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


/*************************************************************
 DB接続
 ログインページからのページ遷移の場合
 ログイン時入力したIDとパスワードが
 userテーブルに登録されているかチェック
 ************************************************************/
// ログインぺージからPOSTされてきた場合
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
        $sql = "SELECT EXISTS(SELECT * FROM user WHERE kind = '里親' and user_id = :user_id AND password = :password)";

        $stm = $pdo->prepare($sql);
        $stm->bindValue(":user_id", $user_id, PDO::PARAM_STR);
        $stm->bindValue(":password", $pass, PDO::PARAM_STR);
        $stm->execute();
        //userテーブルに該当するユーザーがいなかった時$resultにfalseが入る 
        $result = $stm->fetch(PDO::FETCH_NUM);
        var_dump($result);

        //userテーブルに該当するユーザーがいなかった場合$errorsにエラーメッセージを追加
        if (!$result[0]) {
            $errors[] = "入力内容が間違っています。";
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

include('parts/header.php');
$pagetitle = "里親マイページ";
?>
<div id="container">
    <main>
        <?php
/*************************************************************
DB接続 userテーブルから会員情報を取り出して表示
************************************************************/

        // ログイン済み（$_SESSION['user_id']がある）ユーザーがログインしてきた場合、$user_idに$_SESSION['user_id']を代入する
        if (!empty($_SESSION['user_id'])) $user_id = $_SESSION['user_id'];

        // DB接続
        try {
            // sql文：userテーブルから$user_idに該当するユーザー情報を取得
            $sql = "SELECT * FROM user WHERE kind = '里親' user_id = :user_id";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(":user_id", $user_id, PDO::PARAM_STR);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            // var_dump($result);
            // $resultにはユーザー情報（レコード）が入っている
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
            <tr><th>パスワード</th><td>*******</td></tr>
            <tr><th>郵便番号</th><td>{$result['zip']}</td></tr>
            <tr><th>住所</th><td>{$result['address']}</td></tr>
            <tr><th>生年月日</th><td>{$result['birth']}</td></tr>
            <tr><th>職業</th><td>{$result['job']}</td></tr>
            </tbody>
            </table>

            <form method="POST" action="">
            <input type="submit" value="会員情報の変更">
            <input type="hidden" name="user_id" value="{$user_id}">
            </form>
            </div>
            EOL;
        } catch (Exception $e) {
            $e->getMessage();
            echo "エラーが発生しました。2";
            // エラーの場合はログインページに
            echo "<a class ='error' href='login.php'>戻る</a>";
        }
        ?>


        <?php
/*************************************************************
 メッセージエリア
************************************************************/
        ?>
        <div>
            <h3>メッセージ</h3>
            <!-- 新規メッセージ的なコメント未設定 -->
            <input type="submit" value="トーク画面へ">
        </div>

        <?php
/*************************************************************
いいね一覧
 DB接続 SELECT
************************************************************/

        try {
            // goodテーブルからanimalテーブルのIDを抽出する
            $sql = "SELECT * FROM good";
            $stm = $pdo->prepare($sql);
            $stm->execute();
            // $resultにはanimal_idが入っている
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($result);

            // animalテーブルから$resultのanimal_idを元に犬猫写真、名前、性別、犬種/猫種、動物がいる地域、（掲載期限）を抽出する
            $sql = "SELECT image_1,title,gender,age,animal_id,kind,animal_area FROM animal";
            $stm = $pdo->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);

            // エスケープ処理
            $result = es($result);
            var_dump($result);
        } catch (Exception $e) {
            $e->getMessage();
            echo "エラーが発生しました。2";
            echo "<a class ='error' href='login.php'>戻る</a>";
        }
        ?>
        <?php
        // いいね一覧表示
        echo "<h3>いいね一覧</h3>";
        if ($result) {
            foreach ($result as $row) {
                echo <<<EOL
                <a href="recruit_detail.php?animal_id={$row['animal_id']}">
                <div> 
                <img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}">
                <p>{$row['title']}</p>
                <p>年齢：{$row['age']}&nbsp;{$row['gender']}</p>
                <p>{$row['animal_area']}</p>
                <p>掲載ID：{$row['animal_id']}</p>
                </div>
                </a>
          EOL;
            }
        }
/*************************************************************
 退会ページ
************************************************************/
        ?>
        <button><a href="delete.php">退会</a></button>
    </main>
</div>

<?php include('parts/footer.php'); ?>