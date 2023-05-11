<?php
// titleで読み込むページ名
$pagetitle = "犬猫情報変更"
?>
<?php include('parts/header.php'); ?>

<?php
// セッション開始
if (!isset($_SESSION)) {
    session_start();
}

require_once("./lib/util.php");
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

// 不正アクセスチェックとanimal_idの取得
if (empty($_SESSION['animal_id']) && empty($_POST['animal_id'])) {
    echo "不正なアクセスです。err:1";
    echo "<a class ='error' href='breeder_mypage.php'>戻る</a>";
    exit();
} elseif (!empty($_SESSION['animal_id'])) {
    $animal_id = $_SESSION['animal_id'];
    $_SESSION['animal_id'] = [];
} elseif (!empty($_POST['animal_id'])) {
    $animal_id = $_POST['animal_id'];
}

// トークンの発行
$bytes = openssl_random_pseudo_bytes(16);
$token = bin2hex($bytes);
$_SESSION['token'] = $token;


/*************************************************************
 DB接続 基本情報
 ************************************************************/
// データベース接続
$user = 'shotohlcd31_kfc';
$password = 'KFCpassword';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
//$host = 'sv14471.xserver.jp';
$dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";


/*************************************************************
 DB接続 animalテーブルから登録情報を取得
 ************************************************************/

// DB接続
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // sql文：userテーブルから$animal_idに該当するユーザー情報を取得
    $sql = "SELECT * FROM animal WHERE animal_id = $animal_id";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $result = $stm->fetch(PDO::FETCH_ASSOC);
    // var_dump($result);
    // エスケープ処理
    $result = es($result);

    // 入力値の設定
    // 一度入力した値がSESSIONに入っているので存在すればその値、
    // 無ければ現時点で犬猫登録されている値を入れる

    $title = !empty($_SESSION['animal']['title']) ? $_SESSION['animal']['title'] : $result['title'];
    $kind = !empty($_SESSION['animal']['kind']) ? $_SESSION['animal']['kind'] : $result['kind'];
    $gender = !empty($_SESSION['animal']['gender']) ? $_SESSION['animal']['gender'] : $result['gender'];
    $age = !empty($_SESSION['animal']['age']) ? $_SESSION['animal']['age'] : $result['age'];
    $area_1 = !empty($_SESSION['animal']['area_1']) ? $_SESSION['animal']['area_1'] : $result['area_1'];
    $area_2 = !empty($_SESSION['animal']['area_2']) ? $_SESSION['animal']['area_2'] : $result['area_2'];
    $area_3 = !empty($_SESSION['animal']['area_3']) ? $_SESSION['animal']['area_3'] : $result['area_3'];
    $animal_area = !empty($_SESSION['animal']['animal_area']) ? $_SESSION['animal']['animal_area'] : $result['animal_area'];
    $animal_character = !empty($_SESSION['animal']['animal_character']) ? $_SESSION['animal']['animal_character'] : $result['animal_character'];
    $other = !empty($_SESSION['animal']['other']) ? $_SESSION['animal']['other'] : $result['other'];


    // 都道府県のセレクトボックスオプションの作成
    $prefList = array(
        '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
    );
    function select_option($area)
    {
        global $prefList;
        $pref_optionlist = "";
        foreach ($prefList as $pref_option) {
            if ($area === $pref_option) {
                $pref_optionlist .= "<option value='{$pref_option}' selected>{$pref_option}</option>";
            }
            $pref_optionlist .= "<option value='{$pref_option}'>{$pref_option}</option>";
        }
        return $pref_optionlist;
    }
    // 都道府県選択のオプション設定
    $area_1_option = select_option($area_1);
    $area_2_option = select_option($area_2);
    $area_3_option = select_option($area_3);
    $animal_area_option = select_option($animal_area);

    // 犬or猫checkedの設定
    $kind_check1 = $kind === "犬" ? "checked" : "";
    $kind_check2 = $kind === "猫" ? "checked" : "";


    // 性別入力値checkedの設定
    $gender_check1 = $gender === "♂" ? "checked" : "";
    $gender_check2 = $gender === "♀" ? "checked" : "";

    //確認ページに渡す画像のパス
    $send_image1 =  "./images/animal_photo/{$result['image_1']}";
    $send_image2 =  "./images/animal_photo/{$result['image_2']}";
    $send_image3 =  "./images/animal_photo/{$result['image_3']}";

?>

