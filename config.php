<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set("Asia/Jakarta");
$host = "localhost"; // Nama host
$username = "u9017608_root"; // Username database
$password = "dhinie12"; // Password database
$database = "u9017608_tokoku"; // Nama database

/**$mysql_user="aplikasikuco_dbs";
 * $mysql_password="inJLdq@zJFZc";
 * $mysql_database="aplikasikuco_dbs";
 * FUngsi koneksi database.
 */

function conn($host, $username, $password, $database)
{
    $conn = mysqli_connect($host, $username, $password, $database);
    // Menampilkan pesan error jika database tidak terhubung
    return ($conn) ? $conn : "Koneksi database gagal: " . mysqli_connect_error();
}

$config = conn($host, $username, $password, $database);
?>