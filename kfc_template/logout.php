<?php
// titleで読み込むページ名
$pagetitle = "ログアウト";
// 関数ファイルの読み込み
include('parts/header.php'); ?>

<?php
// セッション開始
if (!isset($_SESSION)) {
    session_start();
}
require_once("./lib/util.php");

// ユーザーIDがセッションに入っていなければログインページに飛ばす
if (empty($_SESSION['user_id'])) { 
  header("Location:login.php");
  exit();
}

// セッション削除
killSession();
  
?>

<div id="container" class="c1">
    <main>
        <h2>ログアウトしました。</h2>
        <p class="c"><button onclick="location.href='index.php'" class="btn_back_one bw_size30">トップページへ戻る</button< /p>
    </main>
</div>
<?php include('parts/footer.php'); ?>