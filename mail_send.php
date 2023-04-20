<?php
// if(!isset($_POST['email']) ||!isset($_POST['title'])||!isset($_POST['message'])):
// if()


$email = $_POST['email'];
$title = $_POST['title'];
$message = $_POST['message'];


mb_language("japanese");
mb_internal_encoding("utf-8");

if(mb_send_mail("{$email}",
"{$title}",
"{$message}",
"From:".mb_encode_mimeheader("名無し").
"<mochiyutotoka@gmail.com>"
)):
print "送信成功";
else:
    print "送信失敗";
endif;


?>