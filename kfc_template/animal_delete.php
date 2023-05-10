<?php
// titleで読み込むページ名
$pagetitle = "犬猫登録削除"
?>
<?php include('parts/header.php'); ?>

<?php
// セッション開始
if(!isset($_SESSION)){
session_start();
}


require_once("./lib/util.php");
// var_dump($_SESSION);
// var_dump($_POST);

// ユーザーIDがセッションに入っていれば$user_idに代入する
if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
  //セッションに入っていなければればログインページに戻す 
  } else { 
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
    // var_dump($result);
    // エスケープ処理
    $result = es($result);

    foreach ($result as $row) {
        echo <<<"EOL"
        <div id="container" class="c1">
            <main>
            <h2>{$pagetitle}</h2>
            <div class="back_btn">
            <button onclick="location.href='animal_manage.php'" class="btn_back_mini marbtm10">戻る</button>
        </div>

  <h3>{$row['title']}</h3>
  <div class="animal_photo_all">
    <img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}">
    <div class="animal_photo">
    <img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}">
    <img src="./images/animal_photo/{$row['image_2']}" alt="{$row['kind']}">
    <img src="./images/animal_photo/{$row['image_3']}" alt="{$row['kind']}">
    </div>
  </div>
  <table class='ta1'>
    <tr>
    <th>性別</th>
    <td>{$row['gender']}</td>
    </tr>
    <tr>
    <th>年齢</th>
    <td>{$row['age']}</td>
    </tr>
    <tr>
    <th>募集対象地域</th>
    <td>{$row['area_1']}</td>
    <td>{$row['area_2']}</td>
    <td>{$row['area_3']}</td>
    </tr>
    <tr>
    <th>動物がいる地域</th>
    <td>{$row['animal_area']}</td>
    </tr>
    <tr>
    <th>特徴（性格等）</th>
    <td>{$row['animal_character']}</td>
    <tr>
    <th>特記事項</th>
    <td>{$row['other']}</td>
    </tr>
      </table>
    <div>
    <h4 class="c">掲載ID{$row['animal_id']}の登録を削除しますか？</h4>
    <form method="POST" action="#">
    <input type="submit" formaction="animal_delete_complet.php" value="削除" class="btn_one marbtm10">
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