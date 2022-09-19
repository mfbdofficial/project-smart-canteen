<?php 
//FUNCTION HANDLE LOGIN & REGISTRASI


//Koneksi Dengan Database "preorder_smart_canteen"
$db = mysqli_connect("localhost", "root", "", "preorder_smart_canteen");


//Buat Function Query Untuk Ambil Data
function queryData($queryUtama) {
    //ambil variable bernama "db" di luar function yang isinya koneksi database
    global $db; 
    //lakukan query pada database yang sudah terkoneksi dengan input-nya itu query di function ini 
    $result = mysqli_query($db, $queryUtama);
    //buat atau siapkan array kosong untuk menampung data (bisa saja data user_pembeli, user_penjual, riwayat)
    $instances = [];
    //tarik semua data yang sudah diquery, while akan berjalan terus selama data masih bisa ditarik (artinya true)
    while ($instance = mysqli_fetch_assoc($result)) {
        //masukkan data yang sudah ditarik ke dalam array yang sudah disiapkan 
        $instances[] = $instance;
    }
    //kembalikan array yang sudah diisi (isi data table dari database yang sudah ditarik)
    return $instances;
}
//function di atas akan kembalikan array dengan bentuk data yang sudah diquery (keseluruhan), lalu difetch semuanya, dan menjadi bentuk array


//Function Handle User Registrasi
//buat function "registrasi()" untuk user yang daftar (database bertambah)
function registrasi($dataregis) {
    global $db;

    //fungsi "stripslashes(datanya)" supaya hilangkan tanda backslash di dalamnya
    $username = mysqli_real_escape_string($db, strip_tags(stripslashes($dataregis["username"]))); //pakai strip_tags() akan hapus semua tag html
    $email = mysqli_real_escape_string($db, stripslashes(strip_tags($dataregis["email"]))); //pakai stripslashes() akan hapus tanda strip ke kanan "\"
    //fungsi "mysqli_real_escape_string(koneksi_database, datanya)" untuk boleh memasukkan tanda kutip di dalamnya dan bisa dimasukin ke database secara aman
    $password1 = mysqli_real_escape_string($db, $dataregis["password1"]);
    $password2 = mysqli_real_escape_string($db, $dataregis["password2"]);
    $noTelpon = $dataregis["noTelpon"];

    //cek username bukan yang milik admin
    if ($username == "LordAdmin123") {
        echo "<script>
            alert('Username Sudah Terdaftar, PAKAI YANG LAIN');
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

    //cek username atau email sudah ada atau belum? (Untuk Pembeli)
    //ambil dulu username atau email yang sama jika ada
    $milikPembeliLain = mysqli_query($db, "SELECT username_pembeli, email_pembeli FROM user_pembeli WHERE username_pembeli = '$username' OR email_pembeli = '$email'");
    //baru tes apakah data terisi? kalo iya berarti ada username yang sama (karena datanya keambli)
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

    //cek apakah ada code di dalam password? 
    if ($password1 !== strip_tags($password1)) {
        echo "<script>
            alert('Dilarang memasukkan code sebagai password');
        </script>";
        return false;
    }
    //cek konfirmasi $password2 sama gak seperti $password1?
    if ($password1 !== $password2) {
        echo "<script>
            alert('Konfirmasi Password TIDAK SESUAI');
        </script>";
        return false;
    }
    //enkripsi password yang sudah di cek sama
    //Kalo pakai fungsi "md5(yang_diacak)" itu sudah tidak aman lagi
    //Pakai fungsi "password_hash(yang_diacak, algoritmanya)"
    //PASSWORD_DEFAULT itu algoritma pengacakan yang dipilihin PHP (akan terus berubah kalo ada cara pengamanan baru)
    $password = password_hash($password1, PASSWORD_DEFAULT);

    //cek nomor telpon, gaboleh masukin selain angka dan batas angka-nya itu 12
    //pakai function "is_numeric()" akan balikin true kalo variable-nya adalah numeric atau numeric string kalo engga akan balikin false
    //function "strlen()" untuk cek panjang suatu string
    if (!is_numeric($noTelpon) || strlen($noTelpon) > 12) {
        echo "<script>
            alert('Mohon Masukkan Nomor Telpon Yang SESUAI');
        </script>";
        return false;
    }

    //Cek apakah dia kategori penjual? dan Data registnya sudah benar "ALPHA321" (ini sebenarnya nanti bebas)
    if (isset($dataregis["akunJual"])) {
        //lalu cek apakah kode registrasinya benar ?
        if ($dataregis["kodeRegis"] === "ALPHA321") {
            //untuk debug
            /*
            echo "<script>
                alert('Kamu Penjual Yang Sah!!!');
            </script>";
            return true;
            */
            //masukkan data registrasi ke database (Khusus Penjual)
            mysqli_query($db, "INSERT INTO user_penjual VALUES('', '$username', '$email',  '$noTelpon', '', '$password')");
            return mysqli_affected_rows($db);
        }
        echo "<script>
            alert('Kode Registrasi SALAH, tanyakan pada admin');
        </script>";
        return false;
    } else {
        if ($dataregis["kodeRegis"] !== "") {
            echo "<script>
                alert('Kalo bukan kategori penjual, tidak perlu memasukkan kode registrasi.');
            </script>";
        return false;
        }
    }

    //untuk debug
    /*
    echo "<script>
        alert('Kamu Kategori Pembeli!!!');
    </script>";
    return true;
    */

    //masukkan data registrasi ke database (Khusus Pembeli)
    mysqli_query($db, "INSERT INTO user_pembeli VALUES('', '$username', '$email',  '$noTelpon', '', '$password')");
    
    //mysqli_affected_rows($db) hasilin 1 kalo query berhasil, -1 jika gagal
    return mysqli_affected_rows($db);
}
?>