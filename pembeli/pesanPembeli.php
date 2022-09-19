<?php 
//SEBAGAI HALAMAN PESAN MENU (PEMBELI)


session_start(); //memulai session
require "fungsiPembeli.php"; //koneksi database dan user defined function


//Mengecek Session Pembeli
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginPembeli"])) {
    //kembalikan ke halaman login
    header("Location: ../index.php");
    exit;
}


//Mengambil Data Untuk Ditampilkan
//query data dari table menu untuk kategori makanan dan minuman, juga yang statusnya itu aktif
$iniMakanan = queryData("SELECT * FROM menu JOIN user_penjual ON menu.id_penjual = user_penjual.id_penjual WHERE kategori = 'makanan' AND stock > 0");
$iniMinuman = queryData("SELECT * FROM menu JOIN user_penjual ON menu.id_penjual = user_penjual.id_penjual WHERE kategori = 'minuman' AND stock > 0");
//dari function "queryData($queryPembeli)" yang sudah dibuat, sebenarnya isi variable diatas itu adalah $instances yang direturn


//Bekas Response Bayar Metode Payment Gateaway (Midtrans)
if (isset($_GET["order_id"]) && isset($_SESSION["keranjangMenu"]) && isset($_SESSION["snapToken"])) {
    $kodeUntukCek = $_GET["order_id"];
    //cek apakan order_id sudah ada di table? agar tidak perlu masukin ganda (bisa jadi data berasal dari user yang hanya melihat)
    $pesananSudahAda = queryData("SELECT * FROM pesanan WHERE kode_pesanan = '$kodeUntukCek'");
    //cek apakah jumlah array-nya 0 (artinya tidak ada)
    if ($pesananSudahAda === false) {
            //Lakukan Proses Masukkan Ke Table Pesanan
            /*
            echo '<script>alert("Anda akan melakukan pembayaran midtrans");</script>'; //hanya untuk debug
            */
            foreach($_SESSION["keranjangMenu"] as $idMenu1 => $jumlah1) {
                $result1 = mysqli_query($db, "SELECT * FROM menu WHERE id_menu = $idMenu1"); //saat ngulang dari foreach itu kita ambil dari database, sesuai id yang saat diperulangkan
                $tarik1 = mysqli_fetch_assoc($result1); //ini tarik data (keluaring data dari lemari yang kita bawa) 
            
                $idPenjual = $tarik1["id_penjual"]; //ini dibikin soalnya nanti untuk pembanding (dicek) dan dibagi per-penjual
            
                //cek apakah dia yang bagian tertentu itu sudah ada? kalo belum maka bikin
                //sekalian kita kelompokkan jadi berdasarkan penjual yang sama
                if(isset($_SESSION["perPenjual"][$idPenjual])) {
                    //kalo udah ada maka gabung ke array tersebut
                    $_SESSION["perPenjual"]["$idPenjual"] += ["$idMenu1" => $jumlah1]; 
                } else {
                    //kelo belum ada maka bikin array tersebut
                    $_SESSION["perPenjual"][$idPenjual][$idMenu1] = $jumlah1;
                }
            }
            //RULES :
            //jadi untuk tiap barang di $_SESSION["keranjang"] kita buat perlangan dan ambil siapa id penjualnya untuk barang tersebut
            //nah lalu kita akan membentuk dan mengelompokkan pesanan itu berdasarkan id penjual
            //terbentuklah $_SESSION["perPenjual"] yang ide-nya itu kita cek, apakah key untuk id penjual tertentu sudah ada?
            //kalo belum ada maka bikin, kalo sudah ada maka tambahin isi array-nya saja
            
            //MEMPROSES $_SESSION["perPenjual"] MENJADI INSERT RECORD PESANAN
            //sekarang proses untuk memasukkan ke table pesanan (berulang tergantung dari banyak jenis penjual di $_SESSION["perPenjual"])
            foreach($_SESSION["perPenjual"] as $idPenjual1 => $menuDipesan) { //mungki bentuk integer $idPenjual itu dari sini
                $idPembeliX = $_SESSION["idPembeli"]; //ini nanti dapet dari idUser
                $idPenjualX = $idPenjual1; //ini akan ada per Penjual-nya dari SESSION per-penjual (bentuknya udah integer)
            
                $namaMenuPerPenjual = [];
                $jumlahPerPenjualX = 0;
                $jumlahMenu = [];
                foreach($menuDipesan as $idMenu2 => $jumlah2) {
                    $result2 = mysqli_query($db, "SELECT * FROM menu WHERE id_menu = $idMenu2"); //saat ngulang dari foreach itu kita ambil dari database, sesuai id yang saat diperulangkan
                    $tarik2 = mysqli_fetch_assoc($result2);
            
                    $namaMenuPerPenjual[] = $tarik2["nama_menu"];
            
                    $subHarga = $tarik2["harga"] * $jumlah2;
                    $jumlahPerPenjualX += $subHarga;
            
                    $jumlahMenu[] = $jumlah2;
                }
                $namaMenuStringX = implode(", ", $namaMenuPerPenjual); //ini namanya sudah dirubah menjadi string
                $jumlahMenuStringX = implode(", ", $jumlahMenu); //ini jumlah menu undah digabung jadi string
                $snapToken = $_SESSION["snapToken"];
            
                //untuk proses pemasukan pesanan ke database dilakukan di dalam perulangan foreach, karena kalo diluar hanya akan 1 data pesanan menu milik 1 penjual
                //sementara isi variable pesanan untuk penjual lain variable-nya sudah tertimpa
                checkoutMidtrans($idPembeliX, $idPenjualX, $namaMenuStringX, $jumlahMenuStringX, $jumlahPerPenjualX, $_GET["order_id"], $snapToken);
            }  
            // var_dump($_SESSION["idPembeli"]);
            // die;
            //nah kalo proses sudah semua maka hapus semua data pesanannya
            echo '<script>alert("Pasanan metode pilihan anda SUDAH DIBUAT.");</script>'; //sebenarnya ini metode midtrans
            /*
            echo "<script> alert ('apakah yang reset PERTAMA ini yang jalan?'); </script>"; //hanya untuk debug
            */
            hapusBekasCheckout();            
    }
}


