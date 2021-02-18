<?php
header("Content-Type: application/json; charset=UTF-8");
include('config.php');
include('function.php');

if (!empty($_REQUEST['api']) && $_REQUEST['api'] == $apikey) {
    if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'login') {
        if (!empty($_REQUEST['username']) && !empty($_REQUEST['password'])) {
            $username = trim(htmlspecialchars(mysqli_real_escape_string($config, $_REQUEST['username'])));
            $password = trim(htmlspecialchars(mysqli_real_escape_string($config, $_REQUEST['password'])));

            $querylogin = mysqli_query($config, "SELECT a.*, b.perusahaan FROM tab_user a, setting b WHERE a.id=b.iduser and a.username = BINARY'$username' AND a.password = MD5('$password') AND a.status='1'") or die(mysql_error());
            if (mysqli_num_rows($querylogin) > 0) {
                $data = mysqli_fetch_array($querylogin);

                if ($data[11] == 1) {

                    $harni = date_format(date_create($now), "Y-m-d");
                    $tglreg = date_format(date_create($data[12]), "Y-m-d");

                    $datein = date_create($tglreg);
                    date_add($datein, date_interval_create_from_date_string("14 days"));
                    $tglexpi = date_format($datein, "Y-m-d");

                    if ($harni <= $tglreg) {

                        $lastlog = mysqli_query($config, "UPDATE tab_user SET lastlogin = '$now' WHERE id = '{$data['id']}'") or die(mysql_error());
                        // jika data tidak terisi/tidak terset
                        $respon["sukses"] = 1;
                        $respon["received"] = array();
                        $tmp = array();
                        $tmp["iduser"] = $data[0];
                        $tmp["username"] = $data[1];
                        $tmp["namalengkap"] = $data[2];
                        $tmp["level"] = $data[4];
                        $tmp["email"] = $data[5];
                        $tmp["telp"] = $data[6];
                        $tmp["provinsi"] = $data[7];
                        $tmp["kota"] = $data[8];
                        $tmp["alamat"] = $data[9];
                        $tmp["status"] = $data[10];
                        $tmp["plan"] = $data[11];
                        $tmp["tgl_registrasi"] = $data[12];
                        $tmp["last_login"] = $data[13];
                        $tmp["perusahaan"] = $data["perusahaan"];
                        array_push($respon["received"], $tmp);
                        $respon["pesan"] = "Login berhasil!";
                        // memprint/mencetak JSON respon
                    } else {
                        $lastlog = mysqli_query($config, "UPDATE tab_user SET lastlogin = '$now', status='0' WHERE id = '{$data['id']}'") or die(mysql_error());
                        // jika data tidak terisi/tidak terset
                        $respon["sukses"] = 0;
                        $respon["received"] = array();
                        $respon["pesan"] = "Paket Free 14 Hari Anda Sudah berakhir!";
                        // memprint/mencetak JSON respon
                    }
                } else {
                    $lastlog = mysqli_query($config, "UPDATE tab_user SET lastlogin = '$now' WHERE id = '{$data['id']}'") or die(mysql_error());
// jika data tidak terisi/tidak terset
                    $respon["sukses"] = 1;
                    $respon["received"] = array();
                    $tmp = array();
                    $tmp["iduser"] = $data[0];
                    $tmp["namalengkap"] = $data[1];
                    $tmp["username"] = $data[2];
                    $tmp["level"] = $data[4];
                    $tmp["email"] = $data[5];
                    $tmp["telp"] = $data[6];
                    $tmp["provinsi"] = $data[7];
                    $tmp["kota"] = $data[8];
                    $tmp["alamat"] = $data[9];
                    $tmp["status"] = $data[10];
                    $tmp["plan"] = $data[11];
                    $tmp["tgl_registrasi"] = $data[12];
                    $tmp["last_login"] = $data[13];
                    $tmp["perusahaan"] = $data["perusahaan"];
                    array_push($respon["received"], $tmp);
                    $respon["pesan"] = "Login berhasil!";
// memprint/mencetak JSON respon
                }
            } else {
// jika data tidak terisi/tidak terset
                $respon["sukses"] = 0;
                $respon["received"] = array();
                $respon["pesan"] = "Username atau password salah!";
// memprint/mencetak JSON respon
            }
        } else {
// jika data tidak terisi/tidak terset
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "Username dan password harap diisi!";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'persediaan-barang') {
        if (!empty($_REQUEST['iduser'])) {
            $iduser = $_REQUEST['iduser'];
            if (!empty($_REQUEST['cari'])) {
                $carian = trim(htmlspecialchars(mysqli_real_escape_string($config, $_REQUEST['cari'])));
                $dataPerPage = 20;
                if (isset($_REQUEST['tab'])) {
                    $noPage = $_REQUEST['tab'];
                } else {
                    $noPage = 1;
                }
                $offset = ($noPage - 1) * $dataPerPage;

                $respon["sukses"] = 1;
                $respon["received"] = array();
                $no = 1 + floatval($offset);
                $query = mysqli_query($config, "SELECT * FROM stok_bahan WHERE iduser='$iduser' AND nama_bahan LIKE '%$carian%' ORDER BY nama_bahan ASC LIMIT $offset, $dataPerPage") or die(mysql_error());
                while ($data = mysqli_fetch_array($query)) {

                    $qrytambahstok = mysqli_query($config, "SELECT SUM(jumlah) AS totalpls FROM dtltambah_stok WHERE id_bahan='{$data['id_bahan']}'") or die(mysql_error());
                    $dplus = mysqli_fetch_array($qrytambahstok);
                    $totalpls = $dplus['totalpls'];

                    $qryjuala = mysqli_query($config, "SELECT SUM(jumlah) AS totalmns FROM dtlpenjualan WHERE idbarang='{$data['id_bahan']}'") or die(mysql_error());
                    $dminus = mysqli_fetch_array($qryjuala);
                    $totalmns = $dminus['totalmns'];

                    $stokdin = $totalpls - $totalmns;

                    $tmp = array();
                    $tmp["no"] = $no;
                    $tmp["idbarang"] = $data['id_bahan'];
                    $tmp["kode"] = $data['brcode'];
                    $tmp["nama_barang"] = $data['nama_bahan'];
//$tmp["jumlah"] = floatval($data['jumlah']);
                    $tmp["jumlah"] = floatval($stokdin);
                    $tmp["satuan"] = $data['satuan'];
                    $tmp["hpp"] = floatval($data['harga_per']);
                    $tmp["total_hpp"] = $data['jumlah'] * $data['harga_per'];
                    $tmp["harga_jual"] = floatval($data['hargaj']);
                    $tmp["total_jual"] = $data['jumlah'] * $data['hargaj'];
                    $tmp["harga_jual_grosir"] = floatval($data['hargag1']);
                    $no++;
                    array_push($respon["received"], $tmp);
                }
//$hasilx = mysqli_query($config, "SELECT COUNT(*) AS jumData FROM stok_bahan WHERE nama_bahan LIKE '%$carian%'") or die(mysql_error());
                $hasilx = mysqli_query($config, "SELECT COUNT(*) AS jumData FROM stok_bahan WHERE iduser='$iduser'") or die(mysql_error());
                $datax = mysqli_fetch_array($hasilx);
                $jumData = $datax['jumData'];
// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
                $jumPage = ceil($jumData / $dataPerPage);
                $respon["jumlah_data"] = floatval($jumData);
                $respon["jumlah_tab"] = $jumPage;
//$respon["result_tab"] = array();
                for ($page = 1; $page <= $jumPage; $page++) {
                    if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) {
                        $rep = array();
                        $showPage = 0;

                        if ($page == $noPage) {
                            $rep["tab"] = $page;
                        } else {
                            $rep["tab"] = $page;
                        }
                        $showPage = $page;
//array_push($respon["result_tab"], $rep);
                    }
                }
                $respon["pesan"] = "Data persediaan diterima";
            } else {
                $dataPerPage = 20;
                if (isset($_REQUEST['tab'])) {
                    $noPage = $_REQUEST['tab'];
                } else {
                    $noPage = 1;
                }
                $offset = ($noPage - 1) * $dataPerPage;

                $respon["sukses"] = 1;
                $respon["received"] = array();
                $no = 1 + floatval($offset);
//$query = mysqli_query($config, "SELECT * FROM stok_bahan ORDER BY nama_bahan ASC LIMIT $offset, $dataPerPage")or die(mysql_error());
                $query = mysqli_query($config, "SELECT * FROM stok_bahan WHERE iduser='$iduser' ORDER BY nama_bahan ASC LIMIT $offset, $dataPerPage") or die(mysql_error());
                while ($data = mysqli_fetch_array($query)) {

                    $qrytambahstok = mysqli_query($config, "SELECT SUM(jumlah) AS totalpls FROM dtltambah_stok WHERE id_bahan='{$data['id_bahan']}'") or die(mysql_error());
                    $dplus = mysqli_fetch_array($qrytambahstok);
                    $totalpls = $dplus['totalpls'];

                    $qryjuala = mysqli_query($config, "SELECT SUM(jumlah) AS totalmns FROM dtlpenjualan WHERE idbarang='{$data['id_bahan']}'") or die(mysql_error());
                    $dminus = mysqli_fetch_array($qryjuala);
                    $totalmns = $dminus['totalmns'];

                    $stokdin = $totalpls - $totalmns;

                    $tmp = array();
                    $tmp["no"] = $no;
                    $tmp["idbarang"] = $data['id_bahan'];
                    $tmp["kode"] = $data['brcode'];
                    $tmp["nama_barang"] = $data['nama_bahan'];
//$tmp["jumlah"] = floatval($data['jumlah']);
                    $tmp["jumlah"] = floatval($stokdin);
                    $tmp["satuan"] = $data['satuan'];
                    $tmp["hpp"] = floatval($data['harga_per']);
                    $tmp["total_hpp"] = $data['jumlah'] * $data['harga_per'];
                    $tmp["harga_jual"] = floatval($data['hargaj']);
                    $tmp["total_jual"] = $data['jumlah'] * $data['hargaj'];
                    $tmp["harga_jual_grosir"] = floatval($data['hargag1']);
                    $no++;
                    array_push($respon["received"], $tmp);
                }
//$hasilx = mysqli_query($config, "SELECT COUNT(*) AS jumData FROM stok_bahan") or die(mysql_error());
                $hasilx = mysqli_query($config, "SELECT COUNT(*) AS jumData FROM stok_bahan WHERE iduser='$iduser'") or die(mysql_error());
                $datax = mysqli_fetch_array($hasilx);
                $jumData = $datax['jumData'];
// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
                $jumPage = ceil($jumData / $dataPerPage);
                $respon["jumlah_data"] = floatval($jumData);
                $respon["jumlah_tab"] = $jumPage;
//$respon["result_tab"] = array();
                for ($page = 1; $page <= $jumPage; $page++) {
                    if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) {
                        $rep = array();
                        $showPage = 0;

                        if ($page == $noPage) {
                            $rep["tab"] = $page;
                        } else {
                            $rep["tab"] = $page;
                        }
                        $showPage = $page;
//array_push($respon["result_tab"], $rep);
                    }
                }
                $respon["pesan"] = "Data persediaan diterima";
            }

        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'data-satuan') {
        if (!empty($_REQUEST['iduser'])) {
            $iduser = $_REQUEST['iduser'];
            $respon["sukses"] = 1;
            $respon["received"] = array();
            $query = mysqli_query($config, "SELECT * FROM tblsatuan WHERE iduser='$iduser' ORDER BY idsat ASC ") or die(mysql_error());
            while ($data = mysqli_fetch_array($query)) {
                $tmp = array();
                $tmp["id_satuan"] = floatval($data['idsat']);
                $tmp["nama_satuan"] = $data['namasatuan'];
                array_push($respon["received"], $tmp);
            }
            $respon["pesan"] = "Data datuan diterima";
// memprint/mencetak JSON respon
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'tambah-satuan') {
        if (!empty($_REQUEST['iduser'])) {
            $namasatuan = $_POST['namasatuan'];
            $query = mysqli_query($config, "INSERT INTO tblsatuan VALUES('', '{$_REQUEST['iduser']}', '$namasatuan')") or die(mysql_error());
            if ($query == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Tambah Satuan Berhasil";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'edit-satuan') {
        if (!empty($_REQUEST['iduser'])) {
            $idsat = $_POST['idsatuan'];
            $namasatuan = $_POST['namasatuan'];
            $query = mysqli_query($config, "UPDATE tblsatuan SET namasatuan='$namasatuan' WHERE idsat='$idsat'") or die(mysql_error());
            if ($query == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Edit Satuan Berhasil";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'hapus-satuan') {
        if (!empty($_REQUEST['iduser'])) {
            $idsat = $_REQUEST['idsatuan'];
            $query = mysqli_query($config, "DELETE FROM tblsatuan WHERE idsat='$idsat'") or die(mysql_error());
            if ($query == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Hapus Satuan Berhasil";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'tambah-produk') {
        if (!empty($_REQUEST['iduser'])) {
            $iduser = $_REQUEST['iduser'];
            $kodebar = $_POST['kodebarang'];
            $namabarang = $_POST['namabarang'];
            $namasatuan = $_POST['namasatuan'];
            $jumlah = $_POST['jumlah'];
            $hargabel = $_POST['harga_beli'];
            $hargaje = $_POST['harga_jual_eceran'];
            $hargajg = $_POST['harga_jual_grosir'];
            $total = $jumlah * $hargabel;

            if ($jumlah <= 0) {
                $query1 = mysqli_query($config, "INSERT INTO stok_bahan (iduser,brcode, nama_bahan, jumlah, satuan, harga_per, total, hargaj, hargag1, hargag2, discount, expired) values('$iduser','$kodebar','$namabarang','$jumlah','$namasatuan','$hargabel','$total','$hargaje','$hargajg','','','')") or die(mysql_error());

                if ($query1 == true) {
                    $respon["sukses"] = 1;
                    $respon["received"] = array();
                    $respon["pesan"] = "Tambah Barang Berhasil";
// memprint/mencetak JSON respon
                } else {
                    $respon["sukses"] = 2;
                    $respon["received"] = array();
                    $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
                }
            } else {
                $today = date("Ymd");
                $hasil = mysqli_query($config, "SELECT MAX(kode_stok) AS last FROM tbltambah_stok WHERE kode_stok LIKE 'STO$today%'") or die(mysql_error());
                $data = mysqli_fetch_array($hasil);
                $lastNosupplier = $data['last'];
                $lastNoUrut = substr($lastNosupplier, 11, 15);
                $b = $lastNoUrut + 1;
                $char = "STO";
                $nou = $char . $today . sprintf("%04s", $b);

                $carisat = mysqli_query($config, "SELECT * FROM tblsatuan WHERE idsat='$namasatuan'") or die(mysql_error());
                $datasat = mysqli_fetch_array($carisat);

                $namasatuan = $datasat['namasatuan'];


                $query1 = mysqli_query($config, "INSERT INTO stok_bahan (iduser,brcode, nama_bahan, jumlah, satuan, harga_per, total, hargaj, hargag1, hargag2, discount, expired) values('$iduser','$kodebar','$namabarang','$jumlah','$namasatuan','$hargabel','$total','$hargaje','$hargajg','','','')") or die(mysql_error());
                $query2 = mysqli_query($config, "INSERT INTO tbltambah_stok (kode_stok, iduser, ketmod, tanggal, total) values('$nou','$iduser','Persediaan', NOW(),'$total')") or die(mysql_error());
                $searchbar = mysqli_query($config, "SELECT id_bahan FROM stok_bahan WHERE brcode LIKE '$kodebar' AND nama_bahan LIKE '$namabarang' AND iduser='$iduser'") or die(mysql_error());
                $databar = mysqli_fetch_array($searchbar);
                $id_bahan = $databar['id_bahan'];
                $query3 = mysqli_query($config, "INSERT INTO dtltambah_stok (kode_stok, id_bahan, iduser, nama_bahan, harga, jumlah, tgl) values('$nou','$id_bahan','$iduser','$namabarang','$hargabel','$jumlah', NOW())") or die(mysql_error());

                if ($query1 == true && $query2 == true && $query3 == true) {
                    $respon["sukses"] = 1;
                    $respon["received"] = array();
                    $respon["pesan"] = "Tambah Barang Berhasil";
// memprint/mencetak JSON respon
                } else {
                    $respon["sukses"] = 2;
                    $respon["received"] = array();
                    $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
                }
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'view-produk') {
        if (!empty($_REQUEST['iduser'])) {
            $iduser = $_REQUEST['iduser'];
            $idbar = $_REQUEST['idbarang'];

            $respon["sukses"] = 1;
            $respon["received"] = array();
            $query = mysqli_query($config, "SELECT * FROM stok_bahan WHERE id_bahan='$idbar' AND iduser='$iduser'") or die(mysql_error());
            while ($data = mysqli_fetch_array($query)) {
                $tmp = array();
                $tmp["idbarang"] = $data['id_bahan'];
                $tmp["kode"] = $data['brcode'];
                $tmp["nama_barang"] = $data['nama_bahan'];
                $tmp["jumlah"] = floatval($data['jumlah']);
                $tmp["satuan"] = $data['satuan'];
                $tmp["harga_beli"] = floatval($data['harga_per']);
                $tmp["harga_jual_eceran"] = floatval($data['hargaj']);
                $tmp["harga_jual_grosir"] = floatval($data['hargag1']);

// $tmp["total_hpp"] = $data['jumlah']*$data['harga_per'];
// $tmp["harga_jual"] = floatval($data['hargaj']);
// $tmp["total_jual"] = $data['jumlah']*$data['hargaj'];
// $tmp["harga_grosir"] = floatval($data['hargaj']);
                array_push($respon["received"], $tmp);
            }
            $respon["pesan"] = "View Produk diterima";
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'edit-produk') {
        if (!empty($_REQUEST['iduser'])) {
            $idbar = $_POST['idbarang'];
            $kodebar = $_POST['kodebarang'];
            $namabarang = $_POST['namabarang'];
            $namasatuan = $_POST['namasatuan'];

            $hargabel = $_POST['harga_beli'];
            $hargaje = $_POST['harga_jual_eceran'];
            $hargajg = $_POST['harga_jual_grosir'];
//$total = $jumlah*$hargabel;

            $query1 = mysqli_query($config, "UPDATE stok_bahan SET brcode='$kodebar', nama_bahan='$namabarang', satuan='$namasatuan', harga_per='$hargabel', hargaj='$hargaje', hargag1='$hargaje', hargag2='$hargajg' WHERE id_bahan='$idbar'") or die(mysql_error());
            if ($query1 == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "edit Barang Berhasil";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'hapus-produk') {
        if (!empty($_REQUEST['iduser'])) {
            $idbar = $_POST['idbarang'];
            $query1 = mysqli_query($config, "DELETE FROM stok_bahan WHERE id_bahan='$idbar'") or die(mysql_error());
            if ($query1 == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Hapus Barang Berhasil";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'tambah-stok') {
        if (!empty($_REQUEST['iduser'])) {
            $iduser = $_REQUEST['iduser'];

            $today = date("Ymd");
            $hasil = mysqli_query($config, "SELECT max(kode_stok) AS last FROM tbltambah_stok WHERE kode_stok LIKE 'STO$today%'") or die(mysql_error());
            $data = mysqli_fetch_array($hasil);
            $lastNosupplier = $data['last'];
            $lastNoUrut = substr($lastNosupplier, 11, 15);
            $b = $lastNoUrut + 1;
            $char = "STO";
            $nou = $char . $today . sprintf("%04s", $b);

            $insert = mysqli_query($config, "INSERT INTO tbltambah_stok(kode_stok,iduser,ketmod,tanggal) VALUES ('$nou','$iduser','Persediaan',NOW())") or die(mysql_error());
            if ($insert == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $tmp = array();
                $tmp["kode_stok"] = $nou;
                $tmp["iduser"] = $iduser;
                $tmp["tanggal"] = date("Y-m-d");
                array_push($respon["received"], $tmp);
                $respon["pesan"] = "kode_stok Berhasil dibentuk, lanjut ke keranjang-stok";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'keranjang-stok') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['kode_stok'])) {
            $iduser = $_REQUEST['iduser'];
            $idstok = $_REQUEST['kode_stok'];
            $respon["sukses"] = 1;
            $qrytab = mysqli_query($config, "SELECT * FROM tbltambah_stok WHERE kode_stok='$idstok'") or die(mysql_error());
            $datab = mysqli_fetch_array($qrytab);
            $respon["kode_stok"] = $idstok;
            $respon["iduser"] = $iduser;
            $respon["tanggal"] = $datab['tanggal'];
            $respon["received"] = array();
            $total = 0;
            $qrydtl = mysqli_query($config, "SELECT * FROM dtltambah_stok LEFT JOIN stok_bahan ON dtltambah_stok.id_bahan=stok_bahan.id_bahan WHERE dtltambah_stok.kode_stok='$idstok'") or die(mysql_error());
            while ($dadtl = mysqli_fetch_array($qrydtl)) {
                $tmp = array();
                $tmp["kodebarang"] = $dadtl['brcode'];
                $tmp["idbarang"] = $dadtl[1];
                $tmp["namabarang"] = $dadtl[3];
                $tmp["jumlah_pesan"] = floatval($dadtl[5]);
                $tmp["harga"] = floatval($dadtl[4]);

                $total += $dadtl[5] * $dadtl[4];

                array_push($respon["received"], $tmp);
            }
            $respon["total_stok"] = $total;
            $respon["pesan"] = "Data keranjang stok";
// memprint/mencetak JSON respon
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & kode_stok tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'add-stok') {
        if (!empty($_REQUEST['iduser'])) {
            $kode_stok = $_POST['kode_stok'];
            $idbarang = $_POST['idbarang'];
            $iduser = $_REQUEST['iduser'];
            $nama_barang = $_POST['nama_barang'];
            $jumlah = $_POST['jumlah_pesan'];
            $hpp = $_POST['hpp'];
            $harga = $_POST['harga_jual'];
            $tgl = date("Y-m-d");

            $insert = mysqli_query($config, "INSERT INTO dtltambah_stok(kode_stok,id_bahan,iduser,nama_bahan,jumlah,harga,tgl) VALUES ('$kode_stok','$idbarang','$iduser','$nama_barang','$jumlah','$hpp','$tgl')") or die(mysql_error());
            if ($insert == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Barang Berhasil Ditambahkan ke keranjang stok";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'delete-stok') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['kode_stok']) && !empty($_REQUEST['idbarang'])) {
            $iduser = $_REQUEST['iduser'];
            $kodejual = $_REQUEST['kode_stok'];
            $idbarang = $_REQUEST['idbarang'];

            $insert = mysqli_query($config, "DELETE FROM dtltambah_stok WHERE kode_stok='$kodejual' AND id_bahan='$idbarang' AND iduser='$iduser'") or die(mysql_error());
            if ($insert == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Barang Berhasil Dihapus dari keranjang";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan hapus mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser, kode_stok & idbarang tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'finish-stok') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['kode_stok']) && !empty($_REQUEST['total_stok'])) {
            $iduser = $_REQUEST['iduser'];
            $kodestok = $_REQUEST['kode_stok'];
            $totalstok = $_REQUEST['total_stok'];

            $sql1 = mysqli_query($config, "UPDATE tbltambah_stok SET total='$totalstok' WHERE kode_stok='$kodestok'") or die(mysql_error());
            $sql2 = mysqli_query($config, "INSERT INTO modal(ketmod,iduser,ktg,jmlmod,tglmod) VALUES ('Persediaan','$iduser','$kodestok','$totalstok',NOW())") or die(mysql_error());

            if ($sql1 == true && $sql2 == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Selesai menambahkan stok ke halaman keranjang-stok";
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser, kode_stok & total_stok tidak disertakan";
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'batal-stok') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['kode_stok'])) {
            $iduser = $_REQUEST['iduser'];
            $idjual = $_REQUEST['kode_stok'];

            $deltab = mysqli_query($config, "DELETE FROM tbltambah_stok WHERE kode_stok='$idjual'") or die(mysql_error());
            $deldtl = mysqli_query($config, "DELETE FROM dtltambah_stok WHERE kode_stok='$idjual'") or die(mysql_error());

            if ($deltab == true && $deldtl == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Tambah Stok Berhasil dibatalkan";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 0;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan hapus mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & kode_stok tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'select-pelanggan') {
        if (!empty($_REQUEST['iduser'])) {
            $respon["sukses"] = 1;
            $respon["received"] = array(
                array("id_customers" => 1, "nama_customers" => "Umum")
            );
            $qrydtl = mysqli_query($config, "SELECT * FROM tblcustomers WHERE submiter='{$_REQUEST['iduser']}'") or die(mysql_error());
            while ($dadtl = mysqli_fetch_array($qrydtl)) {
                $tmp = array();
                $tmp["id_customers"] = $dadtl[0];
                $tmp["nama_customers"] = $dadtl[1];
                array_push($respon["received"], $tmp);
            }
            $respon["pesan"] = "data pelanggan diterima";
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'penjualan') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['jhar'])) {
            $iduser = $_REQUEST['iduser'];
            $jhar = $_REQUEST['jhar'];
            $idpel = $_REQUEST['id_customers'];

            $today = date("Ymd");
            $hasil = mysqli_query($config, "SELECT MAX(kode_penjualan) AS last FROM tblpenjualan WHERE kode_penjualan LIKE 'TRJ$today%'") or die(mysql_error());
            $data = mysqli_fetch_array($hasil);
            $lastNosupplier = $data['last'];
            $lastNoUrut = substr($lastNosupplier, 11, 15);
            $b = $lastNoUrut + 1;
            $char = "TRJ";
            $nou = $char . $today . sprintf("%04s", $b);
            if ($jhar == 1) {
                $pelanggan = "Eceran";
            } else if ($jhar == 2) {
                $pelanggan = "Distributor";
            } else {
                $pelanggan = "Eceran";
            }
            $tanggal = date("Y-m-d");
            $insert = mysqli_query($config, "INSERT INTO tblpenjualan(kode_penjualan,iduser,idpel,pelanggan,tgl) VALUES ('$nou','$iduser','$idpel','$pelanggan','$tanggal')") or die(mysql_error());
            if ($insert == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $tmp = array();
                $tmp["id_penjualan"] = $nou;
                $tmp["pelanggan"] = $pelanggan;
                $tmp["iduser"] = $iduser;
                $tmp["tanggal"] = $tanggal;
                array_push($respon["received"], $tmp);
                $respon["pesan"] = "id_penjualan berhasil dibentuk lanjut ke daftar-belanja";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
            }

        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & jhar tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'daftar-belanja') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['id_penjualan'])) {
            $iduser = $_REQUEST['iduser'];
            $idjual = $_REQUEST['id_penjualan'];
            $respon["sukses"] = 1;
            $qrytab = mysqli_query($config, "SELECT * FROM tblpenjualan JOIN setting ON tblpenjualan.iduser=setting.iduser WHERE tblpenjualan.kode_penjualan='$idjual'") or die(mysql_error());
            $datab = mysqli_fetch_array($qrytab);
            $respon["id_penjualan"] = substr($idjual, 0, -12) . substr($idjual, 5);
            $respon["pelanggan"] = $datab['pelanggan'];
            $respon["iduser"] = $iduser;
            $respon["logotoko"] = "https://" . $_SERVER['HTTP_HOST'] . "/ritel/" . $datab['logo'];
            $respon["namatoko"] = $datab['perusahaan'];
            $respon["telptoko"] = $datab['tlp'];
            $respon["alamattoko"] = $datab['alamat'];
            $respon["tanggal"] = indoBln($datab['tgl']) . " " . date("H:i");
            $respon["received"] = array();
            $total = 0;
            $qrydtl = mysqli_query($config, "SELECT * FROM dtlpenjualan LEFT JOIN stok_bahan ON dtlpenjualan.idbarang=stok_bahan.id_bahan WHERE dtlpenjualan.kode_penjualan='$idjual'") or die(mysql_error());
            while ($dadtl = mysqli_fetch_array($qrydtl)) {
                $tmp = array();
                $tmp["kodebarang"] = $dadtl['brcode'];
                $tmp["idbarang"] = $dadtl[1];
                $tmp["namabarang"] = $dadtl[3];
                $tmp["jumlah_pesan"] = floatval($dadtl[4]);
                $tmp["harga"] = floatval($dadtl[6]);

                $total += $dadtl[4] * $dadtl[6];

                array_push($respon["received"], $tmp);
            }
            $respon["total_belanja"] = $total;
            $respon["textprint"] = $datab['pesan'];
            $respon["pesan"] = "Data daftar belanja";
// memprint/mencetak JSON respon
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & id_penjualan tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'scan-kode') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['kode'])) {
            $iduser = $_REQUEST['iduser'];
            $kode = $_REQUEST['kode'];

            $respon["sukses"] = 1;
            $respon["received"] = array();
            $query = mysqli_query($config, "SELECT * FROM stok_bahan WHERE brcode='$kode' AND iduser='$iduser'") or die(mysql_error());
            $data = mysqli_fetch_array($query);

            $qrytambahstok = mysqli_query($config, "SELECT SUM(jumlah) AS totalpls FROM dtltambah_stok WHERE id_bahan='{$data['id_bahan']}'") or die(mysql_error());
            $dplus = mysqli_fetch_array($qrytambahstok);
            $totalpls = $dplus['totalpls'];

            $qryjuala = mysqli_query($config, "SELECT SUM(jumlah) AS totalmns FROM dtlpenjualan WHERE idbarang='{$data['id_bahan']}'") or die(mysql_error());
            $dminus = mysqli_fetch_array($qryjuala);
            $totalmns = $dminus['totalmns'];

            $stokdin = $totalpls - $totalmns;


            $tmp = array();
            $tmp["idbarang"] = $data['id_bahan'];
            $tmp["kode"] = $data['brcode'];
            $tmp["nama_barang"] = $data['nama_bahan'];
            $tmp["jumlah_persediaan"] = floatval($stokdin);
            $tmp["satuan"] = $data['satuan'];
            $tmp["hpp"] = floatval($data['harga_per']);
            $tmp["harga_jual_eceran"] = floatval($data['hargaj']);
            $tmp["harga_jual_grosir"] = floatval($data['hargag1']);

            array_push($respon["received"], $tmp);
            $respon["pesan"] = "Scan kode berhasil";
// memprint/mencetak JSON respon

        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & kode tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'scan-barang') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['pelanggan']) && !empty($_REQUEST['kode'])) {
            $iduser = $_REQUEST['iduser'];
            $pelanggan = $_REQUEST['pelanggan'];
            $kode = $_REQUEST['kode'];

            $respon["sukses"] = 1;
            $respon["received"] = array();
            $query = mysqli_query($config, "SELECT * FROM stok_bahan WHERE brcode='$kode' AND iduser='$iduser'") or die(mysql_error());
            $data = mysqli_fetch_array($query);

            $qrytambahstok = mysqli_query($config, "SELECT SUM(jumlah) AS totalpls FROM dtltambah_stok WHERE id_bahan='{$data['id_bahan']}'") or die(mysql_error());
            $dplus = mysqli_fetch_array($qrytambahstok);
            $totalpls = $dplus['totalpls'];

            $qryjuala = mysqli_query($config, "SELECT SUM(jumlah) AS totalmns FROM dtlpenjualan WHERE idbarang='{$data['id_bahan']}'") or die(mysql_error());
            $dminus = mysqli_fetch_array($qryjuala);
            $totalmns = $dminus['totalmns'];

            $stokdin = $totalpls - $totalmns;

            $tmp = array();
            $tmp["idbarang"] = $data['id_bahan'];
            $tmp["kode"] = $data['brcode'];
            $tmp["nama_barang"] = $data['nama_bahan'];
            $tmp["jumlah_persediaan"] = floatval($stokdin);
            $tmp["satuan"] = $data['satuan'];
            $tmp["hpp"] = floatval($data['harga_per']);
            if ($pelanggan == 'Eceran') {
                $tmp["harga_jual"] = floatval($data['hargaj']);
            } else if ($pelanggan == 'Distributor') {
                $tmp["harga_jual"] = floatval($data['hargag1']);
            } else {
                $tmp["harga_jual"] = floatval($data['hargaj']);
            }
            array_push($respon["received"], $tmp);
            $respon["pesan"] = "Scan data berhasil";
// memprint/mencetak JSON respon
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser, pelanggan & kode tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'tambah-keranjang') {
        if (!empty($_REQUEST['iduser'])) {
            $kode_penjualan = $_POST['id_penjualan'];
            $idbarang = $_REQUEST['idbarang'];
            $iduser = $_REQUEST['iduser'];
            $nama_barang = $_POST['nama_barang'];
            $jumlah = $_REQUEST['jumlah_pesan'];
            $hpp = $_REQUEST['hpp'];
            $harga = $_POST['harga_jual'];
            $tgl = date("Y-m-d");

            $qrytambahstok = mysqli_query($config, "SELECT SUM(jumlah) AS totalpls FROM dtltambah_stok WHERE id_bahan='$idbarang'") or die(mysql_error());
            $dplus = mysqli_fetch_array($qrytambahstok);
            $totalpls = $dplus['totalpls'];

            $qryjuala = mysqli_query($config, "SELECT SUM(jumlah) AS totalmns FROM dtlpenjualan WHERE idbarang='$idbarang'") or die(mysql_error());
            $dminus = mysqli_fetch_array($qryjuala);
            $totalmns = $dminus['totalmns'];

            $stokdin = $totalpls - $totalmns;

            if ($stokdin >= $jumlah) {

                $insert = mysqli_query($config, "INSERT INTO dtlpenjualan(kode_penjualan,idbarang,iduser,nama_barang,jumlah,hpp,harga,tgl) VALUES ('$kode_penjualan','$idbarang','$iduser','$nama_barang','$jumlah','$hpp','$harga','$tgl')") or die(mysql_error());
                if ($insert == true) {
                    $respon["sukses"] = 1;
                    $respon["received"] = array();
                    $respon["pesan"] = "Barang Berhasil Ditambahkan ke keranjang";
// memprint/mencetak JSON respon
                } else {
                    $respon["sukses"] = 2;
                    $respon["received"] = array();
                    $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
                }
            } else if ($stokdin >= $jumlah) {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "Persediaan tidak mencukupi";
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'hapus-keranjang') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['id_penjualan']) && !empty($_REQUEST['idbarang'])) {
            $iduser = $_REQUEST['iduser'];
            $kodejual = $_REQUEST['id_penjualan'];
            $idbarang = $_REQUEST['idbarang'];

            $insert = mysqli_query($config, "DELETE FROM dtlpenjualan WHERE kode_penjualan='$kodejual' AND idbarang='$idbarang' AND iduser='$iduser'") or die(mysql_error());
            if ($insert == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Barang Berhasil Dihapus dari keranjang";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan hapus mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser, id_penjualan & idbarang tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'pembayaran') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['id_penjualan']) && !empty($_REQUEST['jumlah_bayar'])) {
            $iduser = $_REQUEST['iduser'];
            $kodejual = $_REQUEST['id_penjualan'];
            $bayar = $_REQUEST['jumlah_bayar'];

            $qrysumdtl = mysqli_query($config, "SELECT SUM(jumlah*harga) AS totalbelanja FROM dtlpenjualan WHERE kode_penjualan='$kodejual' AND iduser='$iduser'") or die(mysql_error());
            $data = mysqli_fetch_array($qrysumdtl);

            $totalbelanja = $data['totalbelanja'];

            if ($bayar >= $totalbelanja) {
                $kembalian = $bayar - $totalbelanja;
                $kurang = 0;
                $dibayar = $totalbelanja;
            } else {
                $kurang = $totalbelanja - $bayar;
                $kembalian = 0;
                $dibayar = $bayar;
            }

            $updatejual = mysqli_query($config, "UPDATE tblpenjualan SET total='$totalbelanja', bayar='$dibayar', kurang='$kurang', kembalian='$kembalian' WHERE kode_penjualan='$kodejual' AND iduser='$iduser'") or die(mysql_error());
            $insertpelun = mysqli_query($config, "INSERT INTO dtlpelunasan (kode_penjualan,bayar,tgl) VALUES ('$kodejual','$dibayar',NOW())") or die(mysql_error());
            if ($updatejual == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array("total_belanja" => $totalbelanja, "dibayar" => $dibayar, "kurang" => $kurang, "kembalian" => $kembalian);
                $respon["pesan"] = "Pembayaran Berhasil ke halaman result-penjualan";
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser, id_penjualan & jumlah_bayar tidak disertakan";
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'result-penjualan') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['id_penjualan'])) {
            $iduser = $_REQUEST['iduser'];
            $idjual = $_REQUEST['id_penjualan'];
            $respon["sukses"] = 1;
            $qrytab = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE kode_penjualan='$idjual'") or die(mysql_error());
            $datab = mysqli_fetch_array($qrytab);
            $respon["id_penjualan"] = $idjual;
            $respon["pelanggan"] = $datab['pelanggan'];
            $respon["iduser"] = $iduser;
            $respon["tanggal"] = $datab['tgl'];
            $respon["received"] = array();
            $total = 0;
            $qrydtl = mysqli_query($config, "SELECT * FROM dtlpenjualan WHERE kode_penjualan='$idjual'") or die(mysql_error());
            while ($dadtl = mysqli_fetch_array($qrydtl)) {
                $tmp = array();

                $tmp["idbarang"] = $dadtl[1];
                $tmp["namabarang"] = $dadtl[3];
                $tmp["jumlah_pesan"] = floatval($dadtl[4]);
                $tmp["harga"] = floatval($dadtl[6]);

                $total += $dadtl[4] * $dadtl[6];

                array_push($respon["received"], $tmp);
            }
            $respon["total_belanja"] = $total;
            $respon["total_bayar"] = $datab['bayar'] + $datab['kembalian'];
            $respon["kekurangan"] = floatval($datab['kurang']);
            $respon["kembalian"] = floatval($datab['kembalian']);
            $respon["pesan"] = "Data daftar belanja";
// memprint/mencetak JSON respon
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & id_penjualan tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'batal-penjualan') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['id_penjualan'])) {
            $iduser = $_REQUEST['iduser'];
            $idjual = $_REQUEST['id_penjualan'];

            $deltab = mysqli_query($config, "DELETE FROM tblpenjualan WHERE kode_penjualan='$idjual'") or die(mysql_error());
            $deldtl = mysqli_query($config, "DELETE FROM dtlpenjualan WHERE kode_penjualan='$idjual'") or die(mysql_error());

            if ($deltab == true && $deldtl == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Penjualan Berhasil dibatalkan";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 0;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan hapus mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & id_penjualan tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'add-pelanggan') {
        if (!empty($_REQUEST['iduser'])) {
            $today = date("Ymd");
            $query = "SELECT max(id_customers) AS last FROM tblcustomers WHERE id_customers LIKE 'CSM$today%'";
            $hasil = mysqli_query($config, $query);
            $data = mysqli_fetch_array($hasil);
            $lastNosupplier = $data['last'];
            $lastNoUrut = substr($lastNosupplier, 11, 15);
            $b = $lastNoUrut + 1;
            $char = "CSM";
            $nou = $char . $today . sprintf("%04s", $b);

            $submiter = $_REQUEST['iduser'];
            $idcustomers = $nou;
            $namacustomers = $_POST['nama_customers'];
            $alamat = $_POST['alamat_customers'];
            $hep = $_POST['telp'];
            $tla = substr($hep, 0, 2);

            if ($tla == "08") {
                $telp = "8" . substr($hep, 2);
            } else if ($tla == "02") {
                $telp = $_POST['telp'];
            } else {
                $telp = $_POST['telp'];
            }

            $insertcus = mysqli_query($config, "INSERT INTO tblcustomers(id_customers, nama_customers, alamat_customers, telp, submiter) VALUES('$idcustomers','$namacustomers','$alamat','$telp','$submiter')") or die(mysql_error());
            if ($insertcus == true) {

                $respon["sukses"] = 1;
                $respon["received"] = array();
                $tmp = array();
                $tmp["id_customers"] = $idcustomers;
                $tmp["nama_customers"] = $namacustomers;
                $tmp["alamat_customers"] = $alamat;
                $tmp["telp"] = $telp;
                array_push($respon["received"], $tmp);
                $respon["pesan"] = "Pelanggan Berhasil Ditambahkan";
// memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'view-pelanggan') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['id_customers'])) {
            $respon["sukses"] = 1;
            $respon["received"] = array();
            $qrydtl = mysqli_query($config, "SELECT * FROM tblcustomers WHERE submiter='{$_REQUEST['iduser']}' AND id_customers='{$_REQUEST['id_customers']}'") or die(mysql_error());
            $dadtl = mysqli_fetch_array($qrydtl);
            $tmp = array();
            $tmp["id_customers"] = $dadtl[0];
            $tmp["nama_customers"] = $dadtl[1];
            $tmp["alamat_customers"] = $dadtl[4];
            $tmp["telp"] = $dadtl[6];
            array_push($respon["received"], $tmp);
            $respon["pesan"] = "data pelanggan diterima";
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & idpel tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'update-pelanggan') {
        if (!empty($_REQUEST['iduser'])) {
            $id_customers = $_POST['id_customers'];
            $nama_customers = $_POST['nama_customers'];
            $alamat_customers = $_POST['alamat_customers'];
            $telp = $_POST['telp'];
            $insertcus = mysqli_query($config, "UPDATE tblcustomers SET nama_customers='$namacustomers', alamat_customers='$alamat', telp='$telp' WHERE id_customers='$id_customers')") or die(mysql_error());
            if ($insertcus == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $respon["pesan"] = "Pelanggan Berhasil Diupdate";
                // memprint/mencetak JSON respon
            } else {
                $respon["sukses"] = 2;
                $respon["received"] = array();
                $respon["pesan"] = "kesalahan input mysql";
                // memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'delete-pelanggan') {
        if (!empty($_REQUEST['iduser'])) {
            if (!empty($_REQUEST['id_customers'])) {
                $id_customers = $_REQUEST['id_customers'];
                $deltcus = mysqli_query($config, "DELETE FROM tblcustomers WHERE submiter='{$_REQUEST['iduser']}' AND id_customers='{$_REQUEST['id_customers']}'") or die(mysql_error());
                if ($deltcus == true) {
                    $respon["sukses"] = 1;
                    $respon["received"] = array();
                    $respon["pesan"] = "Pelanggan Berhasil dihapus";
// memprint/mencetak JSON respon}
                } else {
                    $respon["sukses"] = 2;
                    $respon["received"] = array();
                    $respon["pesan"] = "kesalahan input mysql";
// memprint/mencetak JSON respon}
                }
            } else {
                $respon["sukses"] = 0;
                $respon["received"] = array();
                $respon["pesan"] = "id_customers tidak disertakan";
// memprint/mencetak JSON respon
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'list-pelanggan') {
        if (!empty($_REQUEST['iduser'])) {
            $respon["sukses"] = 1;
            $respon["received"] = array();
            $qrydtl = mysqli_query($config, "SELECT * FROM tblcustomers WHERE submiter='{$_REQUEST['iduser']}'") or die(mysql_error());
            while ($dadtl = mysqli_fetch_array($qrydtl)) {

                $nomor_tujuan = "62" . $dadtl[6];
                $rawmsg = "Hallo " . ucfirst($dadtl[1]);

                $tmp = array();
                $tmp["id_customers"] = $dadtl[0];
                $tmp["nama_customers"] = $dadtl[1];
                $tmp["alamat_customers"] = $dadtl[4];
                $tmp["telp"] = "+62" . $dadtl[6];
                $tmp["link_whatsapp"] = "https://api.whatsapp.com/send?phone=" . $nomor_tujuan . "&text=" . $rawmsg;

                array_push($respon["received"], $tmp);
            }
            $respon["pesan"] = "data pelanggan diterima";
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'data-piutang') {
        if (!empty($_REQUEST['iduser'])) {
            if (!empty($_REQUEST['dari']) && !empty($_REQUEST['sampai'])) {
                $dari = date_format(date_create($_REQUEST['dari']), "Y-m-d");
                $sampai = date_format(date_create($_REQUEST['sampai']), "Y-m-d");

                $respon["sukses"] = 1;
                $respon["received"] = array();
                $totalpiutang = 0;
                $query = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE iduser='{$_REQUEST['iduser']}' AND kurang > 0 AND idpel !='' AND tgl BETWEEN '$dari' AND '$sampai' ORDER BY tgl DESC ") or die(mysql_error());
                while ($data = mysqli_fetch_array($query)) {
                    $qryset = mysqli_query($config, "SELECT * FROM setting WHERE iduser='{$_REQUEST['iduser']}'") or die(mysql_error());
                    $daset = mysqli_fetch_array($qryset);
                    $namatoko = ucfirst($daset['perusahaan']);

                    $qrycus = mysqli_query($config, "SELECT * FROM tblcustomers WHERE id_customers='{$data['idpel']}'") or die(mysql_error());
                    $dacus = mysqli_fetch_array($qrycus);

                    $mesage = "Hallo " . ucfirst($dacus['nama_customers']) . ", Tagihan Kamu di " . $namatoko . " tercatat Rp. " . number_format($data['kurang'], 2) . " belum terbayar lho. Segera dibayar / diselesaikan ya...";

                    $rawmsg = rawurlencode($mesage);

                    $nomor_tujuan = "62" . $dacus['telp'];
                    $totalpiutang += $data['kurang'];
                    $tmp = array();
                    $tmp["tanggal"] = indoDate($data['tgl']);

                    $tmp["pelanggan"] = ucfirst($dacus['nama_customers']);
                    $tmp["id_penjualan"] = $data['kode_penjualan'];
                    $tmp["total_piutang"] = number_format($data['kurang'], 2);
                    $tmp["link_whatsapp"] = "https://api.whatsapp.com/send?phone=" . $nomor_tujuan . "&text=" . $rawmsg;
                    $tmp["link_sms"] = "sms:+" . $nomor_tujuan . "?body=" . $rawmsg;
                    array_push($respon["received"], $tmp);
                }
                $respon["total_piutang"] = $totalpiutang;
                $respon["pesan"] = "data piutang diterima";
            } else {
                $respon["sukses"] = 1;
                $respon["received"] = array();
                $totalpiutang = 0;
                $query = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE iduser='{$_REQUEST['iduser']}' AND kurang > 0 AND idpel !='' ORDER BY tgl DESC ") or die(mysql_error());
                while ($data = mysqli_fetch_array($query)) {
                    $qryset = mysqli_query($config, "SELECT * FROM setting WHERE iduser='{$_REQUEST['iduser']}'") or die(mysql_error());
                    $daset = mysqli_fetch_array($qryset);
                    $namatoko = ucfirst($daset['perusahaan']);

                    $qrycus = mysqli_query($config, "SELECT * FROM tblcustomers WHERE id_customers='{$data['idpel']}'") or die(mysql_error());
                    $dacus = mysqli_fetch_array($qrycus);

                    $mesage = "Hallo " . ucfirst($dacus['nama_customers']) . ", Tagihan Kamu di " . $namatoko . " tercatat Rp. " . number_format($data['kurang'], 2) . " belum terbayar lho. Segera dibayar / diselesaikan ya... Terima kasih";

                    $rawmsg = rawurlencode($mesage);

                    $nomor_tujuan = "62" . $dacus['telp'];
                    $totalpiutang += $data['kurang'];

                    $tmp = array();
                    $tmp["tanggal"] = indoDate($data['tgl']);
                    $tmp["pelanggan"] = ucfirst($dacus['nama_customers']);
                    $tmp["id_penjualan"] = $data['kode_penjualan'];
                    $tmp["total_piutang"] = number_format($data['kurang'], 2);
                    $tmp["link_whatsapp"] = "https://api.whatsapp.com/send?phone=" . $nomor_tujuan . "&text=" . $rawmsg;
                    $tmp["link_sms"] = "sms:+" . $nomor_tujuan . "?body=" . $rawmsg;
                    array_push($respon["received"], $tmp);
                }
                $respon["total_piutang"] = $totalpiutang;
                $respon["pesan"] = "data piutang diterima";
            }
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'detail-piutang') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['id_penjualan'])) {
            $iduser = $_REQUEST['iduser'];
            $idjual = $_REQUEST['id_penjualan'];
            $respon["sukses"] = 1;
            $qrytab = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE kode_penjualan='$idjual'") or die(mysql_error());
            $datab = mysqli_fetch_array($qrytab);
            $respon["id_penjualan"] = $idjual;
            $respon["pelanggan"] = $datab['pelanggan'];
            $respon["iduser"] = $iduser;
            $respon["tanggal"] = $datab['tgl'];
            $respon["received"] = array();
            $total = 0;
            $qrydtl = mysqli_query($config, "SELECT * FROM dtlpenjualan WHERE kode_penjualan='$idjual'") or die(mysql_error());
            while ($dadtl = mysqli_fetch_array($qrydtl)) {
                $tmp = array();

                $tmp["idbarang"] = $dadtl[1];
                $tmp["namabarang"] = $dadtl[3];
                $tmp["jumlah_pesan"] = floatval($dadtl[4]);
                $tmp["harga"] = floatval($dadtl[6]);

                $total += $dadtl[4] * $dadtl[6];

                array_push($respon["received"], $tmp);
            }
            $respon["total_belanja"] = $total;
            $respon["pembayaran"] = array();
            $nb = 1;
            $toba = 0;
            $jbar = mysqli_query($config, "SELECT * FROM dtlpelunasan WHERE kode_penjualan='$idjual'") or die(mysql_error());
            while ($fl = mysqli_fetch_array($jbar)) {
                $thl = array();
                $thl["no"] = $nb;
                $thl["tanggal"] = indoDate($fl['tgl']);
                $thl["nominal"] = floatval($fl['bayar']);

                $toba += $thl["nominal"];

                array_push($respon["pembayaran"], $thl);
                $nb++;
            }
//$respon["total_bayar"] = $datab['bayar']+$datab['kembalian'];
            $respon["total_bayar"] = floatval($toba);
            if ($total <= $toba) {
                $respon["kekurangan"] = 0;
                $respon["kembalian"] = floatval($datab['kembalian']);
                $respon["var_status"] = 2;
                $respon["label_status"] = "Lunas";
            } else {
                $respon["kekurangan"] = floatval($datab['kurang']);
                $respon["kembalian"] = 0;
                $respon["var_status"] = 1;
                $respon["label_status"] = "Belum Lunas";
            }
            $respon["pesan"] = "Data piutang belanja";
// memprint/mencetak JSON respon
        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & id_penjualan tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'bayar-piutang') {
        if (!empty($_REQUEST['iduser']) && !empty($_REQUEST['id_penjualan'])) {
            $iduser = $_REQUEST['iduser'];
            $idjual = $_REQUEST['id_penjualan'];

            $total = $_POST['total_belanja'];
            $pelunasan = $_POST['total_bayar'];
            $pembayaran = $_POST['pembayaran'];
            $bayar = $pelunasan + $pembayaran;
            $tgl = date("Y-m-d");

            if ($bayar < $total) {
                $kurang = $total - $bayar;
                $sql1 = mysqli_query($config, "UPDATE tblpenjualan SET bayar=bayar+'$bayar', kurang='$kurang' WHERE kode_penjualan='$idjual'") or die(mysql_error());
                $sql2 = mysqli_query($config, "INSERT INTO dtlpelunasan (kode_penjualan,bayar,tgl) VALUES ('$idjual','$pembayaran','$tgl')") or die(mysql_error());
            } else if ($bayar > $total) {
                $kembalian = $bayar - $total;
                $sql1 = mysqli_query($config, "UPDATE tblpenjualan SET bayar=bayar+'$total', kurang='0', kembalian='$kembalian' WHERE kode_penjualan='$idjual'") or die(mysql_error());
                $sql2 = mysqli_query($config, "INSERT INTO dtlpelunasan (kode_penjualan,bayar,tgl) VALUES ('$idjual','$pembayaran','$tgl')") or die(mysql_error());
            } else {
                $kurang = $total - $bayar;
                $sql1 = mysqli_query($config, "UPDATE tblpenjualan SET bayar=bayar+'$bayar', kurang='$kurang' WHERE kode_penjualan='$idjual'") or die(mysql_error());
                $sql2 = mysqli_query($config, "INSERT INTO dtlpelunasan (kode_penjualan,bayar,tgl) VALUES ('$idjual','$pembayaran','$tgl')") or die(mysql_error());
            }

            if ($sql1 == true && $sql2 == true) {
                $respon["sukses"] = 1;
                $respon["received"] = array("total_belanja" => $total, "total_bayar" => $pelunasan, "pembayaran" => $pembayaran, "total_bayar" => $bayar);
                $respon["pesan"] = "Pelunasan Berhasil Ditambahkan";

            }

        } else {
            $respon["sukses"] = 0;
            $respon["received"] = array();
            $respon["pesan"] = "iduser & id_penjualan tidak disertakan";
// memprint/mencetak JSON respon
        }
        echo json_encode($respon);
    } else if(!empty($_REQUEST['data']) && $_REQUEST['data'] == 'riwayat-penjualan'){
        $iduser = $_REQUEST['iduser'];

        $qrypenjualan = mysqli_query($config, "SELECT * FROM tblpenjualan WHERE iduser='$iduser' limit 5") or die(mysql_error());

        $respon["sukses"] = 1;
        $respon["pesan"] = "Sukses mendapatkan data penjualan";
        $respon["received"] = array();
        while ($fl = mysqli_fetch_array($qrypenjualan)) {
            array_push($respon["received"], $fl);
        }

        echo json_encode($respon);
    } else if (!empty($_REQUEST['data']) && $_REQUEST['data'] == 'halaman') {
// jika data tidak terisi/tidak terset
        $respon["sukses"] = 1;
        $respon["received"] = array();
        $tmp = array();
        $tmp[""] = "";
        $tmp[""] = "";
        $tmp[""] = "";
        $tmp[""] = "";
        array_push($respon["received"], $tmp);
        $respon["pesan"] = "Contoh Halaman";
// memprint/mencetak JSON respon
        echo json_encode($respon);
    } else {
// jika data tidak terisi/tidak terset
        $respon["sukses"] = 0;
        $respon["received"] = array();
        $respon["pesan"] = "Jenis Data Error";
// memprint/mencetak JSON respon
        echo json_encode($respon);
    }
} else {
// jika data tidak terisi/tidak terset
    $respon["sukses"] = 0;
    $respon["received"] = array();
    $respon["pesan"] = "Jenis Data Dan Api Key Error";
// memprint/mencetak JSON respon
    echo json_encode($respon);
}

?>