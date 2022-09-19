<?php
//MENANGANI AKSI SELESAIKAN PESANAN CASH


session_start(); //memulai session
require "fungsiPenjual.php"; //koneksi database dan user defined function


//Mengecek Session Penjual
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginPenjual"])) {
    //kembalikan ke halaman login
    header("Location: ../index.php");
    exit;
}


//Menyimpan Data Diperlukan
$idPenjual = $_SESSION["idPenjual"];
$kodePesanan = $_GET["kodePesanan"];
/*
var_dump($kodePesanan); //hanya untuk debug
*/


//Mengambil Data Diperlukan
$iniPesanan = queryData("SELECT * FROM pesanan WHERE id_penjual = $idPenjual AND kode_pesanan = '$kodePesanan'");
$idPesanan = $iniPesanan[0]["id_pesanan"];


//Untuk Mulai Menyelesaikan Pesanan Cash
//cek apakah sudah tekan tombol gagalkan?
if (isset($_POST["tombolSelesaikan"])) {
    $idSelesaikan = $_POST["idSelesaikan"];
    /*
    var_dump($idSelesaikan); /hanya untuk debug
    */
    //jalankan function yang dibuat sendiri di fungsiPenjual.php untuk selesaikan pesanan
    //cek apakah hasil return query true? dari function "selesaikanPesanan()" di fungsiPenjual.php
    if (selesaikanPesanan($idSelesaikan)) { //membawa parameter "$idSelesaikan" (id pesanan yang ingin diselesaikan)
        echo "<script>
                alert('BERHASIL SELESAIKAN pesanan.');
                document.location.href='menuPenjual.php';
            </script>";
    } else {
        echo "<script>
                alert('TIDAK BERHASIL SELESAIKAN pesanan.');
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
    <title>Document</title>
    <style>
        body {
            background-color: #fae04b;
        }
    </style>
</head>
<body>
    <a href="menuPenjual.php">Tidak Jadi Selesaikan Pesanan</a>

    <h4>Pembeli Sudah Membayar?</h4>

    <p>Menyelesaikan pesanan berarti menganggap pembeli sudah membayar dan transaksi berjalan dengan baik tanpa masalah. </p>
    <p>Jadi pastikan anda sebagai penjual sudah menerima bayaran dari pembeli.</p>
    
    <form action="" method="post">
        <input type="hidden" value="<?= $idPesanan; ?>" name="idSelesaikan">
        <button type="submit" onclick="confirm('Yakin SELESAIKAN PESANAN?');" name="tombolSelesaikan">Selesaikan</button>
    </form>
</body>
</html>
