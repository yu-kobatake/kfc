<?php
require_once("./lib/util.php");

// セッション開始
session_start();

// 文字エンコードの検証
if (!cken($_POST)) {
  $encoding = mb_internal_encoding();
  $err = "Encoding Error! The expected encoding is " . $encoding;
  // エラーメッセージを出して、以下のコードをすべてキャンセルする
  exit($err);
}

// POSTされた値をセッション変数に受け渡す
if (isset($_POST['user_id'])) {
  $_SESSION['user_id'] = $_POST['user_id'];
}

/* 未ログイン状態ならトップへ飛ばす？ */
// if (!isset($_SESSION['username'])) {
//   header('Location: ./index.php');
//   exit;
// }

/* DB読み込み */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  /* ログイン状態で、かつ退会ボタンを押した */
  if (isset($_SESSION['user_id'])) {

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
      $sql = "SELECT * FROM user WHERE user_id = $HIT";

      // プリペアドステートメントを作る
      $stm = $pdo->prepare($sql);

      // SQLクエリを実行する
      $stm->execute();

      // 結果の取得（連想配列で受け取る）
      $userdata = $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $err =  '<span class="error">エラーがありました。</span><br>';
      $err .= $e->getMessage();
      exit($err);
    }
  }
}
?>

<?php
// titleで読み込むページ名
$pagetitle = "退会申し込み"
?>
<?php include('parts/header.php'); ?>
<div id="container">
  <main>
    <h2><?php echo $pagetitle ?></h2>

    <form action="delete_complet.php" method="POST">
      <?php
      foreach ($userdata as $val) {
        echo "ユーザーネーム：" . $val["user_name"] . "<br>";
        echo "氏名：" . $val["name"] . "<br>";
        echo "メールアドレス：" . $val["email"] . "<br>";
      }
      ?>
      <dl>
        <dt>退会理由をお聞かせください。（任意）</dt>
        <dd><textarea name="delete_reason" cols="30" rows="10"></textarea></dd>
      </dl>
      <input type="hidden" name="is_delete" value="1">
      <input type="submit" value="退会する（退会後は戻せません）">
    </form>

    <p><a href="/">トップに戻る</a></p>
  </main>
</div>
<?php include('parts/footer.php'); ?>


<!-- JavaScript -->
<!-- <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script> -->
</body>

</html>