<?php
session_start();
require_once("./lib/util.php");
// titleで読み込むページ名
$pagetitle = "ペット情報変更完了";


// ユーザーIDがセッションに入っていれば$user_idに代入する
if (!empty($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
//セッションに入っていなければればログインページに戻す 
} else { 
  header("Location:login.php");
  exit();
}

// トークンチェック
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


// POSTされたanimal_idを変数に入れる
$animal_id = $_POST['animal_id'];

// リロード時に重複してSQL実行されないようにする
if (!empty($_SESSION['animal'])) {

  //エラーを入れるセッションを空にする
  $_SESSION['animal']['error'] = [];
?>

<?php include('parts/header.php'); ?>
<?php

  // セッションの変更情報を変数に入れる
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
  
  
  // var_dump($file1);
  // var_dump($file2);
  // var_dump($file3);
  // var_dump($title);
  // var_dump($kind);
  // var_dump($gender);
  // var_dump($age);
  // var_dump($area_1);
  // var_dump($area_2);
  // var_dump($area_3);
  // var_dump($animal_area);
  // var_dump($animal_character);
  // var_dump($other);


  /*************************************************************
 画像の変更の有無による分岐
   ************************************************************/
  // ファイル名の拡張子が画像になっているかを判別する関数
  // 拡張子を文字列としてreturn
  function mine_type($tmp_name)
  {
    if (strpos($tmp_name, 'jpg') !== false ||strpos($tmp_name, 'jpeg') !== false) {
      return "jpg";
    }
    if (strpos($tmp_name, 'png') !== false) {
      return "png";
    }
  }
  //画像3枚分繰り返す 
  for ($i = 1; $i <= 3; $i++) {
    // 画像に変更があれば
    if (!empty(${"file".$i})) {
      // $mineには拡張子が文字列として入る
      ${"mine".$i} = mine_type(${"file".$i}['type']);
      // var_dump(${"mine".$i});
      // var_dump(${"file".$i}['type']);
      // DBに登録するパス名を変数に入れる
      ${"image_".$i} = "{$animal_id}_image{$i}.{${"mine".$i}}";
      // animal_photoフォルダに保存する際に使用する変数に入れる
      ${"image_data".$i} = $_SESSION['animal']["image_$i"];
    } else { 
      //画像に変更がなければ現在animal_photoフォルダに保存されている画像名を変数に入れる
      $filename1 = "./images/animal_photo/{$animal_id}_image{$i}.jpg";
      $filename2 = "./images/animal_photo/{$animal_id}_image{$i}.jpeg";
      $filename3 = "./images/animal_photo/{$animal_id}_image{$i}.png";
      // var_dump($filename1);
      
      if (file_exists($filename1)) {
        ${"image_" . $i} = "{$animal_id}_image{$i}.jpg";
      }
      if (file_exists($filename2)) {
        ${"image_" . $i} = "{$animal_id}_image{$i}.jpeg";
      }
      if (file_exists($filename3)) {
        ${"image_" . $i} = "{$animal_id}_image{$i}.png";
      }
    }
  }


  // セッションの削除
  $_SESSION['animal'] = [];
 
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
 DB接続 UPDATE animalテーブル
     ************************************************************/

    try {
      
      $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      // var_dump($pdo);
      // sql文：アニマルテーブルを更新する
      $sql = "UPDATE animal SET title=?,kind=?,gender=?,age=?,area_1=?,area_2=?,area_3=?,animal_area=?,animal_character=?,other=?,image_1=?,image_2=?,image_3=? WHERE animal_id=?";
      $stm = $pdo->prepare($sql);
      $result = $stm->execute(array($title, $kind, $gender, $age, $area_1, $area_2, $area_3, $animal_area, $animal_character, $other, $image_1, $image_2, $image_3, $animal_id));
      
      // var_dump($image_1);
      // var_dump($image_2);
      // var_dump($image_3);
// 画像3枚分繰り返す
      for ($i = 1; $i <= 3; $i++) {
        // 画像の変更があった場合
        if (!empty(${"file" . $i}))   {

          $filename1 = "./images/animal_photo/{$animal_id}_image{$i}.jpg";
          $filename2 = "./images/animal_photo/{$animal_id}_image{$i}.jpeg";
          $filename3 = "./images/animal_photo/{$animal_id}_image{$i}.png";
          // var_dump($filename1);
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
          // var_dump('./images/animal_photo/' . ${"image_".$i});
// 新しい画像をanimal_photフォルダにアップロードする
          file_put_contents('./images/animal_photo/'.${"image_".$i},${"image_data".$i});
        }
      }
// $_SESSION['token']の削除
$_SESSION['token'] = [];
      
    } catch (Exception $e) {
      $e->getMessage();
      echo "変更に失敗しました。再度入力してください。";
      echo "<form method = 'post' action ='animal_change.php'>";
      echo "<input type='submit' value='前のページに戻る'>";
      echo "<input type='hidden' name='animal_id' value='<?= es($animal_id) ;?>'>";
echo "<input type='hidden' name='token' value='<?= es($token) ;?>'>";
echo "</form>";
exit();
}
}

?>


<div id="container">
    <main>
        <h2>犬猫情報変更完了</h2>
        <p>内容を変更しました。</p>
    </main>
</div>


<form method="POST" action="#">
    <input type="submit" value="ページを確認する" name="<?= $animal_id; ?>"
        formaction="recruit_detail.php?animal_id=<?= es($animal_id); ?>">
    <input type="submit" value="犬猫管理画面へ" formaction="animal_manage.php">

</form>


<?php include('parts/footer.php'); ?>