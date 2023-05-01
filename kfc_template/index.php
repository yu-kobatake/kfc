<?php
// titleで読み込むページ名
$pagetitle = "ここはタイトル"
?>
<?php include('parts/header.php'); ?>
<div id="container">
  <main>
    <!-- message_add.phpにPOSTするフォーム -->
    　<form method="post" action="./message_add.php">
      <textarea class="textarea form-control" placeholder="メッセージを入力ください" name="text"></textarea>
      <input type="hidden" name="destination_user_id" value="<?= $destination_user['user_id'] ?>">
      <div class="message_btn">
        <div class="message_image">
          <!-- <input type="file" name="image" class="my_image" accept="image/*" multiple> -->
        </div>
        <button class="btn btn-outline-primary" type="submit" name="post" value="post" id="post">投稿</button>
      </div>
      　
    </form>

  </main>
</div>

<?php include('parts/footer.php'); ?>