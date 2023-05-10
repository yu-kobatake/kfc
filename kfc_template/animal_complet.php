<?php
// titleで読み込むページ名
$pagetitle = "犬猫登録完了"
?>
<?php include('parts/header.php'); ?>

<?php
// セッション開始
if (!isset($_SESSION)) {
  session_start();
}

require_once("./lib/util.php");

// ユーザーIDがセッションに入っていれば$user_idに代入する
if (!empty($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  //セッションに入っていなければればログインページに戻す 
} else {
  header("Location:login.php");
  exit();
}

// トークンチェック
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  echo "不正なアクセスです。err:2";
  echo "<a class ='error' href='login.php'>戻る</a>";
  exit();
}
if (isset($_POST['token']) && isset($_SESSION['token'])) {
  if ($_POST['token'] !== $_SESSION['token']) {
    echo "不正なアクセスです。err:1";
    echo "<a class ='error' href='login.php'>戻る</a>";
    exit();
  }
} else {
  echo "不正なアクセスです。err:2";
  echo "<a class ='error' href='login.php'>戻る</a>";
  exit();
}

?>
<?php
// animal_id変数の初期化
$animal_id = "";


// リロード時に重複してSQL実行されないようにする
if (!empty($_SESSION['animal'])) {


  // セッションでわたってきた値を変数に入れる

  $title = $_SESSION['animal']['title'];
  $file1 = $_SESSION['animal']['file1'];
  $file2 = $_SESSION['animal']['file2'];
  $file3 = $_SESSION['animal']['file3'];
  $kind = $_SESSION['animal']['kind'];
  $gender = $_SESSION['animal']['gender'];
  $age = $_SESSION['animal']['age'];
  $area_1 = $_SESSION['animal']['area_1'];
  $area_2 = $_SESSION['animal']['area_2'];
  $area_3 = $_SESSION['animal']['area_3'];
  $animal_area = $_SESSION['animal']['animal_area'];
  $animal_character = $_SESSION['animal']['animal_character'];
  $other = $_SESSION['animal']['other'];


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
 DB接続 INSERT animalテーブル
 画像以外のデータを登録
 画像パスは仮にimage_1,image_2,image_3とする
   ************************************************************/

  try {

    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // var_dump($pdo);
    // sql文：アニマルテーブルに登録
    $sql = "INSERT INTO animal VALUES(0,:title,:image_1,:image_2,:image_3,:kind,:gender,:age,:area_1,:area_2,:area_3,:animal_area,:animal_character,:other,:user_id)";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(":title", $title, PDO::PARAM_STR);
    $stm->bindValue(":image_1", "image_1", PDO::PARAM_STR);
    $stm->bindValue(":image_2", "image_2", PDO::PARAM_STR);
    $stm->bindValue(":image_3", "image_3", PDO::PARAM_STR);
    $stm->bindValue(":kind", $kind, PDO::PARAM_STR);
    $stm->bindValue(":gender", $gender, PDO::PARAM_STR);
    $stm->bindValue(":age", $age, PDO::PARAM_STR);
    $stm->bindValue(":area_1", $area_1, PDO::PARAM_STR);
    $stm->bindValue(":area_2", $area_2, PDO::PARAM_STR);
    $stm->bindValue(":area_3", $area_3, PDO::PARAM_STR);
    $stm->bindValue(":animal_area", $animal_area, PDO::PARAM_STR);
    $stm->bindValue(":animal_character", $animal_character, PDO::PARAM_STR);
    $stm->bindValue(":other", $other, PDO::PARAM_STR);
    $stm->bindValue(":user_id", $user_id, PDO::PARAM_STR);
    $stm->execute();
  } catch (Exception $e) {
    $e->getMessage();
    echo "登録に失敗しました。再度入力してください。";
    echo "<a class ='error' href='animal.php'>入力ページへ戻る</a>";
    exit();
  }


?>
<?php
  /*************************************************************
 DB接続 SELECT animal_idの取得
   ************************************************************/
  try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // animal_idの取得
    $sql = "SELECT animal_id FROM animal WHERE title=:title and kind=:kind and gender=:gender and age=:age and area_1=:area_1 and animal_area=:animal_area";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(":title", $title, PDO::PARAM_STR);
    $stm->bindValue(":kind", $kind, PDO::PARAM_STR);
    $stm->bindValue(":gender", $gender, PDO::PARAM_STR);
    $stm->bindValue(":age", $age, PDO::PARAM_STR);
    $stm->bindValue(":area_1", $area_1, PDO::PARAM_STR);
    // $stm->bindValue(":area_2",$area_2,PDO::PARAM_STR);
    // $stm->bindValue(":area_3",$area_3,PDO::PARAM_STR);
    $stm->bindValue(":animal_area", $animal_area, PDO::PARAM_STR);
    // $stm->bindValue(":animal_character",$animal_character,PDO::PARAM_STR);
    // $stm->bindValue(":other",$other,PDO::PARAM_STR);    
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($result);

    //$animal_id変数に代入
    $animal_id = $result[0]['animal_id'];
  } catch (Exception $e) {
    $e->getMessage();
    echo "エラーが発生しました。";
    echo "<a class ='error' href='animal.php'>入力画面に戻る</a>";
    exit();
  }
  ?>


