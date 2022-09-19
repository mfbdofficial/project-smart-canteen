<?php
//SEBAGAI HALAMAN PENJUAL (MENANGANI AKSI TAMBAH MENU)


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
$username = $_SESSION["username"];
$idPenjual = $_SESSION["idPenjual"];


//Bagian Handle Aksi Menambahkan Menu
//cek apakah tombol tambahkan sudah ditekan?
if (isset($_POST["tekan"])) {
    //Query Insert Data, dengan cara pakai function (ada di halaman terpisah)
    if (tambahMenu($_POST) > 0 ) {
        //misal notif pakai fungsi javascript "alert" untuk munculin kotak pesan (pop-up)
        //fungsi "document.location.href='halaman_dituju'" adalah fungsi redirect untuk bahasa javascript
        echo "<script>
            alert('Data menu BERHASIL ditambahkan.');
            document.location.href='menuPenjual.php';
        </script>";
    } else {
        echo "<script>
            alert('Data menu GAGAL ditambahkan.');
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
    <title>Tambah Menu</title>
    <style>
        body {
            background: url('../admin/background-makanan-admin.jpg');
        }
        
        
        .aksi-tambah-menu li {
            padding: 0.5rem;
        }
    </style>
</head>
<body>
    <!--Bagian Tulisan Tambah Data Menu-->
    <h1>Tambah Data Menu</h1>


    <!--Bagian Form Aksi Tambah Menu-->
    <!--di sini pakai metode request POST-->
    <!--enctype="multipart/form-data" membuat form punya 2 jalur. 1 dikelola $_POST dan 1 dikelola $_FILES-->
    <section class="aksi-tambah-menu">
        <form actions="" method="post" enctype="multipart/form-data">
            <ul>
                <!--pakai "required" untuk input agar data harus wajib diisi-->
                <li>
                    <label for="kategori">Kategori :</label>
                    <input type="text" name="kategori" id="kategori" placeholder='"makanan" atau "minuman"' required>
                </li>
                <li>
                    <label for="nama">Nama Menu :</label>
                    <input type="text" name="nama" id="nama" pattern="[a-zA-Z ]+" required oninvalid="this.setCustomValidity('Nama makanan hanya boleh huruf')" oninput="this.setCustomValidity('')">
                </li>
                <li>
                    <label for="harga">Harga :</label>
                    <input type="text" name="harga" id="harga" pattern="[0-9]+" required oninvalid="this.setCustomValidity('Harga makanan hanya boleh angka')" oninput="this.setCustomValidity('')">
                </li>
                <li>
                    <label for="info">Info Menu :</label>
                    <input type="text" name="info" id="info">
                </li>
                <!--Mulai bagian input untuk upload bentuk file-->
                <!--enctype="multipart/form-data" di atas membuat isi data  bertipe file di form "gambar" gaada lagi di variable "_POST"
                tapi adanya di variable superglobal "_FILES"-->
                <li>
                    <label for="gambar">File Gambar Menu :</label>
                    <input type="file" name="gambar" id="gambar">
                </li>
                <li>
                    <button type="submit" name="tekan">Tambah Menu</button>
                </li>
            </ul>
        </form>
    </section>
    <!--ingat yang dipakai dari input di form adalah (name="...")-nya bukan id-nya-->   
</body>
</html>