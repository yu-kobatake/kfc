<?php
session_start();
require_once("./lib/util.php");

$user = 'username';
$password = 'kfc';
$dbName = 'shotohlcd31_ kfc';
$host = 'localhost';
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
?>
<?php
if (!cken($_POST)) {
  exit("不正な文字コードです。");
}
$_POST = es($_POST);

//token確認 
if (isset($_SESSION['token']) && isset($_POST['token'])) {
  if ($_SESSION['token'] !== $_POST['token']) {
    echo "<p>不正なアクセスです。①</p>";
    echo "<a href='recruit_detail.php'><button>前ページに戻る</button></a><br>";
    exit();
  }
} else {
  echo "<p>不正なアクセスです。②</p>";
  echo "<a href='recruit_detail.php'><button>前ページに戻る</button></a><br>";
  exit();
}

?>

<?php
// titleで読み込むページ名
$pagetitle = "里親申し込み確認"
?>
<?php include('parts/header.php'); ?>
<div id="container">
  <main>
    <?php
    // if (!empty($_SESSION['user_id'])) {
    //   $user_id = $_SESSION['user_id'];
    // } else {
    //   echo "<p>ログインしてください。</p>";
    // }

    $user_id = 8;
    $animal_id = 2;

    if (!empty($_POST['animal_id'])) {
      $animal_id = $_POST['animal_id'];
    } else {
      echo "<p>無効な掲載IDです。</p>";
      echo "<a href='recruit_detail.php'><button>前ページに戻る</button></a><br>";
    }


    // animalテーブルへの接続
    try {
      $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "データベース{$dbName}に接続しました", "<br>"; //確認用
      if (!empty($animal_id)) {
        $sql = "SELECT * FROM animal WHERE animal_id = :animal_id ";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':animal_id', $animal_id, PDO::PARAM_STR);

        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($result);
      }

      // animal表示
      if (isset($result)) {
        foreach ($result as $row) {
          echo <<<"EOL"
            <div>
            <h3>動物情報</h3>
            <p>掲載ID：{$row['animal_id']}</p>
            <img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}">
            <table class='ta1'>
              <tr><th>性別</th></tr>
              <tr><td>{$row['gender']}</td></tr>
              <tr><th>年齢</th></tr>
              <tr><td>{$row['age']}</td></tr>
            </table>
            </div>
            EOL;
        }
      }
    } catch (Exception $e) {
      echo '<span class ="error">エラーがありました</span><br>';
      echo $e->getMessage();
      exit();
    }


    // userテーブルへの接続
    try {
      $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "データベース{$dbName}に接続しました", "<br>"; //確認用
      if (!empty($user_id)) {
        $sql = "SELECT * FROM user WHERE user_id = :user_id ";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':user_id', $user_id, PDO::PARAM_STR);

        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($result);
      }
      // user表示
      if (isset($result)) {
        foreach ($result as $row) {
          echo <<<"EOL"
          <div>
          <h3>ユーザー情報</h3>
          <table class='ta1'>
            <tr><th>ユーザー名</th></tr>
            <tr><td>{$row['user_name']}</td></tr>
            <tr><th>性別</th></tr>
            <tr><td>{$row['gender']}</td></tr>
            <tr><th>生年月日</th></tr>
            <tr><td>{$row['birth']}</td></tr>
            <tr><th>住所</th></tr>
            <tr><td>{$row['address']}</td></tr>
            <tr><th>ご職業</th></tr>
            <tr><td>{$row['job']}</td></tr>
          </table>
          </div>
          EOL;
        }
      }
    } catch (Exception $e) {
      echo '<span class ="error">エラーがありました</span><br>';
      echo $e->getMessage();
      exit();
    }
    ?>

    <?php
    // 解答項目表示
    if(!empty($_POST['question_1'])){
      $_SESSION['question_1']=$_POST['question_1'];
      $question_1=$_POST['question_1'];
    }else
    ?>
  </main>
</div>

<?php include('parts/footer.php'); ?>