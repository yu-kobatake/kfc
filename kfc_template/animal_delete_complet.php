<?php
// titleで読み込むページ名
$pagetitle = "犬猫登録削除完了"
?>
<?php include('parts/header.php'); ?>

<?php
// セッション開始
if (!isset($_SESSION)) {
    session_start();
}

require_once("./lib/util.php");


// セッションにユーザーIDが入っていなければログインページに飛ばす
if (empty($_SESSION['user_id'])) {
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


// DB接続 基本情報
// データベース接続
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
$dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";


// DB接続 animalテーブルから登録情報を取得

// DB接続
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // sql文：userテーブルから$animal_idに該当するユーザー情報を取得
    $sql = "DELETE FROM animal WHERE animal_id = $animal_id";
    $stm = $pdo->prepare($sql);
    $stm->execute();

    // animal_photoフォルダのがぞうを画像を削除する

    for ($i = 1; $i <= 3; $i++) {
        
  
          $filename1 = "./images/animal_photo/{$animal_id}_image{$i}.jpg";
          $filename2 = "./images/animal_photo/{$animal_id}_image{$i}.jpeg";
          $filename3 = "./images/animal_photo/{$animal_id}_image{$i}.png";
          // 既存の画像を削除する
          if (file_exists($filename1)) {
            unlink($filename1);
          }
          if (file_exists($filename2)) {
            unlink($filename2);
          }
          if (file_exists($filename3)) {
            unlink($filename3);
          }
       
      }
?>

<div id="container" class="c1">
    <main>
        <div class="c">
            <?php echo "<p>掲載ID:$animal_id の登録情報を削除しました。</p>" ?>
            <button onclick="location.href='animal_manage.php'" class="btn_one">犬猫管理画面に戻る</button>
        </div>
    </main>
</div>
<?php
} catch (Exception $e) {
    $e->getMessage();
    echo "エラーが発生しました。2";
    // エラーの場合は犬猫管理ページに
    echo "<button><a class='error' href='animal_manage.php'>戻る</a></button>";
}

?>

<?php include('parts/footer.php'); ?>