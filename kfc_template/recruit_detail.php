<?php

// titleで読み込むページ名
$pagetitle = "里親募集詳細";
include('parts/header.php');

// セッション開始
if (!isset($_SESSION)) {
  session_start();
}

//関数ファイル読み込み 
require_once("./lib/util.php");

// データベース接続
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
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


<div id="container" class="c1">
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


    //  いいね機能

    // 変数の初期化
    $dbPostData = ''; //表示している犬猫のデータ
    $dbPostGoodNum = ''; //いいねの数
    $user_id = $_SESSION['user_id'];

    // get送信がある場合
    if (!empty($_GET['animal_id'])) {
      // DBからいいねの数を取得
      $dbPostGoodNum = count(getGood($animal_id));
    }

    // いいね用関数

    // anima_idについたいいねレコード全てを取得する
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
        error_log('エラーが発生しました：' . $e->getMessage());
      }
    }

    // 訪れたユーザーがいいね済みかどうか調べる
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
        // var_dump($result);
        if ($stm->rowCount()) {
          return true;
        } else {
          return false;
        }
      } catch (Exception $e) {
        error_log('エラーが発生しました:' . $e->getMessage());
      }
    }

    // animalテーブルへの接続
    try {
      $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

        <div class="back_btn marbtm20">
            <?php
        // $_POST['breeder']でポストされてきた場合は犬猫管理画面へ戻す
        $send_filename = "";
        if (!empty($_POST['breeder'])) {
          $send_filename = "animal_manage.php";
        } else {
          $send_filename = "recruit.php";
        }
        ?>
            <a href="<?= $send_filename; ?>" class="btn_back_mini">
                < 戻る</a>
        </div>



        <div class="post" data-postid="<?= es($animal_id); ?>">
            <div>
                <?php
          foreach ($result as $row) {
            echo "<h2 class='r_title'>{$row['title']}</h2>";
            
            //いいねの表示
            // ログイン済みの場合のみいいねを表示させる
            if (!empty($_SESSION['user_id'])) {
          ?>
            </div>
            <div class="r">
                <div class="btn-good <?php if (isGood($user_id, $animal_id)) {
                                  echo 'active '; //いいね
                                } else {
                                  echo ''; //未いいね
                                }; ?>">

                    <span>いいね</span>
                    <i class=" fa-heart 
                    <?php if (isGood($user_id, $animal_id)) {
                      echo 'fas active  '; //いいね
                    } else {
                      echo 'far'; //未いいね
                    }; ?>"></i>
                    <span class="goodcount"><?php echo $dbPostGoodNum; ?></span>
                </div>
            </div>

            <!-- いいねの表示終わり -->
            <?php
            } // ログイン済みの場合のみいいねを表示させる終了


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

                        <table class='ta1 ta_fixed'>
                            <tr>
                                <th>性別</th>
                                <td colspan="3">{$row['gender']}</td>
                            </tr>
                            <tr>
                                <th>年齢</th>
                                <td colspan="3">{$row['age']}</td>
                            </tr>
                            <tr>
                                <th>募集対象地域</th>
                                <td>{$row['area_1']}</td>
                                <td>{$row['area_2']}</td>
                                <td>{$row['area_3']}</td>
                            </tr>
                            <tr>
                                <th>動物がいる地域</th>
                                <td colspan="3">{$row['animal_area']}</td>
                            </tr>
                            <tr>
                                <th>特徴（性格等）</th>
                                <td colspan="3">{$row['animal_character']}</td>
                            </tr>
                            <tr>
                                <th>特記事項</th>
                                <td colspan="3">{$row['other']}</td>
                            </tr>
                        </table>
                </div>
                EOL;
          }
        }
  ?>

            <p class="c" style="color:red">※里親申し込みには会員登録が必要です。</p>
            <!-- 申込フォームへ -->
            <form action="./recruit_form.php" method="POST">
                <input type="hidden" name='animal_id' value="<?php echo $animal_id ?>">
                <?php
    if ($user_id !== $destination_user_id) {
      echo "<input type='submit' name='submit' value='申し込みフォームへ' class='btn_one'>";
    }
    ?>
                <?php
    // $_POST['breeder']でポストされてきた場合は犬猫管理画面へ戻す
    $send_filename = "";
    if (!empty($_POST['breeder'])) {
      $send_filename = "animal_manage.php";
    } else {
      $send_filename = "recruit.php";
    }
    ?>
                <button type="button" class="btn_back_one martop10"
                    onclick="location.href='<?= $send_filename; ?>'">戻る</button>
            </form>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="./js/good.js">
</script>
<script>
var thumbs = document.querySelectorAll('.thumbnails');
for (var i = 0; i < thumbs.length; i++) {
    thumbs[i].onclick = function() {
        document.getElementById('main-img').src = this.dataset.imagesrc;
    };
}
</script>
<?php include('parts/footer.php'); ?>