<?php
session_start();
require_once("./lib/util.php");

// titleで読み込むページ名
$pagetitle = "メッセージ"
?>
<?php include('parts/header_message.php'); ?>
<div id="container">
  <main>
    <?php
    // 現在ログインしているユーザー情報
    $_SESSION['user_id'] = 1;
    $current_user = get_user($_SESSION['user_id']);
    // メッセージ送信先のユーザー情報
    $_GET['user_id'] = 2;
    $destination_user = get_user($_GET['user_id']);
    // やり取りされるメッセージ情報
    $messages = get_messages($current_user['user_id'], $destination_user['user_id']);
    ?>

    <body>
      <div class="message">
        <div class="message_top_btn">
          <a href="message_top.php">&lt;トークルーム一覧へ</a>
        </div>
        <h2 class="center"><?= $destination_user['user_name'] ?></h2>
        <?php foreach ($messages as $message) : ?>
          <div class="my_message">
            <?php if ($message['user_id'] == $current_user['user_id']) : ?>
              <div class="mycomment right">
                <span class="message_created_at"><?= convert_to_fuzzy_time($message['created_at']) ?></span>
                <p><?= $message['text'] ?></p>
              </div>
            <?php else : ?>
              <div class="left">
                <div class="says"><?= $message['text'] ?></div><span class="message_created_at"><?= convert_to_fuzzy_time($message['created_at']) ?></span>
              <?php endif; ?>
              </div>
            <?php endforeach ?>
          </div>
          <div class="message_process">
            <h2 class="message_title">メッセージ</h2>

            <!-- message_add.phpにPOSTするフォーム -->
            <form method="post" action="./message_add.php">
              <textarea class="textarea form-control" placeholder="メッセージを入力ください" name="text"></textarea>
              <input type="hidden" name="destination_user_ID" value="<?= $destination_user['user_id']; ?>">
              <div class="message_btn">
                <div class="message_image">
                  <!-- <input type="file" name="image" class="my_image" accept="image/*" multiple> -->
                </div>
                <button class="btn btn-outline-primary" type="submit" name="post" value="post" id="post">投稿</button>
              </div>
            </form>
          </div>

    </body>

    <?php
    function get_user($user_id)
    {
      try {
        $user = 'username';
        $password = 'kfc';
        $dbName = 'shotohlcd31_ kfc';
        $host = 'localhost';
        $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
        $dbh = new PDO($dsn, $user, $password);
        $sql = "SELECT user_id,user_name,password FROM user
                      WHERE user_id = :id";
        // $sql = "SELECT user_id,user_name,password FROM user
        //         WHERE user_id = :id AND delete_flg = 0 ";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
      } catch (\Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        // set_flash('error', ERR_MSG1);
        echo "ERR_MSG1";
      }
    }


    function get_messages($user_id, $destination_user_id)
    {
      try {
        $user = 'username';
        $password = 'kfc';
        $dbName = 'shotohlcd31_ kfc';
        $host = 'localhost';
        $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
        $dbh = new PDO($dsn, $user, $password);
        $sql = "SELECT *
                      FROM message
                      WHERE (user_id = :id_1 and destination_user_id = :destination_user_id_1) or (user_id = :destination_user_id_2 and destination_user_id = :id_2)
                      ORDER BY created_at ASC";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id_1', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':destination_user_id_1', $destination_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_2', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':destination_user_id_2', $destination_user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();

        // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($result);
      } catch (\Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        // set_flash('error', ERR_MSG1);
        echo "ERR_MSG1";
      }
    }

    ?>


    <?php
    /**
     * X秒前、X分前、X時間前、X日前などといった表示へ変換
     * 一分未満は秒、一時間未満は分、一日未満は時間、
     * 31日以内はX日前、それ以上はX月X日と返す
     * X月X日表記の時、年が異なる場合はyyyy年m月d日と、年も表示する
     *
     * @param   <String> $time_db       strtotime()で変換できる時間文字列 (例：yyyy/mm/dd H:i:s)
     * @return  <String>                X日前,などといった文字列
     **/
    function convert_to_fuzzy_time($time_db)
    {
      date_default_timezone_set('Asia/Tokyo');

      $unix   = strtotime($time_db);
      $now    = time();
      $diff_sec   = $now - $unix;

      if ($diff_sec < 60) {
        $time   = $diff_sec;
        $unit   = "秒前";
      } elseif ($diff_sec < 3600) {
        $time   = $diff_sec / 60;
        $unit   = "分前";
      } elseif ($diff_sec < 86400) {
        $time   = $diff_sec / 3600;
        $unit   = "時間前";
      } elseif ($diff_sec < 2764800) {
        $time   = $diff_sec / 86400;
        $unit   = "日前";
      } else {
        if (date("Y") != date("Y", $unix)) {
          $time   = date("Y年n月j日", $unix);
        } else {
          $time   = date("n月j日", $unix);
        }

        return $time;
      }

      return (int)$time . $unit;
    }
    ?>

  </main>
</div>

<?php include('parts/footer.php'); ?>