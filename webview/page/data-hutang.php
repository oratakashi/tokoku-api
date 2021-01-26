<?php if(!empty($_GET['act']) && $_GET['act']=='detail') {
$kode = $_GET['kode'];
$query = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE iduser='$iduser' AND kode_penjualan='$kode'") or die(mysql_error());
$data = mysqli_fetch_array($query);

?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="?page=data-hutang<?php echo $linkikutan; ?>"><i class="icon-chevron-left"></i> Kembali</a>
                        <div class="pull-right">Detail Laporan Penjualan</div>
                    </div>
                <div class="panel-body"> 
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><?php echo indoDate($data['tgl']); ?></th>
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
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php } else {
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
                        <i class="icon-plus"></i> Laporan Piutang<br><b><?php if(!empty($_POST['darisampai'])) { echo indoDate($dari)." s/d ".indoDate($sampai); } ?></b>
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
                    <table class="table table-striped table-hover">
                        <tbody>
                            <?php $no = 1; 
                            if(!empty($_POST['darisampai'])){ 
                            $query = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE iduser='$iduser' AND kurang > 0 AND idpel !='' AND tgl BETWEEN '$dari' AND '$sampai' ORDER BY tgl DESC ") or die(mysql_error());
                            } else {
                            $query = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE iduser='$iduser' AND kurang > 0 AND idpel !='' ORDER BY tgl DESC ") or die(mysql_error());
                            }
                            while ($data = mysqli_fetch_array($query)) {
$qryset = mysqli_query($config, "SELECT * FROM setting WHERE iduser='$iduser'") or die(mysql_error());
$daset = mysqli_fetch_array($qryset);
$namatoko = ucfirst($daset['perusahaan']);
                                
$qrycus = mysqli_query($config, "SELECT * FROM tblcustomers WHERE id_customers='{$data['idpel']}'") or die(mysql_error());
$dacus = mysqli_fetch_array($qrycus);
                            
                            
                            
$mesage = "Hallo ".ucfirst($dacus['nama_customers']).", Tagihan Kamu di ".$namatoko." tercatat Rp. ".number_format($data['kurang'],2)." belum terbayar lho. Segera dibayar / diselesaikan ya...";

$rawmsg = rawurlencode($mesage);

$nomor_tujuan = "62".$dacus['telp'];

?>
                            <tr>
                                <td>
                                    <b><?php echo indoDate($data['tgl']); ?></b><br>
                                    <?php echo ucfirst($dacus['nama_customers']); ?></b><br>
                                    <?php echo $data['kode_penjualan']; ?><br>
                                    Rp. <?php echo number_format($data['kurang'],2); ?>
                                </td>
                                <td style="text-align:right;vertical-align:middle">
                                    
                                </td>
                                <td style="text-align:right;vertical-align:middle">
                                    <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo $nomor_tujuan; ?>&text=<?php echo $rawmsg; ?>" class="btn btn-success"><i class="fa fa-whatsapp"></i></a>
                                    <a target="_blank" href="sms:+<?php echo $nomor_tujuan; ?>?body=<?php echo $rawmsg; ?>" class="btn btn-success"><i class="fa fa-envelope"></i></a>
                                    <!--<a style="background-color:#5CB85C;color:#FFFFFF;border-radius: 5px;padding:3px;" href="?page=data-hutang&act=detail&kode=<?php echo $data['kode_penjualan'].$linkikutan; ?>">Detail </a><br>-->
                                    
                                </td>
                            </tr>
                            <?php $no++;} ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php } ?>