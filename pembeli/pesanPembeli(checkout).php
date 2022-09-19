<?php 
//MENANGANI AKSI CHECKOUT KERANJANG


session_start(); //memulai session
require "fungsiPembeli.php"; //koneksi database dan user defined function


//Mengecek Session Pembeli
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginPembeli"])) {
    //kembalikan ke halaman login
    header("Location: ../index.php");
    exit;
}


/*
echo "<pre>";
print_r($_SESSION); //hanya untuk debug
echo "</pre>"; 
die;
*/


//Mengecek Session Keranjang Menu
if(!isset($_SESSION["keranjangMenu"]) OR empty($_SESSION["keranjangMenu"])) {
    echo '<script>alert("Anda Belum Memesan Menu Apapun")</script>';
    echo '<script>location="pesanPembeli.php"</script>';
}


//Mengecek Session Kode Pesanan
if (isset($_SESSION["kodePesanan"])) {
    $kodeLama = $_SESSION["kodePesanan"];
    //khusus GoPay 
    //kita cek apakah $_SESSION["kodePesanan"] yang kita punya itu ada di table pemicu?
    $dataPemicu = queryData("SELECT * FROM pemicu WHERE kode_pesanan = '$kodeLama'");
    //cek apakah data trigger yang kita tarik berdasarkan $_SESSION["kodePesanan"] itu ada? atau tidak kosong?
    if ($dataPemicu != 0 && isset($_SESSION["keranjangMenu"]) && isset($_SESSION["kodePesanan"])) {
        echo '<script> document.location.href="pesanPembeli.php"; </script>';
        die;
    }
}


//Membuat Dan Menyimpan Kode Acak
$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters); //ini menghitung banyak character-nya dimulai dari 1
$randomString = '';
for($i = 0; $i < 10; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
}


//Untuk Payment Gateaway Midtrans
//require_once dirname(__FILE__) . 'vendor/midtrans/midtrans-php/Midtrans.php'; //tadinya gabisa require-nya error
require_once '../vendor/midtrans/midtrans-php/Midtrans.php'; //kalo ini bisa

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-95Pj26erGQYFFwGB2BYbFOu-';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;
//SAMPE SINI UNTUK PAYMENT GATEAWAY


