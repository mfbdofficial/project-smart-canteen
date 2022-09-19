<?php 
//SEBAGAI HALAMAN RIWAYAT (PENJUAL)


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


//Bagian Menyimpan Data Session (Untuk Memanggil Ke Database)
$username = $_SESSION["username"];
$idPenjual = $_SESSION["idPenjual"];


//Bagian Mengambil Data Untuk Ditampilkan
//query data dari table user_penjual dan riwayat dibagi keterangan berhasil dan gagal (khusus penjual yang susuai)
$iniPenjual = queryData("SELECT * FROM user_penjual WHERE username_penjual = '$username'");
/*
$iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'berhasil'");
$iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'gagal'");
*/
//dari function "queryData($queryPenjual)" yang sudah dibuat, sebenarnya isi variable diatas itu adalah $instances yang direturn
$iniRequestMencairkan = queryData("SELECT * FROM request_cair WHERE id_penjual = $idPenjual");
if (isset($_POST["searchDate"])) {
    $dateInput = $_POST["dateInput"];
    if ($dateInput == "") {
        $iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'berhasil'");
        $iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'gagal'");
    } else {
        $iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'berhasil' AND DATE(tanggal) = '$dateInput'");
        $iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'gagal' AND DATE(tanggal) = '$dateInput'");
    }
} else if (isset($_POST["searchText"])) {
    $textInput = $_POST["textInput"];
    if ($textInput == "") {
        $iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'berhasil'");
        $iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'gagal'");
    } else {
        $iniRiwayatSelesai = queryData("SELECT * FROM riwayat r JOIN user_pembeli p ON r.id_pembeli = p.id_pembeli WHERE (r.id_penjual = $idPenjual) AND (r.keterangan = 'berhasil') AND (r.menu_dipesan LIKE '%$textInput%' OR p.username_pembeli LIKE '%$textInput%')");
        $iniRiwayatGagal = queryData("SELECT * FROM riwayat r JOIN user_pembeli p ON r.id_pembeli = p.id_pembeli WHERE (r.id_penjual = $idPenjual) AND (r.keterangan = 'gagal') AND (r.menu_dipesan LIKE '%$textInput%' OR p.username_pembeli LIKE '%$textInput%')");
    }
} else {
    $iniRiwayatSelesai = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'berhasil'");
    $iniRiwayatGagal = queryData("SELECT * FROM riwayat WHERE id_penjual = $idPenjual AND keterangan = 'gagal'");
}
//kalo dari sisi penjual, maka ambil data joinnya dengan pembeli untuk memperoleh informasinya, kalo sisi pembeli kebalikannya
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat (Penjual)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Pacifico&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="riwayatPenjual.css">
</head>
<body>
    <!--Bagian Navigation Bar-->
    <nav>
        <img src="../LogoCanteen2.png">

        <div class="logo">
            <h4>Smart Canteen</h4>
        </div>
        
        <ul class="navigasi">
            <li><a href="menuPenjual.php">Menu</a></li>
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
    <a class="tombolLogout" href="../logoutsmartcanteen.php" onclick="return confirm('Apakah yakin Logout dari halaman?');">Logout</a>


    <section class="template-saldo">
        <!--Bagian Menampilkan Saldo Penjual-->
        <div class="infoSaldo">
            <p>Saldo anda :</p>
            <h4 class="saldoAnda"> <b> Rp. <?= $iniPenjual[0]["saldo_penjual"] ?> </b> </h4>
        </div>
    
    
        <!--Bagian Tombol Edit Profile-->
        <div class="editProfile">
            <a class="edit-penjual" href="riwayatPenjual(ubah).php"> <b> Edit Profile </b> </a>
        </div>
    

        <!--Bagian Request Mencairkan Saldo (Menampilkan & Membuat Request-->
        <div class="requestMencairkan">
            <?php if($iniRequestMencairkan) : ?>
                <h3>Request Pencairan Anda</h3>
                <table border="1" cellpadding="10" cellspacing="0">
                    <tr>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Total</td>
                        <td>Aksi</td>
                    </tr>
                    <?php
                        //Perhitungan Total Requst Saldo-nya
                        $totalRequest = 0;
                        foreach ($iniRequestMencairkan as $request) : 
                        $totalRequest += $request["nominal_request"];
                    ?>
                        <tr>
                            <td> <?= $request["username_penjual"] ?> </td>
                            <td> <?= $request["email_penjual"]; ?> </td>
                            <td> <?= $request["nominal_request"]; ?> </td>
                            <td class="td-tombol">
                                <!--Proses selesaikan pesanan akan method POST ke homePembeli(selesaikan).php-->
                                <form class="tombolAksiCair" action="" method="post">
                                    <input type="hidden" value="<?= $request["id_cair"]; ?>" name="idCair">
                                    <button type="submit" class="selesaikan" name="selesaikanCair" onclick="return confirm('YAKIN SELESAIKAN REQUEST PENCAIRAN?');">Selesaikan</button> 
                                </form>
                                | 
                                <!--Proses selesaikan pesanan akan method POST ke homePembeli(selesaikan).php-->
                                <form class="tombolAksiCair" action="" method="post">
                                    <input type="hidden" value="<?= $request["id_cair"]; ?>" name="idCair">
                                    <button type="submit" class="batalkan" name="batalkanCair" onclick="return confirm('YAKIN BATALKAN REQUEST PENCAIRAN?');">Batalkan</button> 
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4>Total request pencairan anda : <?= $totalRequest ?></h4>
             <?php endif; ?> 
    
            <!--cek apakah belum ada request? dengan cek apakah semua yang diquery kosong-->
            <?php if($iniRequestMencairkan == false) : ?>
                <h4>Sedang tidak ada</h4>
                <h4>request mencairkan saldo.</h4>
            <?php endif; ?>
        </div>
        

        <!--Bagian Tombol Request Mencairkan Saldo-->
        <div class="aksi-mencairkan">
            <form action="riwayatPenjual(proses).php" method="post">
                <div class="posisi">
                    <p>Nominal untuk request mencairkan saldo :</p>
                    <input type="number" name="nominalSaldo" min="1000" step="1000">   
                </div>
                <button class="tombol" type="submit">Minta Saldo</button>
            </form>
        </div>
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
                <input type="text" name="textInput"><button type="submit" name="searchText" placeholder="  Search..."> <b> Cari Teks </b> </button>
            </form>
        </div>
    </section>


    <!--Bagian Menampilkan Data Riwayat-->
    <section class="info-riwayat">
        <div class="riwayatSelesai">
            <h3>Transaksi Selesai</h2>
            <?php 
                if($iniRiwayatSelesai) {   
            ?>
                    <table border="1" cellpadding="10" cellspacing="0">
                        <tr>
                            <td>No</td>
                            <td>Tanggal</td>
                            <td>Pemesan</td>
                            <td>Menu Dipesan</td>
                            <td>Total</td>
                        </tr> 
                        <?php $i = 1; ?>
                        <?php foreach($iniRiwayatSelesai as $riwayatSelesai) : ?>
                            <tr>
                                <td> <?= $i; ?>.</td>
                                <td> <?= $riwayatSelesai["tanggal"]; ?> </td>
                                <!--query untuk ambil username pembeli-->
                                <?php $id_pembeli = $riwayatSelesai["id_pembeli"]; ?>
                                <?php $iniPembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $id_pembeli"); ?>
                                <td> <?= $iniPembeli[0]["username_pembeli"]; ?> </td>
                                <td> <?= $riwayatSelesai["menu_dipesan"]; ?> </td>
                                <td>Rp. <?= $riwayatSelesai["jumlah_transaksi"]; ?> </td>
                            </tr>
                            <?php $i++; ?> 
                        <?php endforeach; ?>  
                    </table>
            <?php 
                } else { 
            ?>
                    <h4>Belum ada transaksi yang diselesaikan.</h4>
            <?php 
                } 
            ?> 
        </div>
        
        <div class="riwayatGagal">
            <h3>Transaksi Dibatalkan</h2>
            <?php 
                if($iniRiwayatGagal) { 
            ?>
                    <table border="1" cellpadding="10" cellspacing="0">
                        <tr>
                            <td>No</td>
                            <td>Tanggal</td>
                            <td>Pemesan</td>
                            <td>Menu Dipesan</td>
                            <td>Total</td>
                            <td>Keterangan</td>
                        </tr> 
                        <?php $i = 1; ?>
                        <?php foreach($iniRiwayatGagal as $riwayatGagal) : ?>
                            <tr>
                                <td> <?= $i; ?>.</td>
                                <td> <?= $riwayatGagal["tanggal"]; ?> </td>
                                <!--query untuk ambil username pembeli-->
                                <?php $id_pembeli = $riwayatGagal["id_pembeli"]; ?>
                                <?php $iniPembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $id_pembeli"); ?>
                                <td> <?= $iniPembeli[0]["username_pembeli"]; ?> </td>
                                <td> <?= $riwayatGagal["menu_dipesan"]; ?> </td>
                                <td>Rp. <?= $riwayatGagal["jumlah_transaksi"]; ?> </td>
                                <td> <?= $riwayatGagal["alasan"]; ?> </td>
                            </tr>
                            <?php $i++; ?> 
                        <?php endforeach; ?>  
                    </table>
            <?php 
                } else { 
            ?>
                    <h4>Belum ada kasus transaksi yang dibatalkan.</h4>
            <?php 
                } 
            ?>    
        </div>
    </section>    
    <!--konsepnya di atas itu, cek apakah ada datanya? (cek apakah variable true?) kalo gaada maka tidak perlu tampilkan table
    sebagai pengganti table maka tampilkan pesan tidak ada data-->
    

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
                                <td>
                                    <ul class="list-info-mobile">
                                        <li>
                                            <p>Pemesan :</p>
                                            <p> <?= $iniPembeli[0]["username_pembeli"]; ?> </p>
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
                                <td>
                                    <ul class="list-info-mobile">
                                        <li>
                                            <p>Pemesan :</p>
                                            <p> <?= $iniPembeli[0]["username_pembeli"]; ?> </p>
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
            ?>
                    <p class="text-empty">Tidak ada transaksi pada tanggal yang anda pilih.</p>
            <?php
                }
            ?>
        </div>
        </div>
    </section>


    <!--Bagian Debug-->
    <?php //var_dump($_POST); ?> <!--ini hanya untuk debug, nanti hilangkan-->
    <br><br><br><br> <!--untuk mengatasi iklan di bawah konten-->

    
    <script src="riwayatPenjual.js"></script>
</body>
</html>



<?php
//Bagian Handle Aksi Selesaikan Pencairan Saldo
//cek apakah tombol selesaikan ditekan?
if (isset($_POST["selesaikanCair"])) {
    if (selesaiRequestCair($_POST) > 0 ) {
        echo "<script>
            alert('Saldo anda SUDAH dicairkan (anda sudah MENERIMA uang).');
            document.location.href='riwayatPenjual.php';
        </script>";
    } else {
        echo "<script>
            alert('Saldo anda GAGAL dicairkan (anda TIDAK MENERIMA uang).');
            document.location.href='riwayatPenjual.php';
        </script>";
    }
}


//Bagian Handle Aksi Batalkan Pencairan Saldo
//cek apakah tombol batalkan ditekan?
if (isset($_POST["batalkanCair"])) {
    if (batalRequestCair($_POST) > 0 ) {
        echo "<script>
            alert('Request pencairan saldo anda BATAL dicairkan (jumlah saldo DIKEMBALIKAN).');
            document.location.href='riwayatPenjual.php';
        </script>";
    } else {
        echo "<script>
            alert('Request pencarian saldo anda GAGAL DIBATALKAN.');
            document.location.href='riwayatPenjual.php';
        </script>";
    }
}
?>