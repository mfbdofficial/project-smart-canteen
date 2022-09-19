<?php 
//SEBAGAI HALAMAN MENU (PENJUAL)


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
//query data dari table pesanan dan menu dibagi kategori makanan dan minuman (khusus penjual yang susuai)
$iniPesananSaldo = queryData("SELECT * FROM pesanan JOIN user_pembeli ON pesanan.id_pembeli = user_pembeli.id_pembeli WHERE id_penjual = $idPenjual AND tipe_pesanan = 'saldo'");
$iniPesananCash = queryData("SELECT * FROM pesanan JOIN user_pembeli ON pesanan.id_pembeli = user_pembeli.id_pembeli WHERE id_penjual = $idPenjual AND tipe_pesanan = 'cash'");
$iniPesananPending = queryData("SELECT * FROM pesanan JOIN user_pembeli ON pesanan.id_pembeli = user_pembeli.id_pembeli WHERE id_penjual = $idPenjual AND status = 'pending'");
$iniPesananSettlement = queryData("SELECT * FROM pesanan JOIN user_pembeli ON pesanan.id_pembeli = user_pembeli.id_pembeli WHERE id_penjual = $idPenjual AND status = 'settlement'");
$iniMakanan = queryData("SELECT * FROM menu WHERE id_penjual = $idPenjual AND kategori = 'makanan'");
$iniMinuman = queryData("SELECT * FROM menu WHERE id_penjual = $idPenjual AND kategori = 'minuman'");
//dari function "queryData($queryPenjual)" yang sudah dibuat, sebenarnya isi variable diatas itu adalah $instances yang direturn


