<?php 
//FUNCTION HANDLE PENJUAL


//Bagian Koneksi Dengan Database "preorder_smart_canteen"
$db = mysqli_connect("localhost", "root", "", "preorder_smart_canteen");


//Bagian Function Query Untuk Ambil Data
function queryData($queryPenjual) {
    //ambil variable bernama "db" di luar function yang isinya koneksi database
    global $db; 

    //lakukan query pada database yang sudah terkoneksi dengan input-nya itu query di function ini 
    $result = mysqli_query($db, $queryPenjual);

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


//Bagian Function Aksi Tambah Menu
//$data berasal dari $_POST yang diinputkan
function tambahMenu($data) {
    global $db;
    global $_SESSION;

    //fungsi "htmlspecialchars()" agar input tidak langsung menampilkan script html (untuk keamanan) 
    //karena mungkin saja user masukkan script html
    $idPenjual = $_SESSION["idPenjual"];
    $kategori = htmlspecialchars($data["kategori"]);
    $namaMenu = mysqli_real_escape_string($db, htmlspecialchars(strip_tags($data["nama"])));
    $harga = htmlspecialchars($data["harga"]);
    $info = mysqli_real_escape_string($db, $data["info"]);

    $kategoriBoleh1 = "makanan";
    $kategoriBoleh2 = "minuman";


    //VALIDASI BELUM ADA, YANG BIKIN GAGAL CUMA KARENA TIPE DATA HARGA DARI MYSQL HARUS INTEGER
    //input kategori harus "makanan" atau "minuman", cek apakah $kategori bukan "makanan" dan "minuman"? (karena selain itu tidak boleh)
    if ($kategori !== "makanan" && $kategori !== "minuman") {
        echo "<script>
            alert('Mohon masukkan data KATEGORI dengan (makanan) atau (minuman)');
        </script>";
        return false;
    }

    //saat tag <input> diketikkan dan masuk lewat metode POST, itu entuknya string walaupun diisinya angka
    //kita harus cek bentuk string tersebut apakah mengandung angka? pakai regex : terdiri dari delimiter, pattern, dan optional modifier
    //dalam regex tanda "/" adalah delimiter, kalo pattern-nya kemungkinan ada tanda "/" maka delimiter-nya disarankan pakai "~" atau "#"
    //tanda "+" itu adalah quantifiers, "+" artinya mencocokan string apapun yang mengandung setidaknya 1 dari pattern
    //yang syarat kedua if di bawah setelah "atau" untuk cek agar tidak ada simbol yang dimasukkan
    if (preg_match("~[0-9]+~", $namaMenu) || preg_match("/[^\p{L}\p{N}\s]/u", $namaMenu)) {
        echo "<script>
            alert('Mohon masukkan data NAMA MENU dengan HURUF SAJA');
        </script>";
        return false;
    }

    //cek input harga bentuk datanya harus integer dan minimal 1000
    if (!is_numeric($harga) || $harga < 1000) {
        echo "<script>
            alert('Mohon masukkan data HARGA dengan ANGKA dan MINIMAL 1000');
        </script>";
        return false;
    }

    //cek validasi input info menu
    if ($info != Strip_tags($info)) {
        echo "<script>
            alert('Mohon untuk TIDAK MEMASUKKAN CODE sebagain info menu.');
        </script>";
        return false;
    }

    //upload gambar
    $gambar = upload();
    if (!$gambar) {
        //kalo $gambar isinya false atau function upload() gagal, maka hentikan proses function tambah() ini
        //bila sudah ketemu "return false;" maka perintah dibawah tidak akan dijalankan
        return false;
    }


    $queryinsert = "INSERT INTO menu VALUES ('', $idPenjual, '$kategori', '$namaMenu', $harga, '$info', '$gambar', 0)";
    mysqli_query($db, $queryinsert);

    return mysqli_affected_rows($db);
}


//Bagian Function Aksi Hapus Menu
//$id2 adalah id menu yang ditangkap metode request GET di halaman menuPenjual(hapus).php
function hapusMenu($id2, $idPenjual) {
    global $db;

    mysqli_query($db, "DELETE FROM menu WHERE id_menu = $id2 AND id_penjual = $idPenjual");

    return mysqli_affected_rows($db);
}


//Bagian Function Aksi Ubah Data Menu
//$dataubah berasal dari $_POST halaman menuPenjual(ubah).php
function ubahMenu($dataubah) {
    global $db;

    $idMenu = $dataubah["idMenu"];
    //fungsi "htmlspecialchars()" agar input tidak langsung menampilkan script html (untuk keamanan)
    $kategori = htmlspecialchars($dataubah["kategori"]);
    $namaMenu = mysqli_real_escape_string($db, htmlspecialchars(strip_tags($dataubah["nama"])));
    $harga = htmlspecialchars($dataubah["harga"]);
    $info = mysqli_real_escape_string($db, $dataubah["info"]);
    $gambarlama = htmlspecialchars($dataubah["gambarlama"]);

    //VALIDASI BELUM ADA, YANG BIKIN GAGAL CUMA KARENA TIPE DATA HARGA DARI MYSQL HARUS INTEGER
    //input kategori harus "makanan" atau "minuman", cek apakah $kategori bukan "makanan" dan "minuman"? (karena selain itu tidak boleh)
    if ($kategori !== "makanan" && $kategori !== "minuman") {
        echo "<script>
            alert('Mohon masukkan data KATEGORI dengan (makanan) atau (minuman)');
        </script>";
        return false;
    }

    //saat tag <input> diketikkan dan masuk lewat metode POST, itu entuknya string walaupun diisinya angka
    //kita harus cek bentuk string tersebut apakah mengandung angka? pakai regex : terdiri dari delimiter, pattern, dan optional modifier
    //dalam regex tanda "/" adalah delimiter, kalo pattern-nya kemungkinan ada tanda "/" maka delimiter-nya disarankan pakai "~" atau "#"
    //tanda "+" itu adalah quantifiers, "+" artinya mencocokan string apapun yang mengandung setidaknya 1 dari pattern
    //yang syarat kedua if di bawah setelah "atau" untuk cek agar tidak ada simbol yang dimasukkan
    if (preg_match("~[0-9]+~", $namaMenu) || preg_match("/[^\p{L}\p{N}\s]/u", $namaMenu)) {
        echo "<script>
            alert('Mohon masukkan data NAMA MENU dengan HURUF SAJA');
        </script>";
        return false;
    }

    //cek input harga bentuk datanya harus integer dan minimal 1000
    if (!is_numeric($harga) || $harga < 1000) {
        echo "<script>
            alert('Mohon masukkan data HARGA dengan ANGKA dan MINIMAL 1000');
        </script>";
        return false;
    }

    //cek validasi input info menu
    if ($info != Strip_tags($info)) {
        echo "<script>
            alert('Mohon untuk TIDAK MEMASUKKAN CODE sebagain info menu.');
        </script>";
        return false;
    }

    //Cek apakah user pilih upload gambar baru atau tidak ?
     if ($_FILES["gambar"]["error"] === 4) {
        $gambar = $gambarlama;
    } else {
        $gambar = upload();
    }

    //query untuk update di database
    $queryinsert = "UPDATE menu SET kategori = '$kategori', nama_menu = '$namaMenu', harga = $harga, info = '$info', gambar = '$gambar' WHERE id_menu = $idMenu";
    mysqli_query($db, $queryinsert);

    return mysqli_affected_rows($db);
}


//Bagian Function Aksi Ubah Status Menu
//untuk ubah status menu, $idMenu didapat dari metode GET menuPenjual.php
function ubahStatusMenu($idMenu) {
    global $db;

    //RULES :
    //kalo sedang aktif maka nonaktif-kan, kalo sedang nonaktif maka aktif-kan
    //mirip seperti toggle, tapi untuk ubah isi database

    //query INSERT dari database untuk mendapat status menu saat ini
    $menu = mysqli_query($db, "SELECT * FROM menu WHERE id_menu = $idMenu");
    $dataMenu = mysqli_fetch_assoc($menu);
    $statusMenu = $dataMenu["status_menu"];

    //cek status menu untuk menjalankan query UPDATE dalam mengubah aktivasi
    if ($statusMenu == "aktif") {
        mysqli_query($db, "UPDATE menu SET status_menu = 'nonaktif' WHERE id_menu = $idMenu");
    } else {
        mysqli_query($db, "UPDATE menu SET status_menu = 'aktif' WHERE id_menu = $idMenu");
    }

    //setelah itu redirect kembali 
    echo "<script> 
            alert('Status aktivasi menu SUDAH diubah.'); 
            document.location.href='menuPenjual.php';
        </script>";
} //Kemungkinan function ini akan dihapus


//Bagian Function Aksi Ubah Stock Menu
//Untuk Ubah Stock Menu
function ubahStockMenu($idMenu, $editStock) {
    global $db;

    if ($editStock < 0 || $editStock % 1 !== 0) {
        echo "<script>
                alert('Stock menu gagal terupdate.Tolong masukkan angka bulat dan tidak lebih kecil dari 0');
                document.location.href='menuPenjual.php';
            </script>";
    } else {
        $queryUpdateStock = "UPDATE menu SET stock = $editStock WHERE id_menu = $idMenu";
        mysqli_query($db, $queryUpdateStock);
        
        echo "<script>
                alert('Stock menu sudah terupdate.');
                document.location.href='menuPenjual.php';
            </script>";
    }
}


//Bagian Function Handle Upload Gambar
//akan dijalankan ketika proses function tambahMenu() untuk $gambar
function upload() {
    //nama akan sudah beserta ekstensi dari file
    $namafile = $_FILES["gambar"]["name"];
    $ukuranfile = $_FILES["gambar"]["size"];
    $errorupload = $_FILES["gambar"]["error"];
    $tempatsimpan = $_FILES["gambar"]["tmp_name"];

    //cek apakah tidak ada gambar di upload?
    if ($errorupload === 4) {
        echo "<script> alert('pilih gambar terlebih dahulu!'); </script>";
        return false;
    }

    //cek ekstensi file apakah benar gambar? (untuk keamanan), bisa jadi user malah upload file lain
    $ekstensiboleh = ["jpg", "jpeg", "png"];
    //fungsi "explode(delimiter, string)" memecah data string menjadi array dipisahkan delimiter (yang jadi delimiter hilang)
    $ekstensigambar = explode(".", $namafile);
    //mengambil array terakhir dengan fungsi "end()" dan membuatnya menjadi huruf kecil semua dengan fungsi strtolower()"
    //karena bisa aja nama file-nya "fajar.uhuy.JPG" maka data di contoh ini akan diubah jadi ["fajar", "uhuy", "JPG"] dan yang kita inginkan adalah ekstensi file-nya (yang terakhir)
    $ekstensigambar = strtolower(end($ekstensigambar));
    //fungsi "in_array(needle, haystack)" untuk mencari dan mengecek eleman dalam array (ada ga sebuah string di dalam sebuah array), kalo ada hasilnya true, kalo gaada hasilnya false
    //btw needle itu artinya jarum dan haystack artinya jerami, jadi konsepnya seperti mencari jarum dalam jerami
    if (!in_array($ekstensigambar, $ekstensiboleh)) {
        echo "<script> alert('tolong upload gambar, bukan yang lain!'); </script>";
        return false;
    }

    //cek jika ukuran terlalu besar, angka dalam byte jadi 1 Mb (misal aturannya gambar ga boleh sampai 1 Mb)
    if ($ukuranfile > 1000000) {
        echo "<script> alert('ukuran gambar terlalu besar!'); </script>";
        return false;
    }

    //lolos pengecekan, maka file gambar siap diupload
    //generate nama file baru supaya nama file tidak sama, karena kalo nama file sama akan tertimpa file yang baru
    //ingat, fungsi titik "." itu untuk menggabungkan string
    $namafilebaru = uniqid();
    //sambungkan kode unik tadi dengan nama ekstensi file-nya
    $namafilebaru .= "." ;
    $namafilebaru .= $ekstensigambar;

    //fungsi "move_uploaded_file(asal_file_name, destination)" memindahkan file komputer ke dalam server, 
    //destination itu relatif terhadap file ini (folder yang sama dengan file ini)
    move_uploaded_file($tempatsimpan, 'img/' . $namafilebaru);

    //file sudah terupload dan disimpan directory, maka kembalikan nama file-nya untuk nanti diupload ke database
    return $namafilebaru;
}


//Bagian Function Ubah Profile Penjual
//$dataubah berasal dari $_POST halaman riwayatPenjual(ubah).php
function ubahProfile($dataubah) {
    global $db;

    $idPenjual = $dataubah["idPenjual"];
    $email = mysqli_real_escape_string($db, stripslashes(strip_tags(($dataubah["email"]))));
    $noTelpon = htmlspecialchars($dataubah["noTelpon"]);
    //fungsi "mysqli_real_escape_string(koneksi_database, datanya)" untuk boleh memasukkan tanda kutip di dalamnya dan bisa dimasukin ke database secara aman
    $passwordLama = mysqli_real_escape_string($db, $dataubah["passwordLama"]);
    $password1 = mysqli_real_escape_string($db, $dataubah["password1"]);
    $password2 = mysqli_real_escape_string($db, $dataubah["password2"]);

    //cek email sudah ada atau belum? (Untuk Pembeli)
    //ambil email yang sama jika ada
    $milikPembeliLain = mysqli_query($db, "SELECT email_pembeli FROM user_pembeli WHERE email_pembeli = '$email'");
    //baru tes apakah data terisi? kalo iya berarti ada username yang sama (karena datanya keambli)
    //cek apakai ada data lebih dari 1 yang terambil?
    if (mysqli_fetch_assoc($milikPembeliLain)) {
        echo "<script>
            alert('Username Atau Email Sudah Terdaftar, PAKAI YANG LAIN');
        </script>"; 
        return false;
    }
    //cek username atau email sudah ada atau belum? (Untuk Penjual)
    //dibawah ini kita logikanya ambil data berdasarkan email tapi yang id-nya bukan miliknya, jadi cek untuk data orang lain (selain diri sendiri)
    $milikPenjualLain = mysqli_query($db, "SELECT email_penjual FROM user_penjual WHERE email_penjual = '$email' AND id_penjual NOT IN ($idPenjual)");
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

    //cek apakah dia ganti password jadi password baru? dicek apa dia isi form untuk ganti password?
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
    $result = mysqli_query($db, "SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
    $row = mysqli_fetch_assoc($result);
    if (password_verify($passwordLama, $row["password_penjual"])) {
        //password lama yang dimasukkan sesuai, baru boleh update database
        $queryinsert = "UPDATE user_penjual SET email_penjual= '$email', no_telpon_penjual = '$noTelpon', password_penjual = '$password' WHERE id_penjual = $idPenjual";
        mysqli_query($db, $queryinsert);
        return mysqli_affected_rows($db);
    }
    echo "<script>
        alert('Mohon Masukkan Password Lama Yang SESUAI');
        </script>";
        
    return false;
}


//Bagian Function Gagalkan Pesanan Metode Cash
//yaitu memasukkan ke table "riwayat" dengan status gagal bersama membawa teks alasan kegagalan pesanan
function gagalkanPesanan($idGagalkan, $isiPesanGagal) {
    global $db;
    
    //kumpulin info terlebih dahulu
    $dataPesanan = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM pesanan WHERE id_pesanan = $idGagalkan"));
    $idPembeli = $dataPesanan["id_pembeli"];
    $idPenjual = $dataPesanan["id_penjual"];
    $menuDipesan = $dataPesanan["menu_dipesan"];
    $jumlahPesanan = $dataPesanan["jumlah_pesanan"];
    $jumlahTransaksi = $dataPesanan["jumlah_transaksi"];

    //validasi input pesan gagal
    $isiPesanGagal = mysqli_real_escape_string($db, $isiPesanGagal);
    if ($isiPesanGagal !== strip_tags($isiPesanGagal)) {
        echo "<script>
            alert('Dilarang memasukkan code pada pesan.');
        </script>";
        return false;
    }

    //query hapus dari table pesanan dan masukkan ke table riwayat (yang query masukan ke table riwayat dibuat variable)
    mysqli_query($db, "DELETE FROM pesanan WHERE id_pesanan = $idGagalkan");
    $masukkanRiwayat = mysqli_query($db, "INSERT INTO riwayat VALUES('', NOW(), $idPembeli, $idPenjual, '$menuDipesan', '$jumlahPesanan', $jumlahTransaksi, 'gagal', '$isiPesanGagal')");

    return $masukkanRiwayat;
}


//Bagian Function Selesaikan Pesanan Metode Cash
//yaitu memasukkan ke table "riwayat" dengan status berhasil
function selesaikanPesanan($idSelesaikan) {
    global $db;
    
    //kumpulin informasi pesanan terlebih dahulu
    $dataPesanan = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM pesanan WHERE id_pesanan = $idSelesaikan"));
    $idPembeli = $dataPesanan["id_pembeli"];
    $idPenjual = $dataPesanan["id_penjual"];
    $menuDipesan = $dataPesanan["menu_dipesan"];
    $jumlahPesanan = $dataPesanan["jumlah_pesanan"];
    $jumlahTransaksi = $dataPesanan["jumlah_transaksi"];

    //query hapus dari table pesanan dan masukkan ke table riwayat (yang query masukan ke table riwayat dibuat variable)
    mysqli_query($db, "DELETE FROM pesanan WHERE id_pesanan = $idSelesaikan");
    $masukkanRiwayat = mysqli_query($db, "INSERT INTO riwayat VALUES('', NOW(), $idPembeli, $idPenjual, '$menuDipesan', '$jumlahPesanan', $jumlahTransaksi, 'berhasil', '')");

    return $masukkanRiwayat;
}


//Bagian Function Membuat Request Mencairkan
//yaitu memasukkan ke table "request_cair" setelah aksi ingin mencairkan saldo
function masukkanRequestCair($idPenjual, $usernamePenjual, $emailPenjual, $nominalRequest) {
    global $db;

    //ambil info saldo lama penjual dan hitung untuk saldo baru
    $dataPenjual = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM user_penjual WHERE id_penjual = $idPenjual"));
    $saldoLama = $dataPenjual["saldo_penjual"];
    $saldoBaru = $saldoLama - $nominalRequest;

    //query untuk update saldo penjual di user_penjual dan memasukkan ke table request_cair
    mysqli_query($db, "UPDATE user_penjual SET saldo_penjual = $saldoBaru WHERE id_penjual = $idPenjual");
    mysqli_query($db, "INSERT INTO request_cair VALUES ('', $idPenjual, '$usernamePenjual', '$emailPenjual', $nominalRequest)");

    return mysqli_affected_rows($db);
}


//Bagian Function Selesaikan Request Mencairkan
//yaitu hapus data dari table "request_cair" setelah aksi selesaikan request mencairkan (penjual sudah menerima uang)
function selesaiRequestCair($dataPOST) {
    global $db;

    //ambil info idCair dari data POST untuk kolom id_cair di table request_cair
    $idCair = $dataPOST["idCair"];

    //query untuk hapus data dari table request_cair
    mysqli_query($db, "DELETE FROM request_cair WHERE id_cair = $idCair");

    return mysqli_affected_rows($db);
}


//Bagian Function Batalkan Request Mencairkan
//yaitu hapus data dari table "request_cair" setelah aksi batalkan request mencairkan (penjual tidak menerima uangnya)
function batalRequestCair($dataPOST) {
    global $db;

    //ambil info idCair dari data POST, ambil info nominal request, ambil info idPenjual bersangkutan, ambil info saldo lama penjual, hitung saldo baru
    $idCair = $dataPOST["idCair"];
    $dataRequestCair = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM request_cair WHERE id_cair = $idCair"));
    $nominalRequest = $dataRequestCair["nominal_request"];
    $idPenjual = $dataRequestCair["id_penjual"];
    $dataPenjual = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM user_penjual WHERE id_penjual = $idPenjual"));
    $saldoLama = $dataPenjual["saldo_penjual"];
    $saldoBaru = $saldoLama + $nominalRequest;

    //query untuk update saldo penjual di user_penjual dan hapus data dari table request_cair
    mysqli_query($db, "UPDATE user_penjual SET saldo_penjual = $saldoBaru WHERE id_penjual = $idPenjual");
    mysqli_query($db, "DELETE FROM request_cair WHERE id_cair = $idCair");

    return mysqli_affected_rows($db);
}
?>