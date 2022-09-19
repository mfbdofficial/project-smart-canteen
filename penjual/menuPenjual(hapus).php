<?php
//MENANGANI AKSI HAPUS MENU


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
$username = $_SESSION["username"];
$idPenjual = $_SESSION["idPenjual"];
//pakai metode request GET, untuk nangkap id di url :
$id1 = $_GET["id"];


//Untuk Mulai Menghapus Menu
//kalo function hapusMenu() dan alert sudah dilakukan, langsung redirect :
if (hapusMenu($id1, $idPenjual) > 0) {
    echo "<script>
        alert('Data menu BERHASIL dihapus.');
        document.location.href='menuPenjual.php';
    </script>";
} else {
    echo "<script>
        alert('Data menu GAGAL dihapus.');
        document.location.href='menuPenjual.php';
    </script>";
} 
?>