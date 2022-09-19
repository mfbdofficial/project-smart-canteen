<?php 
//SEBAGAI HALAMAN SALDO (ADMIN)


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
$iniRequestSaldo = queryData("SELECT * FROM request_saldo");
//query data dari table user_pembeli dan user_penjual
$iniPembeli = queryData("SELECT * FROM user_pembeli");
$iniPenjual = queryData("SELECT * FROM user_penjual");
//dari function "queryData($queryAdmin)" yang sudah dibuat, sebenarnya isi variable diatas itu adalah $instances yang direturn
$iniRequestMencairkan = queryData("SELECT * FROM request_cair");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saldo (Admin)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Pacifico&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="saldoAdmin.css">
</head>
<body>
    <!--Bagian Navigation Bar-->
    <nav>
        <img src="../LogoCanteen2.png">

        <div class="logo">
            <h4>Smart Canteen</h4>
        </div>
        
        <ul class="navigasi">
            <li><a href="">Saldo</a></li>
            <li><a href="userAdmin.php">User</a></li>
            <li><a href="riwayatAdmin.php">Riwayat</a></li>
        </ul>

        <div class="menu-toggle">
            <input type="checkbox">
            <span></span> <!--nth-child dihitung dari 1, dan ini termasuk child ke 2-->
            <span></span> <!--walau misal hanya milih selector span saja di CSS, tetap saja nth-child sesuai urutan dan semua element child tetap dihitung (walau bukan <span>)-->
            <span></span> <!--jadi baris ini adalah nth-child(4), yaitu child keempat-->
        </div>
    </nav>


    <!--Bagian Tombol Logout-->
    <a class="tombolLogout" href="../logoutsmartcanteen.php" onclick="return confirm('Apakah yakin Logout dari halaman?');" >Logout</a>


    <!--Bagian Menampilkan Data-->
    <div class="container">
        <!--Bagian Menampilkan Request Isi Saldo-->
        <section class="request-saldo">
            <h2>Request Isi Saldo</h2>
            <!--cek apakah sedang ada request untuk isi saldo?-->
            <?php if($iniRequestSaldo) : ?>
                <table border="1" cellpadding="10" cellspacing="0">
                    <tr>
                        <td>No</td>
                        <td>Username</td>
                        <td>Total</td>
                        <td>Aksi</td>
                    </tr>
                    <?php 
                    $i = 1;
                    foreach($iniRequestSaldo as $request): 
                    ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $request["username_pembeli"]; ?></td>
                            <td>Rp. <?= $request["nominal_request"]; ?></td>
                            <td> <a class="batalkan" href="saldoAdmin.php?idRequest=<?= $request["id_saldo"]; ?>& aksiA=batal">Batalkan</a> | <a class="selesaikan" href="saldoAdmin.php?idRequest=<?= $request["id_saldo"]; ?>& aksiA=selesai& username=<?= $request["username_pembeli"]; ?>">Selesaikan</a> </td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?> 

            <!--cek apakah sedang tidak ada request isi saldo?-->
            <?php if($iniRequestSaldo == false) : ?>
                <h3>Sedang tidak ada request isi saldo.</h3>
            <?php endif; ?>
        </section>
        

        <!--Bagian Menampilkan Request Mencairkan Saldo-->
        <section class="request-mencairkan">
            <?php if($iniRequestMencairkan) : ?>
                <h2>Request Pencairan Penjual</h2>
                <table border="1" cellpadding="10" cellspacing="0">
                    <tr>
                        <td>No</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Total</td>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($iniRequestMencairkan as $request) : 
                    ?>
                        <tr>
                            <td> <?= $i; ?> </td>
                            <td> <?= $request["username_penjual"]; ?> </td>
                            <td> <?= $request["email_penjual"]; ?> </td>
                            <td>Rp. <?= $request["nominal_request"]; ?> </td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?> 

            <!--cek apakah belum ada request? dengan cek apakah semua yang diquery kosong-->
            <?php if($iniRequestMencairkan == false) : ?>
                <h3>Sedang tidak ada request mencairkan saldo.</h3>
            <?php endif; ?>
        </section>


        <!--Bagian Menampilkan Jumlah Saldo User-->
        <section class="info-saldo">
            <h2>Saldo Pembeli</h1>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>No</td>
                    <td>Username</td>
                    <td>Total Saldo</td>
                </tr>
                <!--selipkan bahasa PHP membuat variable "i" untuk bagian nomer dengan -->
                <?php $i = 1; ?>
                <!--selipkan bahasa PHP untuk membuat foreach, jadi sistemnya perintah foreach-nya menjalankan ngerluarin tag HTML-->
                <?php foreach( $iniPembeli as $pembeli ) : ?>
                    <tr>
                        <td> <?php echo $i ?> </td>
                        <td> <?= $pembeli["username_pembeli"]; ?> </td>
                        <td> <?= $pembeli["saldo_pembeli"]; ?> </td>
                    </tr>
                    <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                    <?php $i++; ?>
                <?php endforeach; ?>    
            </table>

            <h2>Saldo Penjual</h1>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>No</td>
                    <td>Username</td>
                    <td>Total Saldo</td>
                </tr>
                <!--selipkan bahasa PHP membuat variable "i" untuk bagian nomer dengan -->
                <?php $i = 1; ?>
                <!--selipkan bahasa PHP untuk membuat foreach, jadi sistemnya perintah foreach-nya menjalankan ngerluarin tag HTML-->
                <?php foreach ($iniPenjual as $penjual) : ?>
                    <tr>
                        <td> <?php echo $i ?> </td>
                        <td> <?= $penjual["username_penjual"]; ?> </td>
                        <td> <?= $penjual["saldo_penjual"]; ?> </td>
                    </tr>
                    <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                    <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </section>
    </div>

    
    <!--Bagian Debug-->
    <br><br><br><br> <!--untuk mengatasi iklan di bawah konten-->


    <script src="saldoAdmin.js"></script>
</body>
</html>



<?php
//Bagian Handle Request Isi Saldo
//untuk tombol batalkan
if(isset($_GET["idRequest"]) && $_GET["aksiA"] == "batal") {
    requestBatal($_GET["idRequest"]);
    echo "<script> alert('Anda MEMBATALKAN request saldo.'); 
        document.location.href='saldoAdmin.php';
        </script>";
    //jangan lupa untuk redirect agar halaman merefresh, jadi data yang baru muncul
}
//untuk tombol selesaikan
if(isset($_GET["idRequest"]) && $_GET["aksiA"] == "selesai") {
    //ambil data id user_pembeli
    $usernamePembeli = $_GET["username"];
    requestSelesai($_GET["idRequest"], $usernamePembeli);
    echo "<script> alert('Anda MENYELESAIKAN request saldo.'); 
        document.location.href='saldoAdmin.php';
        </script>";
}
?>