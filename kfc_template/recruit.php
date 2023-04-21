<?php
require_once("./lib/util.php");
$user = 'username';
$password = 'kfc';
$dbName = 'shotohlcd31_ kfc';
$host = 'localhost';
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
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
        <input type="radio" name="kind" value="全て" checked>全て
        <input type="radio" name="kind" value="犬">犬
        <input type="radio" name="kind" value="猫">猫
      </p>
      <!-- 「都道府県」リスト -->
      <?php
      $pref_list = ['', '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'];
      ?>
      <p>募集対象地域
        <select name="area">
          <?php
          foreach ($pref_list as $pref) {
            echo " <option value='$pref'>$pref</option>";
          }
          ?>
        </select>
      </p>
      <p>動物のいる地域
        <select name="animal_area">
          <?php
          foreach ($pref_list as $pref) {
            echo " <option value='$pref'>$pref</option>";
          }
          ?>
        </select>
      </p>
      <p>キーワード
        <input type="text" name="keyword">
      </p>
      <p><input type="submit" name="submit" value="検索"></p>
    </form>
    <?php
    // $errors = [];
    if (!cken($_POST)) {
      exit("不正な文字コードです。");
    }
    $_POST = es($_POST);
    if (isset($_POST['submit'])) {
      $kind = $_POST['kind'];
      echo "{$kind}<br>";
      //$areaと$animal_areaと$keywordはemptyじゃなければ代入
      if (!empty($_POST['area'])) {
        $area = $_POST['area'];
        echo "{$area}<br>";
      } else {
        $area = '';
      }
      if (!empty($_POST['animal_area'])) {
        $animal_area = $_POST['animal_area'];
        echo "{$animal_area}<br>";
      } else {
        $animal_area = '';
      }
      if (!empty($_POST['keyword'])) {
        $keyword = "%".$_POST['keyword']."%";
        echo "{$keyword}<br>";
      } else {
        $keyword = '';
      }

      // animalテーブルへの接続
      try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "データベース{$dbName}に接続しました", "<br>";
        //検索条件に合わせてsql文を作成

        // 3項目(kind,area,animal_area)
        if (!empty($area) && !empty($animal_area)) {
          $sql = "SELECT*FROM animal 
          WHERE kind = :kind AND 
          (area_1 = :area_1 OR area_2 = :area_2 OR area_3 = :area_3) AND
          animal_area = :animal_area";
           echo "kind,area,animal_area<br>";
        } else {
          if (!empty($area) || !empty($animal_area)) {
            //2項目(kind,area)
            if (!empty($area)) {
              $sql = "SELECT*FROM animal 
              WHERE kind = :kind AND 
              (area_1 = :area_1 OR area_2 = :area_2 OR area_3 = :area_3)";
                   echo "kind,area<br>";
            }
            //2項目(kind,animal_area)
            if (!empty($animal_area)) {
              $sql = "SELECT*FROM animal 
              WHERE kind = :kind AND 
              animal_area = :animal_area";
                   echo "kind,animal_area<br>";
            }
          }
          //1項目(kind) 
          else {
            $sql = "SELECT*FROM animal 
              WHERE kind = :kind";
            echo "kind<br>";
          }
        }
        // keywordあれば追記
        if (!empty($keyword)) {
          $sql .= " AND concat(animal_id, title, gender ,age ,other) LIKE :keyword";
        }

        // $sql = "SELECT*FROM animal 
        //  WHERE kind = :kind AND 
        //  (area_1 = :area_1 OR area_2 = :area_2 OR area_3 = :area_3) AND
        //  animal_area = :animal_area AND
        //  concat(animal_id, title, gender ,age ,other) LIKE :keyword";
        echo $sql;

        $stm = $pdo->prepare($sql);
        //プレースホルダーを作る
        $stm->bindValue(':kind', $kind, PDO::PARAM_STR);
        // $stm->bindValue(':area_1', $area, PDO::PARAM_STR);
        // $stm->bindValue(':area_2', $area, PDO::PARAM_STR);
        // $stm->bindValue(':area_3', $area, PDO::PARAM_STR);
        // $stm->bindValue(':animal_area', $animal_area, PDO::PARAM_STR);
        $stm->bindValue(':keyword', $keyword, PDO::PARAM_STR);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        var_dump($result);
      } catch (Exception $e) {
        echo '<span class ="error">エラーがありました</span><br>';
        echo $e->getMessage();
        exit();
      }
    }

    ?>

  </main>
</div>

<?php include('parts/footer.php'); ?>