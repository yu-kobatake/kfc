<?php
// titleで読み込むページ名
$pagetitle = "犬猫情報変更確認"
?>
<?php include('parts/header.php'); ?>

<?php
// セッション開始
if(!isset($_SESSION)){
session_start();
}

require_once("./lib/util.php");


// ユーザーIDがセッションに入っていれば$user_idに代入する
if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
  //セッションに入っていなければればログインページに戻す 
  } else { 
    header("Location:login.php");
    exit();
  }

// エンコードチェック
if (!cken($_POST)) {
    echo '<a href="login.php">ログインページに戻る</a>';
    exit("エンコードエラー");
}

// トークンチェック
if (isset($_POST['token']) && isset($_SESSION['token'])) {
    if ($_POST['token'] !== $_SESSION['token']) {
        echo "不正なアクセスです。err:1";
        echo "<a class ='error' href='animal_manage.php'>管理画面に戻る</a>";
        exit();
    }
} else {
    echo "不正なアクセスです。err:2";
    echo "<a class ='error' href='animal_manage.php'>管理画面に戻る</a>";
    exit();
}

// トークンの設定
$token = $_SESSION['token'];

// エスケープ処理
$_POST = es($_POST);

//ログインページに戻って表示させるエラー文を入れる配列の初期化 
$errors = [];

// POSTされたanimal_idを変数に入れる
$animal_id = $_POST['animal_id'];

// セッションに$animal_idを入れる
$_SESSION['animal_id'] = $animal_id;


// バリデーション

// $title タイトルのバリデーション
$title = preg_replace('/^[　]+|[　]+$/u', "", $_POST['title']);
if (empty($title)) {
    $errors[] = "【タイトル】は必須です";
} else if (mb_strlen($title) > 40) {
    $errors[] = "【タイトル】は40文字以内で入力して下さい";
}

