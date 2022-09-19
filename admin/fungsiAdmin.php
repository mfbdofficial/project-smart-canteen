<?php 
//FUNCTION HANDLE ADMIN


//Bagian Koneksi Dengan Database "preorder_smart_canteen"
$db = mysqli_connect("localhost", "root", "", "preorder_smart_canteen");


//Bagian Function Query Untuk Ambil Data
function queryData($queryAdmin) {
    //ambil variable bernama "db" di luar function yang isinya koneksi database
    global $db; 

    //lakukan query pada database yang sudah terkoneksi dengan input-nya itu query di function ini 
    $result = mysqli_query($db, $queryAdmin);
    
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


//Bagian Function Hapus User
//$username2 adalah id user yang ditangkap metode request GET di halaman userAdmin.php
function hapusUser($username2, $id2) {
    global $db;

    //cek dulu apakah yang dihapus itu user pembeli?
    $result1 = mysqli_query($db, "SELECT * FROM user_pembeli WHERE username_pembeli = '$username2'");
    var_dump($result1);
    if (mysqli_num_rows($result1) === 1) {
        //SAMPE SINI DATA SUDAH TERBAWA!!!
        //hapus semua yang berhubungan di table riwayat
        mysqli_query($db, "DELETE FROM riwayat WHERE id_pembeli = '$id2'");
        //hapus semua yang berhubungan di table pesanan
        mysqli_query($db, "DELETE FROM pesanan WHERE id_pembeli = '$id2'");
        //hapus semua yang berhubungan di table request_saldo
        mysqli_query($db, "DELETE FROM request_saldo WHERE id_pembeli = '$id2'");
        //maka hapus di table user_pembeli
        $statusHapus = mysqli_query($db, "DELETE FROM user_pembeli WHERE username_pembeli = '$username2'");
    }

    //cek dulu apakah yang dihapus itu user penjual?
    $result2 = mysqli_query($db, "SELECT * FROM user_penjual WHERE username_penjual = '$username2'");
    if (mysqli_num_rows($result2) === 1) {
        //hapus semua yang berhubungan di table riwayat
        mysqli_query($db, "DELETE FROM riwayat WHERE id_penjual = '$id2'");
        //hapus semua yang berhubungan di table pesanan
        mysqli_query($db, "DELETE FROM pesanan WHERE id_penjual = '$id2'");
        //hapus semua yang berhubungan di table request_cair
        mysqli_query($db, "DELETE FROM request_cair WHERE id_penjual = '$id2'");
        //hapus semua yang berhubungan di table menu
        mysqli_query($db, "DELETE FROM menu WHERE id_penjual = '$id2'"); //menu ditaruh bawah karena kemungkinan ada jadi FOREIGN KEY di table "pesanan" dan "riwayat"
        //maka hapus di table user_penjual
        $statusHapus = mysqli_query($db, "DELETE FROM user_penjual WHERE username_penjual = '$username2'");
    }

    //NOTE : karena ada foreign key, maka harus hapus semua data terkait terlebih dahulu!!!
    return $statusHapus;
}


//Bagian FUnction Batalkan Request Isi Saldo
function requestBatal($idSaldo) {
    global $db;

    //query untuk hapus request saldo
    mysqli_query($db, "DELETE FROM request_saldo WHERE id_saldo = $idSaldo");
}
    

//Bagian Function Selesaikan Request Saldo (Menambah Saldo User)
function requestSelesai($idSaldo, $usernamePembeli) {
    global $db;

    //ambil data saldo lama user
    $pembeli = mysqli_query($db, "SELECT * FROM user_pembeli WHERE username_pembeli = '$usernamePembeli'");
    $pembeliArray = mysqli_fetch_assoc($pembeli);
    $saldoPembeli = $pembeliArray["saldo_pembeli"];

    //ambil data saldo request
    $request = mysqli_query($db, "SELECT * FROM request_saldo WHERE id_saldo = $idSaldo");
    $requestArray = mysqli_fetch_assoc($request);
    $saldoRequest = $requestArray["nominal_request"];
    var_dump($saldoPembeli);
    var_dump($saldoRequest);

    //perhitungan
    $saldoBaru = $saldoPembeli + $saldoRequest;
    //query update saldo user_pembeli
    mysqli_query($db, "UPDATE user_pembeli SET saldo_pembeli = $saldoBaru WHERE username_pembeli = '$usernamePembeli'");
    //query untuk hapus request saldo
    mysqli_query($db, "DELETE FROM request_saldo WHERE id_saldo = $idSaldo");
}
?>