<?php
// titleで読み込むページ名
$pagetitle = "里親申し込み完了"
?>
<?php include('parts/header.php'); ?>

<?php
// セッション開始
if (!isset($_SESSION)) {
  session_start();
}
require_once("./lib/util.php");
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
$dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";

// ユーザーIDがセッションに入っていれば$user_idに代入する
if (!empty($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
//セッションに入っていなければればログインページに戻す 
} else { 
  header("Location:login.php");
  exit();
}

?>
<?php
if (!cken($_POST)) {
  exit("不正な文字コードです。");
}
$_POST = es($_POST);
$animal_id = $_SESSION['animal_id'];

// userテーブルへの接続
try {
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if (!empty($animal_id)) {
    $sql = "SELECT user_id FROM animal WHERE animal_id = :animal_id ";
    $stm = $pdo->prepare($sql);
    $stm->bindValue(':animal_id', $animal_id, PDO::PARAM_STR);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_ASSOC);
  }
} catch (Exception $e) {
  echo '<span class ="error">エラーがありました</span><br>';
  echo $e->getMessage();
  exit();
}
$user_id = $result['user_id'];
?>

<div id="container" class="c1">
    <main>
        <p class="c">申し込みが完了しました。</p>
        <form action="./message.php" method="get">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="submit" value="ブリーダーへメッセージを送信する" class="btn_one">
        </form>
    </main>
</div>

<?php include('parts/footer.php'); ?>