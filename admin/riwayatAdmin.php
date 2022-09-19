<?php 
//SEBAGAI HALAMAN RIWAYAT (ADMIN)


//Bagian Memulai Session & Koneksi Module
session_start(); //memulai session
require "fungsiAdmin.php"; //koneksi database dan user defined function


//Bagian Mengecek Session Admin (Untuk Memastikan Sudah Login)
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginAdmin"])) {
    //kembalikan ke halaman login
    header("Location: ../index.php");
    exit;
}


//Bagian Mengambil Data Untuk Ditampilkan
//query data dari table riwayat untuk keterangan gagal dan berhasil
/*
$iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE keterangan = 'berhasil'");
$iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE keterangan = 'gagal'");
*/
//dari function "queryData($queryAdmin)" yang sudah dibuat, sebenarnya isi variable di atas adalah $instances yang di-return
if (isset($_POST["searchDate"])) {
    $dateInput = mysqli_real_escape_string($db, strip_tags($_POST["dateInput"]));
    if ($dateInput == "") {
        $iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE keterangan = 'berhasil'");
        $iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE keterangan = 'gagal'");
    } else {
        $iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE keterangan = 'berhasil' AND DATE(tanggal) = '$dateInput'");
        $iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE keterangan = 'gagal' AND DATE(tanggal) = '$dateInput'");
    }
} else if (isset($_POST["searchText"])) {
    $textInput = $_POST["textInput"];
    if ($textInput == "") {
        $iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE keterangan = 'berhasil'");
        $iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE keterangan = 'gagal'");
    } else {
        $iniRiwayatSelesai = queryData("SELECT * FROM riwayat r JOIN user_penjual p ON r.id_penjual = p.id_penjual JOIN user_pembeli pp ON r.id_pembeli = pp.id_pembeli WHERE (r.keterangan = 'berhasil') AND (r.menu_dipesan LIKE '%$textInput%' OR p.username_penjual LIKE '%$textInput%' OR pp.username_pembeli LIKE '%$textInput%')");
        $iniRiwayatGagal = queryData("SELECT * FROM riwayat r JOIN user_penjual p ON r.id_penjual = p.id_penjual JOIN user_pembeli pp ON r.id_pembeli = pp.id_pembeli WHERE (r.keterangan = 'gagal') AND (r.menu_dipesan LIKE '%$textInput%' OR p.username_penjual LIKE '%$textInput%' OR pp.username_pembeli LIKE '%$textInput%')");
    }
} else {
    $iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE keterangan = 'berhasil'");
    $iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE keterangan = 'gagal'");
}
//MINUS KEAMANANNYA KALO TEXT DAN JOIN SUPAYA BISA SEARCH BERDASARKAN PENJUAL
//sifat $_POST akan hilang kali halaman direfresh
//konsep query rumit di atas adalah melakukan JOIN 2 table lalu mencari dengan WHERE ada lebih dari kondisi
//jadi di WHERE clause-nya pada kondisi diberikan tanda kurung untuk menentukan yang dijalankan lebih dahulu (OR atau AND duluan atau maunya gimana bisa terserah)
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat (Admin)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Pacifico&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="riwayatAdmin.css">
</head>
<body>
    <!--Bagian Navigation Bar-->
    <nav>
        <img src="../LogoCanteen2.png">

        <div class="logo">
            <h4>Smart Canteen</h4>
        </div>
        
        <ul class="navigasi">
            <li><a href="saldoAdmin.php">Saldo</a></li>
            <li><a href="userAdmin.php">User</a></li>
            <li><a href="">Riwayat</a></li>
        </ul>

        <div class="menu-toggle">
            <input type="checkbox">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>


    <!--Bagian Tombol Logout-->
    <a class="tombolLogout" href="../logoutsmartcanteen.php" onclick="return confirm('Apakah yakin Logout dari halaman?');" >Logout</a>
    
    
    <!--Bagian Search Riwayat-->
    <section class="pencarian-riwayat">
        <div class="pencarian-tanggal">
            <h3>Jika ingin mencari dengan tanggal</h3>
            <form action="" method="post">
                <input type="date" name="dateInput">
                <button type="submit" name="searchDate"> <b> Cari Tanggal </b> </button>
            </form>
        </div>
        <div class="pencarian-teks">
            <h3>Jika ingin mencari dengan teks</h3>
            <form action="" method="post">
                <input type="text" name="textInput" placeholder="  Search..."><button type="submit" name="searchText"> <b> Cari Teks </b> </button>
            </form>
        </div>
    </section>


    <!--Bagian Menampilkan Data Riwayat-->
    <section class="info-riwayat">
        <div class="riwayatSelesai">
            <h1>Transaksi Selesai</h1>
            <?php
                if ($iniRiwayatSelesai) {
            ?>
                    <table border="1" cellpadding="10" cellspacing="0">
                        <tr>
                            <td>Tanggal</td>
                            <td>Username Pembeli</td>
                            <td>Username Penjual</td>
                            <td>Pesanan</td>
                            <td>Total</td>
                        </tr>
                        <!--selipkan bahasa PHP membuat variable "i" untuk bagian nomer dengan -->
                        <?php $i = 1; ?>
                        <!--selipkan bahasa PHP untuk membuat foreach, jadi sistemnya perintah foreach-nya menjalankan ngerluarin tag HTML-->
                        <?php foreach( $iniRiwayatSelesai as $riwayatSelesai ) : ?>
                            <tr>
                                <td> <?= $riwayatSelesai["tanggal"]; ?> </td>
                                <!--query untuk ambil username pembeli-->
                                <?php $id_pembeli = $riwayatSelesai["id_pembeli"]; ?>
                                <?php $iniPembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $id_pembeli"); ?>
                                <td> <?= $iniPembeli[0]["username_pembeli"]; ?> </td>
                                <!--query untuk ambil username penjual-->
                                <?php $id_penjual = $riwayatSelesai["id_penjual"]; ?>
                                <?php $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $id_penjual"); ?>
                                <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                                <td> <?= $riwayatSelesai["menu_dipesan"]; ?> </td> 
                                <td> <?= $riwayatSelesai["jumlah_transaksi"]; ?> </td> 
                            </tr>
                            <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </table>
            <?php
                } else {
                    echo "Tidak ada transaksi pada tanggal yang anda pilih.";
                }
            ?>
            <!--query nampilin array di atas 0 karena id cuma akan ada 1 (ga sama). array-nya akan double tapi cuma ada index pertama saja-->
        </div>

        <div class="riwayatGagal">
            <h1>Transaksi Gagal</h1>
            <?php
                if ($iniRiwayatGagal) {
            ?>
                    <table border="1" cellpadding="10" cellspacing="0">
                        <tr>
                            <td>Tanggal</td>
                            <td>Username Pembeli</td>
                            <td>Username Penjual</td>
                            <td>Pesanan</td>
                            <td>Total</td>
                            <td>Keterangan</td>
                        </tr>
                        <!--selipkan bahasa PHP membuat variable "i" untuk bagian nomer dengan -->
                        <?php $i = 1; ?>
                        <!--selipkan bahasa PHP untuk membuat foreach, jadi sistemnya perintah foreach-nya menjalankan ngerluarin tag HTML-->
                        <?php foreach( $iniRiwayatGagal as $riwayatGagal ) : ?>
                            <tr>
                                <td> <?= $riwayatGagal["tanggal"]; ?> </td>
                                <!--query untuk ambil username pembeli-->
                                <?php $id_pembeli = $riwayatGagal["id_pembeli"]; ?>
                                <?php $iniPembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $id_pembeli"); ?>
                                <td> <?= $iniPembeli[0]["username_pembeli"]; ?> </td>
                                <!--query untuk ambil username penjual-->
                                <?php $id_penjual = $riwayatGagal["id_penjual"]; ?>
                                <?php $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $id_penjual"); ?>
                                <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                                <td> <?= $riwayatGagal["menu_dipesan"]; ?> </td> 
                                <td> <?= $riwayatGagal["jumlah_transaksi"]; ?> </td> 
                                <td> <?= $riwayatGagal["alasan"]; ?> </td>
                            </tr>
                            <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </table>
            <?php
                } else {
                    echo "Tidak ada transaksi pada tanggal yang anda pilih.";
                }
            ?>
        </div>
    </section>


    <section class="info-riwayat-mobile">
        <div class="riwayatSelesai-mobile">
            <h1>Transaksi Selesai</h1>
            <?php
                if ($iniRiwayatSelesai) {
            ?>
                    <table border="1" cellpadding="10" cellspacing="0">
                        <tr>
                            <td>Tanggal</td>
                            <td>Info</td>
                            <td>Total</td>
                        </tr>
                        <!--selipkan bahasa PHP membuat variable "i" untuk bagian nomer dengan -->
                        <?php $i = 1; ?>
                        <!--selipkan bahasa PHP untuk membuat foreach, jadi sistemnya perintah foreach-nya menjalankan ngerluarin tag HTML-->
                        <?php foreach( $iniRiwayatSelesai as $riwayatSelesai ) : ?>
                            <tr>
                                <td> <?= $riwayatSelesai["tanggal"]; ?> </td>
                                <!--query untuk ambil username pembeli-->
                                <?php $id_pembeli = $riwayatSelesai["id_pembeli"]; ?>
                                <?php $iniPembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $id_pembeli"); ?>
                                <!--query untuk ambil username penjual-->
                                <?php $id_penjual = $riwayatSelesai["id_penjual"]; ?>
                                <?php $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $id_penjual"); ?>
                                <td>
                                    <ul class="list-info-mobile">
                                        <li>
                                            <p>Pembeli :</p>
                                            <p> <?= $iniPembeli[0]["username_pembeli"]; ?> </p>
                                        </li>
                                        <li>
                                            <p>Penjual :</p>
                                            <p> <?= $iniPenjual[0]["username_penjual"]; ?> </p>
                                        </li>
                                        <li>
                                            <p>Pesanan :</p>
                                            <p> <?= $riwayatSelesai["menu_dipesan"]; ?> </p>
                                        </li>
                                    </ul>
                                </td>
                                <td> <?= $riwayatSelesai["jumlah_transaksi"]; ?> </td> 
                            </tr>
                            <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </table>
            <?php
                } else {
                    echo "Tidak ada transaksi pada tanggal yang anda pilih.";
                }
            ?>
        </div>

        <div class="riwayatGagal-mobile">
            <h1>Transaksi Gagal</h1>
            <?php
                if ($iniRiwayatGagal) {
            ?>
                    <table border="1" cellpadding="10" cellspacing="0">
                        <tr>
                            <td>Tanggal</td>
                            <td>Info</td>
                            <td>Total</td>
                            <td>Keterangan</td>
                        </tr>
                        <!--selipkan bahasa PHP membuat variable "i" untuk bagian nomer dengan -->
                        <?php $i = 1; ?>
                        <!--selipkan bahasa PHP untuk membuat foreach, jadi sistemnya perintah foreach-nya menjalankan ngerluarin tag HTML-->
                        <?php foreach( $iniRiwayatGagal as $riwayatGagal ) : ?>
                            <tr>
                                <td> <?= $riwayatGagal["tanggal"]; ?> </td>
                                <!--query untuk ambil username pembeli-->
                                <?php $id_pembeli = $riwayatGagal["id_pembeli"]; ?>
                                <?php $iniPembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $id_pembeli"); ?>
                                <!--query untuk ambil username penjual-->
                                <?php $id_penjual = $riwayatGagal["id_penjual"]; ?>
                                <?php $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $id_penjual"); ?>
                                <td>
                                    <ul class="list-info-mobile">
                                        <li>
                                            <p>Pembeli :</p>
                                            <p> <?= $iniPembeli[0]["username_pembeli"]; ?> </p>
                                        </li>
                                        <li>
                                            <p>Penjual :</p>
                                            <p> <?= $iniPenjual[0]["username_penjual"]; ?> </p>
                                        </li>
                                        <li>
                                            <p>Pesanan :</p>
                                            <p> <?= $riwayatGagal["menu_dipesan"]; ?> </p>
                                        </li>
                                    </ul>
                                </td>
                                <td> <?= $riwayatGagal["jumlah_transaksi"]; ?> </td> 
                                <td> <?= $riwayatGagal["alasan"]; ?> </td>
                            </tr>
                            <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </table>
            <?php
                } else {
                    echo "Tidak ada transaksi pada tanggal yang anda pilih.";
                }
            ?>
        </div>
        </div>
    </section>
    
    <!--Bagian Debug-->
    <?php //var_dump($_POST); ?> <!--ini hanya untuk debug, nanti hilangkan-->
    <br><br><br><br> <!--untuk mengatasi iklan di bawah konten-->

    
    <script src="riwayatAdmin.js"></script>
</body>
</html>