<?php
//MENANGANI AKSI MEREQUEST MENCAIRKAN 


session_start(); //memulai session
require "fungsiPenjual.php"; //koneksi database dan user defined function


//Mengecek Session Penjual
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginPenjual"])) {
    //kembalikan ke halaman login
    header("Location: ../index.php");
    exit;
}


//Mengecek Data Nominal
//cek apakah ada data post nominal sald0?
if (!isset($_POST["nominalSaldo"])) {
    /*
    echo "<script>alert('sampe sini udah jalan cuy');</script>"; //hanya untuk debug
    die;
    */
    header("Location: riwayatPenjual.php");
}


//Menyimpan Data Diperlukan
$nominalRequest = intval(htmlspecialchars($_POST["nominalSaldo"]));
$idPenjual = $_SESSION["idPenjual"];
$result = mysqli_query($db, "SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
$penjual = mysqli_fetch_assoc($result);
$saldoSekarang = $penjual["saldo_penjual"];
$usernamePenjual = $penjual["username_penjual"];
$emailPenjual = $penjual["email_penjual"];
/*
var_dump($saldoSekarang); //hanya untuk debug
die;
*/


//Cek Validasi Nominal Request
if ($nominalRequest == 0 || $nominalRequest < 1000 || $nominalRequest > $saldoSekarang ) {
    echo "<script> alert('Tolong masukkan jumlah nominal dengan benar, BUKAN 0 dan BUKAN HURUF, pencairan MINIMAL Rp. 1000 dan TIDAK MELEBIHI JUMLAH SALDO Anda.'); 
            document.location.href='riwayatPenjual.php'
        </script>";
    die;
}


//Untuk Mulai Membuat Request Mencairkan
//masukkan ke database request_cair 
if (masukkanRequestCair($idPenjual, $usernamePenjual, $emailPenjual, $nominalRequest) == true) {
    //beri pesan kalo berhasil & Redirect Kembali Ke isiPembeli.php
    echo "<script> alert('Request mencairkan BERHASIL dibuat.'); 
            document.location.href='riwayatPenjual.php'
        </script>";    
} else {
    //beri pesan kalo gagal & Redirect Kembali Ke isiPembeli.php
    echo "<script> alert('Request mencairkan GAGAL dibuat.'); 
            document.location.href='riwayatPenjual.php'
        </script>";
}
?>