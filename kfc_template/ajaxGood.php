<?php
session_start();

$user = 'testuser';
$password = 'pw4testuser';
$dbName = 'shotohlcd31_kfc';
$host = 'localhost';
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

$user_id = $_SESSION['user_id'];

// 関数定義
function getGood($animal_good_id){
	// いいねを取得;
	try {
    global $dsn;
    global $user;
    global $password;
    global $animal_good_id;
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = 'SELECT * FROM good WHERE animal_id = :animal_good_id';
		// クエリ実行
    $stm = $pdo->prepare($sql);
    $result= $stm->execute(array(':animal_good_id' => $animal_good_id));

		if($stm){
			return $stm->fetchAll();
		}else{
			return false;
		}
	} catch (Exception $e) {
		error_log('エラー発生：'.$e->getMessage());
	}
}




// postがある場合
if(isset($_POST['animal_good_id'])){
    $animal_good_id = $_POST['animal_good_id'];

    try{
        //DB接続
        $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
      $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
        // goodテーブルからanimal_idとuser_idが一致したレコードを取得するSQL文
        $sql = 'SELECT * FROM good WHERE animal_id = :animal_good_id AND user_id = :user_id';

        $stm = $pdo->prepare($sql);
        $result= $stm->execute(array(':animal_good_id' => $animal_good_id, 'user_id' => $user_id));
        // クエリ実行
        $resultCount = $stm->rowCount();
        // レコードが1件でもある場合
        if(!empty($resultCount)){
            // レコードを削除する
            $sql = 'DELETE FROM good WHERE animal_id = :animal_good_id AND user_id = :user_id';
            $stm = $pdo->prepare($sql);
            // クエリ実行
        $result= $stm->execute(array(':animal_good_id' => $animal_good_id, ':user_id' => $user_id));
            echo count(getGood($animal_good_id));
        }else{
            // レコードを挿入する
            $sql = 'INSERT INTO good (animal_id, user_id, created_date) VALUES (:animal_good_id, :user_id, :date)';
            $stm = $pdo->prepare($sql);
            // クエリ実行
            $result= $stm->execute(array(':animal_good_id' => $animal_good_id, ':user_id' => $user_id, ':date' => date('Y-m-d H:i:s')));
            echo count(getGood($animal_good_id));
        }
    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}