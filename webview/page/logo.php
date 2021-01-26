<?php
$query = mysqli_query($config, "SELECT * FROM setting JOIN tab_user ON setting.iduser=tab_user.id WHERE setting.iduser='$iduser'") or die(mysql_error());
$data = mysqli_fetch_array($query);
if(!empty($_POST['logoin'])) {
    
    $imgname = $_FILES['userlogo']['name'];
    $imgsize = $_FILES['userlogo']['size'];
    $imgtype = $_FILES['userlogo']['type'];
    $imgerro = $_FILES['userlogo']['error'];
    
    if($imgname !== '') {
        if($imgsize < 400000) { 
            $path = $_FILES['userlogo']['tmp_name'];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
            $image_info = getimagesize($_FILES['userlogo']['tmp_name']);
            $image_width = $image_info[0];
            $image_height = $image_info[1];
            
            //$ekstensi = array('jpg','png','jpeg','gif','JPG','PNG','JPEG','GIF');
            $ekstensi = array('.png','.PNG');
            $eks = image_type_to_extension($image_info[2]);
            
            if(in_array($eks, $ekstensi) == true) {
                if(!empty($image_width) && !empty($image_height)) {
                    
                    if($image_width < 420 ) {
                        
                   // echo $imgname."<br>".$imgsize."<br>".$imgtype."<br>".$imgerro."<br>".$path."<br>".$image_width."<br>".$image_height."<br>".$eks;
                    
                    define('UPLOAD_DIR', '../logo/');
                    $image_parts = explode(";base64,", $base64);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $nfilename =  uniqid() . '.png';
                    $file = UPLOAD_DIR.$nfilename;
                    file_put_contents($file, $image_base64);
                    
                    $updatesetting = mysqli_query($config, "UPDATE setting SET logo='logo/$nfilename' WHERE iduser='$iduser'") or die(mysql_error());
                        if($updatesetting == true) {
                            echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=setting&success=yes$linkikutan\">");
                        } else {
                            echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=logo&error=no$linkikutan\">");
                        }
                    } else if($image_width < 80 ) {
                        echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=logo&error=width$linkikutan\">");
                    } else {
                        echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=logo&error=width$linkikutan\">");
                    }
                } else {
                echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=logo&error=valid$linkikutan\">");
                }
            } else {
                echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=logo&error=type$linkikutan\">");
            }
            
        } else if($imgsize < 1000){ 
            echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=logo&error=minsize$linkikutan\">");
        } else {
            echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=logo&error=maxsize$linkikutan\">");
        }
    } else {
        echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=logo&error=nofile$linkikutan\">");
    }
    
    
    // define('UPLOAD_DIR', 'images/');
    // $image_parts = explode(";base64,", $base64);
    // $image_type_aux = explode("image/", $image_parts[0]);
    // $image_type = $image_type_aux[1];
    // $image_base64 = base64_decode($image_parts[1]);
    // $file = UPLOAD_DIR . uniqid() . '.png';
    // file_put_contents($file, $image_base64);
    
    
}
?>



<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-cogs"></i> Logo
                    </div>
                <div class="panel-body">
<?php if(!empty($_GET['error'])) {
$error = $_GET['error'];
switch ($error) {
    case "nofile":
        $pesan = "File Gambar png Tidak disertakan";
        break;
    case "minsize":
        $pesan = "File Gambar png terlalu kecil";
        break;
    case "maxsize":
        $pesan = "File Gambar png terlalu besar";
        break;
    case "type":
        $pesan = "File Gambar bukan png";
        break;
    case "valid":
        $pesan = "File Gambar png rusak";
        break;
    case "width":
        $pesan = "ukuran File Gambar png harus 386px";
        break;
    default:
        $pesan = "Kesalahan input data";
}
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <?php echo $pesan; ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php } ?>


                    <div class="form-group text-center">
                        <a class="btn btn-default"><img src="../<?php echo $data['logo']; ?>" width="386" height="91" alt="logo"></a>
                        <p>Ukuran Gambar 386 X 91 </p>
                    </div>
                    <form role="form" name="setting" action="" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <input name="userlogo" type="file" class="inputFile">
                        </div>
                        <div class="form-group">
                            <a class="btn btn-danger" href="?page=setting<?php echo $linkikutan ?>">Batal</a>
                            <input type="submit" name="logoin" value="Submit" class="btn btn-primary">
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>