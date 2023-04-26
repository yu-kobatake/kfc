<?php
session_start();
require_once("./lib/util.php");
?>
<?php
if (!cken($_POST)) {
  exit("不正な文字コードです。");
}
$_POST = es($_POST);
// var_dump($_POST);
// var_dump($_SESSION);

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
?>

<?php 
// セッション破棄
killSession();
?>

<?php
// titleで読み込むページ名
$pagetitle = "里親申し込み完了"
?>
<?php include('parts/header.php'); ?>
<div id="container">
  <main>
<p>申し込みが完了しました。</p>
<a href='recruit.php'><button>トップページに戻る</button></a>
  </main>
</div>

<?php include('parts/footer.php'); ?>