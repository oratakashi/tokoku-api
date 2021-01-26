<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-plus"></i> Laporan Stok
                    </div>
                <div class="panel-body">
<a class="btn btn-success" href="?page=laporan-penjualan&iduser=<?php echo $iduser; ?>&api=<?php echo $_GET['api']; ?>">Penjualan</a>
<a class="btn btn-success" href="?page=penambahan-stok&iduser=<?php echo $iduser; ?>&api=<?php echo $_GET['api']; ?>">Stok Masuk</a>
<a class="btn btn-success" href="?page=laporan-stok&iduser=<?php echo $iduser; ?>&api=<?php echo $_GET['api']; ?>">Persediaan</a>
<hr>
<form role="form" name="period" action="" method="POST">
<div class="form-group">
<div class="input-group mb-3">
  <input type="text" class="form-control pull-left" name="cari" <?php if(!empty($_POST['cari'])){ echo 'value="'.$_POST['cari'].'"';} ?>  placeholder="Cari">
  <span class="input-group-btn">
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> Cari</button>
 </span>
</div>
</div>
</form>
                                <table class="table table-striped table-hover" >
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Barang</th>
                                            <th colspan="2" style="text-align:center">Jumlah</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
$dataPerPage=10;
if(isset($_POST['nom']))
{
    $noPage = $_POST['nom'];
} 
else $noPage = 1;
$offset = ($noPage - 1) * $dataPerPage;
if (!empty($_POST['cari'])) {
$carinama = mysqli_query($config, "SELECT * FROM stok_bahan WHERE nama_bahan LIKE '%{$_POST['cari']}%' AND iduser='{$iduser}' ORDER BY nama_bahan ASC");
$temunama=mysqli_fetch_assoc($carinama);

$caribrcode = mysqli_query($config, "SELECT * FROM stok_bahan WHERE brcode = '{$_POST['cari']}' AND iduser='{$iduser}' ORDER BY nama_bahan ASC");
$temubrcode=mysqli_fetch_assoc($caribrcode);

if ($temunama) {
$query = mysqli_query($config, "SELECT * FROM stok_bahan WHERE nama_bahan LIKE '%{$_POST['cari']}%' AND iduser='{$iduser}' ORDER BY nama_bahan ASC");
} else if ($temubrcode){
$query = mysqli_query($config, "SELECT * FROM stok_bahan WHERE brcode = '{$_POST['cari']}' AND iduser='{$iduser}' ORDER BY nama_bahan ASC");
}
} else {
$query = mysqli_query($config, "SELECT * FROM stok_bahan WHERE iduser='{$iduser}' ORDER BY nama_bahan ASC");
}
//$query = mysqli_query($config, "SELECT * FROM stok_bahan ORDER BY nama_bahan ASC LIMIT $offset, $dataPerPage");
$no = 1; $sumjum=0;
while ($data = mysqli_fetch_array($query)) {
    
$qrytambahstok = mysqli_query($config, "SELECT SUM(jumlah) AS totalpls FROM dtltambah_stok WHERE id_bahan='{$data['id_bahan']}'") or die(mysql_error());
$dplus = mysqli_fetch_array($qrytambahstok);
$totalpls = $dplus['totalpls'];

$qryjuala = mysqli_query($config, "SELECT SUM(jumlah) AS totalmns FROM dtlpenjualan WHERE idbarang='{$data['id_bahan']}'") or die(mysql_error());
$dminus = mysqli_fetch_array($qryjuala);
$totalmns = $dminus['totalmns'];

$stokdin = $totalpls-$totalmns;    
    
    
    $atel = $stokdin*$data['harga_per'];
    $sumjum += $atel;
?>
    	<tr>
        	<td class="numeric"><?php echo $no; ?></td>
			<td>
			    <?php echo $data['nama_bahan']; ?><br>
                <b>KODE : <small><?php echo $data['brcode']; ?></small></b>
            </td>
            <td style="text-align:right;"><b><?php echo $stokdin; ?></b></td>
            <td style="text-align:right;">
                <b>Rp. <?php echo number_format($data['harga_per']); ?></b><br>Rp. <?php echo number_format($stokdin*$data['harga_per']); ?>
            </td>

        </tr>
    <?php 
		$no++;
	} 
	?>    
	<tr>
                                <td colspan="2">
                                    <h2><b>TOTAL</b></h2>
                                </td>
                                <td colspan="2" style="text-align:right"><h2><b><?php echo number_format($sumjum); ?></b></h2></td>
                            </tr>
                                    </tbody>
                                </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>