//Bekas Bayar Pakai GoPay atau ShopeePay
//cek apakah kode transaksi kita ini ada di table trigger? kalo ada itu artinya kita sehabis melakukan aksi bayar GoPay atau ShopeePay
if (isset($_SESSION["kodePesanan"])) {
    $kodePesanan = $_SESSION["kodePesanan"];
    $dataPemicu = queryData("SELECT * FROM pemicu WHERE kode_pesanan = '$kodePesanan'");
    //cek apakah data trigger yang kita tarik berdasarkan $_SESSION["kodePesanan"] itu ada? atau tidak kosong?
    if ($dataPemicu != false && isset($_SESSION["keranjangMenu"]) && isset($_SESSION["kodePesanan"])) {
        //maka lakukan proses masukkan ke table pesanan dengan status langsung settlement
        /*
        echo '<script>alert("Anda akan melakukan pembayaran midtrans");</script>'; //hanya untuk debug
        */
        foreach($_SESSION["keranjangMenu"] as $idMenu1 => $jumlah1) {
            $result1 = mysqli_query($db, "SELECT * FROM menu WHERE id_menu = $idMenu1"); //saat ngulang dari foreach itu kita ambil dari database, sesuai id yang saat diperulangkan
            $tarik1 = mysqli_fetch_assoc($result1); //ini tarik data (keluaring data dari lemari yang kita bawa) 
                
            $idPenjual = $tarik1["id_penjual"]; //ini dibikin soalnya nanti untuk pembanding (dicek) dan dibagi per-penjual
                
            //cek apakah dia yang bagian tertentu itu sudah ada? kalo belum maka bikin
            //sekalian kita kelompokkan jadi berdasarkan penjual yang sama
            if(isset($_SESSION["perPenjual"][$idPenjual])) {
                //kalo udah ada maka gabung ke array tersebut
                $_SESSION["perPenjual"]["$idPenjual"] += ["$idMenu1" => $jumlah1]; 
            } else {
                //kelo belum ada maka bikin array tersebut
                $_SESSION["perPenjual"][$idPenjual][$idMenu1] = $jumlah1;
            }
        }
        //RULES :
        //jadi untuk tiap barang di $_SESSION["keranjang"] kita buat perlangan dan ambil siapa id penjualnya untuk barang tersebut
        //nah lalu kita akan membentuk dan mengelompokkan pesanan itu berdasarkan id penjual
        //terbentuklah $_SESSION["perPenjual"] yang ide-nya itu kita cek, apakah key untuk id penjual tertentu sudah ada?
        //kalo belum ada maka bikin, kalo sudah ada maka tambahin isi array-nya saja
                
        //MEMPROSES $_SESSION["perPenjual"] MENJADI INSERT RECORD PESANAN
        //sekarang proses untuk memasukkan ke table pesanan (berulang tergantung dari banyak jenis penjual di $_SESSION["perPenjual"])
        foreach($_SESSION["perPenjual"] as $idPenjual1 => $menuDipesan) { //mungki bentuk integer $idPenjual itu dari sini
            $idPembeliX = $_SESSION["idPembeli"]; //ini nanti dapet dari idUser
            $idPenjualX = $idPenjual1; //ini akan ada per Penjual-nya dari SESSION per-penjual (bentuknya udah integer)
                
            $namaMenuPerPenjual = [];
            $jumlahPerPenjualX = 0;
            $jumlahMenu = [];
            foreach($menuDipesan as $idMenu2 => $jumlah2) {
                $result2 = mysqli_query($db, "SELECT * FROM menu WHERE id_menu = $idMenu2"); //saat ngulang dari foreach itu kita ambil dari database, sesuai id yang saat diperulangkan
                $tarik2 = mysqli_fetch_assoc($result2);
                
                $namaMenuPerPenjual[] = $tarik2["nama_menu"];
                
                $subHarga = $tarik2["harga"] * $jumlah2;
                $jumlahPerPenjualX += $subHarga;
                
                $jumlahMenu[] = $jumlah2;
            }
            $namaMenuStringX = implode(", ", $namaMenuPerPenjual); //ini namanya sudah dirubah menjadi string
            $jumlahMenuStringX = implode(", ", $jumlahMenu); //ini jumlah menu undah digabung jadi string
            $snapToken = $_SESSION["snapToken"];
                
            //untuk proses pemasukan pesanan ke database dilakukan di dalam perulangan foreach, karena kalo diluar hanya akan 1 data pesanan menu milik 1 penjual
            //sementara isi variable pesanan untuk penjual lain variable-nya sudah tertimpa
            checkoutMidtransDone($idPembeliX, $idPenjualX, $namaMenuStringX, $jumlahMenuStringX, $jumlahPerPenjualX, $kodePesanan, $snapToken);      
        }  
        // var_dump($_SESSION["idPembeli"]);
        // die;
        //hapus data dari table pemicu
        $db = mysqli_connect("localhost", "root", "", "preorder_smart_canteen");
        mysqli_query($db, "DELETE FROM pemicu WHERE kode_pesanan = '$kodePesanan'");
        echo '<script>alert("Pasanan metode plihan anda SUDAH DIBUAT.");</script>'; //sebenarnya ini metode midtrans
        //nah kalo proses sudah semua maka hapus semua data pesanannya
        hapusBekasCheckout();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu (Pembeli)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Pacifico&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="pesanPembeli.css">
</head>
<body>
    <nav>
        <img src="../LogoCanteen2.png">

        <div class="logo">
            <h4>Smart Canteen</h4>
        </div>
        
        <ul class="navigasi">
            <li><a href="homePembeli.php">Home</a></li>
            <li><a href="#">Pesan Menu</a></li>
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


    <!--Untuk Logout-->
    <a class="tombolLogout" href="../logoutsmartcanteen.php" onclick="return confirm('Apakah yakin Logout dari halaman?');">Logout</a>


    <h2>Selamat Memesan Menu :</h2>
    

    <div class="pilih">
        <a href="#makanan">Makanan</a> | <a href="#minuman">Minuman</a>
    </div>


    <h3 id="makanan">Makanan :</h3>


    <section class="daftar-makanan">
        <?php foreach($iniMakanan as $makanan) : ?>
            <div class="makanan">
                <img src="../penjual/img/<?= $makanan["gambar"]; ?>" alt="">
                <p class="namaMenu"> <b> <?= $makanan["nama_menu"]; ?> </b> </p>
                <p>Rp <?= $makanan["harga"]; ?> </p>
                <p> <?= $makanan["username_penjual"]; ?> </p> 
                <a href="pesanPembeli(keranjang).php?id=<?=$makanan['id_menu']?>">Beli</a>
            </div>
        <?php endforeach; ?>    
    </section>


    <!--float: left di css akan terus berlanjut ke element setelehnya, maka perlu dibersihkan :-->
    <div class="bersihkan-float"></div>


    <h3 id="minuman">Minuman :</h3>

    
    <section class="daftar-minuman">
        <?php foreach($iniMinuman as $minuman) : ?>
            <div class="minuman">
                <img src="../penjual/img/<?= $minuman["gambar"]; ?>" alt="">
                <p class="namaMenu"> <b> <?= $minuman["nama_menu"]; ?> </b> </p>
                <p>Rp <?= $minuman["harga"]; ?> </p>
                <p> <?= $minuman["username_penjual"]; ?> </p> 
                <a href="pesanPembeli(keranjang).php?id=<?=$minuman['id_menu']?>">Beli</a>
            </div>
        <?php endforeach; ?> 
    </section> 


    <!--float: left di css akan terus berlanjut ke element setelehnya, maka perlu dibersihkan :-->
    <div class="bersihkan-float"></div>
    

    <!--Untuk space tempat memesan-->
    <div class="space-pesanan"></div>


    <!--The Shopping Cart Modal-->
    <div id="modalKeranjang" class="keranjang">           
        <!--Modal content-->
        <div class="contentKeranjang">
            <span class="closeKeranjang">&times;</span>
            <h2>Daftar Pesanan Anda</h2>
            <p> <b> Pastikan kembali daftar pesanan dan jumlah sudah sesuai sebelum melakukan fix pesan. </b> </p>
            <!--Pakai Sistem PercobaanPHPCampuran/ShoppingCart1.php dan PercobaanPHPCampuran/ShoppingCart1(sambungan).php tapi posisi pemunculan table dimanipulasi lagi-->
            <?php
                $tagihan = 0; //bikin variable untuk tagihan total
                if(isset($_SESSION["keranjangMenu"])) {
            ?>        
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <td>Nama Menu</td>
                    <td>Jumlah Beli</td>
                    <td>Harga</td>
                    <td>Sub Harga</td>
                    <td>Aksi</td>
                </tr>
                <?php
                    foreach($_SESSION["keranjangMenu"] as $id_menu=> $jumlah): //tulis begini supaya bisa ambil nama key sama valuenya
                        $result = mysqli_query($db, "SELECT * FROM menu WHERE id_menu = $id_menu"); //saat ngulang dari foreach itu kita ambil dari database, sesuai id yang saat diperulangkan
                        $tarik = mysqli_fetch_assoc($result); //ini tarik data (keluaring data dari lemari yang kita bawa) 
                        //perhitungan untuk sub harga
                        $subHarga = $tarik["harga"] * $jumlah;
                        $tagihan += $subHarga; //bagian tagihan ini nanti akan dimasukkan ke database
                ?>
                    <tr>
                        <td><?= $tarik["nama_menu"]; ?></td>
                        <td><?= $jumlah; ?></td>
                        <td>Rp. <?php echo number_format($tarik["harga"]); ?></td>
                        <td>Rp. <?php echo number_format($subHarga); ?></td>
                        <td><a href="pesanPembeli(keranjang).php?idHapus=<?=$id_menu?>">Hapus</a></td>
                    </tr>
            <?php
                    endforeach;
                } else {
                    echo "Anda Belum Memesan Apapun";
                }
            ?> 
            </table>
            <br>
            <a class="tombolCheckout" href="pesanPembeli(checkout).php">Checkout</a>
        </div>
    </div>

    
    <!--Untuk Footer Pesanan-->
    <div class="kotak-pesanan">
        <table>
            <tr>
                <td><button id="tombolModal" class="tombolModal">Lihat Keranjang</button></td> 
                <td class="tulisanTotal">Total : Rp <?php echo number_format($tagihan); ?></td>
            </tr>
        </table>
    </div>


    <script src="pesanPembeli.js"></script>
</body>
</html>

<?php
/*
var_dump(count($iniMakanan)); //hanya untuk debug
*/

/*
$pesananSudahAda = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM midtrans WHERE kode_pesanan = 'BSUW98YXRU'"));
$pesananSudahAdas[] = $pesananSudahAda;
$x = count($pesananSudahAdas);
var_dump($x); //hanya untuk debug percobaan count() untuk hitung array
*/

/*
echo "<pre>";
print_r($_SESSION); //hanya untuk debug
echo "</pre>";
echo "<br> <br> <br>";
*/
?>