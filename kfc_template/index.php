<?php
// titleで読み込むページ名
$pagetitle = "犬猫里親募集"
?>

<?php include('parts/header.php'); ?>
<?php
// セッション開始
if(!isset($_SESSION)){
  session_start();
}

// $_SESSION = [];
require_once("./lib/util.php");
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
//$host = 'sv14471.xserver.jp';
$host = 'localhost';
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
?>
<?php
// animalテーブルへの接続
try {
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // 新着animalのSQL文（8件まで）
  $sql = "SELECT * FROM animal ORDER BY animal_id DESC LIMIT 12";

  // echo "{$sql}<br>"; //確認用
  $stm = $pdo->prepare($sql);

  // クエリ実行
  $stm->execute();
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  echo '<span class ="error">エラーがありました</span><br>';
  echo $e->getMessage();
  exit();
}
?>

<!-- message_add.phpにPOSTするフォーム -->
<?php
      /*
      <form method="post" action="./message_add.php">
      <textarea class="textarea form-control" placeholder="メッセージを入力ください" name="text"></textarea>
      <input type="hidden" name="destination_user_id" value="<?= $destination_user['user_id'] ?>">
<div class="message_btn">
    <div class="message_image">
        <!-- <input type="file" name="image" class="my_image" accept="image/*" multiple> -->
    </div>
    <button class="btn btn-outline-primary" type="submit" name="post" value="post" id="post">投稿</button>
</div>
</form>
*/
?>

<!-- ---------------------------
  スライドショー（slick）
---------------------------- -->
<div id="index_page">
<aside id="mainimg">
    <div class="mainimg">
        <div><img src="images/1.jpg" alt=""></div>
        <div><img src="images/2.jpg" alt=""></div>
        <div><img src="images/3.jpg" alt=""></div>
    </div>
    <img src="images/kazari.png" alt="" class="kazari">
</aside>

<div id="container" class="c1">
    <main>

        <!-- ---------------------------
      犬猫リスト
    ---------------------------- -->
        <section>
            <h2>犬猫たちが里親さんを待っています<span>New Animals</span></h2>
            <!-- ここから犬猫リスト -->
            <div class="list-container">
                <?php
        if (isset($result)) {
          foreach ($result as $row) {
            echo <<<"EOL"
              <div class="list">
                <a href="recruit_detail.php?animal_id={$row['animal_id']}">
                  <figure><img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}"></figure>
                  <div class="text">
                    <h4>{$row['title']}</h4>
                    <p class="name">年齢：{$row['age']}&nbsp;{$row['gender']}</p>
                    <p>{$row['animal_area']}</p>
                    <p>掲載ID：{$row['animal_id']}</p>
                  </div>
                  <span class="newicon">NEW</span>
                </a>
              </div>
        EOL;
          }
        }
        ?>
            </div>
            <!--/.list-container-->
            <!-- ここまで犬猫リスト -->
        </section>

        <!-- ---------------------------
      お知らせ
    ---------------------------- -->
        <section>
            <h2>お知らせ<span>What's New</span></h2>
            <dl id="new">
                <dt>2023/04/27<span class="icon-bg1">新着</span></dt>
                <dd>ホームページをリニューアルしました。<span class="newicon">NEW</span></dd>
                <dt>2023/04/05<span class="icon-bg1">新着</span></dt>
                <dd>ブリーダーさん向け登録ページを一部更新しました。</dd>
                <dt>2023/03/20<span class="icon-bg2">イベント</span></dt>
                <dd>子猫譲渡会の開催決定しました。詳細はイベントページより！</dd>
                <dt>2023/03/12<span>その他</span></dt>
                <dd>お知らせのサンプルテキスト。サンプルテキスト。サンプルテキスト。</dd>
            </dl>
        </section>
    </main>
</div>
</div><!--index_page-->

<?php include('parts/footer.php'); ?>