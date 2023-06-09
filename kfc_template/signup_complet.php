<?php
error_reporting(0);

// titleで読み込むページ名
$pagetitle = "新規会員登録完了";
?>
<?php include('parts/header.php'); ?>

<?php
require_once("./lib/util.php");

// セッション開始
if(!isset($_SESSION)){
  session_start();
}

if ($_SESSION['token2'] !== $_POST['token2']) :
  // 正しくない場合の処理
  $_SESSION['token2'] = "";
  $_POST['token2'] = "";
  $display = '※不正なアクセスのためエラー※'; // IDのお知らせエラー
  // echo <<< EOL
  // <p class="c">アクセスエラーです。</p>
  // EOL;
  // PHPエラーを非表示
else :

  if (!empty($_POST)) {
      $kind = $_SESSION['kind'];
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
  }

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
    $sql = "INSERT INTO user (kind, user_name, name, furigana, gender, email, password, address, zip, birth, job) VALUE ('$kind', '$user_name', '$name', '$furigana', '$gender', '$email', '$pass', '$address',	'$zip', '$birth',	'$job')";

    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);

    // SQL文を実行する
    $stm->execute();

    // IDを通知するため、自動生成したIDを取得
    $userdata_id = $pdo->lastInsertId();

    /*---------- user_id ⇒ SESSIONへ ----------*/
    if(!empty($userdata_id)){
      $_SESSION['user_id'] = $userdata_id;
      $display = $userdata_id; // IDを表示する用
    }

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

<div id="container" class="c1">
  <main>
    <h2><?php echo $pagetitle ?></h2>
    <div class="c">
    <p>会員登録が完了しました。</p>
    <p>あなたのユーザーIDは「<span style="color:red;font-weight:bold;"><?php echo $display; ?></span>」です。<br>
    <span style="color:red;">このIDはログイン時に必要</span>です。紛失されないようご注意ください。
    </p>
    <p>ログインページより、マイページへアクセスしてください。</p>
    <p class="p">
      <button type="button" class="btn_one" onclick="location.href='./login.php'">ログインページへ</button>
    </p>
    </div>
  </main>
</div>
<?php include('parts/footer.php'); ?>