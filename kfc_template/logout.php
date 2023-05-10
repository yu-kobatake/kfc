<?php
session_start();
require_once("./lib/util.php");

// ユーザーIDがセッションに入っていなければログインページに飛ばす
if (empty($_SESSION['user_id'])) { 
  header("Location:login.php");
  exit();
}

killSession();
  // titleで読み込むページ名
  $pagetitle = "ログアウトページ"
  
?>
<?php include('parts/header.php'); ?>
<div id="container" class="c1">
    <main>
        <h2>ログアウトしました。</h2>
        <p class="c"><a href="index.php"><button class="btn_back_one bw_size30">トップページへ戻る</button></a></p>
    </main>
</div>

<?php include('parts/footer.php'); ?>