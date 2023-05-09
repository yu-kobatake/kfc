<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <?php echo "<title>$pagetitle</title>"; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animal_detail.css">
</head>

<body>

    <header>
        <h1 id="logo"><a href="./index.php"><img src="images/logo.png" alt="Sample Recipe Site"></a></h1>
        <ul>
            <li><a href="./index.php">ホーム</a></li>
            <li><a href="./recruit.php">里親募集</a></li>
            <li><a href="./event.php">イベント</a></li>
            <li><a href="./about.php">当サイトについて</a></li>
            <?php if(!empty($_SESSION['user_id'])){
                echo '<li><a href="./login.php">マイページ</a></li>';
                echo '<li><a href="./logout.php">ログアウト</a></li>';
            } else {
              echo '<li><a href="./login.php">ログイン</a></li>';   
            }

?>

        </ul>
    </header>

    <!--開閉ボタン（ハンバーガーアイコン）-->
    <div id="menubar_hdr">
        <span></span><span></span><span></span>
    </div>
    <!--スマホ用の開閉ブロック（メニュー）-->
    <div id="menubar">
        <ul>
            <li><a href="./index.php">ホーム</a></li>
            <li><a href="./recruit.php">里親募集</a></li>
            <li><a href="./event.php">イベント</a></li>
            <li><a href="./about.php">当サイトについて</a></li>
            <?php if(!empty($_SESSION['user_id'])){
                echo '<li><a href="./logout.php">ログアウト</a></li>';
            }?>
        </ul>
        <ul class="submenu btn">
            <?php if(!empty($_SESSION['user_id'])){
            echo '<li><a href="./login.php">マイページ</a></li>';
        } else {
          echo '<li><a href="./login.php">ログイン</a></li>';   
        }
?>
        </ul>
    </div>

    <!--/#menubar-->