<?php 
//SEBAGAI HALAMAN HOME (PEMBELI)


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


//Bagian Mengambil Data Untuk Ditampilkan
//query data dari table user_pembeli dan pesanan (khusus pembeli yang susuai)
$iniPembeli = queryData("SELECT * FROM user_pembeli WHERE username_pembeli = '$username'");
$iniPesananTerbayar = queryData("SELECT * FROM pesanan WHERE id_pembeli = $idPembeli AND status = 'terbayar'");
$iniPesananBelumTerbayar = queryData("SELECT * FROM pesanan WHERE id_pembeli = $idPembeli AND status = 'belum terbayar'");
$iniPesananPending = queryData("SELECT * FROM pesanan WHERE id_pembeli = $idPembeli AND status = 'pending'");
$iniPesananSettlement = queryData("SELECT * FROM pesanan WHERE id_pembeli = $idPembeli AND status = 'settlement'");
//dari function "queryData($queryPembeli)" yang sudah dibuat, sebenarnya isi variable diatas itu adalah $instances yang direturn
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home (Pembeli)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Pacifico&display=swap" rel="stylesheet">

    <!--UNTUK LINK SCRIPT PAYMENT GATEAWAY-->
    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-hTWsFQZqu1hCoL63">
    </script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->

    <link rel="stylesheet" href="homePembeli.css">
