<?php 
//FUNCTION HANDLE PEMBELI


//Bagian Koneksi Dengan Database "preorder_smart_canteen"
$db = mysqli_connect("localhost", "root", "", "preorder_smart_canteen");


//Bagian Function Query Untuk Ambil Data
function queryData($queryPembeli) {
    //ambil variable bernama "db" di luar function yang isinya koneksi database
    global $db; 

    //lakukan query pada database yang sudah terkoneksi dengan input-nya itu query di function ini 
    $result = mysqli_query($db, $queryPembeli);

    //buat atau siapkan array kosong untuk menampung data (bisa saja data user_pembeli, user_penjual, riwayat)
    if (mysqli_num_rows($result)) {
        $instances = [];
        //tarik semua data yang sudah diquery, while akan berjalan terus selama data masih bisa ditarik (artinya true)
        while ($instance = mysqli_fetch_assoc($result)) {
            //masukkan data yang sudah ditarik ke dalam array yang sudah disiapkan 
            $instances[] = $instance;
        }
        //kembalikan array yang sudah diisi (isi data table dari database yang sudah ditarik)
        return $instances;
    } else {
        return false;
    }
}
//function di atas akan kembalikan array dengan bentuk data yang sudah diquery (keseluruhan), lalu difetch semuanya, dan menjadi bentuk array


//Bagian Function Ubah Profile Pembeli
//$dataubah berasal dari $_POST halaman akunPembeli(ubah).php
function ubahProfile($dataubah) {
    global $db;

    $idPembeli = $dataubah["idPembeli"];
    $username = mysqli_real_escape_string($db, stripslashes(strip_tags($dataubah["username"])));
    $email = mysqli_real_escape_string($db, stripslashes(strip_tags($dataubah["email"])));
    $noTelpon = htmlspecialchars($dataubah["noTelpon"]);
    //fungsi "mysqli_real_escape_string(koneksi_database, datanya)" untuk boleh memasukkan tanda kutip di dalamnya dan bisa dimasukin ke database secara aman
    $passwordLama = mysqli_real_escape_string($db, $dataubah["passwordLama"]);
    $password1 = mysqli_real_escape_string($db, $dataubah["password1"]);
    $password2 = mysqli_real_escape_string($db, $dataubah["password2"]);

    //cek username bukan yang milik admin
    if ($username == "LordAdmin123") {
        echo "<script>
            alert('Username Sudah Terdaftar, PAKAI YANG LAIN');
        </script>"; 
        return false;
    }
    //cek username atau email sudah ada atau belum? (Untuk Pembeli)
    //ambil dulu username atau email yang sama jika ada
    //dibawah ini kita logikanya ambil data berdasarkan username atau email tapi yang id-nya bukan miliknya, jadi cek untuk data orang lain (selain diri sendiri)
    $milikPembeliLain = mysqli_query($db, "SELECT username_pembeli, email_pembeli FROM user_pembeli WHERE (username_pembeli = '$username' OR email_pembeli = '$email') AND id_pembeli NOT IN ($idPembeli)");
    //baru tes apakah data terisi? kalo iya berarti ada username yang sama (karena datanya keambli)
    //cek apakai ada data lebih dari 1 yang terambil?
    if (mysqli_fetch_assoc($milikPembeliLain)) {
        echo "<script>
            alert('Username Atau Email Sudah Terdaftar, PAKAI YANG LAIN');
        </script>"; 
        return false;
    }
    //cek username atau email sudah ada atau belum? (Untuk Penjual)
    $milikPenjualLain = mysqli_query($db, "SELECT username_penjual, email_penjual FROM user_penjual WHERE username_penjual = '$username' OR email_penjual = '$email'");
    if (mysqli_fetch_assoc($milikPenjualLain)) {
        echo "<script>
            alert('Username Atau Email Sudah Terdaftar, PAKAI YANG LAIN');
        </script>"; 
        return false;
    }

    //cek email, apakah valid? dibawah ini namanya teknik regex
    if (!preg_match("/[\w]+@[a-z.]+/", $email)) {
        echo "<script>
            alert('Email Yang Dimasukkan TIDAK VALID');
        </script>"; 
    return false;
    }

    //cek nomor telpon, gaboleh masukin selain angka dan batas angka-nya itu 12
    //pakai function "is_numeric()" akan balikin true kalo variable-nya adalah numeric atau numeric string kalo engga akan balikin false
    //function "strlen()" untuk cek panjang suatu string
    if (!is_numeric($noTelpon) || strlen($noTelpon) > 12) {
        echo "<script>
            alert('Mohon Masukkan Nomor Telpon Yang VALID');
        </script>";
        return false;
    }
    
    $password = password_hash($passwordLama, PASSWORD_DEFAULT);

    //cek apakah dia ganti password jadi password baru? dicek apa dia isi form untuk ganti password
    if (strlen($password1) != 0 || strlen($password2) != 0) {
        //cek apakah ada code di dalam password?
        if ($password1 !== strip_tags($password1)) {
            echo "<script>
                alert('Dilarang memasukkan code sebagai password');
            </script>";
            return false;
        }
        if ($password1 !== $password2) {
            echo "<script>
                alert('Konfirmasi Password TIDAK SESUAI');
            </script>";
            return false;
        }
        $password = password_hash($password1, PASSWORD_DEFAULT);
    }
    
    //cek apakah password lama yang dimasukkan sesuai?
    //ambil dahulu data password di database
    $result = mysqli_query($db, "SELECT * FROM user_pembeli WHERE id_pembeli = $idPembeli");
    $row = mysqli_fetch_assoc($result);
    if (password_verify($passwordLama, $row["password_pembeli"])) {
        //password lama yang dimasukkan sesuai, baru boleh update database
        $queryinsert = "UPDATE user_pembeli SET username_pembeli = '$username', email_pembeli= '$email', no_telpon_pembeli = '$noTelpon', password_pembeli = '$password' WHERE id_pembeli = $idPembeli";
        mysqli_query($db, $queryinsert);
        return mysqli_affected_rows($db);
    }
    echo "<script>
        alert('Mohon Masukkan Password Lama Yang SESUAI');
        </script>";
    return false;
}


