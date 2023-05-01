<?php
session_start();
require_once("./lib/util.php");
// var_dump($_SESSION);
// var_dump($_POST);

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

/*************************************************************
 DB接続 基本情報
 ************************************************************/
// $user = "shotohlcd31_kfc";
$user = "testuser";
$password = "pw4testuser";
$dbName = "shotohlcd31_kfc";
// $host = "sv14471.xserver.jp";
$host = "localhost";
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";


/*************************************************************
 DB接続 animalテーブルから登録情報を取得
 ************************************************************/

// DB接続
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // sql文：animalテーブルから$animal_idに該当する犬猫情報を取得
    $sql = "SELECT * FROM animal WHERE animal_id = $animal_id";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    var_dump($result);
    // エスケープ処理
    $result = es($result);
    
    // titleで読み込むページ名
    $pagetitle = "犬猫登録削除";
    include('parts/header.php');
    foreach ($result as $row) {
        echo <<<"EOL"
        <div id="container">
            <main>
  <h2>{$row['title']}</h2>
  <div>
  <img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}">
  <div style="display:flex">
  <img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}" style="width: 30%;">
  <img src="./images/animal_photo/{$row['image_2']}" alt="{$row['kind']}"style="width: 30%;">
  <img src="./images/animal_photo/{$row['image_3']}" alt="{$row['kind']}"style="width: 30%;">
  </div>
  </div>
  <table class='ta1'>
    <tr><th>性別</th></tr>
    <tr><td>{$row['gender']}</td></tr>
    <tr><th>年齢</th></tr>
    <tr><td>{$row['age']}</td></tr>
    <tr><th>募集対象地域</th></tr>
    <tr><td>{$row['area_1']}</td></tr>
    <tr><td>{$row['area_2']}</td></tr>
    <tr><td>{$row['area_3']}</td></tr>
    <tr><th>動物がいる地域</th></tr>
    <tr><td>{$row['animal_area']}</td></tr>
    <tr><th>特徴（性格等）</th></tr>
    <tr><td>{$row['animal_character']}</td></tr>
    <tr><th>特記事項</th></tr>
    <tr><td>{$row['other']}</td></tr>
      </table>
     </div>
    <div>
    <h4>掲載ID{$row['animal_id']}の登録を削除しますか？</h4>
    <form method="POST" action="#">
    <input type="submit" formaction="animal_delete_complet.php" value="削除">
    <input type="submit" formaction="animal_manage.php" value="戻る">
    <input type="hidden" name="animal_id" value="{$row['animal_id']}">
</div>


EOL;
}



} catch (Exception $e) {
$e->getMessage();
echo "エラーが発生しました。2";
// エラーの場合はログインページに
echo "<a class='error' href='animal_manage.php'>戻る</a>";
}

?>



</main>
</div>

<?php include('parts/footer.php'); ?>