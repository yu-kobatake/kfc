<?php
require_once("./lib/util.php");

/* セッション開始 */
session_start();

var_dump($_SESSION['user_id']);

/* 未ログイン状態のアクセスは、トップへリダイレクトする */
if (!isset($_SESSION['user_id'])) {
  header('Location: ./index.php');
  exit;
}

// 文字エンコードの検証
if (!cken($_POST)) {
  $encoding = mb_internal_encoding();
  $err = "Encoding Error! The expected encoding is " . $encoding;
  // エラーメッセージを出して、以下のコードをすべてキャンセルする
  exit($err);
}

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


    
/* このユーザーが「ブリーダー」且つ「登録している犬猫がいた場合」の犬猫データの削除 */

//MySQLデータベースに接続する
try {

  $pdo = new PDO($dsn, $user, $password);
  // プリペアドステートメントのエミュレーションを無効にする
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  // 例外がスローされる設定にする
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // SQL文：退会するユーザーのidが登録されている犬猫がanimalテーブルに存在していれば3枚の画像のパスを取得する
  $sql = "SELECT image_1,image_2,image_3 FROM animal WHERE user_id = $HIT";
  
  // プリペアドステートメントを作る
  $stm = $pdo->prepare($sql);
  
  // SQLクエリを実行する
  $stm->execute();
  
  // 結果の取得（連想配列で受け取る）
  // $resultには多次元配列で犬猫ごとに3枚の写真が入っている
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);
  var_dump($result);

  // $resultに値が入っていれば画像パスを取り出して、
  // ./images/animal_photo/フォルダに入っている画像を削除する
  if($result){
    foreach($result as $key => $animal)  {
      foreach($animal as $imagekey => $imagevalue){
        var_dump($imagevalue);
        unlink("./images/animal_photo/{$imagevalue}");
      }
    }
  }

  /* このユーザーが「犬猫登録をしていれば」animalテーブルから「犬猫のレコードを削除する」 */
  
  //SQL文：退会するユーザーのidが登録されている犬猫のレコードを削除する
  $sql = "DELETE FROM animal WHERE user_id = 4";
   // プリペアドステートメントを作る
   $stm = $pdo->prepare($sql);
  
   // SQLクエリを実行する
   $stm->execute();
//削除した行数を取得
$cnt = $stm->rowCount();
//削除した行数が1以上なら削除成功、0なら削除できる番号がないとみなす
var_dump($cnt);

} catch (PDOException $e) {
  $err =  '<span class="error">エラーがありました。</span><br>';
  $err .= $e->getMessage();
  exit($err);
  //exit;
}

    // セッションを破壊
    killSession();
  } else {
    echo "不正なアクセスです。";
  }
}
?>

<?php
// titleで読み込むページ名
$pagetitle = "退会完了"
?>
<?php include('parts/header.php'); ?>
<div id="container" class="c1">
    <main>
        <h2><?php echo $pagetitle ?></h2>
        <div class="c">
            <p>
                退会完了しました。<br>
                ご利用ありがとうございました。
            </p>
            <p><a href="./index.php">トップに戻る</a></p>
        </div>
    </main>
</div>
<?php include('parts/footer.php'); ?>