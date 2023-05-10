<?php
require_once("./lib/util.php");

// セッション開始
session_start();

// ユーザーIDがセッションに入っていれば$user_idに代入する
if (!empty($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
//セッションに入っていなければればログインページに戻す 
} else { 
  header("Location:login.php");
  exit();
}

// 変数へSESSIONの値を格納
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$name = $_SESSION['name'];
$furigana = $_SESSION['furigana'];
$email = $_SESSION['email'];
$pass = $_SESSION['password'];
$zip = $_SESSION['zip'];
$address = $_SESSION['address'];
$birth = $_SESSION['birth'];
$gender = $_SESSION['gender'];
$job = $_SESSION['job'];


if ($_SESSION['token2'] !== $_POST['token2']) :
  // 正しくない場合は戻るボタンを表示
  echo <<< EOL
  <p>不正なアクセスです。</p>
  <a href="signup.php"><button>戻る</button></a>
  EOL;

else :

  // データベース接続
  $user = 'shotohlcd31_kfc';
  $password = 'KFCpassword';
  $dbName = 'shotohlcd31_kfc';
  $host = 'localhost';
  //$host = 'sv14471.xserver.jp';
  $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";

  //MySQLデータベースに接続する
  try {
    $pdo = new PDO($dsn, $user, $password);
    // プリペアドステートメントのエミュレーションを無効にする
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // 例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*---------- 対象のuser_idのデータを更新 ----------*/
    $sql = "UPDATE user SET
    user_name = '$user_name',
    name = '$name',
    furigana = '$furigana',
    gender = '$gender',
    email = '$email',
    password= '$pass',
    zip = '$zip',
    address= '$address',
    birth= '$birth',
    job= '$job'
    WHERE user_id = $user_id";

    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);

    // SQLクエリを実行する
    $stm->execute();
  } catch (PDOException $e) {
    $err =  '<span class="error">エラーがありました。</span><br>';
    $err .= $e->getMessage();
    exit($err);
    exit;
  }
  // セッションを破壊
  killSession();

endif;
?>
<?php
// titleで読み込むページ名
$pagetitle = "会員情報の変更完了"
?>
<?php include('parts/header.php'); ?>
<div id="container" class="c1">
    <main>
        <h2><?php echo $pagetitle ?></h2>
        <div class="c">
            <p>
                会員情報の内容を変更しました。<br>
            </p>
            <p><a href="./login.php">マイページに戻る</a></p>
        </div>
    </main>
</div>
<?php include('parts/footer.php'); ?>