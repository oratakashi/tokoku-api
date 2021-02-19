<?php if (!empty($_GET['act']) && $_GET['act'] == 'detail') {
    $kode = $_GET['kode'];
    $query = mysqli_query($config, "SELECT * FROM tbltambah_stok WHERE iduser='$iduser' AND kode_stok='$kode'") or die(mysql_error());
    $data = mysqli_fetch_array($query);

?>
    <sectione class="zero">
        <div class="containere">
            <div class="row">
                <div class="col-md-12 col-md-offset-0 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a href="?page=penambahan-stok<?php echo $linkikutan; ?>"><i class="fa fa-chevron-left"></i> Kembali</a>
                            <div class="pull-right">Detail Laporan Penambahan Stok</div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo indoDate($data['tanggal']); ?></th>
                                        <th style="text-align:right">#<?php echo $kode; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    $total = 0;
                                    $querydtl = mysqli_query($config, "SELECT * FROM dtltambah_stok WHERE iduser='$iduser' AND kode_stok='$kode'") or die(mysql_error());
                                    while ($dtl = mysqli_fetch_array($querydtl)) {
                                        $total += $dtl['harga'] * $dtl['jumlah']; ?>
                                        <tr>
                                            <td>
                                                <b><?php echo $dtl['nama_bahan']; ?></b><br>
                                                <small>Qty : <?php echo $dtl['jumlah']; ?> x Rp. <?php echo number_format($dtl['harga']); ?></small>
                                            </td>
                                            <td style="text-align:right">
                                                <b>Rp. <?php echo number_format($dtl['harga'] * $dtl['jumlah']); ?></b><br>
                                            </td>
                                        </tr>
                                    <?php $no++;
                                    } ?>
                                    <tr>
                                        <td><b>TOTAL</b></td>
                                        <td style="text-align:right"><b>Rp. <?php echo number_format($total); ?></b></td>
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
    if (!empty($_POST['darisampai'])) {
        $tgd = explode(" - ", $_POST['darisampai']);
        $dari = date_format(date_create($tgd[0]), "Y-m-d");
        $sampai = date_format(date_create($tgd[1]), "Y-m-d");
    }
?>
    <sectione class="zero">
        <div class="containere">
            <div class="row">
                <div class="col-md-12 col-md-offset-0 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="icon-plus"></i> Laporan Penambahan Stok<br><b><?php if (!empty($_POST['darisampai'])) {
                                                                                        echo indoDate($dari) . " s/d " . indoDate($sampai);
                                                                                    } ?></b>
                        </div>
                        <div class="panel-body">
                            <a class="btn btn-success" href="?page=laporan-penjualan&iduser=<?php echo $iduser; ?>&api=<?php echo $_GET['api']; ?>">Penjualan</a>
                            <a class="btn btn-success" href="?page=penambahan-stok&iduser=<?php echo $iduser; ?>&api=<?php echo $_GET['api']; ?>">Stok Masuk</a>
                            <a class="btn btn-success" href="?page=laporan-stok&iduser=<?php echo $iduser; ?>&api=<?php echo $_GET['api']; ?>">Persediaan</a>
                            <hr>
                            <form role="form" name="period" action="" method="POST">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control pull-left" name="darisampai" id="daterange-btn" <?php if (!empty($_POST['darisampai'])) {
                                                                                                                                    echo 'value="' . $_POST['darisampai'] . '"';
                                                                                                                                } ?>placeholder="Dari Tanggal">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-success"><i class="icon-search"></i> Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-striped table-hover">
                                <tbody>
                                    <?php $no = 1;
                                    $tota = 0;
                                    if (!empty($_POST['darisampai'])) {
                                        $query = mysqli_query($config, "SELECT * FROM tbltambah_stok WHERE iduser='$iduser' AND tanggal BETWEEN '$dari' AND '$sampai' ORDER BY tanggal DESC ") or die(mysql_error());
                                    } else {
                                        $query = mysqli_query($config, "SELECT * FROM tbltambah_stok WHERE iduser='$iduser' ORDER BY tanggal DESC ") or die(mysql_error());
                                    }
                                    while ($data = mysqli_fetch_array($query)) {
                                        $tota += $data['total'];
                                    ?>
                                        <tr>
                                            <td>
                                                <b><?php echo indoDate($data['tanggal']); ?></b><br>ID Penambahan Stok : <?php echo $data['kode_stok']; ?>
                                            </td>
                                            <td style="text-align:right">
                                                <a style="background-color:#5CB85C;color:#FFFFFF;border-radius: 5px;padding:3px;" href="?page=penambahan-stok&act=detail&kode=<?php echo $data['kode_stok'] . $linkikutan; ?>">Detail <i class="icon-search"></i></a><br>
                                                <?php echo number_format($data['total']); ?>
                                            </td>
                                        </tr>
                                    <?php $no++;
                                    } ?>
                                    <tr>
                                        <td>
                                            <h2><b>TOTAL</b></h2>
                                        </td>
                                        <td style="text-align:right">
                                            <h2><b><?php echo number_format($tota); ?></b></h2>
                                        </td>
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