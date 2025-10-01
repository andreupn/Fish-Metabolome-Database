<?php
include "classes/class.phpmailer.php";
$mail = new PHPMailer;

$mail->IsSMTP();

$mail->SMTPSecure = 'ssl';

$mail->Host = "mail.fmdb.info"; //hostname masing-masing provider email
$mail->SMTPDebug = 0;
$mail->Port = 465;
$mail->SMTPAuth = true;

$mail->Timeout = 60; // timeout pengiriman (dalam detik)
$mail->SMTPKeepAlive = true;

$mail->Username = "admin@fmdb.info"; //user email
$mail->Password = "M5Rc9EfGgaKYJzd"; //password email
$mail->SetFrom("$_POST[email]","$_POST[name]"); //set email pengirim
$mail->Subject = "$_POST[subject]"; //subyek email
$mail->AddAddress("admin@fmdb.info","Website Admininistrator"); //tujuan email
$mail->MsgHTML("$_POST[message]");

if($mail->Send()) echo "Message has been sent";
//                  echo "<script>window.location='Contact.php';</script>";
else echo "Failed to sending message";
  //    echo "<script>window.location='contact.php';</script>";
?>
