<?php
$pagetitle = "犬猫管理ページ";
include('parts/header.php');

// セッション開始
if(!isset($_SESSION)){
  session_start();
}

require_once("./lib/util.php");

// ユーザーIDがセッションに入っていれば$user_idに代入する
if (!empty($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  
} else { //セッションに入っていなければればログインページに戻す
  header("Location:login.php");
  exit();
}

// SESSIONの削除
$_SESSION['animal'] = [];

//  DB接続 基本情報
  // データベース接続
  $user = 'shotohlcd31_kfc';
  $password = 'KFCpassword';
  $dbName = 'shotohlcd31_kfc';
  $host = 'localhost';
  $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";

// DB接続 SELECT　登録している犬猫の表示

// animalテーブルへの接続
try {
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM animal WHERE user_id = :user_id";

  $stm = $pdo->prepare($sql);
  $stm->bindValue(":user_id", $user_id, PDO::PARAM_STR);
  $stm->execute();
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);
  // var_dump($result);
  $result_count = count($result);
} catch (Exception $e) {
  echo '<span class ="error">エラーがありました</span><br>';
  echo $e->getMessage();
  exit();
}

 ?>
<div id="container" class="c1">
    <main>
        <button onclick="location.href='breeder_mypage.php'" class="btn_back_mini marbtm10">
            < 戻る</button>
                <h2>犬猫管理画面</h2>
                <?php
    // 犬猫を登録していれば表示
    if ($result_count > 0) {
          echo "<div class='animal list-container'>";

      foreach ($result as $row) {
        echo <<<"EOL"
        <div class="list">
        <figure><img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}"></figure>
        <div class="text">
        <h4>{$row['title']}</h4>
            <p class="name">年齢：{$row['age']}&nbsp;{$row['gender']}</p>
            <p>{$row['animal_area']}</p>
            <p>掲載ID：{$row['animal_id']}</p>
            <form method="POST" action="#" class="c martop10">
            <input type="submit" value="確認" class="btn_animal conf" formaction="recruit_detail.php?animal_id={$row['animal_id']}">
            <input type="submit" value="修正" class="btn_animal change" formaction="animal_change.php">
            <input type="submit" value="削除" class="btn_animal del" formaction="animal_delete.php">
            <input type="hidden" name="animal_id" value="{$row['animal_id']}">
            </form>
          </div>
        </div>
        EOL;
      }
      echo "</div>";

      // 犬猫登録をしていない場合
    } else {
      echo "<p>犬猫登録されていません</p>";
    }
    
    ?>
                <form method="POST" action="breeder_mypage.php">
                    <input type="submit" value="マイページへ戻る" class="btn_back_one">
                </form>
    </main>
</div>
<?php include('parts/footer.php'); ?>