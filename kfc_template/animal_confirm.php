<?php
// titleで読み込むページ名
$pagetitle = "犬猫登録確認"
?>
<?php include('parts/header.php'); ?>

<?php
// セッション開始
if (!isset($_SESSION)) {
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
    echo '<a href="animal.php">登録画面に戻る</a>';
    exit("エンコードエラー");
}

// トークンチェック
if (isset($_POST['token']) && isset($_SESSION['token'])) {
    if ($_POST['token'] !== $_SESSION['token']) {
        echo "不正なアクセスです。err:1";
        echo "<a class ='error' href='login.php'>戻る</a>";
        exit();
    }
} else {
    echo "不正なアクセスです。err:2";
    echo "<a class ='error' href='login.php'>戻る</a>";
    exit();
}

// トークンの設定
$token = $_SESSION['token'];

// エスケープ処理
$_POST = es($_POST);

//ログインページに戻って表示させるエラー文を入れる配列の初期化 
$errors = [];

/*************************************************************
 バリデーション
 ************************************************************/
// $title タイトルのバリデーション
$title = preg_replace('/^[　]+|[　]+$/u', "", $_POST['title']);
if (empty($title)) {
    $errors[] = "【タイトル】は必須です";
} else if (mb_strlen($title) > 40) {
    $errors[] = "【タイトル】は40文字以内で入力して下さい";
}

// $file1,$file2,$file3($_FILEの連想配列が入っている)
//  画像のバリデーション
// ファイルがアップロードされているか
if (
    !is_uploaded_file($_FILES["image_1"]['tmp_name']) ||
    !is_uploaded_file($_FILES["image_2"]['tmp_name']) ||
    !is_uploaded_file($_FILES["image_3"]['tmp_name'])
) {
    $errors[] = "【掲載画像】は3枚選択してください。";
} else {

    for ($i = 1; $i <= 3; $i++) {
        $allow_ext = array('jpg', 'jpeg', 'png');
        $file_ext = pathinfo($_FILES["image_{$i}"]['name'], PATHINFO_EXTENSION);
        // 拡張子が正しいかの確認
        if (!in_array(strtolower($file_ext), $allow_ext)) {
            $errors[] = "【画像{$i}】は画像ファイルを添付してください";
        } else {
            ${"file" . $i} = $_FILES["image_{$i}"];
            //選択した犬猫画像を読み込む 
            $_SESSION['animal']["image_{$i}"]['data'] = file_get_contents($_FILES["image_{$i}"]['tmp_name']);
            $_SESSION['animal']["image_{$i}"]['type'] = exif_imagetype($_FILES["image_{$i}"]['tmp_name']);
        }
    }
}

// 犬種/猫種のバリデーション
$kind = preg_replace('/^[　]+|[　]+$/u', "", $_POST["kind"]);
if (empty($kind)) {
    $errors[] = "【犬種/猫種】は必須です";
} else if (mb_strlen($kind) > 40) {
    $errors[] = "【犬種/猫種】は40文字以内で入力して下さい";
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
    '選択してください', '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県',
    '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
);

if (($_POST["area_1"] === "選択してください" || empty($_POST["area_1"])) ||
    ($_POST["area_2"] === "選択してください" || empty($_POST["area_2"])) ||
    ($_POST["area_3"] === "選択してください" || empty($_POST["area_3"]))
) {
    $errors[] = "【募集対象地域】は3つ選択してください。";
} else {

    for ($i = 1; $i <= 3; $i++) {

        if (in_array($_POST["area_{$i}"], $prefList)) {
            ${"area_" . $i} = $_POST["area_{$i}"];
        } else {
            $errors[] = "【募集対象地域{$i}】に入力エラーがありました。";
        }
    }
}


// if (in_array($_POST['area_1'], $prefList)) {
//     if ($_POST['area_1'] === "選択してください" ||$_POST['area_1'] === "設定しない") {
//         $errors[] = "【募集対象地域1】は必須です";
//     } else {
//         $area_1 = $_POST["area_1"];
//     }
// } else {
//     $errors[] = "【募集対象地域1】に入力エラーがありました。";
// }


