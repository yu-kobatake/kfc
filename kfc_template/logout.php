<?php
session_start();
require_once("./lib/util.php");
killSession();
  // titleで読み込むページ名
  $pagetitle = "ログアウトページ"
  
?>
<?php include('parts/header.php'); ?>
<div id="container">
    <main>
        ログアウトしました。
        <a href="index.php"><button>トップページへ戻る</button></a>
    </main>
</div>

<?php include('parts/footer.php'); ?>