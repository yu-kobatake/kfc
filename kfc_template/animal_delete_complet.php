<?php
session_start();
require_once("./lib/util.php");


// ユーザーIDがセッションに入っていれば$user_idに代入する
if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
  //セッションに入っていなければればログインページに戻す 
  } else { 
    header("Location:login.php");
    exit();
  }

// 不正アクセスチェックとanimal_idの取得
if (empty($_SESSION['animal_id']) && empty($_POST['animal_id'])) {
    echo "不正なアクセスです。err:1";
    echo "<a class ='error' href='breeder_mypage.php'>戻る</a>";
    exit();
} elseif (!empty($_SESSION['animal_id'])) {
    $animal_id = $_SESSION['animal_id'];
    $_SESSION['animal_id'] = [];
} elseif (!empty($_POST['animal_id'])) {
    $animal_id = $_POST['animal_id'];
}
var_dump($_SESSION);
var_dump($_POST);

/*************************************************************
 DB接続 基本情報
 ************************************************************/
  // データベース接続
  $user = 'shotohlcd31_kfc';
  $password = 'KFCpassword';
  $dbName = 'shotohlcd31_kfc';
  $host = 'localhost';
  //$host = 'sv14471.xserver.jp';
  $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";


/*************************************************************
 DB接続 animalテーブルから登録情報を取得
 ************************************************************/

// DB接続
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // sql文：userテーブルから$animal_idに該当するユーザー情報を取得
    $sql = "DELETE FROM animal WHERE animal_id = $animal_id";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    //削除した行数を取得
$cnt = $stm->rowCount();
//削除した行数が1以上なら削除成功、0なら削除できる番号がないとみなす
// var_dump($cnt);
    // var_dump($result);

  // titleで読み込むページ名
  $pagetitle = "犬猫登録情報削除完了"
?>
<?php include('parts/header.php'); ?>
<div id="container">
    <main>
        <?php echo "<p>掲載ID:$animal_id の登録情報を削除しました。</p>" ?>
        <button><a href="animal_manage.php">犬猫管理画面に戻る</a></button>

    </main>
</div>
<?php
} catch (Exception $e) {
$e->getMessage();
echo "エラーが発生しました。2";
// エラーの場合は犬猫管理ページに
echo "<a class='error' href='animal_manage.php'>戻る</a>";
}

?>

<?php include('parts/footer.php'); ?>