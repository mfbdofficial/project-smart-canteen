<?php
//UNTUK HANDLE TOMBOL HAPUS USER (ADMIN)


session_start(); //memulai session
require "fungsiAdmin.php"; ////koneksi database dan user defined function


//Mengecek Session Admin
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginAdmin"])) {
    //kembalikan ke halaman login
    header("Location: loginsmartcanteen.php");
    exit;
}


//Handle Aksi Hapus
//pakai metode request GET, untuk nangkap id di url :
$username1 = $_GET["username"];
$id1 = $_GET["id"];
//kalo function hapusUser() dan alert sudah dilakukan, langsung redirect :
if (hapusUser($username1, $id1) > 0) {
    echo "<script>
        alert('User ini BERHASIL dihapus.');
        document.location.href='userAdmin.php';
    </script>";
} else {
    echo "<script>
        alert('User ini GAGAL dihapus.');
        document.location.href='userAdmin.php';
    </script>";
} 
?> 