//Bagian Function Ubah Stock Menu
function ubahStock($idMenu, $stockBaru) {
    global $db;
    mysqli_query($db, "UPDATE menu SET stock = $stockBaru WHERE id_menu = $idMenu");
}


//Bagian Function Checkout Metode Saldo
//yaitu aksi untuk memasukkan ke database pesanan (saldo), saldo berkurang
function checkoutSaldo($idPembeliX, $idPenjualX, $namaMenuStringX, $jumlahMenuStringX, $jumlahPerPenjualX, $randomStringX) {
    /*
    echo '<script>alert("Sampe Sini Udah Jalan");</script>'; //hanya untuk debug
    */
    global $db;
    $randomStringY = $randomStringX;
    $masukPesananSaldo = mysqli_query($db, "INSERT INTO pesanan VALUES ('', NOW(), $idPembeliX, $idPenjualX, '$namaMenuStringX', '$jumlahMenuStringX', $jumlahPerPenjualX, '$randomStringX', 'saldo', 'terbayar', '')");
    //Tambah Saldo Penjual
    $result = mysqli_query($db, "SELECT * FROM user_penjual WHERE id_penjual = $idPenjualX"); //ambil data penjual dari database
    $row = mysqli_fetch_assoc($result);
    $saldoPenjualLama = $row["saldo_penjual"]; //tangkap saldo penjual
    $saldoPenjualBaru = $saldoPenjualLama + $jumlahPerPenjualX; //perhitungan ditambah dengan biaya belanja pembeli untu perpenjualnya
    //edit table dengan saldo yang baru berdasarkan penjual yang datanya kita ambil (menu pesanan yang bersangkutan)
    mysqli_query($db, "UPDATE user_penjual SET saldo_penjual = $saldoPenjualBaru WHERE id_penjual = $idPenjualX"); 
}


//Bagian Function Checkout Metode Cash
//yaitu aksi untuk memasukkan ke database pesanan (cash), saldo tidak berkurang
function checkoutCash($idPembeliX, $idPenjualX, $namaMenuStringX, $jumlahMenuStringX, $jumlahPerPenjualX, $randomStringX) {
    /*
    echo '<script>alert("Sampe Sini Udah Jalan");</script>'; //hanya untuk debug
    */
    global $db;
    $randomStringY = $randomStringX;
    $masukPesananSaldo = mysqli_query($db, "INSERT INTO pesanan VALUES ('', NOW(), $idPembeliX, $idPenjualX, '$namaMenuStringX', '$jumlahMenuStringX', $jumlahPerPenjualX, '$randomStringX', 'cash', 'belum terbayar', '')");
}


//Bagian Function Checkout Metode Payment Gateway (Pending)
//function untuk memasukkan ke database pesanan (midtrans), awal status adalah pending, aksi simulasi bayar akan terdeteksi
function checkoutMidtrans($idPembeliX, $idPenjualX, $namaMenuStringX, $jumlahMenuStringX, $jumlahPerPenjualX, $randomStringX, $snapToken) {
    /*
    echo '<script>alert("Sampe Sini Udah Kerja");</script>'; //hanya untuk debug
    */
    global $db;
    $randomStringY = $randomStringX;
    $masukPesananSaldo = mysqli_query($db, "INSERT INTO pesanan VALUES ('', NOW(), $idPembeliX, $idPenjualX, '$namaMenuStringX', '$jumlahMenuStringX', $jumlahPerPenjualX, '$randomStringX', 'payment gateaway', 'pending', '$snapToken')");
}


