<?php
require_once("./lib/util.php");

/* セッション開始 */
session_start();

// 文字エンコードの検証
if (!cken($_POST)) {
  $encoding = mb_internal_encoding();
  $err = "Encoding Error! The expected encoding is " . $encoding;
  // エラーメッセージを出して、以下のコードをすべてキャンセルする
  exit($err);
}

/* 未ログイン状態ならトップへ飛ばす？ */
// if (!isset($_SESSION['username'])) {
//   header('Location: ./index.php');
//   exit;
// }

// POSTされた値をセッション変数に受け渡す
if (isset($_POST['user_id'])) {
  $_SESSION['user_id'] = $_POST['user_id'];
}

var_dump($_SESSION['user_id']);

/* 退会処理 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  /* ログイン状態で、かつ退会ボタンを押した */
  if (isset($_SESSION['user_id']) && isset($_POST['is_delete']) && $_POST['is_delete'] === '1') {

    // データベース接続
    $user = 'shotohlcd31_kfc';
    $password = 'KFCpassword';
    $dbName = 'shotohlcd31_kfc';
    $host = 'localhost';
    //$host = 'sv14471.xserver.jp';
    $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";

    //MySQLデータベースに接続する
    try {

      // 該当のユーザーIDを取り出す
      $HIT = intval($_SESSION['user_id']);

      $pdo = new PDO($dsn, $user, $password);
      // プリペアドステートメントのエミュレーションを無効にする
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      // 例外がスローされる設定にする
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // 該当のユーザーIDのデータを削除
      $sql = "DELETE FROM user WHERE user_id = $HIT";

      // プリペアドステートメントを作る
      $stm = $pdo->prepare($sql);

      // SQLクエリを実行する
      $stm->execute();

      // 結果の取得（連想配列で受け取る）
      $brand = $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $err =  '<span class="error">エラーがありました。</span><br>';
      $err .= $e->getMessage();
      exit($err);
      //exit;
    }

    // セッションを破壊
    killSession();
  }
}
?>

<?php
// titleで読み込むページ名
$pagetitle = "退会完了"
?>
<?php include('parts/header.php'); ?>
<div id="container">
  <main>
    <h2><?php echo $pagetitle ?></h2>
    <p>
      退会完了しました。<br>
      ご利用ありがとうございました。
    </p>
    <p><a href="./index.php">トップに戻る</a></p>
  </main>
</div>
<?php include('parts/footer.php'); ?>