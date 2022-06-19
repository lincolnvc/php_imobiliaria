<?php
require_once HELPERDIR . "phpmailer/class.phpmailer.php";
global $mail;
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "tls";
$mail->WordWrap = 80;
$mail->IsHTML( true );
?>