<?php
// titleで読み込むページ名
$pagetitle = "犬猫情報変更完了";
include('parts/header.php'); ?>

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


 
//  画像の変更の有無による分岐
  
// ファイル名の拡張子が画像になっているかを判別する関数
// 拡張子を文字列としてreturn
  function mine_type($tmp_name)
  {
    if (strpos($tmp_name, 'jpg') !== false || strpos($tmp_name, 'jpeg') !== false) {
      return "jpg";
    }
    if (strpos($tmp_name, 'png') !== false) {
      return "png";
    }
  }
  //画像3枚分繰り返す 
  for ($i = 1; $i <= 3; $i++) {
    // 画像に変更があれば
    if (!empty(${"file" . $i})) {
      // $mineには拡張子が文字列として入る
      ${"mine" . $i} = mine_type(${"file" . $i}['type']);
      // DBに登録する画像の名前をimage[1,2,3]変数に入れる
      ${"image_" . $i} = "{$animal_id}_image{$i}.{${"mine" .$i}}";
      // animal_photoフォルダに画像を保存する際に使用するimage_data[1,2,3]変数にデータを入れる
      ${"image_data" . $i} = $_SESSION['animal']["image_$i"];

    } else {
      //画像に変更がなければ現在animal_photoフォルダに保存されている画像名を変数に入れる
      $filename1 = "./images/animal_photo/{$animal_id}_image{$i}.jpg";
      $filename2 = "./images/animal_photo/{$animal_id}_image{$i}.jpeg";
      $filename3 = "./images/animal_photo/{$animal_id}_image{$i}.png";

      //現在登録されている画像の名前をimage[1,2,3]変数に入れる
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


  // データベース接続
  $user = 'shotohlcd31_kfc';
  $password = 'KFCpassword';
  $dbName = 'shotohlcd31_kfc';
  $host = 'localhost';
  $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";

// DB接続 UPDATE animalテーブル
  try {
    
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql文：アニマルテーブルを更新する
    $sql = "UPDATE animal SET title=?,kind=?,gender=?,age=?,area_1=?,area_2=?,area_3=?,animal_area=?,animal_character=?,other=?,image_1=?,image_2=?,image_3=? WHERE animal_id=?";
    $stm = $pdo->prepare($sql);
    $result = $stm->execute(array($title, $kind, $gender, $age, $area_1, $area_2, $area_3, $animal_area, $animal_character, $other, $image_1, $image_2, $image_3, $animal_id));

    // 画像3枚分繰り返す
    for ($i = 1; $i <= 3; $i++) {
      // 画像の変更があった場合
      if (!empty(${"file" . $i})) {

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
        // 新しい画像をanimal_photフォルダにアップロードする
        file_put_contents('./images/animal_photo/' . ${"image_" . $i}, ${"image_data" . $i});
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

<div id="container" class="c1">
    <main>
        <div class="c">
            <h2>犬猫情報変更完了</h2>
            <p>内容を変更しました。</p>
        </div>
        <form method="POST" action="#">
            <p class="c">
                <input type="submit" value="ページを確認する" name="<?= $animal_id; ?>"
                    formaction="recruit_detail.php?animal_id=<?= es($animal_id); ?>" class="btn_one">
            </p>
            <p class="c">
                <input type="submit" value="犬猫管理画面へ" formaction="animal_manage.php" class="btn_back_one">
            </p>
<<<<<<< HEAD
            <input type="hidden" name="back_1">
=======
>>>>>>> cbdf3be8c644234b84883b1a409e4eb4f1a0e085
        </form>
    </main>
</div>

<?php include('parts/footer.php'); ?>