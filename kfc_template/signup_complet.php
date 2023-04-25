<?php
require_once("./lib/util.php");

// セッション開始
session_start();

// ユーザーIDをSESSIONに入れること！
// $_SESSION['user_id'] = 

// $_SESSION['agreement'] = isset($_POST['agreement']) ? preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['agreement']) : null;

$kind = $_SESSION['kind'];
$user_name = $_SESSION['user_name'];
$name = $_SESSION['name'];
$furigana = $_SESSION['furigana'];
$email = $_SESSION['email'];
$password = $_SESSION['password'];
$zip = $_SESSION['zip'];
$address = $_SESSION['address'];
$birth = $_SESSION['birth'];
$gender = $_SESSION['gender'];
$job = $_SESSION['job'];


// if ($_SESSION['token'] !== $_POST['token']) :
//   // 正しくない場合は戻るボタンを表示
//   echo <<< EOL
//   <p>不正なアクセスです。</p>
//   <a href="signup.php"><button>戻る</button></a>
//   EOL;
// else :

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

  /*---------- 新規のデータを追加 ----------*/
  $sql = "INSERT INTO user (kind, user_name, name, furigana, gender, email, password, address, zip, birth, job) VALUE ('$kind', '$user_name', '$name', '$furigana', '$gender', '$email', '$password', '$address',	'$zip', '$birth',	'$job')";

  // プリペアドステートメントを作る
  $stm = $pdo->prepare($sql);

  // SQLクエリを実行する
  $stm->execute();

  /*---------- 今、登録したユーザーIDを抽出（登録メールアドレスと同じレコードのIDを取得） ----------*/
  $sql2 = "SELECT user_id FROM user WHERE email = '$email'";

  // プリペアドステートメントを作る
  $stm2 = $pdo->prepare($sql2);

  // SQLクエリを実行する
  $stm2->execute();
  // 結果の取得
  $userdata = $stm2->fetchAll(PDO::FETCH_ASSOC);

  /*---------- user_id ⇒ SESSIONへ ----------*/
  $_SESSION['user_id'] = $userdata[0]['user_id'];

  // 結果の取得
  // $userdata = $stm->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  $err =  '<span class="error">エラーがありました。</span><br>';
  $err .= $e->getMessage();
  exit($err);
  //exit;
}

// セッションを破壊
//killSession();

  // endif;

?>
<?php
// titleで読み込むページ名
$pagetitle = "新規会員登録完了";
?>
<?php include('parts/header.php'); ?>
<div id="container" class="c1">
  <main>
    <h2>新規会員登録完了</h2>
    <div class="c">
    <p>
      会員登録が完了しました。<br>
      ログインページよりマイページへアクセスしてください。
    </p>
    <p><a href="./login.php">ログインページへ</a></p>
    </div>
</div>
<?php include('parts/footer.php'); ?>