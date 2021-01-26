<?php if(!empty($_GET['act']) && $_GET['act']=='detail') {
$kode = $_GET['kode'];
$query = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE iduser='$iduser' AND kode_penjualan='$kode'") or die(mysql_error());
$data = mysqli_fetch_array($query);

if(!empty($data['idpel'])) {
if($data['idpel'] == 1) {
$pelanggan = "Umum";
} else { 
$qrydtl = mysqli_query($config, "SELECT * FROM tblcustomers WHERE id_customers='{$data['idpel']}'") or die(mysql_error());
$dadtl = mysqli_fetch_array($qrydtl);
$pelanggan = $dadtl[1];
}
} else {
$pelanggan = "Umum";
}

?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a class="btn btn-xs" href="?page=laporan-penjualan<?php echo $linkikutan; ?>"><i class="fa fa-chevron-left"></i> Kembali</a>
                        <div class="pull-right">Detail Laporan Penjualan</div>
                    </div>
                <div class="panel-body"> 
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nama Pelanggan: <?php echo $pelanggan; ?><br>
                                    <?php echo indoDate($data['tgl']); ?></th>
                                <th style="text-align:right">#<?php echo $kode; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; $total = 0;
                            $querydtl = mysqli_query($config, "SELECT * FROM dtlpenjualan WHERE iduser='$iduser' AND kode_penjualan='$kode'") or die(mysql_error());
                            while ($dtl = mysqli_fetch_array($querydtl)) { $total +=$dtl['harga']*$dtl['jumlah']; ?>
                            <tr>
                                <td>
                                    <b><?php echo $dtl['nama_barang']; ?></b><br>
                                    <small>Qty : <?php echo $dtl['jumlah']; ?> x Rp. <?php echo number_format($dtl['harga']);?></small>
                                </td>
                                <td style="text-align:right">
                                    <b>Rp. <?php echo number_format($dtl['harga']*$dtl['jumlah']);?></b><br>
                                </td>
                            </tr>
                            <?php $no++;} ?>
                            <tr>
                                <td><b>TOTAL</b></td>
                                <td style="text-align:right"><b>Rp. <?php echo number_format($total);?></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <a class="btn btn-danger" data-toggle="modal" data-target="#dele<?php echo $kode; ?>"><i class="fa fa-remove"></i> Hapus Transaksi</a>
<div class="modal fade" id="dele<?php echo $kode; ?>" tabindex="-1" role="dialog" aria-labelledby="Hapus" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-group">
				<label>Anda Yakin Ingin Menghapus Transaksi ?</label><br>
				<small>*data yang dihapus tidak dapat dikembalikan lagi</small>
				</div>
				<br><br><br>
				<div class="form-group">
				<a href="?page=laporan-penjualan&act=hapus&kode=<?php echo $data['kode_penjualan'].$linkikutan; ?>" style="margin-left: 0px;" class="btn btn-danger">Hapus</a>
				<button type="submit" style="margin-left: 0px;" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Batal</button>
				</div>
			</div>
		</div>
	</div>
</div>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php
} else if(!empty($_GET['act']) && $_GET['act']=='hapus') {
$kode = $_GET['kode'];
$query = mysqli_query($config, "DELETE FROM tblpenjualan WHERE iduser='$iduser' AND kode_penjualan='$kode'") or die(mysql_error());
if($query == true) {
    echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=laporan-penjualan$linkikutan\">");
}
    
} else {
if(!empty($_POST['darisampai'])) {
    $tgd = explode(" - ", $_POST['darisampai']);
    $dari = date_format(date_create($tgd[0]),"Y-m-d");
    $sampai = date_format(date_create($tgd[1]),"Y-m-d");
}
?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-plus"></i> Laporan Penjualan<br><b><?php if(!empty($_POST['darisampai'])) { echo indoDate($dari)." s/d ".indoDate($sampai); } ?></b>
                    </div>
                <div class="panel-body">
<a class="btn btn-success" href="?page=laporan-penjualan&iduser=<?php echo $iduser; ?>&api=<?php echo $_GET['api']; ?>">Penjualan</a>
<a class="btn btn-success" href="?page=penambahan-stok&iduser=<?php echo $iduser; ?>&api=<?php echo $_GET['api']; ?>">Stok Masuk</a>
<a class="btn btn-success" href="?page=laporan-stok&iduser=<?php echo $iduser; ?>&api=<?php echo $_GET['api']; ?>">Persediaan</a>
<hr>
<form role="form" name="period" action="" method="POST">
<div class="form-group">
<div class="input-group mb-3">
  <input type="text" class="form-control pull-left" name="darisampai" id="daterange-btn" <?php if(!empty($_POST['darisampai'])){ echo 'value="'.$_POST['darisampai'].'"';} ?>placeholder="Dari Tanggal">
  <span class="input-group-btn">
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> Cari</button>
 </span>
</div>
</div>
</form>
                    <table class="table table-striped table-hover">
                        <tbody>
                            <?php $no = 1; $tota = 0;
                            if(!empty($_POST['darisampai'])){ 
                            $query = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE iduser='$iduser' AND tgl BETWEEN '$dari' AND '$sampai' ORDER BY tgl DESC ") or die(mysql_error());
                            } else {
                            $query = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE iduser='$iduser' ORDER BY tgl DESC ") or die(mysql_error());
                            }
                            while ($data = mysqli_fetch_array($query)) {
                            $tota +=$data['total'];
                            ?>
                            <tr>
                                <td>
                                    <b><?php echo indoDate($data['tgl']); ?></b><br>ID Penjualan : <?php echo $data['kode_penjualan']; ?>
                                </td>
                                <td style="text-align:right">
                                    <a style="background-color:#5CB85C;color:#FFFFFF;border-radius: 5px;padding:3px;" href="?page=laporan-penjualan&act=detail&kode=<?php echo $data['kode_penjualan'].$linkikutan; ?>">Detail <i class="icon-search"></i></a><br>
                                    <?php echo number_format($data['total']); ?>
                                </td>
                            </tr>
                            <?php $no++;} ?>
                            <tr>
                                <td>
                                    <h2><b>TOTAL</b></h2>
                                </td>
                                <td style="text-align:right"><h2><b><?php echo number_format($tota); ?></b></h2></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php } ?>