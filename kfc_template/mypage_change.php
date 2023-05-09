<?php
// titleで読み込むページ名
$pagetitle = "登録情報の変更";
?>
<?php include('parts/header.php'); ?>
<?php
require_once("./lib/util.php");

// セッション開始
if(!isset($_SESSION)){
  session_start();
}

// トークン発行・登録
$bytes = openssl_random_pseudo_bytes(16);
$token = bin2hex($bytes);
$_SESSION["token"] = $token;

// エラー文の取得
$error = !empty($_SESSION['error']) ? $_SESSION['error'] : "";

// user_idの値があれば受け渡す
if(isset($_POST['user_id'])){
  $_POST = es($_POST);
  $user_id = $_POST['user_id'];
} elseif(isset($_SESSION['user_id'])){
  $user_id = $_SESSION['user_id'];
}

/*-------- SESSION不具合・エラー文の対策 --------*/
// 推移前のページURLを取得
$uri = rtrim($_SERVER["HTTP_REFERER"], '/');
$uri = substr($uri, strrpos($uri, '/') + 1);
// マイページから来た場合
if($uri === 'breeder_mypage.php' || $uri === 'parent_mypage.php'){
  // idとkind以外のセッションを、一旦、空にする
  unset($_SESSION['user_name']);
  unset($_SESSION['name']);
  unset($_SESSION['furigana']);
  unset($_SESSION['email']);
  unset($_SESSION['password']);
  unset($_SESSION['zip']);
  unset($_SESSION['address']);
  unset($_SESSION['birth']);
  unset($_SESSION['gender']);
  unset($_SESSION['job']);
}
// ※確認⇒戻るボタンから来たときはセッションを消さない

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

  // userテーブルの内容を全て抽出
  $sql = "SELECT * FROM user WHERE user_id = '$user_id'";

  // プリペアドステートメントを作る
  $stm = $pdo->prepare($sql);

  // SQLクエリを実行する
  $stm->execute();

  // 結果の取得（連想配列で受け取る）
  $userdata = $stm->fetchAll(PDO::FETCH_ASSOC);
  
} catch (PDOException $e) {
  $err =  '<span class="error">エラーがありました。</span><br>';
  $err .= $e->getMessage();
  exit($err);
}

// 値を表示するために取得
foreach ($userdata as $val) {
  // kindは固定
  $_SESSION['kind'] = $val["kind"];
  // DB情報を変数へ
  $user_name = $val["user_name"];
  $name = $val["name"];
  $furigana = $val["furigana"];
  $email = $val["email"];
  $password = $val["password"];
  $zip = $val["zip"];
  $address = $val["address"];
  $birth = $val["birth"];
  $gender = $val["gender"];
  $job = $val["job"];
}


// 戻ってきたとき、最初からvalueに値を入れる用（値を代入するか、空を代入するか）
//$kind = !empty($_SESSION['kind']) ? $_SESSION['kind'] : "";
$user_name = !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : $user_name;
$name = !empty($_SESSION['name']) ? $_SESSION['name'] : $name;
$furigana = !empty($_SESSION['furigana']) ? $_SESSION['furigana'] : $furigana;
$email = !empty($_SESSION['email']) ? $_SESSION['email'] : $email;
$password = !empty($_SESSION['password']) ? $_SESSION['password'] : $password;
$zip = !empty($_SESSION['zip']) ? $_SESSION['zip'] : $zip;
$address = !empty($_SESSION['address']) ? $_SESSION['address'] : $address;
$birth = !empty($_SESSION['birth']) ? $_SESSION['birth'] : $birth;
$gender = !empty($_SESSION['gender']) ? $_SESSION['gender'] : $gender;
$job = !empty($_SESSION['job']) ? $_SESSION['job'] : $job;

