<?php if(!empty($_GET['act']) && $_GET['act'] == 'add') {
$kode = $_GET['kode'];
$tabjur = mysqli_query($config, "SELECT * FROM tbltransaksi WHERE kode_tran='$kode'") or die(mysql_error());
$dtjur=mysqli_fetch_array($tabjur);

if(!empty($_POST['tambahkan'])) {
    $kop = explode("|", $_POST['nama_tran']);
    $id_tran = $kop[0];
    $nama_tran = $kop[1];
    $ctg = $kop[2];
    $nominal = $_POST['nominal'];
    $kode = $_POST['kode'];
    
    $tambahkan = mysqli_query($config, "INSERT INTO dtltransaksi(kode_tran,idtran,iduser,nama_tran,ctg,nominal,tgl) VALUES('$kode','$id_tran','$iduser','$nama_tran','$ctg','$nominal',NOW())") or die(mysql_error());

    if($tambahkan == true) {
        echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=jurnal-kas&act=add&kode=$kode$linkikutan\">");
    }
}

if(!empty($_POST['selesai'])) {
    $kode = $_POST['kode'];
    $total = $_POST['total'];
    
    $selesai = mysqli_query($config, "UPDATE tbltransaksi SET total='$total' WHERE kode_tran='$kode' AND iduser='$iduser'") or die(mysql_error());
    if($selesai == true) {
        echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=jurnal-kas$linkikutan\">");
    }
}

?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-plus"></i> Jurnal Kas
                    </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th colspan="2">No.Transaksi</th> 
                                </tr>
                            </thead>
                            <tbody>				  
                                <tr>
                                    <td><?php echo $dtjur['tgl'] ; ?></td>
                                    <td colspan="2"><?php echo $kode; ?></td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th>Nama Transaksi</th>
                                    <th>Nominal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <form action="" method="POST">
                                <?php $kd = $kode; $total = 0;
                                $s = mysqli_query($config, "SELECT * FROM dtltransaksi WHERE kode_tran='$kd' AND iduser='$iduser'") or die(mysql_error());
                                while($sql = mysqli_fetch_array($s)){
                                $subt=$sql['nominal'];
                                $total+=$subt; ?>
                                <tr>
                                    <td><?php echo $sql['nama_tran']; ?></td>
                                    <td>Rp. <?php echo number_format($subt); ?></td>
                                    <td>
                                        <a class="btn btn-danger btn-xs" href="?page=jurnal-kas&act=deldtl&kode=<?php echo $kode; ?>&id=<?php echo $sql['idtran'].$linkikutan; ?>">Hapus <i class="icon-trash "></i></a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td>
                                        <?php $select = mysqli_query($config, "SELECT * FROM tbltranslain WHERE iduser='$iduser'") or die(mysql_error()); ?>
                                        <select class="form-control" name="nama_tran" data-rel="chosen" required="required">
                                            <option value="0">-Pilih Transaksi-</option>
                                            <?php while ($bar=mysqli_fetch_array($select)) { ?>
                                            <option value="<?php echo $bar['id_trnlain'] ?>|<?php echo $bar['keterangan'];?>|<?php echo $bar['kategori'];?>"><?php echo $bar['keterangan'];?></option>
                                        <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="nominal" Placeholder="Masukkan Nominal" required>
                                    </td>
                                    <td>
                                        <input type="hidden" name="kode" value="<?php echo $kd; ?>">
                                        <button type="submit" name="tambahkan" value="Tambahkan" class="btn btn-success">Tambahkan <i class="icon-plus-sign"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><h2>TOTAL</h2></td>
                                    <td colspan="2"><h2>Rp. <?php echo number_format($total); ?></h2></td>
                                </tr>
                            </form>
                            </tbody>
                        </table>
                        <form action="" method="POST">
                        <input type="hidden" name="kode" value="<?php echo $kd; ?>">
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        <button type="submit" name="selesai" value="Selesai" class="btn btn-primary">Selesai</button>
                        <a class="btn btn-danger" href="?page=jurnal-kas&act=batal&kode=<?php echo $kode.$linkikutan; ?>">Batal</a>
                        </form>				
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php } else if(!empty($_GET['act']) && $_GET['act'] == 'deldtl') {
    $kode = $_GET['kode'];
    $id = $_GET['id'];
    
    $deldtl = mysqli_query($config, "DELETE FROM dtltransaksi WHERE kode_tran='$kode' AND idtran='$id'") or die(mysql_error());
    if($deldtl == true) {
        echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=jurnal-kas&act=add&kode=$kode$linkikutan\">");
    }
    
} else if(!empty($_GET['act']) && $_GET['act'] == 'batal') {
    $kode = $_GET['kode'];
    
    $deldtl = mysqli_query($config, "DELETE FROM dtltransaksi WHERE kode_tran='$kode'") or die(mysql_error());
    $batal = mysqli_query($config, "DELETE FROM tbltransaksi WHERE kode_tran='$kode'") or die(mysql_error());
    
    if($deldtl == true && $batal == true) {
        echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=jurnal-kas$linkikutan\">");
    }
    
} else {
$today = date("Ymd");
$hasil = mysqli_query($config, "SELECT max(kode_tran) AS last FROM tbltransaksi WHERE kode_tran LIKE 'TRL$today%'") or die(mysql_error());
$data  = mysqli_fetch_array($hasil);
$lastNosupplier = $data['last'];
$lastNoUrut = substr($lastNosupplier, 11, 15);
$b    = $lastNoUrut + 1;
$char = "TRL";
$nou  = $char.$today.sprintf("%04s", $b);

if(!empty($_POST['addjurnal'])) {
    $kode = $_POST['kode_tran'];
    $addjurnal = mysqli_query($config, "INSERT INTO tbltransaksi (kode_tran,iduser,tgl) values ('$kode','$iduser',NOW())") or die(mysql_error());
    if($addjurnal == true) {
        echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=jurnal-kas&act=add&kode=$kode$linkikutan\">");
    }
    
}
?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-plus"></i> Jurnal Kas
                    </div>
                <div class="panel-body">
                    <form role="form" name="input-transaksi" action="" method="POST">
                        <div class="form-group">
                            <label for="Transaksi">Nomor Transaksi</label>
                            <input type="text" class="form-control" name="kode_tran" value="<?php echo $nou; ?>" readonly>
                        </div>
                        <button type="submit" name="addjurnal" value="Submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php } ?>