<?php
if(!empty($_POST['step']) && $_POST['step']=='2') {
$idbarang = $_POST['id_barang'];
$se = mysqli_query($config, "SELECT * FROM stok_bahan WHERE id_bahan='$idbarang'");
$pi = mysqli_fetch_array($se);
$namabarang = $pi['nama_bahan'];
$satlama = $pi['satuan'];
?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <i class="icon-tags"></i> Konversi
                    </div>
                    <div class="panel-body">
<form role="form" action="" method="POST">
<div class="form-group">
    <label for="Nama Barang">Nama Barang</label>
    <input type="text" class="form-control" name="namabarang" value="<?php echo $namabarang;?>" readonly>
<input type="hidden" class="form-control" name="idbarang" value="<?php echo $idbarang;?>">
</div>
<div class="form-group">
    <label for="Nama Barang">Barcode</label>
    <input type="text" class="form-control" name="barcode">
</div>
<div class="form-group">
    <label for="namabaru">Nama Baru</label>
    <input type="text" class="form-control" name="namabaru">
</div>
<div class="form-group">
    <label for="JMLa">Jumlah <?php echo $satlama;?></label>
    <input type="text" class="form-control" name="jmla">
</div>
<div class="form-group">
    <label for="JMLb">Jumlah Baru</label>
    <input type="text" class="form-control" name="jmlb">
</div>
<div class="form-group">
    <label for="Satbaru">Satuan Baru</label>
    <input type="text" class="form-control" name="satbaru">
</div>
        <button type="button" class="btn btn-danger" onclick="history.back();"><i class="icon-arrow-left"></i> Back</button>
        <button type="submit" name="step" value="3" class="btn btn-primary">OK <i class="icon-arrow-right"></i></button>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php
} else if(!empty($_POST['step']) && $_POST['step']=='3') {
$idbarang = $_POST['idbarang'];
$namabaru = $_POST['namabaru'];
$barcode = $_POST['barcode'];
$jmla = $_POST['jmla'];
$jmlb = $_POST['jmlb'];
$satbaru = $_POST['satbaru'];
$se = mysqli_query($config, "SELECT * FROM stok_bahan WHERE id_bahan='$idbarang'");
$pi = mysqli_fetch_array($se);
$namabarang = $pi['nama_bahan'];
$satlama = $pi['satuan'];
$harga_per =$pi['harga_per'];
$hpp = ($harga_per*$jmla)/$jmlb;
?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <i class="icon-tags"></i> Konversi
                    </div>
                    <div class="panel-body">
<table class="table table-bordered table-striped table-condensed">		
<form role="form" action="" method="POST">
<tr><td colspan="3"><b>Barcode<b></td><td style="text-align: right;"><b><?php echo $barcode; ?><b></td></tr>
<tr><td colspan="3"><b>Nama Baru<b></td><td style="text-align: right;"><b><?php echo $namabaru; ?><b></td></tr>
<tr><td colspan="3"><b>Jumlah A<b></td><td style="text-align: right;"><b><?php echo $jmla;?> <?php echo $satlama;?><b></td></tr>
<tr><td colspan="3"><b>Jumlah B<b></td><td style="text-align: right;"><b><?php echo $jmlb;?> <?php echo $satbaru;?><b></td></tr>
<tr><td colspan="3"><b>HPP<b></td><td style="text-align: right;"><b>Rp. <?php echo number_format($hpp); ?><b></td></tr>
<tr><td colspan="3"><b>Harga Jual<b></td><td style="text-align: right;"><input type="text" class="form-control" name="hjual"></td></tr>
<input type="hidden" value="<?php echo $idbarang; ?>" name="idbarang">
<input type="hidden" value="<?php echo $barcode; ?>" name="barcode">
<input type="hidden" value="<?php echo $namabaru; ?>" name="namabaru">
<input type="hidden" value="<?php echo $jmla; ?>" name="jmla">
<input type="hidden" value="<?php echo $jmlb; ?>" name="jmlb">
<input type="hidden" value="<?php echo $hpp; ?>" name="hpp">
<input type="hidden" value="<?php echo $satbaru; ?>" name="satbaru">
</table>
<a href="?page=konversi<?php echo $linkikutan; ?>" class="btn btn-danger"><i class="icon-arrow-left"></i> Batal</a>
<button type="submit" name="step" value="4" class="btn btn-primary">
								Selesai <i class="icon-arrow-right"></i>
								</button>
							</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php
} else if(!empty($_POST['step']) && $_POST['step']=='4') {
$idbarang = $_POST['idbarang'];
$namabaru = $_POST['namabaru'];
$brcode = $_POST['barcode'];
$jmla = $_POST['jmla'];
$jmlb = $_POST['jmlb'];
$hpp = $_POST['hpp'];
$hjual = $_POST['hjual'];
$satbaru = $_POST['satbaru'];
$se = mysqli_query($config, "select * from stok_bahan where id_bahan='{$idbarang}'");
$pi = mysqli_fetch_array($se);
$jumlama = $pi['jumlah'];
$hperl = $pi['harga_per'];
$jumbaru = $jumlama-($jmla*$jmlb);
$totl = $jmlb*$hpp;
$totbr = $jumbaru*$hperl;

$query1 = mysqli_query($config, "update stok_bahan set jumlah='$jumbaru', total='$totbr' where id_bahan='$idbarang'") or die(mysqli_error());

$cari=mysqli_query($config, "SELECT * FROM stok_bahan WHERE brcode='$brcode'");
$temu=mysqli_fetch_assoc($cari);

if ($temu) {
$ste = mysqli_query($config, "select * from stok_bahan where brcode='$brcode'");
$pti = mysqli_fetch_array($ste);
$jumtbaru = $pti['jumlah']+$jmlb;
$hrgbr=$hpp;
$totalbrr=$hrgbr*$jumtbaru;
$query2 = mysqli_query($config, "UPDATE stok_bahan SET jumlah='$jumtbaru', total='$totalbrr' where brcode='$brcode'") or die(mysqli_error());
} else {
$query2 = mysqli_query($config, "INSERT INTO stok_bahan(iduser, brcode, nama_bahan, jumlah, satuan, harga_per, total, hargaj) VALUES('$iduser', '$brcode', '$namabaru', '$jmlb', '$satbaru', '$hpp', '$totl', '$hjual')") or die(mysqli_error());
}
if ($query1 && $query2) { echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=index.php?page=konversi$linkikutan&success=yes\">");
}
} else { ?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <i class="icon-tags"></i> Konversi
                    </div>
                    <div class="panel-body">
                    
<form role="form" action="" method="POST">
<div class="form-group">
<?php $select = mysqli_query($config, "SELECT * FROM stok_bahan WHERE iduser='$iduser'"); ?>
<select class="form-control chzn-select" tabindex="2"  name="id_barang" data-rel="chosen" required="required">
<option value="0">-Pilih Barang-</option>
<?php while ($bar=mysqli_fetch_array($select)) { ?>
<option value="<?php echo $bar['id_bahan'];?>"><?php echo $bar['nama_bahan'];?></option>
<?php } ?>
</select>
</div>
<button type="submit" name="step" value="2" class="btn btn-primary">OK <i class="icon-share-alt"></i></button>
</form>
                    
                    
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php } ?>