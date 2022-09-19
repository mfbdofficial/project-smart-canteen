<?php
//SEBAGAI HALAMAN PEMBELI (HANDLE AKSI EDIT PROFILE PEMBELI)


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


//Bagian Mengambil Data Untuk Digunakan
$iniPembeli = queryData("SELECT * FROM user_pembeli WHERE id_pembeli = $idPembeli");


//Bagian Handle Aksi Edit Profile Pembeli
//cek apakah sudah menekan? :
if (isset($_POST["tekan"])) {
    //pakai function ubahMenu() yang sudah dibuat di fungsiPenjual.php
    //cek apakah hasil yang dikembalikan function ubahMenu() lebih dari 0 (setidaknya minimal 1 baris terpenuhi) :
    if (ubahProfile($_POST) > 0) {
        //misal notif pakai fungsi javascript "alert" untuk munculin kotak pesan (pop-up)
        //fungsi "document.location.href='halaman_dituju'" adalah fungsi redirect javascript
        echo "<script>
            alert('Edit profile BERHASIL dilakukan.');
            document.location.href='akunPembeli.php';
        </script>";
    } else {
        echo "<script>
            alert('Edit profile GAGAL dilakukan.');
            document.location.href='akunPembeli.php';
        </script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile Pembeli</title>
    <style>
        body {
            background: url('../admin/background-makanan-admin.jpg');
        }
        

        .aksi-edit-profile li {
            padding: 0.1rem;
        }


        body a {
            display: block;
            width: 205px;
            margin-top: -21px;
            margin-left: 3px;
            background-color: black;
            padding: 3px;
            text-decoration: none;
            color: #FFD767;
            border-radius: 5px;
        }



        /*RESPONSIVE BREAKPOINT*/
        @media screen and (max-width: 600px) {
            .form label, .form input {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!--Bagian Tulisan Edit My Profile-->
    <h1>Edit My Profile</h1>
    

    <!--Bagian Tombol Kembali (Tidak Jadi Edit Profile)-->
    <!--asalkan ga pencet tombol ubah, maka data yang akan ada disitu saja, tidak disimpan dan diproses-->
    <a href="akunPembeli.php">Kembali (tidak jadi edit profile)</a>


    <!--Bagian Form Aksi Edit Profile Pembeli-->
    <!--pakai metode request POST-->
    <section class="aksi-edit-profile">
        <form class="form" actions="" method="post" enctype="multipart/form-data">
            <!--pakai form tidak terlihat untuk tetap mengirim data, jadi id tetap dikirim ulang-->
            <input type="hidden" name="idPembeli" value="<?php echo $iniPembeli[0]["id_pembeli"]; ?>">
            <ul>
                <!--pakai "required" untuk input agar data harus diisi-->
                <!--pakai "values" agar data sudah ada di form, nampilin (tertulis) data sebelumnya-->
                <li>
                    <label for="username">Username :</label>
                    <input type="text" name="username" id="username" pattern="[a-zA-Z0-9]{6,}" required oninvalid="this.setCustomValidity('harus minimal 6 hanya huruf dan angka')" oninput="this.setCustomValidity('')" value="<?php echo $iniPembeli[0]["username_pembeli"]; ?>" >
                </li>
                <li>
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" required oninvalid="this.setCustomValidity('tolong masukkan email yang valid mengandung @')" oninput="this.setCustomValidity('')" value="<?php echo $iniPembeli[0]["email_pembeli"]; ?>" >
                </li>
                <li>
                    <label for="noTelpon">Nomor Telpon :</label>
                    <input type="tel" name="noTelpon" id="noTelpon" pattern="(628|08)[0-9]{10}" required oninvalid="this.setCustomValidity('tolong masukkan nomor yang valid diawali 628 atau 08')" oninput="this.setCustomValidity('')" value="<?php echo $iniPembeli[0]["no_telpon_pembeli"]; ?>" >
                </li>
                <li>
                    <label for="passwordLama">Masukkan Password Lama :</label>
                    <input type="password" name="passwordLama" id="passwordLama">
                    <p>Di bawah tidak perlu diisi jika tidak ingin ganti password</p>
                </li>
                <li>
                    <label for="password1">Password Baru :</label>
                    <input type="password" name="password1" id="password1">
                </li>
                <li>
                    <label for="password2">Konfirmasi Password :</label>
                    <input type="password" name="password2" id="password2">
                </li>
                <br>
                <li>
                    <button type="submit" name="tekan">Ubah Profile</button>
                </li>
            </ul>
        </form>   
    </section>
</body>
</html>
