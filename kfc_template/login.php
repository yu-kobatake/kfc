<?php
// titleで読み込むページ名
$pagetitle = "ログイン"
?>
<?php include('parts/header.php'); ?>

<?php
// セッション開始
if (!isset($_SESSION)) {
    session_start();
}
require_once("./lib/util.php");


// トークンの発行
$bytes = openssl_random_pseudo_bytes(16);
$token = bin2hex($bytes);
$_SESSION['token'] = $token;

?>


<?php
/*************************************************************************************************************
 DB接続 基本情報
 ************************************************************************************************************/
// データベース接続
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
//$host = 'sv14471.xserver.jp';
$dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";


/*************************************************************************************************************
 DB接続 ログイン済み（$_SESSION['user_id']が存在している）のユーザーがログインページに遷移した場合
 ************************************************************************************************************/
if (isset($_SESSION['user_id'])) {

    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // sql文：userテーブルのuserカラムが$_SESION['user_id']と一致するkindカラムを抽出
        $sql = "SELECT kind FROM user WHERE user_id = :user_id";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(":user_id", $_SESSION['user_id'], PDO::PARAM_STR);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);

        // 取得したユーザー情報のkindカラムの値が里親やブリーダーかによって遷移先マイページを分岐させる
        $user_kind = $result['kind'];
        if ($user_kind === "里親") {
            header("Location:parent_mypage.php");
            exit();
        } elseif ($user_kind === "ブリーダー") {
            header("Location:breeder_mypage.php");
            exit();
        }
    } catch (Exception $e) {
        $e->getMessage();
        echo "再度ログインしてください。";
    }
}

?>

<div id="container" class="c1">
    <main>
        <h2>ログイン</h2>
        <?php
        /*************************************************************************************************************
         未ログインのユーザーがログインページに遷移した場合
         ログインフォームを表示させる
         ************************************************************************************************************/

        // エラーを受け取る処理
        if (!empty($_SESSION['error'])) {
            $errors = $_SESSION['error'];
            foreach ($errors as $value) {
                echo "<span class='error'>$value</span>";
            }
            $_SESSION['error'] = [];
        }
        ?>

        <p class="r"><a href="#">パスワードを忘れた方</a></p>

        <div class="login_area">
            <div>
                <h3>里親希望の方</h3>
                <form method="POST" action="parent_mypage.php">
                    <ul>
                        <li><label><span class="label_ttl">ユーザーID</span><br>
                                <input type="text" name="user_id" class="ws"></label></li>
                        <li><label><span class="label_ttl">パスワード</span><br>
                                <input type="password" name="password" class="wl"></label></li>
                        <li><input type="submit" value="ログイン" name="login_send"></li>
                        <input type="hidden" name="token" value="<?= es($token); ?>">
                    </ul>
                </form>
            </div>
            <div>
                <h3>ブリーダーの方</h3>
                <form method="POST" action="breeder_mypage.php">
                    <ul>
                        <li><label><span class="label_ttl">ユーザーID</span><br>
                                <input type="text" name="user_id" class="ws"></label></li>
                        <li><label><span class="label_ttl">パスワード</span><br>
                                <input type="password" name="password" class="wl"></label></li>
                        <li><input type="submit" value="ログイン" name="login_send"></li>
                        <input type="hidden" name="token" value="<?= es($token); ?>">
                    </ul>
                </form>
            </div>
        </div>
        <h3>会員登録がまだの方</h3>
        <button type="button" onclick="location.href='signup.php'" class="btn_one bw_size100">新規会員登録はこちら</button>
    </main>
</div>

<?php include('parts/footer.php'); ?>