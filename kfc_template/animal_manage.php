<?php
session_start();
require_once("./lib/util.php");

// ユーザーidがセッションに入っていなければログインページに戻す
if (!empty($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  header("Location:login.php");
  exit();
}

// SESSIONの削除
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
DB接続 SELECT
登録している犬猫の表示
 ************************************************************/
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

$pagetitle = "犬猫管理ページ";
include('parts/header.php'); ?>
<div id="container">
    <main>

        <?php
    // 犬猫を登録していれば表示
    if (isset($result)) {
      // define('MAX','12');
      // // 総数は$result_countに入っている
      // $max_page = ceil($result_count / MAX);
      // if(!isset($_GET['page_id'])){
      //   $now = 1;
      // }else{
      //   $now = $_GET['page_id'];
      // }
      // $start_no = ($now - 1) * MAX;

      // $disp_data = array_slice($result, $start_no, MAX, true);


      // 
      echo "<div class='animal list-container'>";
      foreach ($result as $row) {
        echo <<<"EOL"
        <div class="list">
        <figure><img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}"></figure>
        <div class="text">
        <p>{$row['title']}</p>
            <p>年齢：{$row['age']}&nbsp;{$row['gender']}</p>
            <p>{$row['animal_area']}</p>
            <p>掲載ID：{$row['animal_id']}</p>
            <form method="POST" action="#">
            <input type="submit" value="確認" formaction="recruit_detail.php?animal_id={$row['animal_id']}">
            <input type="submit" value="修正" formaction="animal_change.php">
            <input type="submit" value="削除" formaction="animal_delete.php">
            <input type="hidden" name="animal_id" value="{$row['animal_id']}">
            </form>
          </div>
        </div>
        EOL;
      }
      echo "</div>";
      // for($i = 1; $i <= $max_page; $i++){ // 最大ページ数分リンクを作成
      //   if ($i == $now) { // 現在表示中のページ数の場合はリンクを貼らない
      //       echo $now. '　'; 
      //   } else {
      //       echo '  <a href="animal_manage.php?page_id={$i}">'.$i.'</a>'.'&nbsp;&nbsp;';
      //   }
      // }
      // 犬猫登録をしていない場合
    } else {
      echo "<p>犬猫登録されていません</p>";
    }
    
    ?>
        <form method="POST" action="breeder_mypage.php">
            <input type="submit" value="マイページへ戻る">
        </form>
    </main>
</div>
<?php include('parts/footer.php'); ?>