</head>
<body>
    <!--Bagian Navigation Bar-->
    <nav>
        <img src="../LogoCanteen2.png">

        <div class="logo">
            <h4>Smart Canteen</h4>
        </div>
        
        <ul class="navigasi">
            <li><a href="#">Home</a></li>
            <li><a href="pesanPembeli.php">Pesan Menu</a></li>
            <li><a href="isiPembeli.php">Isi Saldo</a></li>
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


    <!--Bagian Menampilkan Saldo & Penjelasan-->
    <section class="saldo-rules">
        <div class="saldo-anda">
            <img src="wallet-icon-flaticon-2.png" alt="">
            <p>Saldo anda saat ini <b>Rp. <?php echo $iniPembeli[0]["saldo_pembeli"] ?></b> </p>
        </div>
    
        <div class="teksHome">
            <p><b>Selesaikan</b> pesanan jika anda sudah menerima makanan anda, maka transaksi dinyatakan selesai. Anda bisa melakukan cek tata cara bayar khusus pembayaran yang menggunakan pihak ketiga dengan menekan <b>Cara Bayar</b>. Pesanan dengan metode <b>cash</b> dibayarkan di tempat penjual (dipisah per penjual).</p>
            <p>Pesanan dari <b>penjual berbeda</b> akan dinyatakan sebagai <b>2 pesanan yang berbeda</b>. Namun setiap <b>aksi membayar dengan Saldo Smart Canteen atau E-Wallet</b> akan dihitung <b>sekaligus membayar semua</b> pesanannya, <b>kecuali</b> untuk <b>Cash</b>. Pesanan dalam <b>1 waktu sama</b> bisa dilihat dari <b>kode pesanannya</b></p>
        </div>
    </section>

    
    <!--Bagian Menampilkan Transaksi Berjalan-->
    <section class="transaksi-berjalan">
        <h3>Transaksi sedang berjalan :</h3> <br>

        <!--cek apakah ada pesanan yang sudah dengan status terbayar (METODE SALDO)-->
        <?php if($iniPesananTerbayar) : ?>
            <h4>Sudah Dibayar Dengan Saldo Smart Canteen</h4>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>Penjual</td>
                    <td>Menu Dipesan</td>
                    <td>Jumlah Pesanan</td>
                    <td>Total</td>
                    <td>Kode Transaksi</td>
                    <td>Aksi</td>
                </tr>
                <?php foreach ($iniPesananTerbayar as $pesanan) : ?>
                    <tr>    
                        <?php
                            $idPenjual = $pesanan["id_penjual"];
                            $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
                        ?>
                        <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                        <td> <?= $pesanan["menu_dipesan"]; ?> </td>
                        <td> <?= $pesanan["jumlah_pesanan"] ?> </td>
                        <td> <?= $pesanan["jumlah_transaksi"]; ?> </td>
                        <td> <?= $pesanan["kode_pesanan"]; ?> </td>
                        <td> 
                            <!--Proses selesaikan pesanan akan method POST ke homePembeli(selesaikan).php-->
                            <form action="homePembeli(selesaikan).php" method="post">
                                <input type="hidden" value="<?= $pesanan["id_pesanan"]; ?>" name="idPesanan">
                                <button type="submit" class="selesaikan" onclick="return confirm('YAKIN SELESAIKAN PESANAN ?');">Selesaikan</button> 
                            </form>                                                    
                        </td>
                    </tr>
                <?php endforeach; ?>   
            </table>
        <?php endif; ?>

        <br>

        <!--cek apakah ada pesanan yang status belum terbayar (METODE CASH)-->
        <?php if($iniPesananBelumTerbayar) : ?>
            <h4>Metode Cash Bayar Ditempat</h4>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>Penjual</td>
                    <td>Menu Dipesan</td>
                    <td>Jumlah Pesanan</td>
                    <td>Total</td>
                    <td>Kode Transaksi</td>
                </tr>
                <?php foreach ($iniPesananBelumTerbayar as $pesanan) : ?>
                    <tr>
                        <?php
                            $idPenjual = $pesanan["id_penjual"];
                            $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
                        ?>
                        <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                        <td> <?= $pesanan["menu_dipesan"]; ?> </td>
                        <td> <?= $pesanan["jumlah_pesanan"] ?> </td>
                        <td> <?= $pesanan["jumlah_transaksi"]; ?> </td>
                        <td> <?= $pesanan["kode_pesanan"]; ?> </td>
                    </tr>
                <?php endforeach; ?>   
            </table>
        <?php endif; ?>

        <br>

        <!--cek apakah ada pesanan yang statusnya pending? (METODE PAYMENT GATEAWAY-->
        <?php if($iniPesananPending) : ?>
            <h4>Pesanan Payment Gateway Pending</h4>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>Penjual</td>
                    <td>Menu Dipesan</td>
                    <td>Jumlah Pesanan</td>
                    <td>Total</td>
                    <td>Kode Transaksi</td>
                    <td>Tata Cara</td>
                </tr>
                <?php foreach ($iniPesananPending as $pesanan) : ?>
                    <tr>
                        <?php
                            $idPenjual = $pesanan["id_penjual"];
                            $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
                        ?>
                        <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                        <td> <?= $pesanan["menu_dipesan"]; ?> </td>
                        <td> <?= $pesanan["jumlah_pesanan"] ?> </td>
                        <td> <?= $pesanan["jumlah_transaksi"]; ?> </td>
                        <td> <?= $pesanan["kode_pesanan"]; ?> </td>
                        <td> <button id="pay-button<?= $pesanan["id_pesanan"]; ?>">Cara Bayar</button> </td>
                    </tr>  
                    <!--UNTUK MEMUNCULKAN SNAP PAYMENT GATEAWAY-->
                    <script type="text/javascript">
                            // For example trigger on button clicked, or any time you need
                            var payButton = document.getElementById('pay-button<?= $pesanan["id_pesanan"]; ?>');
                            payButton.addEventListener('click', function () {
                            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
                            window.snap.pay('<?= $pesanan["snap_token"]; ?>');
                            // customer will be redirected after completing payment pop-up
                        });
                    </script> 
                    <!--cara kerja di atas itu kita ulang2 terus untuk tiap pesanan code JavaScript-nya (tiap button itu akan berbeda 1 sama lain dari attribut "id"nya)-->
                <?php endforeach; ?>   
            </table>
        <?php endif; ?>

        <br>

        <!--cek apakah ada pesanan yang sudah dengan status settlement (METODE PAYMENT GATEAWAY)-->
        <?php if($iniPesananSettlement) : ?>
            <h4>Sudah Dibayar (E-Wallet / Bank / Indomaret / Alfa Group)</h4>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>Penjual</td>
                    <td>Menu Dipesan</td>
                    <td>Jumlah Pesanan</td>
                    <td>Total</td>
                    <td>Kode Transaksi</td>
                    <td>Aksi</td>
                </tr>
                <?php foreach ($iniPesananSettlement as $pesanan) : ?>
                    <tr>
                        <?php
                            $idPenjual = $pesanan["id_penjual"];
                            $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
                        ?>
                        <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                        <td> <?= $pesanan["menu_dipesan"]; ?> </td>
                        <td> <?= $pesanan["jumlah_pesanan"] ?> </td>
                        <td> <?= $pesanan["jumlah_transaksi"]; ?> </td>
                        <td> <?= $pesanan["kode_pesanan"]; ?> </td>
                        <td> 
                            <!--Proses selesaikan pesanan akan method POST ke homePembeli(selesaikan).php-->
                            <form action="homePembeli(selesaikan).php" method="post">
                                <input type="hidden" value="<?= $pesanan["id_pesanan"]; ?>" name="idPesanan">
                                <button type="submit" class="selesaikan" onclick="confirm('YAKIN SELESAIKAN PESANAN ?');">Selesaikan</button> 
                            </form>                                                    
                        </td>
                    </tr>
                <?php endforeach; ?>   
            </table>
        <?php endif; ?>

        <!--cek apakah belum ada pesanan? dengan cek apakah semua yang diquery kosong-->
        <?php if($iniPesananTerbayar == false && $iniPesananPending == false) : ?>
            <h3>Sedang tidak ada pesanan yang berjalan.</h3>
        <?php endif; ?>
    </section>  
    

    <!--Bagian Menampilkan Transaksi Berjalan Mobile-->
    <section class="transaksi-berjalan-mobile">
        <h3>Transaksi sedang berjalan :</h3> <br>

        <!--cek apakah ada pesanan yang sudah dengan status terbayar (METODE SALDO)-->
        <?php if($iniPesananTerbayar) : ?>
            <h4>Sudah Dibayar Dengan Saldo Smart Canteen</h4>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>Penjual</td>
                    <td>Info Pesanan</td>
                    <td>Kode Transaksi</td>
                </tr>
                <?php foreach ($iniPesananTerbayar as $pesanan) : ?>
                    <tr>    
                        <?php
                            $idPenjual = $pesanan["id_penjual"];
                            $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
                        ?>
                        <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                        <td>
                            <ul class="list-info-mobile">
                                <li>
                                    <p>Menu :</p>
                                    <p> <?= $pesanan["menu_dipesan"]; ?> </p>
                                </li>
                                <li>
                                    <p>Jumlah Pesanan :</p>
                                    <p> <?= $pesanan["jumlah_pesanan"]; ?> </p>
                                </li>
                                <li>
                                    <p>Jumlah Transaksi :</p>
                                    <p> <?= $pesanan["jumlah_transaksi"]; ?> </p>
                                </li>
                            </ul>
                        </td>
                        <td> 
                            <?= $pesanan["kode_pesanan"]; ?> 
                            <!--Proses selesaikan pesanan akan method POST ke homePembeli(selesaikan).php-->
                            <form action="homePembeli(selesaikan).php" method="post">
                                <input type="hidden" value="<?= $pesanan["id_pesanan"]; ?>" name="idPesanan">
                                <button type="submit" class="selesaikan" onclick="return confirm('YAKIN SELESAIKAN PESANAN ?');">Selesaikan</button> 
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>   
            </table>
        <?php endif; ?>

        <br>

        <!--cek apakah ada pesanan yang status belum terbayar (METODE CASH)-->
        <?php if($iniPesananBelumTerbayar) : ?>
            <h4>Metode Cash Bayar Ditempat</h4>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>Penjual</td>
                    <td>Info Pesanan</td>
                    <td>Kode Transaksi</td>
                </tr>
                <?php foreach ($iniPesananBelumTerbayar as $pesanan) : ?>
                    <tr>
                        <?php
                            $idPenjual = $pesanan["id_penjual"];
                            $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
                        ?>
                        <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                        <td>
                            <ul class="list-info-mobile">
                                <li>
                                    <p>Menu Dipesan :</p>
                                    <p> <?= $pesanan["menu_dipesan"]; ?> </p>
                                </li>
                                <li>
                                    <p>Jumlah Pesanan :</p>
                                    <p> <?= $pesanan["jumlah_pesanan"] ?> </p>
                                </li>
                                <li>
                                    <p>Jumlah Transaksi :</p>
                                    <p> <?= $pesanan["jumlah_transaksi"]; ?> </p>
                                </li>
                            </ul>
                        </td>
                        <td> <?= $pesanan["kode_pesanan"]; ?> </td>
                    </tr>
                <?php endforeach; ?>   
            </table>
        <?php endif; ?>

        <br>

        <!--cek apakah ada pesanan yang statusnya pending? (METODE PAYMENT GATEAWAY-->
        <?php if($iniPesananPending) : ?>
            <h4>Pesanan Payment Gateway Pending</h4>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>Penjual</td>
                    <td>Info Pesanan</td>
                    <td>Kode Transaksi</td>
                </tr>
                <?php foreach ($iniPesananPending as $pesanan) : ?>
                    <tr>
                        <?php
                            $idPenjual = $pesanan["id_penjual"];
                            $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
                        ?>
                        <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                        <td>
                            <ul class="list-info-mobile">
                                <li>
                                    <p>Menu Dipesan :</p>
                                    <p> <?= $pesanan["menu_dipesan"]; ?> </p>
                                </li>
                                <li>
                                    <p>Jumlah Pesanan :</p>
                                    <p> <?= $pesanan["jumlah_pesanan"] ?> </p>
                                </li>
                                <li>
                                    <p>Jumlah Transaksi :</p>
                                    <p> <?= $pesanan["jumlah_transaksi"]; ?> </p>
                                </li>
                            </ul>
                        </td>
                        <td> 
                            <?= $pesanan["kode_pesanan"]; ?> 
                            <button id="pay-button<?= $pesanan["id_pesanan"]; ?>">Cara Bayar</button> 
                        </td>
                    </tr>  
                    <!--UNTUK MEMUNCULKAN SNAP PAYMENT GATEAWAY-->
                    <script type="text/javascript">
                            // For example trigger on button clicked, or any time you need
                            var payButton = document.getElementById('pay-button<?= $pesanan["id_pesanan"]; ?>');
                            payButton.addEventListener('click', function () {
                            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
                            window.snap.pay('<?= $pesanan["snap_token"]; ?>');
                            // customer will be redirected after completing payment pop-up
                        });
                    </script> 
                    <!--cara kerja di atas itu kita ulang2 terus untuk tiap pesanan code JavaScript-nya (tiap button itu akan berbeda 1 sama lain dari attribut "id"nya)-->
                <?php endforeach; ?>   
            </table>
        <?php endif; ?>

        <br>

        <!--cek apakah ada pesanan yang sudah dengan status settlement (METODE PAYMENT GATEAWAY)-->
        <?php if($iniPesananSettlement) : ?>
            <h4>Sudah Dibayar (E-Wallet / Bank / Indomaret / Alfa Group)</h4>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>Penjual</td>
                    <td>Info Pesanan</td>
                    <td>Kode Transaksi</td>
                </tr>
                <?php foreach ($iniPesananSettlement as $pesanan) : ?>
                    <tr>
                        <?php
                            $idPenjual = $pesanan["id_penjual"];
                            $iniPenjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
                        ?>
                        <td> <?= $iniPenjual[0]["username_penjual"]; ?> </td>
                        <td>
                            <ul class="list-info-mobile">
                                <li>
                                    <p>Menu Dipesan :</p>
                                    <p> <?= $pesanan["menu_dipesan"]; ?> </p>
                                </li>
                                <li>
                                    <p>Jumlah Pesanan :</p>
                                    <p> <?= $pesanan["jumlah_pesanan"] ?> </p>
                                </li>
                                <li>
                                    <p>Jumlah Transaksi :</p>
                                    <p> <?= $pesanan["jumlah_transaksi"]; ?> </p>
                                </li>
                            </ul>
                        </td>
                        <td> 
                            <?= $pesanan["kode_pesanan"]; ?> 
                            <!--Proses selesaikan pesanan akan method POST ke homePembeli(selesaikan).php-->
                            <form action="homePembeli(selesaikan).php" method="post">
                                <input type="hidden" value="<?= $pesanan["id_pesanan"]; ?>" name="idPesanan">
                                <button type="submit" onclick="confirm('YAKIN SELESAIKAN PESANAN ?');">Selesaikan</button> 
                            </form>  
                        </td>
                    </tr>
                <?php endforeach; ?>   
            </table>
        <?php endif; ?>

        <!--cek apakah belum ada pesanan? dengan cek apakah semua yang diquery kosong-->
        <?php if($iniPesananTerbayar == false && $iniPesananPending == false) : ?>
            <h3>Sedang tidak ada pesanan yang berjalan.</h3>
        <?php endif; ?>
    </section>


    <!--Bagian Sambutan Pengenalan-->
    <section class="pengenalan">
        <div class="gambar-sambutan">
            <!-- <img src="smart-phone-food-unsplash-1.jpg" alt="Gambar tidak muncul"> -->
        </div>
        <div class="tulisan">
            <h3>Sudah mengenal sistem pre order smart canteen?</h3>
            <p>Sistem pre order smart canteen adalah sistem pemesanan makanan online yang memiliki peran untuk mempraktiskan sistem pemesanan di kantin.</p>
            <p>Sistem pre order smart canteen memiliki jangkauan lokal khusus untuk satu wilayah saja, kehusus kampus tertentu.</p>
            <p>Dengan fitur pre order, masyarakat dapat melakukan pemesanan makanan terlebih dahulu sebelum menuju kantin.</p>
            <p>Silahkan memesan menu pada halaman "Pesan Menu" lalu pilih metode bayar yang anda minati (Saldo, Cash, Payment Gateaway)</p>
        </div>
    </section>


    <!--Bagian Debug-->
    <br><br><br><br> <!--untuk mengatasi iklan di bawah konten-->


    <script src="homePembeli.js"></script>
</body>
</html>