<?php

  /*************************************************************
 DB接続 INSERT　image画像パスの上書き
   ************************************************************/
  // 画像拡張子の識別と設定
  function mine_type($tmp_name)
  {
    if (strpos($tmp_name, 'jpeg') !== false) {
      return "jpg";
    }
    if (strpos($tmp_name, 'png') !== false) {
      return "png";
    }
  }
  // 拡張子を抜き出す
  $mine1 = mine_type($file1['type']);
  $mine2 = mine_type($file2['type']);
  $mine3 = mine_type($file3['type']);

  // DBに登録するパス名の設定
  $image_1 = "{$animal_id}_image1.{$mine1}";
  $image_2 = "{$animal_id}_image2.{$mine2}";
  $image_3 = "{$animal_id}_image3.{$mine3}";

  // var_dump($image_1);
  // animal_photoフォルダに保存する際に使用する変数に入れる
  $image_data1 = $_SESSION['animal']["image_1"];
  $image_data2 = $_SESSION['animal']["image_2"];
  $image_data3 = $_SESSION['animal']["image_3"];

  // セッションの削除
  $_SESSION['animal'] = [];


  try {

    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // var_dump($pdo);
    // sql文：アニマルテーブルのimage_,image_2,image_3に画像パスを設定
    $sql = "UPDATE animal SET image_1 =:image_1,image_2 = :image_2,image_3=:image_3 WHERE animal_id = :animal_id";

    $stm = $pdo->prepare($sql);
    $stm->bindValue(":image_1", $image_1, PDO::PARAM_STR);
    $stm->bindValue(":image_2", $image_2, PDO::PARAM_STR);
    $stm->bindValue(":image_3", $image_3, PDO::PARAM_STR);
    $stm->bindValue(":animal_id", $animal_id, PDO::PARAM_STR);
    $stm->execute();
  } catch (Exception $e) {
    $e->getMessage();
    echo "登録に失敗しました。再度入力してください。";
    echo "<a class ='error' href='animal.php'>前のページ戻る</a>";
    exit();
  }

  // 画像をanimal_photoフォルダに保存
  file_put_contents('./images/animal_photo/' . $image_1, $image_data1);
  file_put_contents('./images/animal_photo/' . $image_2, $image_data2);
  file_put_contents('./images/animal_photo/' . $image_3, $image_data3);

  // $animal_idをセッションに保存
  $_SESSION['animal_id'] = $animal_id;
}

// リロード後に確認ページに飛ぶための$animal_id設定
$animal_id = $_SESSION['animal_id'];
// var_dump($_SESSION['animal_id']);
// var_dump($animal_id);
$_SESSION['animal_id'] = [];

?>
<div id="container">
    <main>
        <h2>犬猫情報登録完了</h2>
        <p>内容を登録しました。</p>
    </main>
</div>


<form method="POST" action="#">
    <input type="submit" value="ページを確認する" name="<?= es($animal_id); ?>"
        formaction="recruit_detail.php?animal_id=<?= es($animal_id); ?>">
    <input type="submit" value="マイページトップへ" formaction="breeder_mypage.php">

</form>
<?php include('parts/footer.php'); ?>