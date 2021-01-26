<?php 
session_start();
include('include/config.php'); 
$provinsi = $_GET['provinsi'];
echo "<option value='Semua'>-- Pilih Semua Kabupaten/Kota --</option>";
$query = mysqli_query($config, "SELECT * FROM regencies WHERE province_id='$provinsi'");
while ($data = mysqli_fetch_array($query)) {
    echo '<option value="'.$data['id'].'">'.$data['name'].'</option>';
}
?>