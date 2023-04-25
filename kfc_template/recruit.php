<?php
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


?>
<?php
// titleで読み込むページ名
$pagetitle = "里親募集ページ"
?>
<?php include('parts/header.php'); ?>
<div id="container">
  <main>
    <!--検索フォーム-->
    <form method="post" action="<?php echo es($_SERVER['SCRIPT_NAME']) ?>">
      <p>種別
        <!-- <input type="radio" name="kind" value="全て" checked>全て
        <input type="radio" name="kind" value="犬">犬
        <input type="radio" name="kind" value="猫">猫 -->
        <?php
        if ($kind === '全て') {
          echo "<input type='radio' name='kind' value='全て' checked>全て";
          echo "<input type='radio' name='kind' value='犬'>犬";
          echo "<input type='radio' name='kind' value='猫'>猫";
        } elseif ($kind === '犬') {
          echo "<input type='radio' name='kind' value='全て'>全て";
          echo "<input type='radio' name='kind' value='犬' checked>犬";
          echo "<input type='radio' name='kind' value='猫'>猫";
        } elseif ($kind === '猫') {
          echo "<input type='radio' name='kind' value='全て'>全て";
          echo "<input type='radio' name='kind' value='犬'>犬";
          echo "<input type='radio' name='kind' value='猫' checked>猫";
        } else {
          echo "<input type='radio' name='kind' value='全て' checked>全て";
          echo "<input type='radio' name='kind' value='犬'>犬";
          echo "<input type='radio' name='kind' value='猫'>猫";
        }
        ?>
      </p>
      <!-- 「都道府県」リスト -->
      <?php
      $pref_list = ['', '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'];
      ?>
      <p>募集対象地域
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
        // var_dump($area);
        ?>
      </p>
      <p>動物のいる地域
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
        // var_dump($animal_area);
        ?>
      </p>
      <p>キーワード
        <input type="text" name="keyword" value="<?php echo $keyword; ?>">
      </p>
      <p><input type="submit" name="submit" value="検索"></p>
    </form>
    <?php
    // var_dump($_POST);//確認用
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


        // echo "{$sql}<br>"; //確認用
        $stm = $pdo->prepare($sql);
        // echo "{$kind}<br>"; //確認用
        // echo "{$area}<br>"; //確認用
        // echo "{$animal_area}<br>"; //確認用


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
        echo "<p>該当{$hit}件です。</p>";
        // var_dump($result); //確認用

      } catch (Exception $e) {
        echo '<span class ="error">エラーがありました</span><br>';
        echo $e->getMessage();
        exit();
      }
    }
    ?>

    <?php
    echo "<div>";
    if (isset($result)) {
      foreach ($result as $row) {
        echo <<<"EOL"
  <a href="recruit_detail.php?animal_id={$row['animal_id']}">
  <div> 
  <img src="./images/animal_photo/{$row['image_1']}" alt="{$row['kind']}">
  <p>{$row['title']}</p>
  <p>年齢：{$row['age']}&nbsp;{$row['gender']}</p>
  <p>{$row['animal_area']}</p>
  <p>掲載ID：{$row['animal_id']}</p>
  </div>
  </a>
  EOL;
      }
    }
    ?>
  </main>
</div>
<img src="./images/animal_photo/" alt="">
<?php include('parts/footer.php'); ?>