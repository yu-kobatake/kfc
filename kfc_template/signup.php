<?php
require_once("./lib/util.php");

// セッション開始
session_start();

// トークン発行・登録
$bytes = openssl_random_pseudo_bytes(16);
$token = bin2hex($bytes);
$_SESSION["token"] = $token;

// エラー文の取得
$error = !empty($_SESSION['error']) ? $_SESSION['error'] : "";

// 戻ってきたとき、最初からvalueに値を入れる処理（値を代入するか、空を代入するか）
$kind = !empty($_SESSION['kind']) ? $_SESSION['kind'] : "";
$user_name = !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : "";
$name = !empty($_SESSION['name']) ? $_SESSION['name'] : "";
$furigana = !empty($_SESSION['furigana']) ? $_SESSION['furigana'] : "";
$email = !empty($_SESSION['email']) ? $_SESSION['email'] : "";
$password = !empty($_SESSION['password']) ? $_SESSION['password'] : "";
$zip = !empty($_SESSION['zip']) ? $_SESSION['zip'] : "";
$address = !empty($_SESSION['address']) ? $_SESSION['address'] : "";
$birth = !empty($_SESSION['birth']) ? $_SESSION['birth'] : "";
$gender = !empty($_SESSION['gender']) ? $_SESSION['gender'] : "";
$job = !empty($_SESSION['job']) ? $_SESSION['job'] : "";

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
// チェックボックス（同意）
function agreeChecked(){
  if(!empty($_SESSION['agreement'])){
    if ($_SESSION['agreement'] === 'on') {
      echo "checked";
    }
  }
}

?>
<?php
// titleで読み込むページ名
$pagetitle = "新規会員登録";
?>
<?php include('parts/header.php'); ?>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<div id="container" class="c1">
  <main>
    <h2><?php echo $pagetitle ?></h2>
    <p>ご利用には会員登録が必要です。（※のついている項目は入力必須）</p>
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
    <form action="signup_comfirm.php" method="POST">
      <table class="ta1">
        <tr>
          <th>里親希望 or ブリーダー※</th>
          <td>
            <label>
              <input type="radio" name="kind" id="" value="里親" <?php checked("里親", $kind); ?>>里親
            </label>
            <label>
              <input type="radio" name="kind" id="" value="ブリーダー" <?php checked("ブリーダー", $kind); ?>>ブリーダー
            </label>
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
        <tr>
          <th>利用規約※</th>
          <td>
            <textarea cols="50" rows="10" class="wl">
サイト利用規約
利用規約
●●●●（以下「本サイト」）は●●●●（以下「当団体」）が運営しています。本サイトご利用の際には以下の利用条件を同意されたものとみなします。
また利用規約は予告なしに変更することがあります。

禁止事項
本サイトのご利用に際し、次の行為を禁止します。

当団体等の権利・財産を侵害する行為、及び侵害するおそれのある行為。
本サイト運営を妨害する行為、及び妨害する恐れのある行為。
当団体の名誉や信頼を傷つける行為、及び傷つける恐れのある行為。
法令違反や公序良俗に反する行為、及び反する恐れのある行為。
当団体が不適切と判断する行為。
利用制限や削除について
本規約に反した行為等が発覚した場合は、削除やサービスの停止などを当団体の判断で対応いたします。
利用者間でのクレーム・損害、また第三者へのクレーム・損害に関しては、当事者間での解決を図り、当団体は関与しないものとします。

著作権及びその他
本サイトの全てのロゴ、画像、文章、音楽・映像等に関する著作権・商標権などの知的財産権その他一切の権利は、当団体に帰属もしくは当団体ががライセンスにによって使用するものです。これらの使用・複製・転載・変更・送信・頒布・譲渡・貸与・二次的使用は、事前の書面による当団体の了承を除き、禁止します。

免責条項について
本サイト掲載の情報に関して、その内容についてに当団体は保証するものではありません。 本サイトのご利用の際に生じたいかなる損害においても当団体は責任を負うものではありません。
ご利用者様には適宜サイトに関連する情報をお送りする場合があります。

情報提供について
本サイトへ送信されたご意見、ご提案、アイディア等は、一般的な情報として取り扱います。当団体がこれらの情報を自由に使用することがあることをご同意いただいた上で、ご提供下さい。

利用者に関する情報の取扱いについて
当団体は、本サイトにアクセスした皆様のプライバシーを保護するため、プライバシーポリシーに記載するように、合理的な範囲で必要な措置をとります。なお、このプライバシーポリシーに関する準拠法は、日本法といたします。また、本ウェブサイトの利用に関するすべての紛争については、●●地方裁判所を第一審の専属的合意管轄裁判所とします。

会員規約
会員とは、本サイトを閲覧および利用している個人または法人のことです。
会員は利用規約を遵守するものとします。
            </textarea>
            <br>
            <label>
              <input type="checkbox" name="agreement" id="agreement" <?php agreeChecked(); ?>>&nbsp;利用規約に同意します。
            </label>
          </td>
        </tr>
      </table>
      <p class="c">
        <input type="submit" value="登録内容を確認する" id="submit-btn">
        <input type="hidden" name="token" value="<?php echo es($token); ?>">
      </p>
    </form>
  </main>
</div>
<script>
  'use strict';
  /*----- 「利用規約に同意」ボタンの挙動  -----*/
  const submitBtn = document.getElementById('submit-btn'); // ボタン
  const agree = document.getElementById('agreement'); // 利用規約チェック
  // １．ページ読み込み時の状態チェック（戻ってきたとき）
  window.onload = function(){
    // チェックされている場合
    if (agree.checked === true) {
      submitBtn.disabled = false;
    }
    // チェックされていない場合
    else {
      submitBtn.disabled = true;
    }
  }
  // ２．実際クリックされたときのチェック
  agree.addEventListener('click', function() {
    // チェックされている場合
    if (agree.checked === true) {
      submitBtn.disabled = false;
    }
    // チェックされていない場合
    else {
      submitBtn.disabled = true;
    }
  });
</script>
<?php include('parts/footer.php'); ?>