// if (in_array($_POST['area_2'], $prefList)) {
// if ($_POST['area_2'] === "選択してください" || $_POST['area_2'] === "設定しない") {
//         $area_2 = "未設定";
//     } else {
//         $area_2 = $_POST["area_2"];
//     }
// } else {
//     $errors[] = "【募集対象地域2】に入力エラーがありました。";
// }

// if (in_array($_POST['area_3'], $prefList)) {
//     if ($_POST['area_3'] === "選択してください" || $_POST['area_3'] === "設定しない") {
//         $area_3 = "未設定";
//     } else {
//         $area_3 = $_POST["area_3"];
//     }
// } else {
//     $errors[] = "【募集対象地域3】に入力エラーがありました。";
// }



if (in_array($_POST['animal_area'], $prefList)) {
    if ($_POST['animal_area'] === "選択してください" || $_POST['animal_area'] === "設定しない") {
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



// エラーがあった場合犬猫登録ページへ戻る
// $_SESSION['error']に$errors[]を代入
if (count($errors) > 0) {
    $_SESSION['animal']['error'] = $errors;
    // var_dump($errors);
    header("Location:animal.php");
    exit();
}


// var_dump($_POST);
// var_dump($_FILES);
// var_dump($file1);
// var_dump($file2);
// var_dump($file3);
// var_dump($title);
// var_dump($kind);
// var_dump($gender);
// var_dump($age);
// var_dump($area_1);
// var_dump($area_2);
// var_dump($area_3);
// var_dump($animal_area);
// var_dump($animal_character);
// var_dump($other);

?>


<div id="container" class="c1">
    <main>
        <!-- <div class="back_btn">
            <button><a href="animal.php">戻る</a></button>
        </div> -->
        <h2>犬猫情報登録確認画面</h2>
        <table class="ta1">
            <tbody>

                <tr>
                    <th>タイトル※</th>
                    <td><?= es($title); ?></td>
                </tr>
                <tr>
                    <th>画像選択（3枚）※</th>
                    <td><img src="./lib/image_1.php" width="200px"></td>
                    <td><img src="./lib/image_2.php" width="200px"></td>
                    <td><img src="./lib/image_3.php" width="200px"> </td>
                </tr>
                <tr>
                    <th>犬種/猫種※</th>
                    <td><?= es($kind); ?></td>
                </tr>
                <tr>
                    <th>性別※</th>
                    <td><?= es($gender); ?>
                <tr>
                    <th>年齢※</th>
                    <td><?= es($age); ?></td>
                </tr>

                <tr>
                    <th rowspan="3">募集対象地域<br>3つまで選択可</th>
                    <td>募集対象地域1：<?= es($area_1); ?> </td>
                    </td>
                </tr>
                <tr>
                    <td>募集対象地域2：<?= es($area_2); ?></td>
                </tr>
                <tr>
                    <td>募集対象地域3：<?= es($area_3); ?></td>
                </tr>
                <tr>
                    <th>動物がいる地域※</th>
                    <td><?= es($animal_area); ?></td>
                </tr>
                <tr>
                    <th>特徴（性格）※</th>
                    <td><?= es($animal_character); ?></td>
                </tr>
                <tr>
                    <th>特記事項※</th>
                    <td><?= es($other); ?></td>
                </tr>
            </tbody>
        </table>
        <form method="POST" action="#">
            <p class="c">
                <input type="submit" value="この内容で登録する" name="send" formaction="animal_complet.php" class="btn_one">
            </p>
            <p class="c">
                <input type="submit" value="戻る" formaction="animal.php" class="btn_back_one">
            </p>
            <input type="hidden" name="token" value="<?= es($token); ?>">
            </from>

    </main>
</div>

</main>
</div>

<?php include('parts/footer.php'); ?>