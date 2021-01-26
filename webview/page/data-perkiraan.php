<?php if(!empty($_GET['act']) && $_GET['act']=='add') {
    
if(!empty($_POST['addperkiraan'])) {
    $kategori = $_POST['kategori'];
    $keterangan = $_POST['keterangan'];
    
    if ($kategori=='kt1'){

    $query1 = mysqli_query($config, "INSERT INTO tbltranslain(id_trnlain,iduser,keterangan,kategori) VALUES('', '$iduser', '$keterangan', '$kategori')") or die(mysql_error());
    $query2 = mysqli_query($config, "INSERT INTO tbltranslain(id_trnlain,iduser,keterangan,kategori) VALUES('', '$iduser', 'Depresiasi $keterangan', '$kategori')") or die(mysql_error());
    $query3 = mysqli_query($config, "INSERT INTO tbltranslain(id_trnlain,iduser,keterangan,kategori) VALUES('', '$iduser', 'Beban Depresiasi $keterangan', 'kt3')") or die(mysql_error());
    
    if ($query1 == true && $query2 == true && $query3 == true) { echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=data-perkiraan$linkikutan\">"); }
    
    } else if ($kategori=='kt2') {
    	
    $query1 = mysqli_query($config, "INSERT INTO tbltranslain(id_trnlain,iduser,keterangan,kategori) VALUES('', '$iduser', '$keterangan dibayar dimuka', '$kategori')") or die(mysql_error());
    $query2 = mysqli_query($config, "INSERT INTO tbltranslain(id_trnlain,iduser,keterangan,kategori) VALUES('', '$iduser', 'Beban $keterangan', 'kt3')") or die(mysql_error());
    
    if ($query1 == true && $query2 == true) { echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=data-perkiraan$linkikutan\">"); }
    
    } else {
    	
    $query = mysqli_query($config, "INSERT INTO tbltranslain(id_trnlain,iduser,keterangan,kategori) VALUES('', '$iduser', '$keterangan', '$kategori')") or die(mysql_error());
    
    if ($query) { echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=data-perkiraan$linkikutan\">"); }
    
    }
    
}
?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-plus"></i> Nama Perkiraan
                    </div>
                <div class="panel-body">
                    <form role="form" name="input-translain" action="" method="POST">
                        <div class="form-group">
                            <label for="Kategori">Kategori Nama Perkiraan</label>
                                <select class="form-control" name="kategori" data-rel="chosen" required="required">
                                    <option value="kt1">Inventaris</option>
                                    <option value="kt2">Biaya dibayar dimuka</option>
                                    <option value="kt3">Biaya-biaya</option>
                                    <option value="kt4">Hutang</option>
                                    <option value="kt5">Biaya atas pendapatan</option>
                                    <option value="kt6">Pendapatan lainnya</option>
                                    <option value="kt7">Biaya atas pendapatan lainnya</option>
                                    <option value="kt8">Biaya Operasional Sales</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="Keterangan">Keterangan Nama Perkiraan</label>
                                <input type="text" class="form-control" name="keterangan" placeholder="Nama Transaksi Lainnya" required="required">
                        </div>
                        <div class="form-group">
                            <a class="btn btn-danger" href="?page=data-perkiraan">Batal</a>
                            <button type="submit" name="addperkiraan" value="OK" class="btn btn-primary">OK</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php } else if(!empty($_GET['act']) && $_GET['act']=='edit') { 
$id = $_GET['id'];
$query = mysqli_query($config, "SELECT * FROM tbltranslain JOIN kategoritrans ON tbltranslain.kategori=kategoritrans.kode AND tbltranslain.iduser='$iduser' AND tbltranslain.id_trnlain='$id'") or die(mysql_error());
$data = mysqli_fetch_array($query);

if(!empty($_POST['editperkiraan'])) {
    $idtr =  $_POST['idtr'];
    $keterangan = $_POST['keterangan'];
    
    $editperkiraan = mysqli_query($config, "UPDATE tbltranslain SET keterangan='$keterangan' WHERE id_trnlain='$idtr' AND iduser='$iduser'") or die(mysql_error());
    
    if($editperkiraan == true) {
        echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=data-perkiraan$linkikutan\">");
    }
    
}

?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-plus"></i> Nama Perkiraan
                    </div>
                <div class="panel-body">
                    <form role="form" name="input-translain" action="" method="POST">
                        <input type="hidden" class="form-control" name="idtr" value="<?php echo $_GET['id']; ?>" required="required">
                        <div class="form-group">
                            <label for="Kategori">Kategori Nama Perkiraan</label>
                                <input type="text" class="form-control" value="<?php echo $data['nama_kategori']; ?>" readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label for="Keterangan">Keterangan Nama Perkiraan</label>
                                <input type="text" class="form-control" name="keterangan" placeholder="Nama Transaksi Lainnya" value="<?php echo $data['keterangan']; ?>" required="required">
                        </div>
                        <div class="form-group">
                            <a class="btn btn-danger" href="?page=data-perkiraan">Batal</a>
                            <button type="submit" name="editperkiraan" value="Update" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php } else if(!empty($_GET['act']) && $_GET['act']=='delete') {
$id = $_GET['id'];
$querydelete = mysqli_query($config, "DELETE FROM tbltranslain WHERE iduser='$iduser' AND id_trnlain='$id'") or die(mysql_error());
if($querydelete == true) {
    echo("<META HTTP-EQUIV=Refresh CONTENT=\"0.1;URL=?page=data-perkiraan$linkikutan\">");
}

} else { ?>
<sectione class="zero">
    <div class="containere">
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <a class="btn btn-success" href="?page=data-perkiraan&act=add<?php echo linkdefault($iduser,$apikey); ?>">
                    <i class="icon-plus"></i>
                    Nama Perkiraan
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-md-offset-0 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icon-tags"></i> Nama Perkiraan
                    </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; 
                                    $query = mysqli_query($config, "SELECT * FROM tbltranslain JOIN kategoritrans ON tbltranslain.kategori=kategoritrans.kode AND tbltranslain.iduser='$iduser' ") or die(mysql_error());
                                    while ($data = mysqli_fetch_array($query)) { ?>
                                    <tr>
                                        <td class="numeric"><?php echo $no; ?></td>
                                        <td><?php echo $data['keterangan']; ?></td>
                                        <td><?php echo $data['nama_kategori']; ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="?page=data-perkiraan&act=edit&id=<?php echo $data['id_trnlain'].linkdefault($iduser,$apikey); ?>"><i class="fa fa-pencil"></i></a>
                                            <a class="btn btn-danger btn-xs" href="#" data-toggle="modal" data-target="#del_<?php echo $data['id_trnlain']; ?>"><i class="fa fa-trash "></i></a>
                                            <div class="modal fade" id="del_<?php echo $data['id_trnlain']; ?>" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content modal-danger">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <h3>Konfirmasi Penghapusan</h3>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda Yakin Data Ini Akan Dihapus? Data Yang Dihapus Tidak Dapat Dikembalikan Lagi!</p>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="?page=data-perkiraan&act=delete&id=<?php echo $data['id_trnlain'].linkdefault($iduser,$apikey); ?>" class="btn btn-danger">Hapus</a>
                                                            <a type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Batal">Batal</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $no++; } ?>    
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</sectione>
<?php } ?>