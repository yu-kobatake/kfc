<?php
session_start();
require_once("./lib/util.php");
// var_dump($_SESSION);
// var_dump($_POST);

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
    // sql文：userテーブルから$user_idに該当するユーザー情報を取得
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
    //セレクトボックスのオプションタグを作成
    // 必須項目の場合のセレクトボックス
    $pref_option1 = "<option hidden>選択してください</option>";
    // 任意項目の場合のセレクトボックス
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

    $area_1_option = option($area_1, $pref_option1);
    $area_2_option = option($area_2, $pref_option2);
    $area_3_option = option($area_3, $pref_option2);
    $animal_area_option = option($animal_area, $pref_option1);

    // 性別入力値checkedの設定
    $gender_check1 = $gender === "♂" ? "checked" : "";
    $gender_check2 = $gender === "♀" ? "checked" : "";

    //確認ページに渡す画像のパス
    $send_image1 =  "./images/animal_photo/{$result['image_1']}";
    $send_image2 =  "./images/animal_photo/{$result['image_2']}";
    $send_image3 =  "./images/animal_photo/{$result['image_3']}";


    // titleで読み込むページ名
    $pagetitle = "ここはタイトル";
    include('parts/header.php');
?>

<div id="container">
    <main>

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
    <h2>登録内容の変更</h2>
    <img src ="./images/animal_photo/{$result['image_1']}" alt="{$result['kind']}">
    <p>掲載ID:{$result['animal_id']}</p>
    <p>（※の項目は入力必須になります。）</p>
    <form method="POST" action="animal_confirm.php" enctype="multipart/form-data">
        <table class="ta1">
            <tbody>
                <tr>
                    <th>タイトル</th>
                    <td><textarea name="title">{$title}</textarea></td>
                </tr>
                <tr>
                    <th rowspan="2">画像選択(3枚)※</th>
                    <th>現在登録されている画像</th>
                    <td><p>画像1</p><img src ="./images/animal_photo/{$result['image_1']}" alt="{$result['kind']}" width="200px"></td>
                    <td><p>画像2</p><img src ="./images/animal_photo/{$result['image_2']}" alt="{$result['kind']}" width="200px"></td>
                    <td><p>画像3</p><img src ="./images/animal_photo/{$result['image_3']}" alt="{$result['kind']}" width="200px"></td>
                </tr>
                <tr>
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
                    <th>犬種猫種※</th>
                    <td><input type="text" name="kind" value="{$kind}"></td>
                </tr>
                <tr>
                    <th>性別</th>
                    <td><label>♂<input type="radio" name="gender" value="♂" {$gender_check1}></label>
                        <label>♀<input type="radio" name="gender" value="♀" {$gender_check2}></label>
                    </td>
                </tr>
                <tr>
                    <th>年齢</th>
                    <td><input type=" text" name="age" value="{$age}"></td>
                </tr>
                <tr>
                    <th rowspan="3">募集対象地域<br>3つまで選択可</th>
                    <td>募集対象地域1
                        <select name="area_1">
                            {$area_1_option}
                        </select>※
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
                    <td><select name="animal_area">
                            {$animal_area_option}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>特徴（性格）</th>
                    <td><textarea name="animal_character">{$animal_character}</textarea>
                </tr>
                <tr>
                    <th>特記事項</th>
                    <td><textarea name="other">{$other}</textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="submit" value="確認ページへ" name="send" formaction="animal_change_confirm.php">
        <input type="submit" value="戻る" formaction="animal_manage.php">
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
            elem.previousElementSibling.innerHTML = `<img src=${blobUrl} width="30%">`
        }
        </script>
        <?php include('parts/footer.php'); ?>