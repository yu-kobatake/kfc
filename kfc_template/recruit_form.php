<?php
session_start();
require_once("./lib/util.php");

// tokenの作成
$bytes = openssl_random_pseudo_bytes(16);
$token = bin2hex($bytes);
$_SESSION['token'] = $token;

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
?>

<?php
// titleで読み込むページ名
$pagetitle = "申し込みフォーム"
?>
<?php include('parts/header.php'); ?>

<?php

if (!empty($_POST['animal_id'])) {
  $animal_id = $_POST['animal_id'];
} elseif (!empty($_SESSION['animal_id'])) {
  $animal_id = $_SESSION['animal_id'];
} else {
  echo "<p>無効な掲載IDです。</p>";
  echo "<a href='recruit.php'><button>里親募集ページに戻る</button></a><br>";
  exit();
}
?>

<div id="container">
  <main>
    <h2>里親申込</h2>
    <div>
      <h3>ご注意</h3>
      <ul>
        <li>サイトご利用のメッセージの内容については利用者同士の責任でお願いいたします。サイト運営側は一切関与いたしません。</li>
        <li>掲載者の都合により、返信にお時間がかかることも考えられます。ご理解の上、ご利用ください。</li>
        <li>双方、譲渡に関わる費用についてご確認とお話し合いをお願いいたします。譲渡条件、譲渡に関わる費用、お引き渡しについてなど、譲渡を成立させるために必要なすべての条件を応募者、掲載者共に同意に達してから譲渡に進んでください。
        </li>
      </ul>
    </div>
    <div>
      <h3>同意事項</h3>
      <p>応募前に必ずご確認ください。</p>
      <ul>
        <li>
          営利目的の利用ではありません。
        </li>
        <li>
          営利目的の利用に加担しません。
          営利目的の掲載だと判断した場合、申し込みを取りやめます。
        </li>
        <li>
          動物の体調などを考慮の上、ワクチンや駆虫など、必要な医療を施します。
        </li>
        <li>
          ケアや保護のためにかかった、医療費・食費・消耗品代・その他、適正な飼育に必要な費用がある場合、一部または全額を同意の上でお支払いします。
        </li>
        <li>
          過剰繁殖抑制のための、不妊去勢手術に努めます。
        </li>
        <li>
          掲載者に対し、サイト上で公開しているユーザーID、ニックネームの他、性別、年代、所在の県を開示します。</li>
        <li>
          慎重に判断した上で里親を申し込みます。
        </li>
        <li>
          免許証など身分証明できるものを提示します。
        </li>
        <li>
          対面してのヒアリング、誓約書記入などの後、動物の里親になります。
          (誓約書のご用意がない場合、当サイトで配布しております。)
        </li>
        <li>
          譲渡に関わる費用について確認と話し合いをします。
          譲渡条件、譲渡に関わる費用、お引き渡しについてなど、譲渡を成立させるために必要なすべての条件を掲載者と共に合意に達してから譲渡に進みます。
        </li>
        <li>
          引渡しは手渡しで行います。
          ※当サイトでは、事前に対面で里親希望の方とお会いして、飼育の考え方や環境について双方確認することを推奨しています。
        </li>
        <li>
          譲渡に関わる費用について確認と話し合いをします。
        </li>
        <li>
          譲渡後の飼育について、犬の場合、ノーリード禁止の場所ではリードをつけること。
          猫の場合、完全室内飼育（通院など必要な場合を除いて外に出さない。リードの有無にかかわらずお散歩をさせない）を厳守します。
        </li>
      </ul>
    </div>

    <?php
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


        echo "<h3>里親申し込みフォーム</h3>";
        echo "<p>下記の里親募集に応募します。</p>";

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
      }
    } catch (Exception $e) {
      echo '<span class ="error">エラーがありました</span><br>';
      echo $e->getMessage();
      exit();
    }


    // userテーブルへの接続
    $user_id = 4;
    try {
      $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      if (!empty($user_id)) {
        $sql = "SELECT * FROM user WHERE user_id = :user_id ";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':user_id', $user_id, PDO::PARAM_STR);

        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

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
      }
    } catch (Exception $e) {
      echo '<span class ="error">エラーがありました</span><br>';
      echo $e->getMessage();
      exit();
    }
    ?>

    <form action="./recruit_confirm.php" method="POST">
      <!-- 確認事項フォーム(初期値) -->
      <?php
      if (!empty($_SESSION['question_1'])) {
        $question_1 = $_SESSION['question_1'];
      } else {
        $question_1 = "";
      }

      if (!empty($_SESSION['question_2'])) {
        $question_2 = $_SESSION['question_2'];
      } else {
        $question_2 = "";
      }

      if (!empty($_SESSION['question_3'])) {
        $question_3 = $_SESSION['question_3'];
      } else {
        $question_3 = "";
      }

      if (!empty($_SESSION['question_4'])) {
        $question_4 = $_SESSION['question_4'];
      } else {
        $question_4 = "";
      }

      if (!empty($_SESSION['question_5'])) {
        $question_5 = $_SESSION['question_5'];
      } else {
        $question_5 = "";
      }

      if (!empty($_SESSION['question_6'])) {
        $question_6 = $_SESSION['question_6'];
      } else {
        $question_6 = "";
      }

      if (!empty($_SESSION['question_7'])) {
        $question_7 = $_SESSION['question_7'];
      } else {
        $question_7 = "";
      }
      ?>

      <table>
        <tr>
          <th>
            1.動物の飼育経験について（種類、飼っていた時期など）
          </th>
        </tr>
        <tr>
          <td>
            <textarea name="question_1"><?php echo es($question_1); ?></textarea>
          </td>
        </tr>
        <tr>
          <th>
            2.動物と一緒に暮らすご家族、恋人、同居人の構成と年齢層（例：父（応募者・30歳）　母（31歳）娘（10歳））
          </th>
        </tr>
        <tr>
          <td>
            <textarea name="question_2"><?php echo es($question_2); ?></textarea>
          </td>
        </tr>
        <tr>
          <th>
            3.現在、動物のために用意しているもの（ケージ、トイレなど）
          </th>
        </tr>
        <tr>
          <td>
            <textarea name="question_3"><?php echo es($question_3); ?></textarea>
          </td>
        </tr>
        <tr>
          <th>
            4.もしも自分や同居人が新たにアレルギーに発症したり、結婚や出産などで増えた家族がアレルギーだった場合はどうしますか
          </th>
        </tr>
        <tr>
          <td>
            <textarea name="question_4"><?php echo es($question_4); ?></textarea>
          </td>
        </tr>
        <tr>
          <th>
            5. 長期にわたって家を留守にする場合、動物をどうしますか
          </th>
        </tr>
        <tr>
          <td>
            <textarea name="question_5"><?php echo es($question_5); ?></textarea>
          </td>
        </tr>
        <tr>
          <th>
            6. 最寄りの動物病院について把握していますか
          </th>
        </tr>
        <tr>
          <td>
            <textarea name="question_6"><?php echo es($question_6); ?></textarea>
          </td>
        </tr>
        <th>
          7.里親を希望する具体的な理由と、どのように動物と生活をする予定なのかをなるべく詳しく書いてください。
        </th>
        </tr>
        <tr>
          <td>
            <textarea name="question_7"><?php echo es($question_7); ?></textarea>
          </td>
        </tr>
      </table>
      <!-- 同意事項(初期値) -->
      <?php
      if (!empty($_SESSION['agree_1'])) {
        $agree_1 = "checked";
      } else {
        $agree_1 = "";
      }
      if (!empty($_SESSION['agree_2'])) {
        $agree_2 = "checked";
      } else {
        $agree_2 = "";
      }
      if (!empty($_SESSION['agree_3'])) {
        $agree_3 = "checked";
      } else {
        $agree_3 = "";
      }

      ?>
      <!-- 入力エラー表示 -->
      <?php
      if (!empty($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        if (count($errors) > 0) {
          foreach ($errors as $e) {
            echo "<span style='color:red'>$e</span><br>";
          }
        }
      }
      ?>

      <table>
        <tr>
          <th>
            同意事項
          </th>
        </tr>
        <tr>
          <td>
            <label>
              <input type="checkbox" name='agree_1' value="agree" <?php echo es($agree_1); ?>>
              1.動物に必要な獣医療を受けさせます。
            </label>
          </td>
        </tr>
        <tr>
          <td>
            <label>
              <input type="checkbox" name='agree_2' value="agree" <?php echo es($agree_2); ?>>
              2.犬の場合、ノーリード禁止の場所ではリードをつけること。猫の場合、完全室内飼育（通院など必要な場合を除いて外に出さない。リードの有無にかかわらずお散歩をさせない）を厳守します
            </label>
          </td>
        </tr>
        <tr>
          <td>
            <label>
              <input type="checkbox" name='agree_3' value="agree" <?php echo es($agree_3); ?>>
              3.同意事項を全て確認し、同意します。
            </label>
          </td>
        </tr>
      </table>
      <!-- 入力エラー表示 -->
      <?php
      if (!empty($_SESSION['errors_agree'])) {
        $errors_agree = $_SESSION['errors_agree'];
        if (count($errors_agree) > 0) {
          foreach ($errors_agree as $e) {
            echo "<span style='color:red'>$e</span><br>";
          }
        }
      }
      ?>
<??>
      <!-- token -->
      <input type='hidden' name='token' value='<?php echo $token; ?>'>
      <!-- animal_id -->
      <input type='hidden' name='animal_id' value='<?php echo $animal_id; ?>'>
      <button name="submit" name="submit">入力内容を確認する</button>
    </form>
    
  </main>
</div>

<?php include('parts/footer.php'); ?>