<?php
$now = date("Y-m-d H:i:s");
$apikey = "746ca866df48a4b0bbe501fbc15df308";

function indoDate($date)
{
    $exp = explode("-", substr($date, 0, 10));
    return $exp[2] . ' ' . month($exp[1]) . ' ' . $exp[0];
}

function indoBln($date)
{
    $exp = explode("-", substr($date, 0, 10));
    return $exp[2] . ' ' . bln($exp[1]) . ' ' . $exp[0];
}

/**
 * Fungsi untuk mengkonversi format bulan angka menjadi nama bulan.
 */
function month($kode)
{
    $month = '';
    switch ($kode) {
        case '01':
            $month = 'Januari';
            break;
        case '02':
            $month = 'Februari';
            break;
        case '03':
            $month = 'Maret';
            break;
        case '04':
            $month = 'April';
            break;
        case '05':
            $month = 'Mei';
            break;
        case '06':
            $month = 'Juni';
            break;
        case '07':
            $month = 'Juli';
            break;
        case '08':
            $month = 'Agustus';
            break;
        case '09':
            $month = 'September';
            break;
        case '10':
            $month = 'Oktober';
            break;
        case '11':
            $month = 'November';
            break;
        case '12':
            $month = 'Desember';
            break;
    }
    return $month;
}

function bln($kode)
{
    $month = '';
    switch ($kode) {
        case '01':
            $month = 'Jan';
            break;
        case '02':
            $month = 'Feb';
            break;
        case '03':
            $month = 'Mar';
            break;
        case '04':
            $month = 'Apr';
            break;
        case '05':
            $month = 'Mei';
            break;
        case '06':
            $month = 'Jun';
            break;
        case '07':
            $month = 'Jul';
            break;
        case '08':
            $month = 'Agu';
            break;
        case '09':
            $month = 'Sep';
            break;
        case '10':
            $month = 'Okt';
            break;
        case '11':
            $month = 'Nov';
            break;
        case '12':
            $month = 'Des';
            break;
    }
    return $month;
}

function hari_ini()
{
    $hari = date("D");

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return $hari_ini;

}

?>