//Bagian Handle Aksi Untuk Mengubah Stock Menu
//apakah tombol ubah stock ditekan? cek apakah ada data idMenu dan editStock dari GET?
if (isset($_GET["idMenu"]) && isset($_GET["editStock"])) {
    ubahStockMenu($_GET["idMenu"], $_GET["editStock"]);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu (Penjual)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Pacifico&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="menuPenjual.css">
</head>
<body>
    <!--Bagian Navigation Bar-->
    <nav>
        <img src="../LogoCanteen2.png">

        <div class="logo">
            <h4>Smart Canteen</h4>
        </div>
        
        <ul class="navigasi">
            <li><a href="">Menu</a></li>
            <li><a href="riwayatPenjual.php">Riwayat</a></li>
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


    <!--Bagian Menampilkan Pesanan Menunggu-->
    <section class="pesanan-menunggu">
        <div class="isiPesananMenunggu">
            <h1>Pesanan Pembeli Anda :</h1> <br>
    
            <!--Untuk Nampilin Pesanan Tipe Saldo-->
            <!--cek apakah ada pesanan yang metode saldo?-->
            <?php if($iniPesananSaldo) : ?>
            <h4>Pesanan Dengan Saldo</h4>
            <table>
                <?php $i = 1; ?>
                <?php foreach ($iniPesananSaldo as $pesananSaldo) : ?>
                    <tr>
                        <td> <?php echo $i; ?>.<br> </td>
                        <td> <b> <?php echo $pesananSaldo["username_pembeli"]; ?> </b>, <b>Menu :</b> <?= $pesananSaldo["menu_dipesan"]; ?> (<?=$pesananSaldo["jumlah_pesanan"];?>). <b>Total :</b> Rp. <?= $pesananSaldo["jumlah_transaksi"] ?> <br>
                        <b>Kode Transaksi:</b> <?= $pesananSaldo["kode_pesanan"] ?> </td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
                    
            <br>
    
            <!--Untuk Nampilin Pesanan Tipe Cash-->
            <!--cek apakah ada pesanan yang metode cash?-->
            <?php if($iniPesananCash) : ?>
                <h4>Pesanan Dengan Cash</h4>
                <table>
                    <?php $i = 1; ?>
                    <?php foreach ($iniPesananCash as $pesananCash) : ?>
                        <tr>
                            <td> <?php echo $i; ?>.<br> </td>
                            <td> <b> <?php echo $pesananCash["username_pembeli"]; ?> </b>, <b>Menu :</b> <?= $pesananCash["menu_dipesan"]; ?> (<?=$pesananCash["jumlah_pesanan"];?>). <b>Total :</b> Rp. <?= $pesananCash["jumlah_transaksi"] ?> <br>
                            <b>Kode Transaksi:</b> <?= $pesananCash["kode_pesanan"] ?> <a class="batalkan" href="menuPenjual(batalkanCash).php?kodePesanan=<?= $pesananCash["kode_pesanan"]; ?>">batalkan</a> | 
                                                                                        <a class="selesaikan" href="menuPenjual(selesaikanCash).php?kodePesanan=<?= $pesananCash["kode_pesanan"]; ?>">selesai</a></td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
    
            <br>
            
            <!--Untuk Nampilin Pesanan Tipe Payment Gateaway-->
            <!--cek apakah ada pesanan payment gateaway yang pending?-->
            <?php if($iniPesananPending) : ?>
            <h4>Pesanan Dengan Payment Gateway (belum dibayar)</h4> <!--ini yang statusnya "pending"-->
            <table>
                <?php $i = 1; ?>
                <?php foreach ($iniPesananPending as $pesananPending) : ?>
                    <tr>
                        <td> <?php echo $i; ?>.<br> </td>
                        <td> <b> <?php echo $pesananPending["username_pembeli"]; ?> </b>, <b>Menu :</b> <?= $pesananPending["menu_dipesan"]; ?> (<?=$pesananPending["jumlah_pesanan"];?>). <b>Total :</b> Rp. <?= $pesananPending["jumlah_transaksi"] ?> <br>
                        <b>Kode Transaksi:</b> <?= $pesananPending["kode_pesanan"] ?>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
    
            <br>
            
            <!--cek apakah ada pesanan payment gateaway yang settlement?-->
            <?php if($iniPesananSettlement) : ?>
            <h4>Pesanan Dengan Payment Gateway (sudah terbayar)</h4> <!--ini yang statusnya "settlement"-->
            <table>
                <?php $i = 1; ?>
                <?php foreach ($iniPesananSettlement as $pesananSettlement) : ?>
                    <tr>
                        <td> <?php echo $i; ?>.<br> </td>
                        <td> <b> <?php echo $pesananSettlement["username_pembeli"]; ?> </b>, <b>Menu :</b> <?= $pesananSettlement["menu_dipesan"]; ?> (<?=$pesananSettlement["jumlah_pesanan"];?>). <b>Total :</b> Rp. <?= $pesananSettlement["jumlah_transaksi"] ?> <br>
                        <b>Kode Transaksi:</b> <?= $pesananSettlement["kode_pesanan"] ?>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </section>


    <!--Bagian Judul Daftar Menu-->
    <h2 class="judulDaftarMenu">Daftar Menu Anda</h2>


    <!--Bagian Tombol Tambah Daftar Menu-->
    <div class="nambah">
        <a href="menuPenjual(tambah).php">Tambah Menu</a>
    </div>


    <!--Bagian Menampilkan Daftar Makanan-->
    <h3 class="judul-makanan">Makanan :</h3>
    <?php
        if ($iniMakanan) {
    ?>
            <section class="daftar-makanan">
                <?php foreach ($iniMakanan as $makanan) : ?>
                    <div class="makanan">
                        <img src="img/<?= $makanan["gambar"] ?>" alt="">
                        <p class="namaMenu"> <b> <?= $makanan["nama_menu"] ?> </b> </p>
                        <p>Rp. <?= $makanan["harga"] ?> </p>
                        <!--fungsi "onclick" (saat diklik), fungsi "confirm" adalah fungsi javascript pop-up confirm-->
                        <!--terlihat seperti kedip saja, karena halaman tutorialphpunpas5(hapus).php cuma jalankan backend php, lalu langsung redirect ke halaman ini-->
                        <a class="deleteMenu" href="menuPenjual(hapus).php?id=<?php echo $makanan["id_menu"]; ?>" onclick="return confirm('Apakah yakin HAPUS MENU?');">Delete</a> <br>
                        <!--Fungsi ubah data (metode request GET)-->
                        <a class="updateMenu" href="menuPenjual(ubah).php?id=<?php echo $makanan["id_menu"]; ?>">Update</a> <br>
                        <!--Fungsi untuk ubah jumlah stock menu-->
                        <form action="" method="get" enctype="multipart/form-data">
                            <input type="hidden" name="idMenu" value="<?php echo $makanan["id_menu"]; ?>">
                            <input type="number" name="editStock" min="0" style="width: 25px;">
                            <button type="submit" name="buttonStock">Ubah Stock</button>
                        </form> 
                        <p>Stock: <b> <?= $makanan["stock"]; ?> </b> </p>
                    </div>
                <?php endforeach; ?>
            </section>
    <?php
        } else {
    ?>
            <h4 class="empty-menu">Anda belum memiliki menu makanan apapun.</h4>
    <?php
        }
    ?>


    <!--float: left di css akan terus berlanjut ke element setelehnya, maka perlu dibersihkan :-->
    <div class="bersihkan-float"></div>


    <!--Bagian Menampilkan Daftar Minuman-->
    <h3 class="judul-minuman">Minuman :</h3>
    <?php
        if ($iniMinuman) {
    ?>
    <section class="daftar-minuman">
        <?php foreach ($iniMinuman as $minuman) : ?>
            <div class="minuman">
                <img src="img/<?= $minuman["gambar"] ?>" alt="">
                <p class="namaMenu"> <b> <?= $minuman["nama_menu"] ?> </b> </p>
                <p>Rp. <?= $minuman["harga"] ?> </p>
                <!--fungsi "onclick" (saat diklik), fungsi "confirm" adalah fungsi javascript pop-up confirm-->
                <!--terlihat seperti kedip saja, karena halaman tutorialphpunpas5(hapus).php cuma jalankan backend php, lalu langsung redirect ke halaman ini-->
                <a class="deleteMenu" href="menuPenjual(hapus).php?id=<?php echo $minuman["id_menu"]; ?>" onclick="return confirm('Apakah yakin HAPUS MENU?');">Delete</a> <br>
                <!--Fungsi ubah data (metode request GET)-->
                <a class="updateMenu" href="menuPenjual(ubah).php?id=<?php echo $minuman["id_menu"]; ?>">Update</a> <br>
                <!--Fungsi untuk ubah jumlah stock menu-->
                <form action="" method="get" enctype="multipart/form-data">
                    <input type="hidden" name="idMenu" value="<?php echo $minuman["id_menu"]; ?>">
                    <input type="number" name="editStock" min="0" style="width: 25px;">
                    <button type="submit" name="buttonStock">Ubah Stock</button>
                </form> 
                <p>Stock: <b> <?= $minuman["stock"]; ?> </b> </p>
            </div>
        <?php endforeach; ?>
    </section>
    <?php
        } else { 
    ?>
            <h4 class="empty-menu">Anda belum memiliki menu minuman apapun.</h4>
    <?php
        }
    ?>


    <!--float: left di css akan terus berlanjut ke element setelehnya, maka perlu dibersihkan :-->
    <div class="bersihkan-float"></div>


    <!--Bagian Debug-->
    <div class="ruang"></div>
    <br><br><br><br> <!--untuk mengatasi iklan di bawah konten-->


    <script src="menuPenjual.js"></script>
</body>
</html>