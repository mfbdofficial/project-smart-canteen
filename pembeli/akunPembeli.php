<?php 
//SEBAGAI HALAMAN AKUN (PEMBELI)


//Bagian Memulai Session & Koneksi Module
session_start(); //memulai session
require "fungsiPembeli.php"; //koneksi database dan user defined function


//Bagian Mengecek Session Pembeli (Untuk Memastikan Sudah Login)
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginPembeli"])) {
    //kembalikan ke halaman login
    header("Location: ../index.php");
    exit;
}


//Bagian Menyimpan Data Session (Untuk Memanggil Ke Database)
$username = $_SESSION["username"];
$idPembeli = $_SESSION["idPembeli"];


//Bagian Mengambil Data Untuk Ditampilakan
//query data dari table user_pembeli riwayat (khusus pembeli yang sesuai)
$iniPembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $idPembeli");
/*
$iniRiwayat = queryData("SELECT * FROM riwayat WHERE id_pembeli = $idPembeli");
*/
//dari function "queryData($queryPembeli)" yang sudah dibuat, sebenarnya isi variable diatas itu adalah $instances yang direturn
if (isset($_POST["searchDate"])) {
    $dateInput = $_POST["dateInput"];
    if ($dateInput == "") {
        $iniRiwayat = queryData("SELECT * FROM riwayat WHERE id_pembeli = $idPembeli");
    } else {
        $iniRiwayat = queryData("SELECT * FROM riwayat WHERE id_pembeli = $idPembeli AND DATE(tanggal) = '$dateInput'");
    }
} else if (isset($_POST["searchText"])) {
    $textInput = $_POST["textInput"];
    if ($textInput == "") {
        $iniRiwayat = queryData("SELECT * FROM riwayat WHERE id_pembeli = id_pembeli");
    } else {
        $iniRiwayat = queryData("SELECT * FROM riwayat r JOIN user_penjual p ON r.id_penjual = p.id_penjual WHERE (id_pembeli = $idPembeli) AND (r.menu_dipesan LIKE '%$textInput%' OR p.username_penjual LIKE '%$textInput%')");
    }
} else {
    $iniRiwayat = queryData("SELECT * FROM riwayat WHERE id_pembeli = $idPembeli");
}
//kalo dari sisi pembeli, maka ambil data joinnya dengan penjual untuk memperoleh informasinya, kalo sisi penjual kebalikannya
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Saldo (Pembeli)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Pacifico&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="akunPembeli.css">
</head>
<body>
    <!--Bagian Navigation Bar-->
    <nav>
        <img src="../LogoCanteen2.png">

        <div class="logo">
            <h4>Smart Canteen</h4>
        </div>
        
        <ul class="navigasi">
            <li><a href="homePembeli.php">Home</a></li>
            <li><a href="pesanPembeli.php">Pesan Menu</a></li>
            <li><a href="isiPembeli.php">Isi Saldo</a></li>
            <li><a href="#">Akun</a></li>
        </ul>

        <div class="menu-toggle">
            <input type="checkbox">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>


    <!--Bagian Tombol Logout-->
    <a class="tombolLogout" href="../logoutsmartcanteen.php" onclick="return confirm('Apakah yakin Logout dari halaman?');">Logout</a>
    

    <!--Bagian Informasi User Pembeli-->
    <section class="info-pribadi">
        <table class="tabel-luar">
            <tr>
                <td><img src="../GambarOrangSettings.png" alt=""></td>
                <td class="isi-info">
                    <table>
                        <tr>
                            <td>Username </td>
                            <td>: <?= $iniPembeli[0]["username_pembeli"]; ?> </td>                           
                        </tr>
                        <tr>
                            <td>Telepon </td>
                            <td>: <?= $iniPembeli[0]["no_telpon_pembeli"]; ?> </td>
                        </tr>
                        <tr>
                            <td>Email </td>
                            <td>: <?= $iniPembeli[0]["email_pembeli"]; ?></td>
                        </tr>
                        <tr>
                            <td>Password </td>
                            <td>: Tidak ditampilkan.</td>
                        </tr>
                    </table>
                    <div class="edit-akun">
                        <a href="akunPembeli(ubah).php">Edit Profile</a>
                    </div>
                </td>
            </tr>
        </table>
    </section>


    <!--Bagian Informasi User Pembeli (Mobile)-->
    <section class="info-pribadi-mobile">
        <table class="tabel-luar-mobile">
            <tr>
                <td><img src="../GambarOrangSettings.png" alt=""></td>
                <td class="isi-info-mobile">
                    <ul class="list-profile-mobile">
                        <li>
                            <p>Username :</p>                           
                            <p> <b> <?= $iniPembeli[0]["username_pembeli"]; ?> </b> </p>
                        </li>
                        <li>
                            <p>Telepon :</p>
                            <p> <b> <?= $iniPembeli[0]["no_telpon_pembeli"]; ?> </b> </p>
                        </li>
                        <li>
                            <p>Email :</p>
                            <p> <b> <?= $iniPembeli[0]["email_pembeli"]; ?> </b> </p>
                        </li>
                        <li>
                            <p>Password :</p>
                            <p> <b> Tidak ditampilkan. </b> </p>
                        </li>
                    </ul>
                    <div class="edit-akun-mobile">
                        <a href="akunPembeli(ubah).php">Edit Profile</a>
                    </div>
                </td>
            </tr>
        </table>
    </section>


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
                <input type="text" name="textInput"><button type="submit" name="searchText" placeholder="  Search..."> <b> Cari Text </b> </button>
            </form>
        </div>
    </section>


    <!--Bagian Menampilkan Data Riwayat-->
    <section class="riwayat-pembeli">
        <h3>Riwayat Transaksi :</h3>
        <?php
            if ($iniRiwayat) {
        ?>
                <table border="1" cellpadding="20" cellspacing="0">
                    <tr>
                        <td>Tanggal</td>
                        <td>Penjual</td>
                        <td>Pesanan</td>
                        <td>Total</td>
                        <td>Keterangan</td>
                    </tr>
                    <?php foreach ($iniRiwayat as $riwayat) : ?>
                        <?php if ($riwayat["keterangan"] == "berhasil") { ?> 
                            <tr class="instance-berhasil">
                                <td> <?= $riwayat["tanggal"]; ?> </td>
                                <!--  -->
                                <?php $id_penjual = $riwayat["id_penjual"]; ?>
                                <?php $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $id_penjual") ?>
                                <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                                <td> <?= $riwayat["menu_dipesan"]; ?> </td>
                                <td> <?= $riwayat["jumlah_transaksi"]; ?> </td>
                                <td> <?= $riwayat["keterangan"]; ?> </td>
                            </tr>
                        <?php } else { ?>
                            <tr class="instance-gagal">
                                <td> <?= $riwayat["tanggal"]; ?> </td>
                                <!--  -->
                                <?php $id_penjual = $riwayat["id_penjual"]; ?>
                                <?php $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $id_penjual") ?>
                                <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                                <td> <?= $riwayat["menu_dipesan"]; ?> </td>
                                <td> <?= $riwayat["jumlah_transaksi"]; ?> </td>
                                <td> <?= $riwayat["keterangan"]; ?> </td>
                            </tr>
                        <?php } ?>
                    <?php endforeach; ?>
                </table>
        <?php
            } else {
        ?>
                <h4>Belum ada riwayat atau riwayat yang anda cari tidak ada.</h4>
        <?php
            }
        ?>
    </section>


    <!--Bagian Menampilkan Data Riwayat (Mobile)-->
    <section class="riwayat-pembeli-mobile">
        <h3>Riwayat Transaksi :</h3>
        <?php
            if ($iniRiwayat) {
        ?>
                <table border="1" cellpadding="20" cellspacing="0">
                    <tr>
                        <td>Tanggal</td>
                        <td>Info</td>
                        <td>Keterangan</td>
                    </tr>
                    <?php foreach ($iniRiwayat as $riwayat) : ?>
                        <?php if ($riwayat["keterangan"] == "berhasil") { ?>
                            <tr class="instance-berhasil">
                                <td> <?= $riwayat["tanggal"]; ?> </td>
                                <!--  -->
                                <?php $id_penjual = $riwayat["id_penjual"]; ?>
                                <?php $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $id_penjual") ?>
                                <td>
                                    <ul class="list-info-mobile">
                                        <li>
                                            <p>Penjual :</p>
                                            <p> <?= $iniPenjual[0]["username_penjual"]; ?> </p>
                                        </li>
                                        <li>
                                            <p>Pesanan :</p>
                                            <p> <?= $riwayat["menu_dipesan"]; ?> (<?= $riwayat["jumlah_pesanan"]; ?>) </p>
                                        </li>
                                        <li>
                                            <p>Total :</p>
                                            <p> <?= $riwayat["jumlah_transaksi"]; ?> </p>
                                        </li>
                                    </ul> 
                                </td>
                                <td> <?= $riwayat["keterangan"]; ?> </td>
                            </tr>
                        <?php } else { ?>
                            <tr class="instance-gagal">
                                <td> <?= $riwayat["tanggal"]; ?> </td>
                                <!--  -->
                                <?php $id_penjual = $riwayat["id_penjual"]; ?>
                                <?php $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $id_penjual") ?>
                                <td>
                                    <ul class="list-info-mobile">
                                        <li>
                                            <p>Penjual :</p>
                                            <p> <?= $iniPenjual[0]["username_penjual"]; ?> </p>
                                        </li>
                                        <li>
                                            <p>Pesanan :</p>
                                            <p> <?= $riwayat["menu_dipesan"]; ?> (<?= $riwayat["jumlah_pesanan"]; ?>) </p>
                                        </li>
                                        <li>
                                            <p>Total :</p>
                                            <p> <?= $riwayat["jumlah_transaksi"]; ?> </p>
                                        </li>
                                    </ul> 
                                </td>
                                <td> <?= $riwayat["keterangan"]; ?> </td>
                            </tr>
                        <?php } ?>    
                    <?php endforeach; ?>
                </table>
        <?php
            } else {
        ?>
                <h4>Belum ada riwayat atau riwayat yang anda cari tidak ada.</h4>
        <?php
            }
        ?>
    </section>


    <!--Bagian Debug-->
    <?php //var_dump($_POST); ?> <!--ini hanya untuk debug, nanti hilangkan-->
    <br><br><br><br> <!--untuk mengatasi iklan di bawah konten-->


    <script src="akunPembeli.js"></script>
</body>
</html>