//Bagian Function Checkout Metode Payment Gateway (Settlement)
//function untuk memasukkan ke database pesanan (midtrans), dari awal langsung bayar dan settlement
function checkoutMidtransDone($idPembeliX, $idPenjualX, $namaMenuStringX, $jumlahMenuStringX, $jumlahPerPenjualX, $randomStringX, $snapToken) {
    /*
    echo '<script>alert("Sampe Sini Udah Kerja");</script>'; //hanya untuk debug
    */
    global $db;
    $randomStringY = $randomStringX;
    $result = mysqli_query($db, "SELECT * FROM midtrans WHERE kode_pesanan = '$randomStringY'");
    $row = mysqli_fetch_assoc($result);
    $status = $row["status_payment"];
    
    //cek apakah statusnya settlement? (kemungkinan saat bikin pesanan GoPay atau ShopeePay ada yang bayar ada juga yang tidak)
    if ($status == "settlement") {
        //tambahkan saldo penjual
        $penjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjualX");
        $saldoLama = $penjual[0]["saldo_penjual"];
        $saldoBaru = $saldoLama + $jumlahPerPenjualX;
        mysqli_query($db, "UPDATE user_penjual SET saldo_penjual = $saldoBaru WHERE id_penjual = $idPenjualX");
    }

    $masukPesananMidtransSelesai = mysqli_query($db, "INSERT INTO pesanan VALUES ('', NOW(), $idPembeliX, $idPenjualX, '$namaMenuStringX', '$jumlahMenuStringX', $jumlahPerPenjualX, '$randomStringX', 'payment gateaway', '$status', '$snapToken')");
}


//Bagian Function Hapus Data Session Keranjang
function hapusBekasCheckout() {
    //hapus $_SESSION["keranjang], $_SESSION["perPenjual"] dan POST["bayarSaldo"] (tombol fix checkout saldo)
    unset($_SESSION["keranjangMenu"]);
    unset($_SESSION["perPenjual"]);
    unset($_POST["bayarSaldo"]);
    unset($_POST["bayarCash"]);
    //hapus $_SESSION["kodePesanan"], yang dibuat untuk handle GoPay dan ShopeePay
    unset($_SESSION["kodePesanan"]);
    echo '<script>
                alert("Anda sudah memesan, KERANJANG DIRESET.");
                document.location.href="pesanPembeli.php";
        </script>';
}


//Bagian Function Update Saldo
//yaitu untuk ubah saldo user setelah suatu proses tertentu
function updateSaldoPembeli($saldoPembeli, $idPembeli) {
    global $db;
    mysqli_query($db, "UPDATE user_pembeli SET saldo_pembeli = $saldoPembeli WHERE id_pembeli = $idPembeli");
}


//Bagian Function Hapus Table "midtrans"
//yaitu untuk hapus dari table midtrans jika transaksi sudah selesai (maka contoh backup dihapus)
function hapusMidtrans($kodePesanan) {
    global $db;
    mysqli_query($db, "DELETE FROM midtrans WHERE kode_pesanan = '$kodePesanan'");
} 


//Bagian Function Hapus Table "pesanan"
//yaitu untuk hapus data dari table pesanan jika transaksi sudah selesai
function hapusPesanan($idPesanan) {
    global $db;
    mysqli_query($db, "DELETE FROM pesanan WHERE id_pesanan = $idPesanan");
} 


//Bagian Function Masukkan Riwayat
//yaitu untuk memasukkan ke table riwayat yang berasal dari table pesanan
function masukkanRiwayat($idPembeli, $idPenjual, $menuDipesan, $jumlahPesanan, $jumlahTransaksi, $keterangan, $alasan) {
    global $db;
    $masukkanRiwayat = mysqli_query($db, "INSERT INTO riwayat VALUES ('', NOW(), $idPembeli, $idPenjual, '$menuDipesan', '$jumlahPesanan', $jumlahTransaksi, '$keterangan', '$alasan')");
}


//Bagian Function Aksi Buat Request Saldo
//yaitu untuk memasukkan ke table "request_saldo" jika user sudah membuat request saldo
function masukkanRequestSaldo($idPembeli, $usernamePembeli, $emailPembeli, $nominalRequest) {
    global $db;
    mysqli_query($db, "INSERT INTO request_saldo VALUES ('', $idPembeli, '$usernamePembeli', '$emailPembeli', $nominalRequest)");
    return mysqli_affected_rows($db);
}
?>