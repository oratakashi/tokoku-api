<?php $today= date("Y-m-d");
if (!empty($_POST['darisampai'])){
$tgd = explode(" - ", $_POST['darisampai']);
    $tgla = date_format(date_create($tgd[0]),"Y-m-d");
    $tglb = date_format(date_create($tgd[1]),"Y-m-d");

$qryjual=mysqli_query($config, "SELECT * ,SUM(jumlah)jumlah FROM dtlpenjualan WHERE tgl BETWEEN '$tgla' AND '$tglb' AND iduser='$iduser' GROUP BY nama_barang");

$qryhpp=mysqli_query($config, "SELECT * ,SUM(jumlah)jumlah FROM dtlpenjualan WHERE tgl BETWEEN '$tgla' AND '$tglb' AND iduser='$iduser' GROUP BY nama_barang");

$qrtln=mysqli_query($config, "SELECT * ,SUM(nominal)nominal FROM dtltransaksi WHERE ctg NOT IN ('kt1', 'kt2' , 'kt4', 'rtr') AND tgl BETWEEN '$tgla' AND '$tglb' AND iduser='$iduser' GROUP BY nama_tran");

$qrtlnop=mysqli_query($config, "SELECT * ,SUM(jml)jnom FROM dtlclaim WHERE status = 'Y' AND tgl BETWEEN '$tgla' AND '$tglb' GROUP BY nama_claim");

$qrncs=mysqli_query($config, "SELECT * ,SUM(debet)debet FROM dtlnoncash WHERE nama_ncs LIKE 'Beban%' AND tgl BETWEEN '$tgla' AND '$tglb' GROUP BY nama_ncs");

$qtln=mysqli_query($config, "SELECT * ,SUM(nominal)nominal FROM dtltransaksi WHERE ctg NOT IN ('kt1', 'kt2' , 'kt4', 'rtr') AND tgl BETWEEN '$tgla' AND '$tglb' AND iduser='$iduser'");
$totln=mysqli_fetch_array($qtln);

$retcs=mysqli_query($config, "SELECT * ,SUM(nominal)nominal FROM dtltransaksi WHERE ctg IN ('rtr') AND tgl BETWEEN '$tgla' AND '$tglb' AND iduser='$iduser'");
$totrecs=mysqli_fetch_array($retcs);

$qtclm=mysqli_query($config, "SELECT * ,SUM(jml)jnom FROM dtlclaim WHERE status = 'Y' AND tgl BETWEEN '$tgla' AND '$tglb'");
$tclaim=mysqli_fetch_array($qtclm);

$qtncs=mysqli_query($config, "SELECT * ,SUM(debet)debet FROM dtlnoncash WHERE nama_ncs LIKE 'Beban%' AND tgl BETWEEN '$tgla' AND '$tglb'");
$totncs=mysqli_fetch_array($qtncs);

$retncs=mysqli_query($config, "SELECT * ,SUM(debet)debet FROM dtlnoncash WHERE nama_ncs LIKE 'Retur Penjualan' AND tgl BETWEEN '$tgla' AND '$tglb'");
$totretncs=mysqli_fetch_array($retncs);

$retur=$totrecs['nominal']+$totretncs['debet'];

$qwer=mysqli_query($config, "SELECT SUM(total) AS topus FROM tbljualpulsa WHERE tgl BETWEEN '$tgla' AND '$tglb'");
$cvgh=mysqli_fetch_array($qwer);
$totalpls=$cvgh['topus'];

$qrytj=mysqli_query($config, "SELECT * ,SUM(jumlah*(harga-ppn))totalj FROM dtlpenjualan WHERE tgl BETWEEN '$tgla' AND '$tglb' AND iduser='$iduser'");
$totalj=mysqli_fetch_array($qrytj);
$penjualan=$totalj['totalj'];

$omzet=($penjualan+$totalpls)-$retur;

$qrhp=mysqli_query($config, "SELECT * ,SUM(jumlah*hpp)totahp FROM dtlpenjualan WHERE tgl BETWEEN '$tgla' AND '$tglb' AND iduser='$iduser'");
$totahp=mysqli_fetch_array($qrhp);

$qrhpl=mysqli_query($config, "SELECT * ,SUM(jumlah*hpp)totahpl FROM dtljualpulsa WHERE tgl BETWEEN '$tgla' AND '$tglb'");
$totahpl=mysqli_fetch_array($qrhpl);

$jumhppj=$totahp['totahp']+$totahpl['totahpl'];

$tbeli = mysqli_query($config, "SELECT SUM(dtlpembelian_bahan.jumlah*stok_bahan.harga_per) FROM dtlpembelian_bahan LEFT JOIN stok_bahan ON dtlpembelian_bahan.nama_bahan=stok_bahan.nama_bahan AND tgl BETWEEN '$tgla' AND '$tglb' ");
$data_tbeli=mysqli_fetch_array($tbeli);
$jumlah_tbeli=$data_tbeli[0];

$qry_persediaan=mysqli_query($config, "SELECT SUM(jmlmod) FROM modal WHERE ketmod = 'Persediaan' AND iduser='$iduser' ");
$data_persediaan=mysqli_fetch_array($qry_persediaan);
$jumlah_persediaan=$data_persediaan[0];

$tjal=mysqli_query($config, "SELECT SUM(jumlah*hpp) FROM dtlpenjualan WHERE tgl BETWEEN '$tgla' AND '$tglb' AND iduser='$iduser'");
$data_tjal=mysqli_fetch_array($tjal);
$jumlah_tjal=$data_tjal[0];

$jumlah_barang=$jumlah_tbeli+$jumlah_persediaan-$jumlah_tjal;

$qrypbeli=mysqli_query($config, "SELECT SUM(harga*jumlah) FROM dtlpembelian_bahan WHERE tgl BETWEEN '$tgla' AND '$tglb'");
$datapbeli=mysqli_fetch_array($qrypbeli);
$jumpbeli=$datapbeli[0];

$qwer=mysqli_query($config, "SELECT SUM(total) AS topus FROM tbljualpulsa WHERE tgl BETWEEN '$tgla' AND '$tglb'");
$cvgh=mysqli_fetch_array($qwer);
$totalpls=$cvgh['topus'];

$tpengl=$totln['nominal']+$totncs['debet']+$tclaim['jnom'];

} else {
$qryjual=mysqli_query($config, "SELECT * ,SUM(jumlah)jumlah FROM dtlpenjualan WHERE iduser='$iduser' GROUP BY nama_barang");

$qryhpp=mysqli_query($config, "SELECT * ,SUM(jumlah)jumlah FROM dtlpenjualan WHERE iduser='$iduser' GROUP BY nama_barang");

$qrtln=mysqli_query($config, "SELECT * ,SUM(nominal)nominal FROM dtltransaksi WHERE ctg NOT IN ('kt1', 'kt2' , 'kt4', 'rtr') AND iduser='$iduser' GROUP BY nama_tran");

$qrtlnop=mysqli_query($config, "SELECT * ,SUM(jml)jnom FROM dtlclaim WHERE status = 'Y' GROUP BY nama_claim");

$qrncs=mysqli_query($config, "SELECT * ,SUM(debet)debet FROM dtlnoncash WHERE nama_ncs LIKE 'Beban%' GROUP BY nama_ncs");

$qtln=mysqli_query($config, "SELECT * ,SUM(nominal)nominal FROM dtltransaksi WHERE ctg NOT IN ('kt1', 'kt2' , 'kt4', 'rtr') AND iduser='$iduser'");
$totln=mysqli_fetch_array($qtln);

$retcs=mysqli_query($config, "SELECT * ,SUM(nominal)nominal FROM dtltransaksi WHERE ctg IN ('rtr') AND iduser='$iduser'");
$totrecs=mysqli_fetch_array($retcs);

$qtclm=mysqli_query($config, "SELECT * ,SUM(jml)jnom FROM dtlclaim WHERE status = 'Y'");
$tclaim=mysqli_fetch_array($qtclm);

$qtncs=mysqli_query($config, "SELECT * ,SUM(debet)debet FROM dtlnoncash WHERE nama_ncs LIKE 'Beban%'");
$totncs=mysqli_fetch_array($qtncs);

$retncs=mysqli_query($config, "SELECT * ,SUM(debet)debet FROM dtlnoncash WHERE nama_ncs LIKE 'Retur Penjualan'");
$totretncs=mysqli_fetch_array($retncs);

$retur=$totrecs['nominal']+$totretncs['debet'];

$qrytj=mysqli_query($config, "SELECT * ,SUM(jumlah*(harga-ppn))totalj FROM dtlpenjualan WHERE iduser='$iduser'");
$totalj=mysqli_fetch_array($qrytj);

$qwer=mysqli_query($config, "SELECT SUM(total) AS topus FROM tbljualpulsa");
$cvgh=mysqli_fetch_array($qwer);
$totalpls=$cvgh['topus'];

$penjualan=$totalj['totalj'];

$omzet=($penjualan+$totalpls)-$retur;

$qrhp=mysqli_query($config, "SELECT * ,SUM(jumlah*hpp)totahp FROM dtlpenjualan WHERE iduser='$iduser'");
$totahp=mysqli_fetch_array($qrhp);

$qrhpl=mysqli_query($config, "SELECT * ,SUM(jumlah*hpp)totahpl FROM dtljualpulsa");
$totahpl=mysqli_fetch_array($qrhpl);

$jumhppj=$totahp['totahp']+$totahpl['totahpl'];

$tbeli = mysqli_query($config, "SELECT SUM(dtlpembelian_bahan.jumlah*stok_bahan.harga_per) FROM dtlpembelian_bahan LEFT JOIN stok_bahan ON dtlpembelian_bahan.nama_bahan=stok_bahan.nama_bahan");
$data_tbeli=mysqli_fetch_array($tbeli);
$jumlah_tbeli=$data_tbeli[0];

$qry_persediaan=mysqli_query($config, "SELECT SUM(jmlmod) FROM modal WHERE ketmod = 'Persediaan' AND iduser='$iduser'");
$data_persediaan=mysqli_fetch_array($qry_persediaan);
$jumlah_persediaan=$data_persediaan[0];

$tjal=mysqli_query($config, "SELECT SUM(jumlah*hpp) FROM dtlpenjualan WHERE iduser='$iduser'");
$data_tjal=mysqli_fetch_array($tjal);
$jumlah_tjal=$data_tjal[0];

$jumlah_barang=$jumlah_tbeli+$jumlah_persediaan-$jumlah_tjal;

$qrypbeli=mysqli_query($config, "SELECT SUM(harga*jumlah) FROM dtlpembelian_bahan");
$datapbeli=mysqli_fetch_array($qrypbeli);
$jumpbeli=$datapbeli[0];

//$tpengl=$totln['nominal'];
$tpengl=$totln['nominal']+$totncs['debet']+$tclaim['jnom'];
}
?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-plus"></i> Laporan Laba Rugi<br><b><?php if(!empty($_POST['darisampai'])) { echo indoDate($tgla)." s/d ".indoDate($tglb); } ?></b>
                    </div>
                <div class="panel-body">
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
                    <table class="table table-striped table-bordered table-hover">
								<tbody>
