<?php
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
                        <i class="icon-plus"></i> Laporan Pengeluaran<br><b><?php if (!empty($_POST['darisampai'])) {
                                                                                echo indoDate($dari) . " s/d " . indoDate($sampai);
                                                                            } ?></b>
                    </div>
                    <div class="panel-body">
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
                                if (!empty($_POST['darisampai'])) {
                                    $query = mysqli_query($config, "SELECT * FROM dtltransaksi WHERE iduser='$iduser' AND tgl BETWEEN '$dari' AND '$sampai' ORDER BY tgl DESC ") or die(mysql_error());
                                } else {
                                    $query = mysqli_query($config, "SELECT * FROM dtltransaksi WHERE iduser='$iduser' ORDER BY tgl DESC ") or die(mysql_error());
                                }
                                while ($data = mysqli_fetch_array($query)) { ?>
                                    <tr>
                                        <td>
                                            <b><?php echo indoDate($data['tgl']); ?></b><br><?php echo $data['nama_tran']; ?>
                                        </td>
                                        <td style="text-align:right">
                                            Rp. <?php echo number_format($data['nominal']); ?>
                                        </td>
                                    </tr>
                                <?php $no++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</sectione>