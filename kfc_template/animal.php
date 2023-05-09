<?php
session_start();
require_once("./lib/util.php");
// titleで読み込むページ名
$pagetitle = "ペット情報登録画面";
// var_dump($_SESSION);
// var_dump($_POST);

// ユーザーIDがセッションに入っていれば$user_idに代入する
if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
  //セッションに入っていなければればログインページに戻す 
  } else { 
    header("Location:login.php");
    exit();
  }

// トークンの発行
$bytes = openssl_random_pseudo_bytes(16);
$token = bin2hex($bytes);
$_SESSION['token'] = $token;


?>
<?php include('parts/header.php'); ?>
<div id="container" class="c1">
    <main>

        <?php
        // var_dump($_SESSION);
        // 確認ページからセッションに入れた値をエスケープ処理
        $_SESSION = es($_SESSION);

        //確認画面から帰ってきた場合の都道府県設定の初期値設定 
        $area_1 = !empty($_SESSION['animal']['area_1']) ? $_SESSION['animal']['area_1'] : "";
        $area_2 = !empty($_SESSION['animal']['area_2']) ? $_SESSION['animal']['area_2'] : "";
        $area_3 = !empty($_SESSION['animal']['area_3']) ? $_SESSION['animal']['area_3'] : "";
        $animal_area = !empty($_SESSION['animal']['animal_area']) ? $_SESSION['animal']['animal_area'] : "";

        // 入力値の設定
        // 一度入力した値がSESSIONに存在すればその値、無ければ空文字を入れる 
        $title =  !empty($_SESSION['animal']['title']) ? $_SESSION['animal']['title'] : "";
        $kind =  !empty($_SESSION['animal']['kind']) ? $_SESSION['animal']['kind'] : "";
        $gender =  !empty($_SESSION['animal']['gender']) ? $_SESSION['animal']['gender']: "";
        $age = !empty($_SESSION['animal']['age']) ?$_SESSION['animal']['age'] : "";
        $area_1 = !empty($_SESSION['animal']['area_1'] ) ? $_SESSION['animal']['area_1']: "";
        $area_2 = !empty($_SESSION['animal']['area_2']) ? $_SESSION['animal']['area_2'] : "";
        $area_3 =  !empty($_SESSION['animal']['area_3'])? $_SESSION['animal']['area_3'] : "";
        $animal_area =  !empty($_SESSION['animal']['animal_area']) ? $_SESSION['animal']['animal_area'] : "";
        $animal_character = !empty($_SESSION['animal']['animal_character']) ? $_SESSION['animal']['animal_character'] : "";
        $other =  !empty($_SESSION['animal']['other'])? $_SESSION['animal']['other'] : "";

        // 都道府県のセレクトボックスオプションの作成
        $prefList = array(
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
        );
        //セレクトボックスのオプションタグを作成
        // 必須のセレクトボックス
        $pref_option1 = "<option hidden>選択してください</option>";
        // 任意のセレクトボックス
        $pref_option2 = "<option hidden>選択してください</option><option value='設定しない'>設定しない</option>";
        function option($pref_select, $pref_option)
        {
            global $prefList;
            if ($pref_select === "未設定") {
                $pref_select = "設定しない";
            }
            $pref_optionlist = $pref_option;
            foreach ($prefList as $pref) {
                if ($pref_select === $pref) {
                    $pref_optionlist .= "<option value='{$pref}' selected>{$pref}</option>";
                }
                $pref_optionlist .= "<option value='{$pref}'>{$pref}</option>";
            }
            return $pref_optionlist;
        }

        // 都道府県選択のオプション設定
        $area_1_option = option($area_1, $pref_option1);
        $area_2_option = option($area_2, $pref_option2);
        $area_3_option = option($area_3, $pref_option2);
        $animal_area_option = option($animal_area, $pref_option1);

        // 性別入力値checkedの設定
        $gender_check1 = $gender === "♂" ? "checked" : "";
        $gender_check2 = $gender === "♀" ? "checked" : "";


        // セッションエラーの削除
        $_SESSION['error'] = [];
        $errors = [];
        var_dump(isset($_SESSION['area_1']));
        ?>
        <div class="back_btn">
            <button><a href="breeder_mypage.php">戻る</a></button>
        </div>
        <h2>犬猫の登録</h2>
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
            <p>登録前に必ずご確認ください。</p>
            <ul>
                <li>
                    営利目的の利用ではありません。
                </li>
                <li>
                    営利目的の利用に加担しません。
                    営利目的の里親申し込みだと判断した場合、譲渡を取りやめます。
                </li>
                <li>
                    動物の体調などを考慮の上、ワクチンや駆虫など、必要な医療を施します。
                    ※不妊去勢手術およびワクチンについて、動物の体調を考慮の上行なってください。
                    行なっていない場合は、その理由も記述の上募集を行うことをお勧めしております。
                </li>
                <li>
                    医療費・食費・消耗品代・その他、適正な飼育に必要な費用の一部または全額を申し受ける場合は、なるべく領収書などの明細がわかるものを提示します。
                </li>
                <li>
                    過剰繁殖抑制のための、不妊去勢手術に努めます。
                </li>
                <li>
                    成犬成猫は原則として不妊去勢手術を行います。
                </li>
                <li>
                    免許証など身分証明できるものを提示します。
                </li>
                <li>
                    対面してのヒアリング、誓約書記入などの後、慎重に判断した上で里親を決定します。
                    (誓約書のご用意がない場合、当サイトで配布しております。)
                </li>
                <li>
                    譲渡に関わる費用について確認と話し合いをします。
                    譲渡条件、譲渡に関わる費用、お引き渡しについてなど、譲渡を成立させるために必要なすべての条件を応募者と共に合意に達してから譲渡に進みます。
                </li>
                <li>
                    引渡しは手渡しで行います。
                    ※当サイトでは、事前に対面で里親希望の方とお会いして、飼育の考え方や環境について双方確認することを推奨しています。
                </li>
            </ul>
        </div>
        <h3>犬猫情報の入力</h3>
        <p>同意事項に同意の上、下記フォーム入力し確認ページへお進みください。</p>
        <p>（※の項目は入力必須）</p>
        <?php
        // 入力内容に不備があった場合のエラー表示
        if (!empty($_SESSION['animal']['error'])) {
            $errors = $_SESSION['animal']['error'];
            foreach ($errors as $value) {
                echo "<span class='error'>$value</span><br>";
            }
        }
        ?>

        <form method="POST" action="animal_confirm.php" enctype="multipart/form-data">
            <table class="ta1">
                <tbody>

                    <tr>
                        <th>掲載タイトル※<br></th>
                        <td><textarea name="title" placeholder="例）マイペースで優しい柴犬の男の子"><?= es($title); ?></textarea></td>
                    </tr>
                    <tr>
                        <th>画像選択(3枚)※</th>
                        <td>
                            <div class="preview-area"></div><input type="file" name="image_1"
                                accept="image/png, image/jpeg" onchange="preview(this)">
                        </td>
                        <td>
                            <div class="preview-area"></div><input type="file" name="image_2"
                                accept="image/png, image/jpeg" onchange="preview(this)">
                        </td>
                        <td>
                            <div class="preview-area"></div><input type="file" name="image_3"
                                accept="image/png, image/jpeg" onchange="preview(this)">
                        </td>

                    <tr>
                        <th>犬種/猫種※</th>
                        <td><input type="text" name="kind" placeholder="柴犬" value="<?= es($kind); ?>"></td>
                    </tr>
                    <tr>
                        <th>性別</th>
                        <td>
                            <label>♂<input type="radio" name="gender" value="♂" <?= $gender_check1; ?>></label>
                            <label>♀<input type="radio" name="gender" value="♀" <?= $gender_check2; ?>></label>
                        </td>
                    </tr>
                    <tr>
                        <th>年齢</th>
                        <td colspan="2"><input type="text" name="age" placeholder="例）5才3ヶ月" value="<?= $age; ?>"></td>
                    </tr>
                    <tr>
                        <th rowspan="3">募集対象地域<br>3つまで選択可</th>
                        <td>募集対象地域1
                            <select name="area_1">
                                <?= $area_1_option; ?></select><span>&ensp;※</span>
                        </td>
                    </tr>
                    <tr>
                        <td>募集対象地域2
                            <select name="area_2">
                                <?= $area_2_option; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>募集対象地域3
                            <select name="area_3">
                                <?= $area_3_option; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>動物がいる地域※</th>
                        <td><select name="animal_area">
                                <?= $animal_area_option; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>特徴（色柄、性格など）</th>
                        <td>
                            <textarea name="animal_character" placeholder="【毛色】 茶・白
                        【体重】 8.6㎏
                        【状態】ワクチン接種済、狂犬病予防接種済、ノミ・ダニ駆除済、歯石除去済、一般血液検査済、マイクロチップ装着済
                        【人馴】 問題なし
                        【性格・その他】 穏やかな男の子です。"><?= $animal_character;?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>特記事項</th>
                        <td>
                            <textarea name="other"><?= $other; ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="確認ページへ" name="send" formaction="animal_confirm.php">
            <input type="submit" value="戻る" formaction="login.php">
            <input type="hidden" name="token" value="<?= es($token); ?>">


            </from>

    </main>
</div>
<!-- 画像をプレビュー表示させる -->
<script>
function preview(elem) {
    const blobUrl = window.URL.createObjectURL(elem.files[0])
    elem.previousElementSibling.innerHTML = `<img src=${blobUrl} width="30%">`
}
</script>
<?php include('parts/footer.php'); ?>
<?php
// セッションの削除
$_SESSION['animal']['animal']['title'] = [];
$_SESSION['kind'] = [];
$_SESSION['animal']['gender'] = [];
$_SESSION['animal']['age'] = [];
$_SESSION['animal']['area_1'] = [];
$_SESSION['animal']['area_2'] = [];
$_SESSION['animal']['area_3'] = [];
$_SESSION['animal']['animal_area'] = [];
$_SESSION['animal']['animal_character'] = [];
$_SESSION['animal']['other'] = [];
?>