<?php
//SEBAGAI HALAMAN PENJUAL (MENANGANI AKSI BATALKAN PESANAN CASH)


//Bagian Memulai Session & Koneksi Module
session_start(); //memulai session
require "fungsiPenjual.php"; //koneksi database dan user defined function


//Bagian Mengecek Session Penjual (Untuk Memastikan Sudah Login)
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginPenjual"])) {
    //kembalikan ke halaman login
    header("Location: ../index.php");
    exit;
}


//Bagian Menyimpan Data Session
$idPenjual = $_SESSION["idPenjual"];
$kodePesanan = $_GET["kodePesanan"];
/*
var_dump($kodePesanan); //hanya untuk debug
*/


//Bagian Mengambil Data Diperlukan
$iniPesanan = queryData("SELECT * FROM pesanan WHERE id_penjual = $idPenjual AND kode_pesanan = '$kodePesanan'");
$idPesanan = $iniPesanan[0]["id_pesanan"];


//Bagian Handle Aksi Gagalkan Pesanan Cash
//cek apakah sudah tekan tombol gagalkan?
if (isset($_POST["tombolGagalkan"])) {
    $idGagalkan = $_POST["idGagalkan"];
    $isiPesanGagal = $_POST["pesanGagal"];
    /*
    var_dump($isiPesanGagal); //hanya untuk debug
    var_dump($idGagalkan); //hanya untuk debug
    */
    //jalankan function yang dibuat sendiri di fungsiPenjual.php untuk gagalkan pesanan
    //cek apakah hasil return query true? dari function "gagalkanPesanan()" di fungsiPenjual.php
    if (gagalkanPesanan($idGagalkan, $isiPesanGagal)) { //membawa parameter "$idGagalkan" (id pesanan yang ingin digagalkan) dan "$isiPesanGagal" (isi keterangan gagalnya)
        echo "<script>
                alert('BERHASIL GAGALKAN pesanan.');
                document.location.href='menuPenjual.php';
            </script>";
    } else {
        echo "<script>
                alert('TIDAK BERHASIL GAGALKAN pesanan.');
                document.location.href='menuPenjual.php';
            </script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gagalkan Cash</title>
    <style>
        body {
            background: url('../admin/background-makanan-admin.jpg');
        }
    </style>
</head>
<body>
    <!--Bagian Tombol Batal Gagalkan Pesanan Cash-->
    <a href="menuPenjual.php">Tidak Jadi Membatalkan Pesanan</a>


    <!--Bagian Judul & Penjelasan Aksi Gagalkan Pesanan Cash-->
    <section class="penjelasan-gagalkan-cash">
        <h4>Membatalkan pesanan?</h4>
        <p>Membatalkan pesanan berarti menyelesaikan dengan status pesanan gagal, kecenderungan menyatakan pesanan gagal biasa disebabkan suatu alasan.</p>
        <p>Apakah terjadi pelanggaran oleh user pembeli? User pembeli tak kunjung datang? User pembeli tidak mau bayar? Silahkan tulis alasan pesan gagal untuk pertimbangan Admin.</p>
    </section>


    <!--Bagian Aksi Gagalkan Pesanan Cash-->
    <form action="" method="post">
        <h4>Silahkan Masukkan Pesan Keterangan :</h4>
        <input type="hidden" value="<?= $idPesanan; ?>" name="idGagalkan">
        <input type="text" name="pesanGagal">
        <button type="submit" onclick="confirm('Yakin GAGALKAN PESANAN?');" name="tombolGagalkan">Gagalkan</button>
    </form>
</body>
</html>