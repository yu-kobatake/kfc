<?php
session_start();
require_once("./lib/util.php");
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
$dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";

?>
<?php
if (!cken($_POST)) {
  exit("不正な文字コードです。");
}
$_POST = es($_POST);

//token確認 
if (isset($_SESSION['token']) && isset($_POST['token'])) {
  if ($_SESSION['token'] !== $_POST['token']) {
    echo "<p>不正なアクセスです。①</p>";
    echo "<a href='recruit.php'><button>里親募集ページに戻る</button></a><br>";
    exit();
  }
} else {
  echo "<p>不正なアクセスです。②</p>";
  echo "<a href='recruit.php'><button>里親募集ページに戻る</button></a><br>";
  exit();
}
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
$user_id=$result['user_id'];
?>

<?php
// セッション破棄
// killSession();
?>

<?php
// titleで読み込むページ名
$pagetitle = "里親申し込み完了"
?>
<?php include('parts/header.php'); ?>
<div id="container">
  <main>
    <p>申し込みが完了しました。</p>
    <form action="./message.php" method="get">
      <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
      <input type="submit" value="メッセージを送信する">
    </form>
  </main>
</div>

<?php include('parts/footer.php'); ?>