<?php
include('include/config.php');
include('include/function.php');
include('class/class.phpmailer.php');
include('include/header.php');
$subject = "Aktivasi Akun Toko Ritel System";

$emailf = "okkyhernanto2@gmail.com";

$message = "
<html>
<head>
<title>Aktivasi Akun Toko Ritel System</title>
</head>
<body>
<p>Kepada Yth Bapak/Ibu </p>
<p></p>
<p>Assalamu’alaikum warahmatullahi wabarakatuh</p>
<p></p>
<p>Terima Kasih Anda telah melakukan pendaftaran Akun Toko Ritel System dengan detail sebagai berikut  :</p>
<p>ID Akun : </p>
<p>Nama Akun : </p>
<p>Password : </p>
<p></p>
<p>Jangan lupa untuk melakukan aktivasi akun melalui link berikut :</p>
<p></p>
<p>Demikian informasi dari kami. Terima kasih telah bergabung bersama layanan jasa kami.</p>
<p></p>
<p>Terima Kasih,</p>
</body>
</html>
";

$mail = new PHPMailer; 
$mail->IsSMTP();
$mail->SMTPSecure = 'ssl'; 
$mail->Host = "aplikasiku.co.id";//"smtp.gmail.com"; //host masing2 provider email
//$mail->SMTPDebug = 2;
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->Username = "info@aplikasiku.co.id";//"noreply@aplikasiku.co.id"; //user email
$mail->Password = "En+AN=p6kP4i";//"Vb*aZ)3!NZF^"; //password email 
$mail->SetFrom("info@aplikasiku.co.id","Aplikasiku Mail System"); //set email pengirim
$mail->Subject = "Aktivasi Akun Toko Ritel System"; //subyek email
$mail->AddAddress($emailf);  //tujuan email
$mail->MsgHTML($message);

if($mail->Send()) {
 echo "terkirim";   
}

?>