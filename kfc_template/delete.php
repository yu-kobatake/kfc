<?php
// titleで読み込むページ名
$pagetitle = "退会申し込み"
?>
<?php include('parts/header.php'); ?>
<?php
require_once("./lib/util.php");

// セッション開始
if(!isset($_SESSION)){
  session_start();
}

/* 未ログイン状態のアクセスは、トップへリダイレクトする */
if(!isset($_POST['user_id'])) {
  ('Location: ./index.php');
  exit;
} else {
  // ログイン済ならば、POSTされた値をセッション変数に受け渡す
  $_SESSION['user_id'] = $_POST['user_id'];
}

// 文字エンコードの検証
if (!cken($_POST)) {
  $encoding = mb_internal_encoding();
  $err = "Encoding Error! The expected encoding is " . $encoding;
  // エラーメッセージを出して、以下のコードをすべてキャンセルする
  exit($err);
}

/* DB読み込み */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // ユーザーIDがあれば
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
      $pdo = new PDO($dsn, $user, $password);
      // プリペアドステートメントのエミュレーションを無効にする
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      // 例外がスローされる設定にする
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // 該当のユーザーIDを取り出す
      $HIT = intval($_SESSION['user_id']);

      // 該当のユーザーIDのデータを抽出
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
  } else {
    header('Location: ./index.php');
    exit;
  }
}

// 表示するためにデータの取得
foreach ($userdata as $val) {
  $user_name = $val["user_name"];
  $name = $val["name"];
  $email = $val["email"];
}

?>

<div id="container" class="c1">
  <main>
    <h2><?php echo $pagetitle ?></h2>
    <form action="delete_complet.php" method="POST">
      <table class="ta1">
        <tr>
          <th>ユーザーID</th>
          <td>
            <?php echo $HIT; ?>
          </td>
        </tr>
        <tr>
          <th>ユーザー名</th>
          <td>
            <?php echo $user_name; ?>
          </td>
        </tr>
        <tr>
          <th>メールアドレス</th>
          <td>
            <?php echo $email; ?>
          </td>
        </tr>
        <tr>
          <th>退会理由をお聞かせください。（任意）</th>
          <td>
            <textarea name="delete_reason" class="ws" rows="5"></textarea>
          </td>
        </tr>
      </table>
      <p class="c txtred">※退会すると、登録頂いたデータや記録は戻せません。</p>
      <p class="c">
        <input type="submit" class="btn_one" value="退会する">
        <button type="button" class="btn_back_one martop10" onclick="location.href='login.php'">戻る</button>
        <input type="hidden" name="is_delete" value="1">
      </p>
    </form>
  </main>
</div>
<?php include('parts/footer.php'); ?>
</body>
</html>