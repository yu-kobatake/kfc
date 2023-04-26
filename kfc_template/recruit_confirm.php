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
if (!cken($_POST)) {
  exit("不正な文字コードです。");
}
$_POST = es($_POST);
// var_dump($_POST);

//token確認 
if (isset($_SESSION['token']) && isset($_POST['token'])) {
  if ($_SESSION['token'] !== $_POST['token']) {
    echo "<p>不正なアクセスです。①</p>";
    echo "<a href='recruit.php'><button>里親募集ページに戻る</button></a><br>";
    exit();
  } else {
    $token = $_POST['token'];
  }
} else {
  echo "<p>不正なアクセスです。②</p>";
  echo "<a href='recruit.php'><button>里親募集ページに戻る</button></a><br>";
  exit();
}

?>

<?php
// titleで読み込むページ名
$pagetitle = "里親申し込み確認"
?>
<?php include('parts/header.php'); ?>
<div id="container">
  <main>
    <?php
    $user_id = 8;

    if (!empty($_POST['animal_id'])) {
      $animal_id = $_POST['animal_id'];
      $_SESSION['animal_id'] = $animal_id;
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
    } catch (Exception $e) {
      echo '<span class ="error">エラーがありました</span><br>';
      echo $e->getMessage();
      exit();
    }


    // userテーブルへの接続
    try {
      $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "データベース{$dbName}に接続しました", "<br>"; //確認用
      if (!empty($user_id)) {
        $sql = "SELECT * FROM user WHERE user_id = :user_id ";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':user_id', $user_id, PDO::PARAM_STR);

        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($result);
      }
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
    } catch (Exception $e) {
      echo '<span class ="error">エラーがありました</span><br>';
      echo $e->getMessage();
      exit();
    }
    ?>

    <?php
    // チェックボックス確認
    $errors_agree = [];
    $_SESSION['errors_agree']=[];
    if (isset($_POST['agree_1'])) {
      $_SESSION['agree_1'] = $_POST['agree_1'];
    } else {
      $_SESSION['agree_1'] = '';
      $errors_agree[] = "同意事項1にチェックを入れてください";
    }

    if (isset($_POST['agree_2'])) {
      $_SESSION['agree_2'] = $_POST['agree_2'];
    } else {
      $_SESSION['agree_2'] = '';
      $errors_agree[] = "同意事項2にチェックを入れてください";
    }

    if (isset($_POST['agree_3'])) {
      $_SESSION['agree_3'] = $_POST['agree_3'];
    } else {
      $_SESSION['agree_3'] = '';
      $errors_agree[] = "同意事項3にチェックを入れてください";
    }

    if (count($errors_agree) > 0) {
      $_SESSION['errors_agree'] = $errors_agree;
      header("Location:recruit_form.php");
    }


    // テキストエリア確認
    $errors = [];
    $_SESSION['errors']=[];
    if (isset($_POST['question_1'])) {
      $_SESSION['question_1'] = $_POST['question_1'];
      $question_1=preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u','',$_POST['question_1']);
      if(empty($question_1)){$errors[] = '項目1は必須項目です。';
      }
    }
    if (isset($_POST['question_2'])) {
      $_SESSION['question_2'] = $_POST['question_2'];
      $question_2=preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u','',$_POST['question_2']);
      if(empty($question_2)){$errors[] = '項目2は必須項目です。';
      }
    }
    if (isset($_POST['question_3'])) {
      $_SESSION['question_3'] = $_POST['question_3'];
      $question_3=preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u','',$_POST['question_3']);
      if(empty($question_3)){$errors[] = '項目3は必須項目です。';
      }
    }
    if (isset($_POST['question_4'])) {
      $_SESSION['question_4'] = $_POST['question_4'];
      $question_4=preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u','',$_POST['question_4']);
      if(empty($question_4)){$errors[] = '項目4は必須項目です。';
      }
    }
    if (isset($_POST['question_5'])) {
      $_SESSION['question_5'] = $_POST['question_5'];
      $question_5=preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u','',$_POST['question_5']);
      if(empty($question_5)){$errors[] = '項目5は必須項目です。';
      }
    }
    if (isset($_POST['question_6'])) {
      $_SESSION['question_6'] = $_POST['question_6'];
      $question_6=preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u','',$_POST['question_6']);
      if(empty($question_6)){$errors[] = '項目6は必須項目です。';
      }
    }
    if (isset($_POST['question_7'])) {
      $_SESSION['question_7'] = $_POST['question_7'];
      $question_7=preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u','',$_POST['question_7']);
      if(empty($question_7)){$errors[] = '項目7は必須項目です。';
      }
    }

    if (count($errors) > 0) {
      $_SESSION['errors'] = $errors;
      header("Location:recruit_form.php");
    }
    // var_dump($_SESSION);
    // var_dump($_POST);
    ?>

    <form action="recruit_complet.php" method="POST">
      <!-- token -->
      <input type='hidden' name='token' value='<?php echo $token; ?>'>
      <input type="submit" name="submit" value="この内容で申し込みする">
    </form> <a href="./recruit_form.php"><button>戻る</button></a>

  </main>
</div>

<?php include('parts/footer.php'); ?>