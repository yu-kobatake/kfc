<?php
session_start();
require_once("./lib/util.php");
?>
<?php
// titleで読み込むページ名
$pagetitle = "トークルーム一覧"
?>
<?php include('parts/header_message.php'); ?>
<div id="container" class='c1'>
  <main>
    <?php

    $destination_user = get_user(2); //$_GET['user_id']
    $current_user = get_user($_SESSION['user_id']);
    $message_relations = get_message_relations($current_user['user_id']);

    foreach ($message_relations as $message_relation) :
      if ($message_relation['destination_user_id'] == $current_user['user_id']) {
        $destination_user = get_user($message_relation['user_id']);
      } else {
        $destination_user = get_user($message_relation['destination_user_id']);
      }
      $bottom_message = get_bottom_message($current_user['user_id'], $destination_user['user_id']);
    ?>

      <body>
        <div class="row">
          <div class="col-8 offset-2">
            <a href='message.php?user_id=<?= $destination_user['user_id'] ?>'>
              <div class="destination_user_list">
                <div class='destination_user_info'>
                  <div class="destination_user_name"><?= $destination_user['user_name'] ?></div>
                  <span class="destination_user_text"><?= $bottom_message['text'] ?></span>
                </div>

                <span class="bottom_message_time"><?= convert_to_fuzzy_time($bottom_message['created_at']); ?></span>
              </div>
            </a>
          </div>
        </div>
      <?php endforeach ?>
      </body>

      <?php

      function get_bottom_message($user_id, $destination_user_id)
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
                    WHERE (user_id = :user_id and destination_user_id = :destination_user_id)
                          or (user_id = :destination_user_id and destination_user_id = :user_id)
                    ORDER BY id DESC";
          $stmt = $dbh->prepare($sql);

          $stmt->execute(array(
            ':user_id' => $user_id,
            ':destination_user_id' => $destination_user_id
          ));
          $stmt->execute();
          return $stmt->fetch();
        } catch (\Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
          // set_flash('error', ERR_MSG1);
          echo "ERR_MSG1";
        }
      }
      ?>
      <?php
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

      function get_message_relations($user_id)
      {
        try {
          $user = 'username';
          $password = 'kfc';
          $dbName = 'shotohlcd31_ kfc';
          $host = 'localhost';
          $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
          $dbh = new PDO($dsn, $user, $password);
          $sql = "SELECT *
                  FROM message_relation
                  WHERE user_id = :user_id_1 OR destination_user_id = :user_id_2";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':user_id_1', $user_id, PDO::PARAM_STR);
          $stmt->bindValue(':user_id_2', $user_id, PDO::PARAM_STR);
          $stmt->execute();
          $result = $stmt->fetchAll();
          return $result;
        } catch (\Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
          // set_flash('error', ERR_MSG1);
          echo "ERR_MSG1";
        }
      }


      ?>
  </main>
</div>

<?php include('parts/footer.php'); ?>