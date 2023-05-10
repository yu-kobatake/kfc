<?php
session_start();
require_once("./lib/util.php");
// エンコードチェック
if (!cken($_POST)) {
    $encoding = mb_internal_encoding();
    $err = "encoding Err! {$encoding}";
    exit($err);
}

var_dump($_SESSION);
var_dump($_POST);
// エスケープ処理
$_POST = es($_POST);

//ログインページに戻って表示させるエラー文を入れる配列の初期化 
$errors = [];
?>

<?php
/*************************************************************
 DB接続 基本情報
 ************************************************************/
// データベース接続
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
//$host = 'sv14471.xserver.jp';
$dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";

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
    if(!empty($_POST['user_id']) && !empty($_POST['password'])){
        
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
        // var_dump($result);
        
        //userテーブルに該当するユーザーがいなかった場合$errorsにエラーメッセージを追加
        if (!$result[0]) {
            $errors[] = "入力内容が間違っています。";
        } else{
            $_SESSION['user_id'] = $user_id;
        }
    } catch (Exception $e) {
        $e->getMessage();
        echo "エラーが発生しました。1";
        echo "<a class ='error' href='login.php'>戻る</a>";
    }
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
<div id="container" class="c1">
    <main>
        <?php
        /*************************************************************
DB接続 userテーブルから会員情報を取り出して表示
         ************************************************************/

        // ログイン済み（$_SESSION['user_id']がある）ユーザーがログインしてきた場合、$user_idに$_SESSION['user_id']を代入する
        if (!empty($_SESSION['user_id'])) $user_id = $_SESSION['user_id'];
        // var_dump($user_id);
        // DB接続
        try {
            // sql文：userテーブルから$user_idに該当するユーザー情報を取得

            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM user WHERE kind = '里親' AND user_id = :user_id";
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
            <tr><th>郵便番号</th><td>{$result['zip']}</td></tr>
            <tr><th>住所</th><td>{$result['address']}</td></tr>
            <tr><th>生年月日</th><td>{$result['birth']}</td></tr>
            <tr><th>職業</th><td>{$result['job']}</td></tr>
            </tbody>
            </table>

            <form method="POST" action="mypage_change.php">
            <input type="submit" value="会員情報の変更" class="btn_one">
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
            <button><a href="message_top.php">メッセージ一覧へ</a></button>
        </div>

        <?php
        /*************************************************************
いいね一覧
 DB接続 SELECT
         ************************************************************/

        try {
            // goodテーブルからanimalテーブルのIDを抽出する
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT animal_id FROM good WHERE user_id = :user_id";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(":user_id", $user_id, PDO::PARAM_STR);
            $stm->execute();
            // $resultにはanimal_idが入っている
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($result);
            
            // いいね一覧表示
            echo "<h3>いいね一覧</h3>";
            
            if($result){
                echo "<div class='animal list-container'>";
                foreach($result as $good){
                    // var_dump($good);
                    $pdo = new PDO($dsn, $user, $password);
                    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    // animalテーブルから$resultのanimal_idを元に犬猫写真、名前、性別、犬種/猫種、動物がいる地域、（掲載期限）を抽出する
                    $sql = "SELECT image_1,title,gender,age,animal_id,kind,animal_area FROM animal WHERE animal_id = :good";
                    $stm = $pdo->prepare($sql);
            $stm->bindValue(":good", $good['animal_id'], PDO::PARAM_STR);
            
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            
            // エスケープ処理
            // $result = es($result);
            // var_dump($result);
            
           
    
                
                    echo <<<EOL
                    <div class="list">
                    <a href="recruit_detail.php?animal_id={$result[0]['animal_id']}">
                    <figure>
                    <img src="./images/animal_photo/{$result[0]['image_1']}" alt="{$result[0]['kind']}">
                    </figure>
                    <div class="text">
                    <h4>{$result[0]['title']}</h4>
                    <p class="name">年齢：{$result[0]['age']}&nbsp;{$good[0]['gender']}</p>
                    <p>{$result[0]['animal_area']}</p>
                    <p>掲載ID：{$result[0]['animal_id']}</p>
                    </div>
                    </a>
                    </div>
              EOL;
                }
                echo "</div>";
                
            } else{
                echo "<p>現在いいねしている犬猫はいません</p>";
            }
        } catch (Exception $e) {
            $e->getMessage();
            echo "エラーが発生しました。2";
            echo "<a class ='error' href='login.php'>戻る</a>";
        }
        ?>
        <?php
        
        /*************************************************************
 退会ページ
         ************************************************************/
        ?>
        <h3>退会</h3>
        <form method="POST" action="delete.php">
            <input type="submit" value="退会ページへ" class="btn_back_mini">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
        </form>
    </main>
</div>

<?php include('parts/footer.php'); ?>