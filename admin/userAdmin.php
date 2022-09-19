<?php 
//SEBAGAI HALAMAN USER (ADMIN)


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
//query data dari table user_pembeli dan user_penjual
$iniPembeli = queryData("SELECT * FROM user_pembeli");
$iniPenjual = queryData("SELECT * FROM user_penjual");
//dari function "queryData($queryAdmin)" yang sudah dibuat, sebenarnya isi variable diatas itu adalah $instances yang direturn
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User (Admin)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Pacifico&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="userAdmin.css">
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
            <li><a href="">User</a></li>
            <li><a href="riwayatAdmin.php">Riwayat</a></li>
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


    <!--Bagian Teks Penjelasan User-->
    <section class="penjelasan-user">
        <h1>Pengelolaan User Smart Canteen</h1>
        <p>Merupakan halaman yang memiliki fungsi untuk memantau daftar user pada sistem web smart canteen, admin memiliki hak untuk menghapus user tertentu pada sistem smart canteen bila dinilai terdapat kejanggalan yang negatif pada user tersebut. 
            User yang terhapus akan kehilangan keseluruhan data riwayat, pesanan dan saldo-nya, diharapkan admin bijak dalam mengambil keputusan penghapusan suatu user.
        </p>
    </section>


    <!--Bagian Menampilkan Semua User-->
    <section class="info-user">
        <h1>Daftar Pemakai Smart Canteen</h1>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <td>No</td>
                <td>Nomor Telpon</td>
                <td>Username</td>
                <td>Email</td>
                <td>Aksi</td>
            </tr>
            <!--selipkan bahasa PHP membuat variable "i" untuk bagian nomer dengan -->
            <?php $i = 1; ?>
            <!--selipkan bahasa PHP untuk membuat foreach, jadi sistemnya perintah foreach-nya menjalankan ngerluarin tag HTML-->
            <?php foreach( $iniPembeli as $pembeli ) : ?>
                <tr>
                    <td> <?php echo $i ?> </td>
                    <td> <?= $pembeli["no_telpon_pembeli"]; ?> </td>
                    <td> <?= $pembeli["username_pembeli"]; ?> </td>
                    <td> <?= $pembeli["email_pembeli"]; ?> </td> 
                    <td>
                        <!--fungsi "onclick" (saat diklik), fungsi "confirm" adalah fungsi javascript pop-up confirm-->
                        <!--terlihat seperti kedip saja, karena halaman tutorialphpunpas5(hapus).php cuma jalankan backend php, lalu langsung redirect ke halaman ini-->
                        <a class="buttonHapus" href="userAdmin(hapus).php?id=<?= $pembeli["id_pembeli"] ?>& username=<?php echo $pembeli["username_pembeli"]; ?>" onclick="return confirm('Apakah yakin HAPUS pembeli?');">hapus</a> 
                    </td>
                </tr>
                <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                <?php $i++; ?>
            <?php endforeach; ?> 
        </table>
        
        <h1>Daftar Penjual Smart Canteen</h1>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <td>No</td>
                <td>Nomor Telpon</td>
                <td>Username</td>
                <td>Email</td>
                <td>Aksi</td>
            </tr>
            <!--selipkan bahasa PHP membuat variable "i" untuk bagian nomer dengan -->
            <?php $i = 1; ?>
            <!--selipkan bahasa PHP untuk membuat foreach, jadi sistemnya perintah foreach-nya menjalankan ngerluarin tag HTML-->
            <?php foreach( $iniPenjual as $penjual ) : ?>
                <tr>
                    <td> <?php echo $i ?> </td>
                    <td> <?= $penjual["no_telpon_penjual"]; ?> </td>
                    <td> <?= $penjual["username_penjual"]; ?> </td>
                    <td> <?= $penjual["email_penjual"]; ?> </td>
                    <td>
                        <!--fungsi "onclick" (saat diklik), fungsi "confirm" adalah fungsi javascript pop-up confirm-->
                        <!--terlihat seperti kedip saja, karena halaman tutorialphpunpas5(hapus).php cuma jalankan backend php, lalu langsung redirect ke halaman ini-->
                        <a class="buttonHapus" href="userAdmin(hapus).php?id=<?= $penjual["id_penjual"] ?>& username=<?php echo $penjual["username_penjual"]; ?>" onclick="return confirm('Apakah yakin HAPUS penjual?');">hapus</a> 
                    </td>
                </tr>
                <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                <?php $i++; ?>
            <?php endforeach; ?> 
        </table>
    </section>


    <section class="info-user-mobile">
        <h1>Daftar Pemakai Smart Canteen</h1>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <td>No</td>
                <td>Info</td>
                <td>Aksi</td>
            </tr>
            <?php $i = 1; ?>
            <?php foreach( $iniPembeli as $pembeli ) : ?>
                <tr>
                    <td> <?php echo $i ?> </td>
                    <td> 
                        <ul class="list-info-mobile">
                            <li>
                                <p>No Telpon :</p>
                                <p> <?= $pembeli["no_telpon_pembeli"]; ?> </p>
                            </li>  
                            <li>
                                <p>Username :</p>
                                <p><?= $pembeli["username_pembeli"]; ?></p>
                            </li>
                            <li>
                                <p>Email :</p>
                                <p><?= $pembeli["email_pembeli"]; ?></p>
                            </li>
                        </ul> 
                    </td> 
                    <td>
                        <!--fungsi "onclick" (saat diklik), fungsi "confirm" adalah fungsi javascript pop-up confirm-->
                        <!--terlihat seperti kedip saja, karena halaman tutorialphpunpas5(hapus).php cuma jalankan backend php, lalu langsung redirect ke halaman ini-->
                        <a class="buttonHapus" href="userAdmin(hapus).php?id=<?= $pembeli["id_pembeli"] ?>& username=<?php echo $pembeli["username_pembeli"]; ?>" onclick="return confirm('Apakah yakin HAPUS pembeli?');">hapus</a> 
                    </td>
                </tr>
                <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                <?php $i++; ?>
            <?php endforeach; ?> 
        </table>

        <h1>Daftar Penjual Smart Canteen</h1>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <td>No</td>
                <td>Info</td>
                <td>Aksi</td>
            </tr>
            <?php $i = 1; ?>
            <?php foreach( $iniPenjual as $penjual ) : ?>
                <tr>
                    <td> <?php echo $i ?> </td>
                    <td> 
                        <ul class="list-info-mobile">
                            <li>
                                <p>No Telpon :</p>
                                <p> <?= $penjual["no_telpon_penjual"]; ?> </p>
                            </li>  
                            <li>
                                <p>Username :</p>
                                <p><?= $penjual["username_penjual"]; ?></p>
                            </li>
                            <li>
                                <p>Email :</p>
                                <p><?= $penjual["email_penjual"]; ?></p>
                            </li>
                        </ul> 
                    </td> 
                    <td>
                        <!--fungsi "onclick" (saat diklik), fungsi "confirm" adalah fungsi javascript pop-up confirm-->
                        <!--terlihat seperti kedip saja, karena halaman tutorialphpunpas5(hapus).php cuma jalankan backend php, lalu langsung redirect ke halaman ini-->
                        <a class="buttonHapus" href="userAdmin(hapus).php?id=<?= $penjual["id_penjual"] ?>& username=<?php echo $penjual["username_penjual"]; ?>" onclick="return confirm('Apakah yakin HAPUS penjual?');">hapus</a> 
                    </td>
                </tr>
                <!--tambah nilai i agar nilai i jadi nomor berikutnya-->
                <?php $i++; ?>
            <?php endforeach; ?> 
        </table>
    </section>


    <!--Bagian Debug-->
    <br><br><br><br> <!--untuk mengatasi iklan di bawah konten-->

    
    <script src="userAdmin.js"></script>
</body>
</html>