//Mengambil Info Pembeli
$idPembeli = $_SESSION["idPembeli"];
$dataPembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $idPembeli");
$usernamePembeli = $dataPembeli[0]["username_pembeli"];
$emailPembeli = $dataPembeli[0]["email_pembeli"];
$phonePembeli = $dataPembeli[0]["no_telpon_pembeli"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--UNTUK LINK SCRIPT PAYMENT GATEAWAY-->
    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-hTWsFQZqu1hCoL63">
    </script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
    <title>Checkout</title>
    <style>
        body {
            background: url('../admin/background-makanan-admin.jpg');
        }
        .container {
            width: 90%;
            margin: 5% auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <p><b>Metode Saldo</b>, akan mengurangi saldo pada akun smart canteen anda. Pesanan hanya bisa dipesan dan diproses jika saldo anda mencukupi dengan jumlah tagihan.</p>
        <p><b>Metode Cash</b>, merupakan metode membayar di tempat. Saldo anda tidak akan dikurangi. Pastikan mengambil pesanan dan membayar dengan baik, karena pelanggaran berlebihan dapat terkena sanksi.</p>
        <p><b>Metode Payment Gateaway</b>, membuat anda bisa melakukan pembayaran melalui berbagai layanan lain (transfer bank, GoPay).</p>
        <p><b>Pesanan dari penjual yang berbeda akan dipecah</b></p>

        <hr>

        <p>Selalu Ingat Kode Pesanan Anda (anda masih bisa melihatnya pada pesanan yang diproses).</p>
        <h4>Kode anda : <?= $randomString ?></h4>

        <hr>

        <h3>Menu Pesanan Anda</h3>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <td>Nama Menu</td>
                <td>Jumlah Beli</td>
                <td>Harga</td>
                <td>Sub Harga</td>
             </tr>
            <?php
                $tagihan = 0;
                foreach($_SESSION["keranjangMenu"] as $idMenu => $jumlah): //tulis begini supaya bisa ambil nama key sama valuenya
                    $result = mysqli_query($db, "SELECT * FROM menu WHERE id_menu = $idMenu"); //saat ngulang dari foreach itu kita ambil dari database, sesuai id yang saat diperulangkan
                    $tarik = mysqli_fetch_assoc($result); //ini tarik data (keluarin data dari lemari yang kita bawa) 
                    //perhitungan untuk sub harga
                    $subHarga = $tarik["harga"] * $jumlah;
                    $tagihan += $subHarga; //bagian tagihan ini nanti akan dimasukkan ke database
            ?>
                <tr>
                    <td><?= $tarik["nama_menu"]; ?></td>
                    <td><?= $jumlah; ?></td>
                    <td>Rp. <?php echo number_format($tarik["harga"]); ?></td>
                    <td>Rp. <?php echo number_format($subHarga); ?></td>
                </tr>
            <?php
                endforeach;
            ?> 
        </table>

        <p>Jumlah pesanan anda : Rp <?= $tagihan; ?></p>

        <h3>Pilih Metode Pembayaran Anda</h3>
        <!--Tombol Bayar Metode Saldo-->
        <form action="" method="post">
            <!-- metode post akan seolah - olah melakukan refresh dan langsung ada variable-nya yang bawa data, (modelan sesuatu yang digenerate akan dilakukan ulang, termasuk kode random) -->
            <input type="hidden" name="randomString" value="<?= $randomString ?>"> <!-- taruh sini supaya jadi data d metode post, karena kalau ngga kode akan langsung berubah tiap refresh -->
            <button type="submit" name="bayarSaldo" onclick="return confirm('Apakah yakin METODE SALDO?');">Metode Saldo</button>
        </form>
        <br>
        <!--Tombol Bayar Metode Cash-->
        <form action="" method="post">
            <!-- metode post akan seolah - olah melakukan refresh dan langsung ada variable-nya yang bawa data, (modelan sesuatu yang digenerate akan dilakukan ulang, termasuk kode random) -->
            <input type="hidden" name="randomString" value="<?= $randomString ?>"> <!-- taruh sini supaya jadi data d metode post, karena kalau ngga kode akan langsung berubah tiap refresh -->
            <button type="submit" name="bayarCash" onclick="return confirm('Apakah yakin METODE CASH?');">Metode Cash</button>
        </form>
        <br>
        <!--Tombol Bayar Metode Payment Gateaway-->
        <button name="bayarMidtrans" onclick="confirm('Apakah yakin METODE PAYMENT GATEAWAY?');" id="pay-button">Metode Payment Gateway</button>
        <!--
        <form action="" method="post">
            <input type="hidden" name="randomString" value="<?= $randomString ?>"> 
            <button type="submit" name="bayarMidtrans" onclick="confirm('Apakah yakin METODE PAYMENT GATEAWAY?');" id="pay-button">Metode Payment Gateaway</button>
        </form>
        -->
    </div> 


    <!--UNTUK ISI INFO YANG AKAN DIBAWA SAAT REQUEST SNAP TOKEN PAYMENT GATEAWAY-->
    <?php
    //INI TIDAK DIJALANKAN KALO MAU COBA TRANSAKSI SALDO DAN CASH DI LOCALHOST AGAR TIDAK ERROR (NANTI JALANKAN LAGI)
    
        $params = array(
            'transaction_details' => array(
                'order_id' => $randomString, //bisa diisi variable 
                'gross_amount' => $tagihan, //bisa diisi variable
            ),
            'customer_details' => array(
                'first_name' => $usernamePembeli,
                'last_name' => '',
                'email' => $emailPembeli,
                'phone' => $phonePembeli,
            ),
        );
        //request tokennya dengan membawa data "$params"
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        //simpan snap tokennya ke session untuk dimasukkan ke database di halaman notification
        $_SESSION["snapToken"] = $snapToken;
    
    ?> 


    <!--UNTUK MEMUNCULKAN SNAP PAYMENT GATEAWAY-->
    <script type="text/javascript">
        // For example trigger on button clicked, or any time you need
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            window.snap.pay('<?= $snapToken; ?>');
            // customer will be redirected after completing payment pop-up
        });
    </script> 
</body>
</html>

<?php
$_SESSION["kodePesanan"] = $randomString;
//pembuatan $_SESSION yang nantinya akan terkena pengaruh aksi dari POST sebaiknya dilatakan di atas
//karena POST itu sebenarnya melakukan redirect tapi dengan membawa data POST (sama aja kayak GET)
//jadi kalo fungsi ini ditaruh di bawah maka akan dijalankan lagi (kena if POST untuk hapus $_SESSION, lalu malah bikin $_SESSION lagi)


