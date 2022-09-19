<?php 
//SEBAGAI HALAMAN ISI SALDO (PEMBELI)


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
    

//Bagian Mengambil Data Untuk Ditampilkan
$idPembeli = $_SESSION["idPembeli"];
$iniRequestSaldo = queryData("SELECT * FROM request_saldo WHERE id_pembeli = $idPembeli");
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

    <link rel="stylesheet" href="isiPembeli.css">
</head>
<body>
    <nav>
        <img src="../LogoCanteen2.png">

        <div class="logo">
            <h4>Smart Canteen</h4>
        </div>
        
        <ul class="navigasi">
            <li><a href="homePembeli.php">Home</a></li>
            <li><a href="pesanPembeli.php">Pesan Menu</a></li>
            <li><a href="#">Isi Saldo</a></li>
            <li><a href="akunPembeli.php">Akun</a></li>
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
    

    <section class="saldo">
        <!--Bagian Menampilkan Request Isi Saldo-->
        <div class="request-saldo">
            <h2>Request Saldo Anda :</h3>
    
            <!--cek apakah sedang ada request isi saldo ?-->
            <?php if($iniRequestSaldo == true) : ?>       
                <table border="1" cellpadding="10" cellspacing="0">
                    <tr>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Total</td>
                    </tr>
                    <?php
                        //Perhitungan Total Requst Saldo-nya
                        $totalRequest = 0;
                        foreach ($iniRequestSaldo as $request) : 
                        $totalRequest += $request["nominal_request"];
                    ?>
                        <tr>
                            <td> <?= $request["username_pembeli"] ?> </td>
                            <td> <?= $request["email_pembeli"]; ?> </td>
                            <td> <?= $request["nominal_request"]; ?> </td>
                        </tr>
                    <?php endforeach; ?>            
                </table>
                <!--Tampilan Jumlah Request Keseluruhan-->
                <h4>Total request saldo : Rp. <?= $totalRequest ?></h4>  
            <?php endif; ?>
            
            <!--cek apakah belum ada request? dengan cek apakah semua yang diquery kosong-->
            <?php if($iniRequestSaldo == false) : ?>
                <h4>Sedang tidak ada request saldo.</h4>
            <?php endif; ?>
        </div>
    
    
        <!--Bagian Penjelasan Isi Saldo-->
        <div class="penjelasan-saldo">
            <h4>Silahkan Mengisi Saldo</h4>
            <p>Anda dapat mengisi saldo anda dengan membuat request isi saldo (memilik opsi atau memasukkan nominal sesuai keinginan anda minimal 1000 maksimal 1000000).</p>
            <p>Admin akan merespon request isi saldo anda setelah anda membayar pada Admin, maka saldo anda akan bertambah.</p>
        </div>
    
    
        <!--Bagian Aksi Isi Saldo-->
        <div class="isi-saldo">
            <h3>Pilih nominal pengisian :</h3>
    
            <div class="pilih-nominal">
                <div class="angka satu">
                    <form action="isiPembeli(proses).php" method="get">
                        <input type="hidden" name="nominalSaldo" value=50000>
                        <button class="tombolOpsi" type="submit" onclick="return confirm('Yakin isi saldo 50.000?');" name="requestSaldo">Rp 50.000</button>
                    </form>
                </div>
                <div class="angka dua">
                    <form action="isiPembeli(proses).php" method="get">
                        <input type="hidden" name="nominalSaldo" value=100000>
                        <button class="tombolOpsi" type="submit" onclick="return confirm('Yakin isi saldo 100.000?');" name="requestSaldo">Rp 100.000</button>
                    </form>
                </div>
                <div class="angka tiga">
                    <form action="isiPembeli(proses).php" method="get">
                        <input type="hidden" name="nominalSaldo" value=200000>
                        <button class="tombolOpsi" type="submit" onclick="return confirm('Yakin isi saldo 200.000?');" name="requestSaldo">Rp 200.000</button>
                    </form>
                </div>
            </div>
            
            <div class="nominal-manual">
                <form action="isiPembeli(proses).php" method="post">
                    <input type="number" name="nominalSaldo" min="1000" step="1000" placeholder="  Nominal..">
                    <button class="tombol" type="submit">isi saldo</button>
                </form>
            </div>
        </div>
    <section>


    <br>
    <br><br><br><br> <!--untuk mengatasi iklan di bawah konten-->


    <!-- waktu itu hanya untuk coba - coba, metode request GET pakai form (mencet tombol, lalu redirect membawa data di URL)
    <form action="homePembeli.php" method="get">
    <input type="hidden" name="x" value=3000>
    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-outline-primary btn-lrg" style="font-size:20px;">CANCEL</button>
    </form>
    -->


    <script src="isiPembeli.js"></script>
</body>
</html>