<div id="container" class="c1">
    <main>
        <h2 class="c">登録内容の変更</h2>
        <!-- <div class="back_btn">
            <button><a href="animal_manage.php">戻る</a></button>
        </div> -->

        <?php
        // エラーを受け取る処理
        if (!empty($_SESSION['animal']['error'])) {
            $errors = $_SESSION['animal']['error'];
            foreach ($errors as $value) {
                echo "<span class='error'>$value</span><br>";
            }
            $_SESSION['animal'] = [];
        }

        echo <<<EOL
    <form method="POST" action="animal_confirm.php" enctype="multipart/form-data">
        <table class="ta1 animal_post">
            <tbody>
                <tr>
                    <th>掲載ID:{$result['animal_id']}</th>
                    <td colspan="3"><img src="./images/animal_photo/{$result['image_1']}" alt="{$result['kind']}"></td>
                </tr>
                <tr>
                    <th>掲載タイトル</th>
                    <td colspan="3"><textarea name="title" class="txtareamini">{$title}</textarea></td>
                </tr>
                <tr class="column3">
                    <th>現在登録されている画像</th>
                    <td><p>画像1</p><img src ="./images/animal_photo/{$result['image_1']}" alt="{$result['kind']}"></td>
                    <td><p>画像2</p><img src ="./images/animal_photo/{$result['image_2']}" alt="{$result['kind']}"></td>
                    <td><p>画像3</p><img src ="./images/animal_photo/{$result['image_3']}" alt="{$result['kind']}"></td>
                </tr>
                <tr class="column3">
                    <th>差し替えたい画像を選択</th>
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
                        <input type="hidden" name="MAX_FILE_SIZE" value="4194304">
                </tr>     
                <tr>
                    <th>犬or猫</th>
                    <td colspan="3"><input type="text" name="kind" value="{$kind}"></td>
                    <th>犬or猫</th>
                    <td>
                        <label>犬<input type="radio" name="kind" value="犬" {$kind_check1}></label>
        <label>猫<input type="radio" name="kind" value="猫" {$kind_check2}></label>
        </td>
        </tr>
        <tr>
            <th>性別</th>
            <td colspan="3"><label>♂<input type="radio" name="gender" value="♂" {$gender_check1}></label>
                <label>♀<input type="radio" name="gender" value="♀" {$gender_check2}></label>
            </td>
        </tr>
        <tr>
            <th>年齢</th>
            <td colspan="3"><input type=" text" name="age" value="{$age}"></td>
        </tr>
        <tr>
            <th rowspan="3">募集対象地域<br>3つ選択</th>
            <td>募集対象地域1
                <select name="area_1">
                    {$area_1_option}
                </select>
            </td>
        </tr>
        <tr>
            <td>募集対象地域2
                <select name="area_2">
                    {$area_2_option}
                </select>
            </td>
        </tr>
        <tr>
            <td>募集対象地域3
                <select name="area_3">
                    {$area_3_option}
                </select>
            </td>
        </tr>
        <tr>
            <th>動物がいる地域※</th>
            <td colspan="3"><select name="animal_area">
                    {$animal_area_option}
                </select>
            </td>
        </tr>
        <tr>
            <th>特徴（性格）</th>
            <td colspan="3"><textarea name="animal_character">{$animal_character}</textarea>
        </tr>
        <tr>
            <th>特記事項</th>
            <td colspan="3"><textarea name="other">{$other}</textarea>
            </td>
        </tr>
        </tbody>
        </table>
        <p class="c">
            <input type="submit" value="確認ページへ" name="send" formaction="animal_change_confirm.php" class="btn_one">
        </p>
        <p class="c">
            <input type="submit" value="戻る" formaction="animal_manage.php" class="btn_back_one">
        </p>
        <input type="hidden" name="token" value="{$token}">
        <input type="hidden" name="animal_id" value="{$animal_id}">
        <input type="hidden" name="send_image1" value="{$send_image1}">
        <input type="hidden" name="send_image2" value="{$send_image2}">
        <input type="hidden" name="send_image3" value="{$send_image3}">
        </form>
</div>
</main>
</div>

EOL;
    } catch (Exception $e) {
        $e->getMessage();
        echo "エラーが発生しました。2";
        // エラーの場合はログインページに
        echo "<a class='error' href='login.php'>戻る</a>";
    }
        ?>

        <!-- 画像をプレビュー表示させる -->
        <script>
        function preview(elem) {
            const blobUrl = window.URL.createObjectURL(elem.files[0])
            elem.previousElementSibling.innerHTML = `<img src=${blobUrl} width=" 30%">`
        }
        </script>
        <?php include('parts/footer.php'); ?>