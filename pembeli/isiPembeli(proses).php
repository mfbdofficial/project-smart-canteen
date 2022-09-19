<?php
//MENANGANI AKSI MEMBUAT REQUEST ISI SALDO


session_start(); //memulai session
require "fungsiPembeli.php"; //koneksi database dan user defined function


//Mengecek Session Pembeli
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginPembeli"])) {
    //kembalikan ke halaman login
    header("Location: ../index.php");
    exit;
}


//Mengecek Keberadaan Data Nominal Saldo Yang Dibawa
if (!isset($_GET["nominalSaldo"]) && !isset($_POST["nominalSaldo"])) {
    /*
    echo "<script>alert('sampe sini udah jalan cuy');</script>"; //hanya untuk debug
    die;
    */
    header("Location: isiPembeli.php");
}


//Tangkap Info Nominal Saldo & Id Pembeli Dari Variable Superglobals
if (isset($_GET["nominalSaldo"])) {
    $nominalRequest = intval(htmlspecialchars($_GET["nominalSaldo"])); //"intval()" untuk mengubah data string menjadi integer
} else {
    $nominalRequest = intval(htmlspecialchars($_POST["nominalSaldo"])); //kalo diisi huruf maka integer akan menghasilkan 0
}
/*
var_dump($nominalRequest); //hanya untuk debug
var_dump($_SESSION["idPembeli"]); hanya untuk debug
*/


//Validasi Data Nominal Saldo
//cek dahulu validasi pengisian saldo tidak boleh 0 (bisa jadi diisi o atau diisi bukan integer)
//atau cek juga pengisian minimal 1000 (tidak boleh kurang)
if($nominalRequest == 0 || $nominalRequest < 1000 || $nominalRequest > 1000000) {
    echo "<script> alert('TOLONG MASUKKAN SALDO DENGAN BENAR, BUKAN 0 DAN BUKAN HURUF, PENGISIAN MINIMAL Rp. 1000, MAKSIMAL Rp. 1.000.000'); 
            document.location.href='isiPembeli.php'
        </script>";
    die;
}


//Menyiapkan Data Diperlukan
$idPembeli = $_SESSION["idPembeli"];
$pembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $idPembeli");
$usernamePembeli = $pembeli[0]["username_pembeli"];
$emailPembeli = $pembeli[0]["email_pembeli"];
/*
var_dump($usernamePembeli); //hanya untuk debug
var_dump($emailPembeli); //hanya untuk debug
*/


//Untuk Mulai Membuat Request Saldo
//masukkan ke database request_saldo
if(masukkanRequestSaldo($idPembeli, $usernamePembeli, $emailPembeli, $nominalRequest) == true) {
    //beri pesan kalo berhasil & Redirect Kembali Ke isiPembeli.php
    echo "<script> alert('Request isi saldo BERHASIL dibuat.'); 
            document.location.href='isiPembeli.php'
        </script>";    
} else {
    //beri pesan kalo gagal & Redirect Kembali Ke isiPembeli.php
    echo "<script> alert('Request isi saldo GAGAL dibuat.'); 
            document.location.href='isiPembeli.php'
        </script>";
};


//Redirect Kembali Ke isiPembeli.php
/*
header("Location: iniPembeli.php");
*/
//kalo redirect-nya di luar tidak bersama pesan alert maka akan langsung dijalankan tanpa nampilkan alert
//karena di atas itu sebenarnya jalankan "echo" dahulu baru program jalan ke bawah dan lalu barulah nantinya alert dari JavaScript yang berjalan