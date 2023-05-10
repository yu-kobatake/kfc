<?php
session_start();
require_once("./lib/util.php");
// $user = 'testuser';
// $password = 'pw4testuser';
// $dbName = 'shotohlcd31_kfc';
// $host = 'localhost';
// $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

// データベース接続
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
//$host = 'sv14471.xserver.jp';
$dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";
?>
<?php
if (!cken($_GET)) {
  exit("不正な文字コードです。");
}
$_GET = es($_GET);

// アンケート情報の$_SESSIONを空にする
$_SESSION['errors_agree'] = [];
$_SESSION['agree_1'] = [];
$_SESSION['agree_2'] = [];
$_SESSION['agree_3'] = [];
$_SESSION['errors'] = [];
$_SESSION['question_1'] = [];
$_SESSION['question_2'] = [];
$_SESSION['question_3'] = [];
$_SESSION['question_4'] = [];
$_SESSION['question_5'] = [];
$_SESSION['question_6'] = [];
$_SESSION['question_7'] = [];

?>

<?php
// titleで読み込むページ名
$pagetitle = "里親募集詳細"
?>
<?php include('parts/header_recruit_detail.php'); ?>
<div id="container">
  <main>
    <?php
    if (!empty($_GET['animal_id'])) {
      $animal_id = $_GET['animal_id'];
      // var_dump($animal_id);      
    } else {
      echo "<p>無効な掲載IDです。</p>";
      echo "<a href='recruit.php'><button>前ページに戻る</button></a><br>";
      exit();
    }
    /******************************************* 
 いいね用コード
     *******************************************/
    $dbPostData = ''; //投稿内容
    $dbPostGoodNum = ''; //いいねの数
    $user_id = $_SESSION['user_id'];

    // get送信がある場合
    if (!empty($_GET['animal_id'])) {
      // DBから投稿データを取得
      // $dbPostData = getPostData($animal_id);
      // DBからいいねの数を取得
      $dbPostGoodNum = count(getGood($animal_id));
      // var_dump($dbPostGoodNum);
      // var_dump(getGood($animal_id));
    }

    // いいね用関数

    // anima_idについたいいねレコード 全てを取得する
    function getGood($animal_id)
    {
      try {
        global $dsn;
        global $user;
        global $password;
        global $animal_id;
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM good WHERE animal_id = :animal_id';
        // クエリ実行
        $stm = $pdo->prepare($sql);
        $result = $stm->execute(array(':animal_id' => $animal_id));
        if ($stm) {
          return $stm->fetchAll();
        } else {
          return false;
        }
      } catch (Exception $e) {
        error_log('エラー発生：' . $e->getMessage());
      }
    }

    // 訪れたユーザーがいいね済みかどうか調べる関数
    function isGood($u_id, $p_id)
    {

      try {
        global $dsn;
        global $user;
        global $password;
        global $animal_id;
        global $user_id;
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
        $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $sql = 'SELECT * FROM good WHERE animal_id = :animal_id AND user_id = :user_id';
        // $data = array(':user_id' => $user_id, ':animal_id' => $animal_id);
        // クエリ実行
        $stm = $pdo->prepare($sql);
        $result = $stm->execute(array(':user_id' => $user_id, ':animal_id' => $animal_id));
        var_dump($result);
        if ($stm->rowCount()) {
          return true;
        } else {
          return false;
        }
      } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
      }
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
    if (isset($result)) {
      $destination_user_id = $result[0]['user_id'];
    ?>
      <div class="back_btn">
        <a href="recruit.php"><button>＜ 戻る</button></a>
      </div>

      <?php
      foreach ($result as $row) {
        echo "<h2>{$row['title']}</h2>";
        // ログイン済みの場合のみいいねを表示させる
        if (!empty($_SESSION['user_id'])) {
      ?>
          <!-- いいねの表示 -->
          <div class="post" data-postid="<?= es($animal_id); ?>">
            <div class="btn-good <?php if (isGood($user_id, $animal_id)) { //いいねの状態文字色ピンク
                                    echo 'active ';
                                  } else { //未いいねの状態文字色指定なし
                                    echo '';
                                  }; ?>">

              <span>いいね</span>
              <i class=" fa-heart 
                    <?php if (isGood($user_id, $animal_id)) { //いいねの状態ハート塗りつぶし
                      echo 'fas active  ';
                    } else { //未いいねの時ハート空洞
                      echo 'far';
                    }; ?>"></i>
              <span class="goodcount"><?php echo $dbPostGoodNum; ?></span>
            </div>
            <!-- いいねの表示終わり -->
      <?php
        } // いいねif修了
        echo <<<"EOL"
                        

                        <!--全体の枠-->
                        <div id='cover'>
                        
                        <!--メイン画像-->
                        <div class='main-frame'>
                        <img id='main-img' src='./images/animal_photo/{$row['image_1']}'>
                        </div>
                        
                        <!--サムネイル部分-->
                        <ul class='thumbspace flex'>
                        <li>
                        <img class='thumbnails' src='./images/animal_photo/{$row['image_1']}' data-imagesrc='./images/animal_photo/{$row['image_1']}' >
                        </li>
                        <li>
                        <img class='thumbnails' src='./images/animal_photo/{$row['image_2']}' data-imagesrc='./images/animal_photo/{$row['image_2']}' >
                        </li>
                        <li>
                        <img class='thumbnails' src='./images/animal_photo/{$row['image_3']}' data-imagesrc='./images/animal_photo/{$row['image_3']}' >
                        </li>
                        </ul>
                        
                        </div>

                        <table class='ta1'>
                            <tr>
                                <th>性別</th>
                            </tr>
                            <tr>
                                <td>{$row['gender']}</td>
                            </tr>
                            <tr>
                                <th>年齢</th>
                            </tr>
                            <tr>
                                <td>{$row['age']}</td>
                            </tr>
                            <tr>
                                <th>募集対象地域</th>
                            </tr>
                            <tr>
                                <td>{$row['area_1']}</td>
                            </tr>
                            <tr>
                                <td>{$row['area_2']}</td>
                            </tr>
                            <tr>
                                <td>{$row['area_3']}</td>
                            </tr>
                            <tr>
                                <th>動物がいる地域</th>
                            </tr>
                            <tr>
                                <td>{$row['animal_area']}</td>
                            </tr>
                            <tr>
                                <th>特徴（性格等）</th>
                            </tr>
                            <tr>
                                <td>{$row['animal_character']}</td>
                            </tr>
                            <tr>
                                <th>特記事項</th>
                            </tr>
                            <tr>
                                <td>{$row['other']}</td>
                            </tr>
                        </table>
                </div>
                EOL;
      }
    }
      ?>
      <p style="color:red">里親申し込みには会員登録が必要です。</p>
      <!-- 申込フォームへ -->
      <form action="./recruit_form.php" method="POST">
        <input type="hidden" name='animal_id' value="<?php echo $animal_id ?>">
        <?php
        if ($user_id !== $destination_user_id) {
          echo "<input type='submit' name='submit' value='申し込みフォームへ'>";
        }
        ?>
      </form>
  </main>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="./js/good.js">
</script>
<script>
    var thumbs = document.querySelectorAll('.thumbnails');
  for (var i = 0; i < thumbs.length; i++) {
    thumbs[i].onclick = function() {
      document.getElementById('main-img').src = this.dataset.imagesrc;
    };
  }

$(window).load(function (){
// ↓ここに幅を変更したいクラスなど入れる
$('.mainimage img').each(function(){
var img_height = $(this).height();
var img_width = $(this).width();
var img = new Image();
img.src = $(this).attr('src');
var _width = img.width;
var device_width = $(window).width();
//横÷縦の値が１以上、つまり横長の場合
if((img_width / img_height) >= 1){
//.css(ここに挿入以下サンプル）
if(_width <= device_width){
$(this).css("width", "auto");
}else{
$(this).css("width", "100%");
}
$(this).css("max-width", "100%");
}else{
// そのほかの場合（縦長の場合）こんな感じ
$(this).css({"max-height": "400px","margin":"0 auto","width":"auto","display":"block"});
if($(this).width() >= device_width){
$(this).css("width", "100%");
}else{
$(this).css("width", "auto");
}
}
});
});
</script>
<?php include('parts/footer.php'); ?>