// 初期値をチェックする
// ラジオボタン（種類・性別）
function checked($value, $select){
  if (is_array($select)) {
    $isChecked = in_array($value, $select);
  } else {
    $isChecked = ($value === $select);
  }
  if ($isChecked) {
    echo "checked";
  }
}
// ラジオボタン（職業）
function selected($value, $select){
  if (is_array($select)) {
    $isChecked = in_array($value, $select);
  } else {
    $isChecked = ($value === $select);
  }
  if ($isChecked) {
    echo "selected";
  }
}

?>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<div id="container" class="c1">
  <main>
    <h2><?php echo $pagetitle ?></h2>
    <!-- エラー文があれば表示 -->
    <div class="error" style="color:red;">
      <?php
      if (!empty($_SESSION['error'])) {
        foreach ($error as $value) {
          echo $value . "<br>";
        }
      }
      ?>
    </div>
    <p>変更したい項目を修正し、確認ページへ進んでください。</p>
    <form action="change_confirm.php" method="POST">
      <table class="ta1">
        <tr>
          <th>里親希望 or ブリーダー※</th>
          <td>
            <?php echo $_SESSION['kind']; ?>
          </td>
        </tr>
        <tr>
          <th>ユーザー名※</th>
          <td><input type="text" name="user_name" class="ws" placeholder="例）太郎" value="<?php echo es($user_name); ?>"></td>
        </tr>
        <tr>
          <th>氏名※</th>
          <td><input type="text" name="name" class="ws" placeholder="例）田中太郎" value="<?php echo es($name); ?>"></td>
        </tr>
        <tr>
          <th>ふりがな※</th>
          <td><input type="text" name="furigana" class="ws" placeholder="例）たなかたろう" value="<?php echo es($furigana); ?>"></td>
        </tr>
        <tr>
          <th>メールアドレス※</th>
          <td><input type="email" name="email" class="wl" placeholder="例）test@test.com" value="<?php echo es($email); ?>"></td>
        </tr>
        <tr>
          <th>パスワード※</th>
          <td><input type="password" name="password" class="ws" placeholder="※半角英数字で入力" value="<?php echo es($password); ?>"></td>
        </tr>
        <tr>
          <th>ご住所※</th>
          <td>
            〒<input type="text" id="zip" name="zip" onKeyUp="AjaxZip3.zip2addr(this,'','address','address');" placeholder="郵便番号7桁" value="<?php echo es($zip); ?>">
            <span class="text_r">郵便番号が入力されると住所が自動表示されます。</span>
            <input type="text" name="address" class="wl" placeholder="例）石川県金沢市●●1-1-1 イヌネコマンション101" value="<?php echo es($address); ?>">
          </td>
        </tr>
        <tr>
          <th>生年月日※</th>
          <td><input type="date" name="birth" value="<?php echo es($birth); ?>"></td>
        </tr>
        <tr>
          <th>性別※</th>
          <td>
            <label>
              <input type="radio" name="gender" value="男" <?php checked("男", $gender); ?>>男
            </label>
            <label>
              <input type="radio" name="gender" value="女" <?php checked("女", $gender); ?>>女
            </label>
            <label>
              <input type="radio" name="gender" value="回答しない" <?php checked("回答しない", $gender); ?>>回答しない
            </label>
          </td>
        </tr>
        <tr>
          <th>職業</th>
          <td>
            <select name="job">
              <option value="会社員" <?php selected("会社員", $job); ?>>会社員</option>
              <option value="パート・アルバイト" <?php selected("パート・アルバイト", $job); ?>>パート・アルバイト</option>
              <option value="経営者・役員" <?php selected("経営者・役員", $job); ?>>経営者・役員</option>
              <option value="自営業" <?php selected("自営業", $job); ?>>自営業</option>
              <option value="その他" <?php selected("その他", $job); ?>>その他</option>
            </select>
          </td>
        </tr>
      </table>
      <p class="c">
        <input type="submit" value="修正内容を確認する" id="submit-btn">
        <input type="hidden" name="token" value="<?php echo es($token); ?>">
      </p>
      <p class="c">
        <button type="button" onclick="location.href='login.php'">戻る</button>
      </p>
    </form>
  </main>
</div>
<?php include('parts/footer.php'); ?>