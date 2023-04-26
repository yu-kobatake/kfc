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
if (!cken($_GET)) {
  exit("不正な文字コードです。");
}
$_GET = es($_GET);
?>

<?php
// titleで読み込むページ名
$pagetitle = "里親募集詳細"
?>
<?php include('parts/header.php'); ?>

<div id="container">
  <main>
    <?php
    if (!empty($_GET['animal_id'])) {
      $animal_id = $_GET['animal_id'];
    } else {
      echo "<p>無効な掲載IDです。</p>";
      echo "<a href='recruit.php'><button>里親募集ページに戻る</button></a><br>";
      exit();
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
    } catch (Exception $e) {
      echo '<span class ="error">エラーがありました</span><br>';
      echo $e->getMessage();
      exit();
    }
    ?>
    <?php
    if (isset($result)) {
      foreach ($result as $row) {
        echo <<<"EOL"
  <div>
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
  EOL;
      }
    }
    ?>
    <!-- 申込フォームへ -->
    <form action="./recruit_form.php" method="POST">
      <input type="hidden" name='animal_id' value="<?php echo $animal_id ?>">
      <input type="submit" name="submit" value="申し込みフォームへ">
    </form>
    <?php
    ?>
  </main>
</div>

<?php include('parts/footer.php'); ?>