//Handle Aksi Membayar Metode Saldo
//cek apakah tombol metode saldo sudah ditekan?
if(isset($_POST["bayarSaldo"])) {
    /*
    echo '<script>alert("APA SAMPE SINI METODE SALDO UDAH JALAN?")</script>'; //hanya untuk debug
    */
    $queryUser = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $_SESSION[idPembeli]");
    $saldoUser = $queryUser[0]["saldo_pembeli"]; //ambil data saldo pembeli
    //Cek Apakah Saldo Milik Pembeli Cukup? Bandingkan Apakah Lebih Besar Atau Sama Dengan Tagihannya?
    if($saldoUser >= $tagihan) {
        //nah kalo saldo udah cukup maka masukkan ke database pesanan
        echo '<script>alert("Saldo anda CUKUP.");</script>';

        //Kurangi Saldo User
        //data "$tagihan" sudah ada dari atas dan "saldoUser" juga sudah ada dari atas untuk pengecekan  
        $sisaSaldo = $saldoUser - $tagihan; //proses perhitungan
        $idPembeli = $_SESSION["idPembeli"]; //tangkap id pembelinya
        updateSaldoPembeli($sisaSaldo, $idPembeli); //"updateSaldo(nilai_saldonya)" itu adalah function yang kita buat sendiri di fungsiPembeli.php

        //Lakukan Proses Masukkan Ke Table Pesanan
        /*
        echo '<script>alert("Anda akan melakukan pembayaran saldo");</script>'; //hanya untuk debug
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
        foreach($_SESSION["perPenjual"] as $idPenjual1 => $menuDipesan) { //mungkin bentuk integer $idPenjual itu dari sini
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
    
            //untuk proses pemasukan pesanan ke database dilakukan di dalam perulangan foreach, karena kalo diluar hanya akan 1 data pesanan menu milik 1 penjual
            //sementara isi variable pesanan untuk penjual lain variable-nya sudah tertimpa
            checkoutSaldo($idPembeliX, $idPenjualX, $namaMenuStringX, $jumlahMenuStringX, $jumlahPerPenjualX, $_POST["randomString"]);
        }  
        // var_dump($_SESSION["idPembeli"]);
        // die;
        //nah kalo proses sudah semua maka hapus semua data pesanannya
        /*
        echo '<script>alert("Pasanan metode saldo SUDAH DIBUAT.");</script>'; //hanya untuk debug
        */
        hapusBekasCheckout();
    } else {
        echo '<script>alert("Saldo anda TIDAK CUKUP.");</script>';
        // kalo saldo gak cukup maka redirect ke halaman pesan pembeli
        echo '<script>location="pesanPembeli.php"</script>';
    }
}


//Handle Aksi Membayar Metode Cash
//cek apakah tombol metode cash sudah ditekan?
if(isset($_POST["bayarCash"])) {
    //Lakukan Proses Masukkan Ke Table Pesanan
    /*
    echo '<script>alert("Anda akan melakukan pembayaran cash");</script>'; //hanya untuk debug
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
    
        //untuk proses pemasukan pesanan ke database dilakukan di dalam perulangan foreach, karena kalo diluar hanya akan 1 data pesanan menu milik 1 penjual
        //sementara isi variable pesanan untuk penjual lain variable-nya sudah tertimpa
        checkoutCash($idPembeliX, $idPenjualX, $namaMenuStringX, $jumlahMenuStringX, $jumlahPerPenjualX, $_POST["randomString"]);
    }  
    // var_dump($_SESSION["idPembeli"]);
    // die;
    echo '<script>alert("Pasanan cash SUDAH DIBUAT.");</script>';
    //nah kalo proses sudah semua maka hapus semua data pesanannya
    hapusBekasCheckout();
}


/*
//METODE PAYMENT GATEAWAY
if (isset($_POST["bayarMidtrans"])) {
    //buat data SESSION untuk simpan kode_pesanan-nya
    $_SESSION["kode_pesanan"] = $_POST["randomString"];
}
*/

/*
echo "<pre>";
print_r($_SESSION); //hanya untuk debug
echo "</pre>";
*/
?>