<tr>
<td colspan="2"><strong>PENJUALAN</strong></td>
</tr>
<tr>
<td>Penjualan Barang</td>
<td style="text-align: right;">Rp. <?php echo number_format($penjualan); ?></td>
</tr>

<tr>
<td style="text-align: right;"><strong>Total Penjualan Bersih</strong></td>
<td style="text-align: right;">Rp. <?php echo number_format($omzet); ?></td>
</tr>
<tr>
<td colspan="2"><strong>HARGA POKOK PENJUALAN</strong></td>
</tr>
<tr>
<td>HPP</td>
<td style="text-align: right;">Rp. <?php echo number_format($jumhppj); ?></td>
</tr>
<tr>
<td style="text-align: right;"><strong>Laba Kotor</strong></td>
<td style="text-align: right;">Rp. <?php echo number_format($omzet-$jumhppj); ?></td>
</tr>
<tr>
<tr>
<td colspan="2"><strong>TRANSAKSI PENGELUARAN</strong></td>
</tr>
<?php
$qpengele=mysqli_query($config, "SELECT *, SUM(nominal) AS nominal FROM dtltransaksi WHERE ctg NOT IN ('kt1', 'kt2' , 'kt4', 'rtr') AND iduser='$iduser' GROUP BY nama_tran");
		while ($dt = mysqli_fetch_array($qpengele)) {
?>
<tr>
<td><?php echo $dt['nama_tran']; ?></td>
<td style="text-align: right;">Rp. <?php echo number_format($dt['nominal']); ?></td>
</tr>
<?php 
	}
?>
<tr>
<td style="text-align: right;"><strong>PENGELUARAN</strong></td>
<td style="text-align: right;">Rp. <?php echo number_format($tpengl); ?></td>
</tr>

<tr>
<td style="text-align: right;"><strong>LABA USAHA</strong></td>
<td style="text-align: right;">Rp. <?php echo number_format(($omzet-$jumhppj)-$tpengl); ?></td>
</tr>

<tr>
<td style="text-align: right;"><strong>Laba Sebelum Pajak</strong></td>
<td style="text-align: right;">Rp. <?php echo number_format(($omzet-$jumhppj)-$tpengl); ?></td>
</tr>
<tr>
<td style="text-align: right;"><strong>Taksiran PPh (0,5%)</strong></td>
<td style="text-align: right;">Rp. <?php echo number_format($pjk=($penjualan*1)/200); ?></td>
</tr>
<tr>
<td style="text-align: right;"><strong>LABA BERSIH SETELAH PAJAK</strong></td>
<td style="text-align: right;">Rp. <?php 
								  $sbl_pjk=($omzet-$jumhppj)-$tpengl;
								  echo number_format($sbl_pjk-$pjk); ?></td>
</tr>
</tbody>
</table>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>