// $file1,$file2,$file3($_FILEで渡ってきた中身)
//  画像のバリデーション
// 拡張子を配列に入れる
$allow_ext = array('jpg', 'jpeg', 'png');
for ($i = 1; $i <= 3; $i++) {

    // ファイルがアップロードされているか
    if (is_uploaded_file($_FILES["image_{$i}"]['tmp_name'])) {

        // 拡張子が正しいかの確認
        $file_ext = pathinfo($_FILES["image_{$i}"]['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_ext), $allow_ext)) {
            $errors[] = "【画像{$i}】は画像ファイルを添付してください";
        } else {
            ${"file" . $i} = $_FILES["image_{$i}"];
            //新しく選択した犬猫画像を読み込む 
            $_SESSION['animal']["image_{$i}"]['data'] = file_get_contents($_FILES["image_{$i}"]['tmp_name']);
            $_SESSION['animal']["image_{$i}"]['type'] = exif_imagetype($_FILES["image_{$i}"]['tmp_name']);
        }
        // ページ上部に表示させるメイン画像のパスをsend_image1変数に入れる
        $send_image1 = $_POST['send_image1'];
    } else { // ファイルがアップロードされていなかったら登録済みの画像を表示させる
        // 変更入力画面からPOSTされた画像のパスを受け取り、変数に入れる
        $send_image1 = $_POST['send_image1'];
        $send_image2 = $_POST['send_image2'];
        $send_image3 = $_POST['send_image3'];
    }
}

// 犬or猫のバリデーション
if (empty($_POST["kind"])) {
    $errors[] = "【犬or猫】は必須です";
} else {
    $kind_value = ["犬", "猫"];
    if (in_array($_POST['kind'], $kind_value)) {
        $kind = $_POST["kind"];
    } else {
        $errors[] = "【犬or猫】に入力エラーがありました。";
    }
}

// 性別のバリデーション
if (empty($_POST["gender"])) {
    $errors[] = "【性別】は必須です";
} else {
    $gender_value = ["♂", "♀"];
    if (in_array($_POST['gender'], $gender_value)) {
        $gender = $_POST["gender"];
    } else {
        $errors[] = "【性別】に入力エラーがありました。";
    }
}

// 年齢（文字入力）のバリデーション
$age = preg_replace('/^[　]+|[　]+$/u', "", $_POST['age']);
if (empty($age)) {
    $errors[] = "【年齢】は必須です";
} else if (mb_strlen($age) > 20) {
    $errors[] = "【年齢】は20文字以内で入力して下さい";
}

// 募集地域/犬猫がいる地域のバリデーション
$prefList = array(
    '選択してください','北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県',
    '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
);

if(($_POST["area_1"] === "選択してください" || empty($_POST["area_1"]))|| 
($_POST["area_2"] === "選択してください" || empty($_POST["area_2"]))|| 
($_POST["area_3"] === "選択してください" || empty($_POST["area_3"]))){
$errors[] = "【募集対地域は画像】は3つ選択してください。";
} else {
    for($i = 1; $i<= 3; $i++){
        if (in_array($_POST["area_{$i}"], $prefList)) {
            ${"area_".$i} = $_POST["area_{$i}"];
        } else {
            $errors[] = "【募集対象地域{$i}】に入力エラーがありました。";
        } 
    }    
}

// 動物がいる地域のバリデーション
if (in_array($_POST['animal_area'], $prefList)) {
    if ($_POST['animal_area'] === "選択してください" || empty($_POST['animal_area'])) {
        $errors[] = "【動物がいる地域】は必須です";
    } else {
        $animal_area = $_POST["animal_area"];
    }
} else {
    $errors[] = "【動物がいる地域】に入力エラーがありました。";
}

//特徴（色柄、性格など)のバリデーション
$animal_character = preg_replace('/^[　]+|[　]+$/u', "", $_POST["animal_character"]);
if (empty($animal_character)) {
    $errors[] = "【特徴】は必須です";
} else if (mb_strlen($title) > 200) {
    $errors[] = "【特徴】は200文字以内で入力して下さい";
}

// 特記事項のバリデーション
$other = preg_replace('/^[　]+|[　]+$/u', "", $_POST["other"]);
if (empty($other)) {
    $errors[] = "【特記事項】は必須です";
} else if (mb_strlen($title) > 500) {
    $errors[] = "【特記事項】は500文字以内で入力して下さい";
}


//SESSIONに登録する 
$_SESSION['animal']['title'] = !empty($title) ? $title : null;
$_SESSION['animal']['file1'] = !empty($file1) ? $file1 : null;
$_SESSION['animal']['file2'] = !empty($file2) ? $file2 : null;
$_SESSION['animal']['file3'] = !empty($file3) ? $file3 : null;
$_SESSION['animal']['kind'] = !empty($kind) ? $kind : null;
$_SESSION['animal']['gender'] = !empty($gender) ? $gender : null;
$_SESSION['animal']['age'] = !empty($age) ? $age : null;
$_SESSION['animal']['area_1'] = !empty($area_1) ? $area_1 : null;
$_SESSION['animal']['area_2'] = !empty($area_2) ? $area_2 : null;
$_SESSION['animal']['area_3'] = !empty($area_3) ? $area_3 : null;
$_SESSION['animal']['animal_area'] = !empty($animal_area) ? $animal_area : null;
$_SESSION['animal']['animal_character'] = !empty($animal_character) ? $animal_character : null;
$_SESSION['animal']['other'] = !empty($other) ? $other : null;


// エラーがあった場合、変更入力画面に戻る
// $_SESSION['animal_error']に$errors[]を代入
if (count($errors) > 0) {
    $_SESSION['animal']['error'] = $errors;
    // var_dump($errors);
    header("Location:animal_change.php");
    exit();
}

?>

<div id="container" class="c1">
    <main>
        <button onclick="location.href='animal_change.php'" class="btn_back_mini marbtm10">
            < 戻る</button>
                <h2>犬猫情報変更確認画面</h2>
                <table class="ta1 animal_post ta_fixed">
                    <tbody>
                        <tr>
                            <th>掲載ID:<?= es($animal_id); ?></th>
                            <td colspan="3"><img src="<?= es($send_image1); ?>"></td>
                        </tr>
                        <tr>
                            <th>掲載タイトル</th>
                            <td colspan="3"><?= es($title); ?>
                            </td>
                        </tr>
                        <tr class="column3">
                            <th>掲載画像（3枚選択）</th>
                            <td valign="top"><img src="<?= !empty($file1) ? "./lib/image_1.php" : es($send_image1); ?>">
                            </td>
                            <td valign="top"><img src="<?= !empty($file2) ? "./lib/image_2.php" : es($send_image2); ?>">
                            </td>
                            <td valign="top"><img src="<?= !empty($file3) ? "./lib/image_3.php" : es($send_image3); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>犬or猫</th>
                            <td colspan="3"><?= es($kind); ?></td>
                        </tr>
                        <tr>
                            <th>性別</th>
                            <td colspan="3"><?= es($gender); ?>
                        <tr>
                            <th>年齢</th>
                            <td colspan="3"><?= es($age); ?></td>
                        </tr>

                        <tr>
                            <th rowspan="3">募集対象地域<br>3つ選択</th>
                            <td>募集対象地域1<?= es($area_1); ?> </td>
                            </td>
                        </tr>
                        <tr>
                            <td>募集対象地域2<?= es($area_2); ?></td>
                        </tr>
                        <tr>
                            <td>募集対象地域3<?= es($area_3); ?></td>
                        </tr>
                        <tr>
                            <th>動物がいる地域</th>
                            <td colspan="3"><?= es($animal_area); ?></td>
                        </tr>
                        <tr>
                            <th>特徴（性格）</th>
                            <td colspan="3"><?= es($animal_character); ?></td>
                        </tr>
                        <tr>
                            <th>特記事項</th>
                            <td colspan="3"><?= es($other); ?></td>
                        </tr>
                    </tbody>
                </table>
                <form method="POST" action="#">
                    <p class="c">
                        <input type="submit" value="この内容で変更する" name="send" formaction="animal_change_complet.php"
                            class="btn_one">
                    </p>
                    <p class="c">
                        <input type="submit" value="戻る" formaction="animal_change.php" class="btn_back_one">
                    </p>
                    <input type="hidden" name="token" value="<?= es($token); ?>">
                    <input type="hidden" name="animal_id" value="<?= es($animal_id); ?>">

                    </from>

    </main>
</div>

</main>
</div>

<?php include('parts/footer.php'); ?>