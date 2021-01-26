<?php
include('include/config.php');
include('include/function.php');
include('class/class.phpmailer.php');
include('include/header.php');

if(!empty($_POST['regis'])) {
        $iduser = (strtotime("now"));
        $perusahaan = mysqli_real_escape_string($config, $_POST['namausaha']);
        $fullname = mysqli_real_escape_string($config, $_POST['fullname']);
        $email = mysqli_real_escape_string($config, $_POST['email']);
        $notelp = $_POST['notelp'];
        $alamat = mysqli_real_escape_string($config, $_POST['alamat']);
        $provinsi = $_POST['provinsi'];
        $kota = $_POST['kota'];
        $plan = $_POST['paket'];
        
        if($plan == 2) {
            $ketpaket = "paket Bundling sebesar Rp. 1.200.000.";
            $status = 0;
        } else if($plan == 12) {
            $ketpaket = "paket 1 Tahun sebesar Rp. 899.900.";
            $status = 0;
        } else if($plan == 3) {
            $ketpaket = "paket 3 Bulan sebesar Rp. 269.700.";
            $status = 0;
        } else if($plan == 1) {
            $ketpaket = "paket free 14 hari.";
            $status = 1;
        } 
        
        $usernama = $_POST['usernama'];
        $sandikata = md5($_POST['sandikata']);
        $pesan = "Terima Kasih Atas Kunjungannya";
        $tgl_reg = date("Y-m-d H:i:s");
        $reg1 = mysqli_query($config, "INSERT INTO tab_user (id, fullname, username, password, email, no_tlp, provi, kotab, alamat, status, plan, tgl_reg) VALUES ('$iduser','$fullname','$usernama','$sandikata','$email','$notelp','$provinsi','$kota','$alamat','$status','$plan','$tgl_reg')") or die(mysqli_error());
        $reg2 = mysqli_query($config, "INSERT INTO setting (iduser, perusahaan, alamat, tlp, namap, pesan) VALUES ('$iduser','$perusahaan','$alamat','$notelp','$fullname','$pesan')") or die(mysqli_error());
    if($reg1 == true && $reg2 == true) {
$emailf = $email;
$subject = "Aktivasi Akun Toko Ritel System";
$message = "
<html>
<head>
<title>Aktivasi Akun Toko Ritel System</title>
</head>
<body>
<p>Kepada Yth Bapak/Ibu ".$fullname."</p>
<p></p>
<p>Assalamu’alaikum warahmatullahi wabarakatuh</p>
<p></p>
<p>Terima Kasih Anda telah melakukan pendaftaran Akun Toko Ritel System dengan detail sebagai berikut  :</p>
<p>ID Akun : ".$iduser."</p>
<p>Nama Akun : ".$_POST['usernama']."</p>
<p>Password : ".$_POST['sandikata']."</p>
<p></p>
<p>Jangan lupa untuk melakukan aktivasi akun melalui link berikut :</p>
<p><a href='https://aplikasiku.co.id/ritel/index.php?activation=".$iduser."&token=".$sandikata."'>Konfirmasi Aktivasi Akun Toko Ritel System</a></p>
<p></p>
<p>Demikian informasi dari kami. Terima kasih telah bergabung bersama layanan jasa kami.</p>
<p></p>
<p>Terima Kasih,</p>
</body>
</html>
";

$watsapmsg = "
Terimakasih atas pemesanan layanan Aplikasiku.co.id dengan pilihan ".$ketpaket." Untuk selanjutnya pembayaran dapat melalui rekening berikut :

BCA
an Yose Sano Hendarsyah
No Rek : 0770558964

Mandiri
an Yose Sano Hendarsyah
No Rek : 1380004673922

BRI
an Yose Sano Hendarsyah
No Rek : 688401010988533

BTN
an Yose Sano Hendarsyah
No Rek : 7072090685

Mohon untuk segera melakukan konfirmasi pembayaran melalui nomor whattsapp ini dengan melampirkan bukti pembayaran.
Terimakasih...
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
    
    echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=pendaftaran.php?paket=1&reg=berhasil\">");
    
    // echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=https://api.whatsapp.com/send?phone=6281915301662&text=".rawurlencode($watsapmsg)."\">"); 
}

    }
}
?>
<?php if(!empty($_GET['paket'])) {
if($_GET['paket'] == 1) {
    $pakete = "Paket Free 14 Hari";
} else {
    $pakete = "Paket Bundling";
}
?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-cogs"></i> Pendaftaran <?php echo $pakete; ?>
                    </div>
                <div class="panel-body">
                <?php if (!empty($_GET['reg']) && $_GET['reg'] == 'berhasil') { ?>
<div class="alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<strong>Pendaftaran berhasil silahkan buka email Anda pada Kotak Masuk / Spam untuk mengaktifkan akun Anda!</strong>
</div>
<?php } ?>
                    <form class="center" method="POST" action="">
            <fieldset class="daftar-form">
                <div class="form-group">
                    <input type="text" name="namausaha" placeholder="Nama Usaha" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="text" name="fullname" placeholder="Nama Pemilik Usaha" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="text" name="email" placeholder="Alamat Email" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="text" name="notelp" placeholder="Nomor Telepon" class="form-control" required>
                </div>
                
                <div class="form-group">
				                                
				                                    
<select class="form-control" name="provinsi" id="provinsi" required="required">
<option selected disabled>-Pilih Provinsi-</option>
<?php $query = mysqli_query($config, "SELECT * FROM provinces");
while ($data = mysqli_fetch_array($query)) { ?>
<option value="<?php echo $data['id'];?>"><?php echo $data['name'];?></option>
<?php } ?>
						</select>
				                                
				                            </div>
<div class="form-group">
<select name="kota" id="kota" class="form-control" required="required">
<option selected disabled>-Pilih Kota-</option>
</select>
				                                
				                            </div>
				    <div class="form-group">
                    <input type="text" name="alamat" placeholder="Alamat Usaha" class="form-control" required>
                </div>      
                <?php if(!empty($_GET['paket']) && $_GET['paket'] == 1) { ?>
<input type="hidden" name="paket" value="1" class="form-control" required>
<?php } else { ?>
                <div class="form-group">
<select name="paket" id="paket" class="form-control" required="required">
<option value="2">-Pilih Paket-</option>
<option value="2">Paket Bundling - 1.200.000</option>
<option value="12">Paket 1 Tahun - 899.900</option>
<option value="3">Paket 3 Bulan - 269.700</option>
<!--<option value="6">Paket 6 Bulan - 539.400</option>
<option value="9">Paket 9 Bulan - 809.100</option>
<option value="12">Paket 1 Tahun - 1.078.000</option>-->

</select>
      </div>
<?php } ?>
                <div class="form-group">
                    <input type="text" id="namauser" name="usernama" placeholder="Username" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="password" id="katasandi" name="sandikata" placeholder="Password" class="form-control" required>
                </div>
                <div class="form-group">
                    <button  type="submit" name="regis" value="Sign Up" class="btn btn-success btn-md btn-block">Sign Up</button>
                </div>
                

            </fieldset>
        </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<script type="text/javascript" src="js/jquery-tklks.js"></script>
<script type="text/javascript">
var htmlobjek;
$(document).ready(function(){
  //apabila terjadi event onchange terhadap object <select id=provinsi>
  $("#provinsi").change(function(){
    var provinsi = $("#provinsi").val();
    $.ajax({
        url: "ambilkota.php",
        data: "provinsi="+provinsi,
        cache: false,
        success: function(msg){
            //jika data sukses diambil dari server kita tampilkan
            //di <select id=kota>
            $("#kota").html(msg);
        }
    });
  });

});

</script>
<?php } else { ?>
<section>
    <div style="text-align: center"><img src="images/apli-logo.png" alt="logo"></div>
        <form class="center" method="" action="">
            <fieldset class="registration-form">
                <div class="form-group">
<a href="?paket=1" class="btn btn-success btn-circle"><i class="fa fa-file icon-lg"></i><br>Paket Free 14 Hari</a>
<a href="?paket=2" class="btn btn-primary btn-circle"><i class="fa fa-book icon-lg"></i><br>Paket Bundling</a>
                </div>
            </fieldset>
        </form>
</section><!--/#registration-->
<?php }
include('include/footer.php');
?>