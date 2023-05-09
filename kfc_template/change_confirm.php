<?php
// titleで読み込むページ名
$pagetitle = "会員情報変更の確認";
?>
<?php include('parts/header.php'); ?>
<?php
require_once("./lib/util.php");

// セッション開始
if(!isset($_SESSION)){
  session_start();
}

// トークン発行・登録
$bytes2 = openssl_random_pseudo_bytes(16);
$token2 = bin2hex($bytes2);
$_SESSION["token2"] = $token2;

// 文字エンコードの検証
if (!cken($_POST)) {
  $encoding = mb_internal_encoding();
  $err = "Encoding Error! The expected encoding is " . $encoding;
  // エラーメッセージを出して、以下のコードをすべてキャンセルする
  exit($err);
}

// セッションに登録されているトークンとPOSTされてきたトークンをチェック
if ($_SESSION['token'] !== $_POST['token']) :
  // 正しくない場合は戻るボタンを表示
  echo <<< EOL
    <p>不正なアクセスです。</p>
    EOL;
else :

  /*---------- DB読み込み ----------*/
  // データベース接続
  $user = 'shotohlcd31_kfc';
  $password = 'KFCpassword';
  $dbName = 'shotohlcd31_kfc';
  $host = 'localhost';
  //$host = 'sv14471.xserver.jp';
  $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";

  //MySQLデータベースに接続する
  try {
    $pdo = new PDO($dsn, $user, $password);
    // プリペアドステートメントのエミュレーションを無効にする
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // 例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 該当のユーザーIDのデータを削除
    $sql = "SELECT email FROM user";

    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);

    // SQLクエリを実行する
    $stm->execute();

    // 結果の取得（連想配列で受け取る）
    $userdata = $stm->fetchAll(PDO::FETCH_ASSOC);


    // トークンが正しければバリデーションチェック
    $_POST = es($_POST); // POST情報をエスケープ

    // 値があれば、trimで前後空白を取り除く。なければ、nullを入れる。
    $_SESSION['user_name'] = isset($_POST['user_name']) ? trim($_POST['user_name'], '\x20\t\r\0\v') : null;
    $_SESSION['name'] = isset($_POST['name']) ? trim($_POST['name'], '\x20\t\r\0\v') : null;
    $_SESSION['furigana'] = isset($_POST['furigana']) ? trim($_POST['furigana'], '\x20\t\r\0\v') : null;
    $_SESSION['email'] = isset($_POST['email']) ? preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['email']) : null;
    $_SESSION['password'] = isset($_POST['password']) ? preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['password']) : null;
    $_SESSION['zip'] = isset($_POST['zip']) ? preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['zip']) : null;
    $_SESSION['address'] = isset($_POST['address']) ? trim($_POST['address'], '\x20\t\r\0\v') : null;
    $_SESSION['birth']  = isset($_POST['birth']) ? preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '' , $_POST['birth']) : null;
    $_SESSION['gender'] = isset($_POST['gender']) ? trim($_POST['gender'], '\x20\t\r\0\v') : null;
    $_SESSION['job'] = isset($_POST['job']) ? trim($_POST['job'], '\x20\t\r\0\v') : null;
    
    // セッションを変数へ
    $kind = $_SESSION['kind'];
    $user_name = $_SESSION['user_name'];
    $name = $_SESSION['name'];
    $furigana = $_SESSION['furigana'];
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    $zip = $_SESSION['zip'];
    $address = $_SESSION['address'];
    $birth = $_SESSION['birth'];
    $gender = $_SESSION['gender'];
    $job = $_SESSION['job'];

    // 必須が空になっていないか or バリデーションチェック
    $error = [];
    $_SESSION['error'] = [];

    if (empty($user_name)) {
      $error[] = "【ユーザー名】は必須です";
    } else {
      $_SESSION['user_name'] = $user_name;
    }
    if (empty($name)) {
      $error[] = "【氏名】は必須です";
    } else {
      $_SESSION['name'] = $name;
    }
    if (empty($furigana)) {
      $error[] = "【ふりがな】は必須です";
    } else {
      $_SESSION['furigana'] = $furigana;
    }
    if (empty($email)) {
      $error[] = "【メールアドレス】は必須です";
    } else {
      // メールアドレスの形式チェック
      if (!preg_match("/^[a-z0-9._+^~-]+@[a-z0-9.-]+$/i", $email)) {
        $error[] = "【メールアドレス】の形式を正しく入力してください。";
      } else {
        $_SESSION['email'] = $email;
      }
    }
    if (empty($password)) {
      $error[] = "【パスワード】は必須です";
    } else {
      $_SESSION['password'] = $password;
    }
    if (empty($zip)) {
      $error[] = "【郵便番号】は必須です";
    } else {
      $_SESSION['zip'] = $zip;
    }
    if (empty($address)) {
      $error[] = "【住所】は必須です";
    } else {
      $_SESSION['address'] = $address;
    }
    if (empty($gender)) {
      $error[] = "【性別】は必須です";
    } else {
      $_SESSION['gender'] = $gender;
    }

    // エラー文の表示
    if (count($error) > 0) {
      $_SESSION['error'] = $error;
      header("Location:mypage_change.php");
      exit();
    }

    
  } catch (PDOException $e) {
    $err =  '<span class="error">エラーがありました。</span><br>';
    $err .= $e->getMessage();
    exit($err);
  }

endif;
?>
<div id="container" class="c1">
  <main>
    <h2><?php echo $pagetitle ?></h2>
    <p>以下の情報で登録します。よろしければページ下の「登録」ボタンを押してください。</p>
    <form action="change_complet.php" method="POST">
      <table class="ta1">
        <tr>
          <th>里親希望 or ブリーダー※</th>
          <td>
            <?php echo $_SESSION['kind']; ?>
          </td>
        </tr>
        <tr>
          <th>ユーザー名※</th>
          <td>
            <?php echo $user_name; ?>
          </td>
        </tr>
        <tr>
          <th>氏名※</th>
          <td>
            <?php echo $name; ?>
          </td>
        </tr>
        <tr>
          <th>ふりがな※</th>
          <td>
            <?php echo $furigana; ?>
          </td>
        </tr>
        <tr>
          <th>メールアドレス※</th>
          <td>
            <?php echo $email; ?>
          </td>
        </tr>
        <tr>
          <th>パスワード※</th>
          <td>
            <?php echo $password; ?>
          </td>
        </tr>
        <tr>
          <th>ご住所※</th>
          <td>
            〒<?php echo $zip; ?><br>
            <?php echo $address; ?>
          </td>
        </tr>
        <tr>
          <th>生年月日※</th>
          <td>
            <?php echo $birth; ?>
          </td>
        </tr>
        <tr>
          <th>性別※</th>
          <td>
            <?php echo $gender; ?>
          </td>
        </tr>
        <tr>
          <th>職業</th>
          <td>
            <?php echo $job; ?>
          </td>
        </tr>
        <tr>
          <th>利用規約※</th>
          <td>
            利用規約に同意します。
          </td>
        </tr>
      </table>
      <p class="c"><input type="submit" value="この内容で登録する"></p>
      <p class="c"><input type="button" value="戻る" onclick="location.href='mypage_change.php'"></p>
      <input type="hidden" name="token2" value="<?php echo es($token2); ?>">
    </form>

  </main>
</div>

<?php include('parts/footer.php'); ?>