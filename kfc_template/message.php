<?php
session_start();
require_once("./lib/util.php");

// titleで読み込むページ名
$pagetitle = "メッセージ";


?>
<?php include('parts/header.php'); ?>


<div id="container" class="c1" style="display:block">
    <main>
        <?php
    // メッセージ送信先のユーザー情報
    if (!isset($_GET['user_id'])) {
      echo "送信先ユーザーが未選択です<br>";
      echo "<a href='message_top.php'><button>戻る</button></a>";
      exit();
    }
    // 現在ログインしているユーザー情報
    $current_user = get_user($_SESSION['user_id']);

    //送信先
    $destination_user = get_user($_GET['user_id']);
    // やり取りされるメッセージ情報
    $messages = get_messages($current_user['user_id'], $destination_user['user_id']);
    ?>

        <body>
            <div class="message">
                <div class="user_flex">
                    <div class="back_btn">
                        <a href="message_top.php"><button>
                                < 戻る</button></a>
                    </div>
                    <div class="user_name">
                        <h2><?= $destination_user['user_name'] ?></h2>
                    </div>
                    <div class="textarea_btn">
                        <a href="#text4">
                            <div class="arrow">
                            </div>
                        </a>
                    </div>
                </div>
                <?php foreach ($messages as $message) : ?>
                <div class="my_message">
                    <?php if ($message['user_id'] == $current_user['user_id']) : ?>
                    <div class="mycomment right">
                        <span class="message_created_at"><?= convert_to_fuzzy_time($message['created_at']) ?></span>
                        <p><?= $message['text'] ?></p>
                    </div>
                </div>
                <?php else : ?>
                <div class="left"><img src="./images/足跡アイコン.png" class="icon_image">
                    <div class="says">
                        <p><?= $message['text'] ?></p>
                    </div>
                    <span class="message_created_at"><?= convert_to_fuzzy_time($message['created_at']) ?>
                    </span>
                </div>
                <?php endif; ?>
                <?php endforeach ?>

                <div class="message_process">
                    <!-- message_add.phpにPOSTするフォーム -->
                    <form method="post" action="./message_add.php">
                        <textarea class="textarea form-control" placeholder="メッセージを入力ください" name="text" id="text4"
                            autocomplete="off"></textarea>
                        <input type="hidden" name="destination_user_ID" value="<?= $destination_user['user_id']; ?>">
                        <div class="message_btn">
                            <button class="btn btn-outline-primary btn_03" type="submit" name="post" value="post"
                                id="post">送信</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
      // 里親申し込み完了後のトークルーム作成

      $user_id = $_SESSION['user_id'];
      $destination_user_ID = $_GET['user_id'];
      $user_id = htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8');
      if (!check_relation_message($user_id, $destination_user_ID)) {
        insert_message($user_id, $destination_user_ID);
        // echo "relation_messageにデータを挿入";
      }
      ?>
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

            <script>
            // テキストエリア入力したら送信ボタン有効
            window.addEventListener('DOMContentLoaded', function() {
                document.getElementById('post').disabled = true;
                document.getElementById('text4').addEventListener('keyup', function() {
                    if (this.value.length < 1) {
                        document.getElementById('post').disabled = true;
                    } else {
                        document.getElementById('post').disabled = false;
                    }
                }, false);

                // postしたら*ms後にdisabledにする
                let btn = document.getElementById('post');
                btn.addEventListener('click', function() {
                    window.setTimeout(click_disabled, 10);
                });
                //post無効の自作関数
                function click_disabled() {
                    document.getElementById('post').disabled = true;
                }
            }, false);
            </script>
        </body>

        <?php
    function get_user($user_id)
    {
      try {
        $user = 'shotohlcd31_kfc';
        $password = 'KFCpassword';
        $dbName = 'shotohlcd31_kfc';
        $host = 'localhost';
        $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";
        $dbh = new PDO($dsn, $user, $password);
        $sql = "SELECT user_id,user_name,password 
        FROM user 
        WHERE user_id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
      } catch (\Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        echo "ERR_MSG1";
      }
    }


    function get_messages($user_id, $destination_user_id)
    {
      try {
        $user = 'shotohlcd31_kfc';
        $password = 'KFCpassword';
        $dbName = 'shotohlcd31_kfc';
        $host = 'localhost';
        $dsn = "mysql:host={$host}; dbname={$dbName}; charset=utf8";
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
      } catch (\Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
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
    ?>

    </main>
</div>

<?php include('parts/footer.php'); ?>