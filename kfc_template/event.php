<?php
// titleで読み込むページ名
$pagetitle = "イベント"
?>
<?php include('parts/header.php'); ?>
<?php
// セッション開始
if(!isset($_SESSION)){
  session_start();
}
// $_SESSION=[];
require_once("./lib/util.php");
// データベース接続
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
//$host = 'sv14471.xserver.jp';
$dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";
?>
<?php
if (!cken($_POST)) {
  exit("不正な文字コードです。");
}
$_POST = es($_POST);
// 種類（全て・犬・猫）
if (!empty($_POST['kind'])) {
  $kind = $_POST['kind'];
} else {
  $kind = '';
}
// 開催地域（都道府県）
if (!empty($_POST['area'])) {
  $area = $_POST['area'];
} else {
  $area = '';
}
// 開催日（ここから～）
if (!empty($_POST['day_start'])) {
  $day_start = $_POST['day_start'];
} else {
  $day_start = '';
}
// 開催日（～ここまで）
if (!empty($_POST['day_end'])) {
  $day_end = $_POST['day_end'];
} else {
  $day_end = '';
}
// キーワード
if (!empty($_POST['keyword'])) {
  $keyword = $_POST['keyword'];
} else {
  $keyword = '';
}
?>
<div id="container" class="c1">
    <main>
        <h2><?php echo $pagetitle ?></h2>
        <!-- <h3>イベント検索</h3> -->
        <!--検索フォーム-->
        <form class="searchf" method="post" action="<?php echo es($_SERVER['SCRIPT_NAME']) ?>">
          <p class='kind_chk'><span class="label">種別</span>
                  <?php
          if ($kind === '全て') {
            echo "<label><input type='radio' name='kind' value='全て' checked>全て</label>";
            echo "<label><input type='radio' name='kind' value='犬'>犬</label>";
            echo "<input type='radio' name='kind' value='猫'>猫</label>";
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
              <span class="label">開催地域</span>
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
                  <span class="label">開催日</span>
                  <input type="date" name="day_start" value="<?php echo $day_start; ?>">
                  ～
                  <input type="date" name="day_end" value="<?php echo $day_end; ?>">
              </p>
              <p><span class="label">キーワード</span>
                  <input class="ws" type="text" name="keyword" value="<?php echo $keyword; ?>">
              </p>
              <p class="c"><input type="submit" name="submit" value="条件を絞って検索する"></p>
        </form>
        <h3>イベント一覧</h3>
        <?php
    // var_dump($_POST);//確認用
    if (isset($_POST['submit'])) {
      //全てが選択された時の処理
      $kind = $_POST['kind'];

      //$areaと$dayと$keywordはemptyじゃなければ代入
      if (!empty($_POST['area'])) {
        $area = $_POST['area'];
      } else {
        $area = '';
      }
      // 日付（期間のため２つのデータあり）
      if (!empty($_POST['day_start'])) {
        $day_start = $_POST['day_start'];
      } else {
        $day_start = '';
      }
      if (!empty($_POST['day_end'])) {
        $day_end = $_POST['day_end'];
      } else {
        $day_end = '';
      }
      // キーワード
      if (!empty($_POST['keyword'])) {
        $keyword = "%" . $_POST['keyword'] . "%";
      } else {
        $keyword = '';
      }

      // テーブルへの接続
      try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 検索条件に合わせてSQL文作成
        if (!empty($area) && !empty($day_start)) {
          if(!empty($day_end)){
            // ▼ 地域 / 開始日 / 終了日 あり（全て）
            $sql = "SELECT * FROM event WHERE area = :area AND day BETWEEN :daystart AND :dayend";
          } else {
            // ▼ 地域 / 開始日 あり
            $sql = "SELECT * FROM event WHERE area = :area AND day >= :daystart";
          }
        } elseif(!empty($area) || !empty($day_start)){
          // ▼ 地域あり
          if(!empty($area)){
            $sql = "SELECT * FROM event WHERE area = :area";
          }
          // ▼ 開始日 / 修了日 あり
          if(!empty($day_start) && !empty($day_end)){
            $sql = "SELECT * FROM event WHERE day BETWEEN :daystart AND :dayend";
          } elseif(!empty($day_start)) {
            $sql = "SELECT * FROM event WHERE day >= :daystart";
          }
        } else {
          // ▼ 検索項目なし
          if ($kind === '全て') {
            $sql = "SELECT * FROM event";
          }
        } // 検索条件のif閉じ

        // kindあれば追記
        if (($kind === '犬' || $kind === '猫') &&
          empty($area) &&
          empty($day_start)
        ) {
          $sql = "SELECT * FROM event 
          WHERE kind = :kind ";
        } elseif ($kind === '犬' || $kind === '猫') {
          $sql .= " AND kind = :kind ";
        }

        // keywordあれば追記
        if (!empty($keyword)) {
          if (
            $kind === '全て' &&
            empty($area) &&
            empty($day_start)
          ) {
            $sql .= " WHERE concat(event_id, title, information) LIKE :keyword ";
          } else {
            $sql .= " AND concat(event_id, title, information) LIKE :keyword ";
          }
        }

        $stm = $pdo->prepare($sql);

        //プレースホルダーを作成
        if ($kind === '犬' || $kind === '猫') {
          $stm->bindValue(':kind', $kind, PDO::PARAM_STR);
        }

        if (!empty($area)) {
          $stm->bindValue(':area', $area, PDO::PARAM_STR);
        }

        if (!empty($day_start) && !empty($day_end)) {
          $stm->bindValue(':daystart', $day_start, PDO::PARAM_STR);
          $stm->bindValue(':dayend', $day_end, PDO::PARAM_STR);
        }
        elseif(!empty($day_start)) {
          $stm->bindValue(':daystart', $day_start, PDO::PARAM_STR);
        }

        if (!empty($keyword)) {
          $stm->bindValue(':keyword', $keyword, PDO::PARAM_STR);
        }

        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        //該当件数
        $hit = count($result);
        echo "<div class='serch_hit'>該当 {$hit}件です。</div>";
        // var_dump($result); //確認用

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
        $sql = "SELECT * FROM event LIMIT 0,20";

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



        <!-- --------------------------------
      ここからイベント一覧
    ---------------------------------- -->
        <div class="list-container">
            <?php
      if (isset($result)) {
        foreach ($result as $row) {
          echo <<<"EOL"
          <div class="list">
            <figure><img src="./images/event_photo/{$row['image_1']}"></figure>
            <div class="text">
              <h4>{$row['title']}</h4>
              <p class="name">種別：{$row['kind']}</p>
              <p>日程：{$row['day']}</p>
              <p>時間：{$row['time']}</p>
              <p>場所：{$row['area']}{$row['area_address']}</p>
              <p style="margin-top:1em;">{$row['information']}</p>
            </div>
          </div>
          EOL;
        }
      }
      ?>
        </div>
        <!--/.list-container-->
    </main>
</div>
<?php include('parts/footer.php'); ?>