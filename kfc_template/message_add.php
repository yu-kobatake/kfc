<?php
session_start();
require_once("./lib/util.php");

// titleで読み込むページ名
$pagetitle = "message_add.php";


?>
<?php include('parts/header.php'); ?>
<div id="container" class="c1 c">
    <main>
        <?php
    $_POST = es($_POST);

    try {
      $date = new DateTime();
      $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));
      $created_at = $date->format('Y-m-d H:i:s');
      $message_text = $_POST['text'];
      $user_id = $_SESSION['user_id'];
      $destination_user_ID = $_POST['destination_user_ID'];

      $message_text = htmlspecialchars($message_text, ENT_QUOTES, 'UTF-8');
      $user_id = htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8');
      $user = 'shotohlcd31_kfc';
      $password = 'KFCpassword';
      $dbName = 'shotohlcd31_kfc';
      $host = 'localhost';
      $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";
      $dbh = new PDO($dsn, $user, $password);

      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO message(text,user_id,destination_user_id,created_at) 
      VALUES (:text,:user_id,:destination_user_ID,:created_at)";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':text', $message_text, PDO::PARAM_STR);
      $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
      $stmt->bindValue(':destination_user_ID', $destination_user_ID, PDO::PARAM_STR);
      $stmt->bindValue(':created_at', $date->format('Y-m-d H:i:s'), PDO::PARAM_STR);
      $stmt->execute();

      //データベース内に自分と送信先のIDがあるかチェック
      if (!check_relation_message($user_id, $destination_user_ID)) {
        insert_message($user_id, $destination_user_ID);
        // echo "relation_messageにデータを挿入";
      }
      header('Location:../kfc_template/message.php?user_id=' . $destination_user_ID . '');
      // echo "メッセージを送信しました";

    } catch (Exception $e) {
      print 'ただいま障害により大変ご迷惑をお掛けしております。';
      exit();
    }

    ?>

        <a href="message.php">戻る</a>
        <?php
    function insert_message($user_id, $destination_user_ID)
    {
      try {
        $user = 'shotohlcd31_kfc';
        $password = 'KFCpassword';
        $dbName = 'shotohlcd31_kfc';
        $host = 'localhost';
        $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";
        $dbh = new PDO($dsn, $user, $password);
        $sql = "INSERT INTO message_relation(user_id,destination_user_id) VALUES (:user_id,:destination_user_ID)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindValue(':destination_user_ID', $destination_user_ID, PDO::PARAM_STR);
        $stmt->execute();
      } catch (\Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        echo "ERR_MSG1";
      }
    }

    function check_relation_message($user_id, $destination_user_ID)
    {
      try {
        $user = 'shotohlcd31_kfc';
        $password = 'KFCpassword';
        $dbName = 'shotohlcd31_kfc';
        $host = 'localhost';
        $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";
        $dbh = new PDO($dsn, $user, $password);
        $sql = "SELECT user_id,destination_user_id
            FROM message_relation
            WHERE (user_id = :user_id and destination_user_id = :destination_user_id)
                  or (user_id = :destination_user_id and destination_user_id = :user_id)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
          ':user_id' => $user_id,
          ':destination_user_id' => $destination_user_ID
        ));
        return $stmt->fetch();
      } catch (\Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
      }
      echo "ERR_MSG1";
    }
    ?>
    </main>
</div>

<?php include('parts/footer.php'); ?>