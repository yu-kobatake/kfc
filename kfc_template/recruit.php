<?php
session_start();
require_once("./lib/util.php");
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
$dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";
?>
<?php
if (!cken($_POST)) {
  exit("不正な文字コードです。");
}
$_POST = es($_POST);

if (!empty($_POST['kind'])) {
  $kind = $_POST['kind'];
} else {
  $kind = '';
}
if (!empty($_POST['area'])) {
  $area = $_POST['area'];
} else {
  $area = '';
}
if (!empty($_POST['animal_area'])) {
  $animal_area = $_POST['animal_area'];
} else {
  $animal_area = '';
}
if (!empty($_POST['keyword'])) {
  $keyword = $_POST['keyword'];
} else {
  $keyword = '';
}

// アンケート情報の$_SESSIONを空にする
$_SESSION['animal_id'] = [];
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
$pagetitle = "里親募集ページ"
?>
<?php include('parts/header.php'); ?>
<div id="container" class="c1">
  <main>
    <h2><?php echo $pagetitle ?></h2>
    <!--検索フォーム-->
    <form class="searchf" method="post" action="<?php echo es($_SERVER['SCRIPT_NAME']) ?>">
      <p class='kind_chk'><span class="label">種別</span>
        <?php
        if ($kind === '全て') {
          echo "<label><input type='radio' name='kind' value='全て' checked>全て</label>";
          echo "<label><input type='radio' name='kind' value='犬'>犬</label>";
          echo "<label><input type='radio' name='kind' value='猫'>猫</label>";
        } elseif ($kind === '犬') {
          echo "<label><input type='radio' name='kind' value='全て'>全て</label>";
          echo "<label><input type='radio' name='kind' value='犬' checked>犬</label>";
          echo "<label><input type='radio' name='kind' value='猫'>猫</label>";
        } elseif ($kind === '猫') {
          echo "<label><input type='radio' name='kind' value='全て'>全て</label>";
          echo "<label><input type='radio' name='kind' value='犬'>犬</label>";
          echo "<label><input type='radio' name='kind' value='猫' checked>猫</label>";
        } else {
          echo "<label><input type='radio' name='kind' value='全て' checked>全て</label>";
          echo "<label><input type='radio' name='kind' value='犬'>犬</label>";
          echo "<label><input type='radio' name='kind' value='猫'>猫</label>";
        }
        ?>
        <!-- 「都道府県」リスト -->
        <?php
        $pref_list = ['', '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'];
        ?>
        <span class="label">募集対象地域</span>
        <select name="area">
          <?php
          foreach ($pref_list as $pref) {
            if ($area == $pref) {
              echo " <option value='$pref' selected>$pref</option>";
            } else {
              echo " <option value='$pref'>$pref</option>";
            }
          }
          ?>
        </select>
        <?php
        ?>
        <span class="label">動物のいる地域</span>
        <select name="animal_area">
          <?php
          foreach ($pref_list as $pref) {
            if ($animal_area == $pref) {
              echo " <option value='$pref' selected>$pref</option>";
            } else {
              echo " <option value='$pref'>$pref</option>";
            }
          }
          ?>
        </select>
        <?php
        ?>
      </p>
      <p><span class="label">キーワード</span>
        <input class="ws" type="text" name="keyword" value="<?php echo $keyword; ?>">
      </p>
      <p><input type="submit" name="submit" value="検索する"></p>
    </form>
    <?php
    if (isset($_POST['submit'])) {
      //全てが選択された時の処理
      $kind = $_POST['kind'];

      //$areaと$animal_areaと$keywordはemptyじゃなければ代入
      if (!empty($_POST['area'])) {
        $area = $_POST['area'];
      } else {
        $area = '';
      }
      if (!empty($_POST['animal_area'])) {
        $animal_area = $_POST['animal_area'];
      } else {
        $animal_area = '';
      }
      if (!empty($_POST['keyword'])) {
        $keyword = "%" . $_POST['keyword'] . "%";
      } else {
        $keyword = '';
      }

      // animalテーブルへの接続
      try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "データベース{$dbName}に接続しました", "<br>";//確認用
        //検索条件に合わせてsql文を作成

        // 2項目(area,animal_area)
        if (!empty($area) && !empty($animal_area)) {
          $sql = "SELECT animal_id,age,animal_area,gender,age,title,image_1,image_2,image_3,kind
          FROM animal 
          WHERE (area_1 = :area_1 OR area_2 = :area_2 OR area_3 = :area_3) AND
          animal_area = :animal_area ";
        } elseif (!empty($area) || !empty($animal_area)) {
          //1項目(area)
          if (!empty($area)) {
            $sql = "SELECT animal_id,age,animal_area,gender,age,title,image_1,image_2,image_3,kind
            FROM animal 
              WHERE (area_1 = :area_1 OR area_2 = :area_2 OR area_3 = :area_3) ";
          }
          //1項目(animal_area)
          if (!empty($animal_area)) {
            $sql = "SELECT animal_id,age,animal_area,gender,age,title,image_1,image_2,image_3,kind
            FROM animal 
              WHERE animal_area = :animal_area ";
          }
          //(項目無し)
        } else {
          if ($kind === '全て') {
            $sql = "SELECT animal_id,age,animal_area,gender,age,title,image_1,image_2,image_3,kind
            FROM animal ";
          }
        }

        // keywordあれば追記
        if (!empty($keyword)) {
          if (
            $kind === '全て' &&
            empty($area) &&
            empty($animal_area)
          ) {
            $sql .= " WHERE concat(animal_id, title, gender ,age ,other) LIKE :keyword ";
          } else {
            $sql .= " AND concat(animal_id, title, gender ,age ,other) LIKE :keyword ";
          }
        }

        // kindあれば追記
        if (($kind === '犬' || $kind === '猫') &&
          empty($area) &&
          empty($animal_area)
        ) {
          $sql = "SELECT animal_id,age,animal_area,gender,age,title,image_1,image_2,image_3,kind 
          FROM animal 
          WHERE kind = :kind ";
        } elseif ($kind === '犬' || $kind === '猫') {
          $sql .= "AND kind = :kind ";
        }


        $stm = $pdo->prepare($sql);


        //プレースホルダーを作成
        if ($kind === '犬' || $kind === '猫') {
          $stm->bindValue(':kind', $kind, PDO::PARAM_STR);
        }
        if (!empty($area)) {
          $stm->bindValue(':area_1', $area, PDO::PARAM_STR);
          $stm->bindValue(':area_2', $area, PDO::PARAM_STR);
          $stm->bindValue(':area_3', $area, PDO::PARAM_STR);
        }
        if (!empty($animal_area)) {
          $stm->bindValue(':animal_area', $animal_area, PDO::PARAM_STR);
        }
        if (!empty($keyword)) {
          $stm->bindValue(':keyword', $keyword, PDO::PARAM_STR);
        }
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        //該当件数
        $hit = count($result);
        echo "<div class='serch_hit'>該当 {$hit}件です。</div>";
      } catch (Exception $e) {
        echo '<span class ="error">エラーがありました</span><br>';
        echo $e->getMessage();
        exit();
      }
    } else {
      /* --------- 初期表示（検索前の全表示） --------- */
      // animalテーブルへの接続
      try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL文でデータ抽出
        $sql = "SELECT animal_id,age,animal_area,gender,age,title,image_1,image_2,image_3,kind
        FROM animal LIMIT 0,20";
        // プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);

        // SQLクエリを実行してfetchAll
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

      } catch (Exception $e) {
        echo '<span class ="error">エラーがありました</span><br>';
        echo $e->getMessage();
        exit();
      }
    }
    ?>
    <!-- ここからリスト -->
    <div class="list-container">
      <?php
      //echo "<div>";
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
    </div><!-- / list-container -->
  </main>
</div>
<img src="./images/animal_photo/" alt="">
<?php include('parts/footer.php'); ?>