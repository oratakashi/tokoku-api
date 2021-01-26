<?php
$query = mysqli_query($config, "SELECT * FROM setting JOIN tab_user ON setting.iduser=tab_user.id WHERE setting.iduser='$iduser'") or die(mysql_error());
$data = mysqli_fetch_array($query);

if(!empty($_POST['pengaturan'])) {
$perusahaan= $_POST['perusahaan'];
$alamat = $_POST['alamat'];
$namap = $_POST['namap'];
$email = $_POST['email'];
$tlp = $_POST['tlp'];
$tempo = $_POST['tempo'];
$pesan = $_POST['pesan'];

if(!empty($_POST['password'])) {

$password = trim(htmlspecialchars(mysqli_real_escape_string($config, $_POST['password'])));
$crpass = md5($password);

$updatesetting = mysqli_query($config, "UPDATE setting SET perusahaan='$perusahaan', alamat='$alamat', tlp='$tlp', namap='$namap', pesan='$pesan', jatuhtempo='$tempo' WHERE iduser='$iduser'") or die(mysql_error());
$updateuser = mysqli_query($config, "UPDATE tab_user SET password='$crpass', fullname='$namap', email='$email', no_tlp='$tlp', alamat='$alamat' WHERE id='$iduser'") or die(mysql_error());
if ($updatesetting == true && $updateuser == true) { echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=setting$linkikutan\">"); }	
} else {
$updatesetting = mysqli_query($config, "UPDATE setting SET perusahaan='$perusahaan', alamat='$alamat', tlp='$tlp', namap='$namap', pesan='$pesan', jatuhtempo='$tempo' WHERE iduser='$iduser'") or die(mysql_error());
$updateuser = mysqli_query($config, "UPDATE tab_user SET fullname='$namap', email='$email', no_tlp='$tlp', alamat='$alamat' WHERE id='$iduser'") or die(mysql_error());
if ($updatesetting == true && $updateuser == true) { echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=setting$linkikutan\">"); }	
}
}

?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-cogs"></i> Pengaturan
                    </div>
                <div class="panel-body">
                    <div class="form-group text-center">
                        <a href="?page=logo<?php echo $linkikutan;?>" class="btn btn-default"><img src="../<?php echo $data['logo']; ?>" width="386" alt="logo"></a>
                    </div>
                    <form role="form" name="setting" action="" method="POST">
                        <div class="form-group">
                        <label for="Perusahaan">Nama Perusahaan</label>
                        <input type="text" class="form-control" name="perusahaan" value="<?php echo $data['perusahaan']; ?>" >
                    </div>
					<div class="form-group">
                        <label for="Alamat">Alamat</label>
                        <textarea id="autosize" name="alamat" class="form-control"><?php echo $data['alamat']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="Pemilik">Fullname</label>
                        <input type="text" class="form-control" name="namap" value="<?php echo $data['namap']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="Pengguna">Username</label>
                        <input type="text" class="form-control" value="<?php echo $data['username']; ?>" readonly/>
                    </div>
                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin diganti">
                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $data['email']; ?>" />
                    </div>
					<div class="form-group">
                        <label for="NoTelp">No.Telp</label>
                        <input type="text" class="form-control" name="tlp" value="<?php echo $data['tlp']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="Jatem">Jatuh Tempo</label>
                        <input type="text" class="form-control" name="tempo" value="<?php echo $data['jatuhtempo']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="Pesan">Pesan</label>
						<textarea class="form-control" name="pesan" col="5"><?php echo $data['pesan']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="pengaturan" value="OK" class="btn btn-primary">Update</button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>