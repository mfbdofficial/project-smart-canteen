<?php
//SEBAGAI HALAMAN REGISTRASI


//Bagian Koneksi Module
require "fungsismartcanteen.php"; //koneksi database dan user defined function


//Bagian Aksi Registrasi (Tekan Tombol)
//cek apakah tombol registrasi sudah ditekan? (cek variable $_POST["registrasi"] sudah dibuat?) :
if (isset($_POST["registrasi"])) {
    //cek apakah proses query tidak ada masalah? dari fungsi registrasi() yang dibuat
    if (registrasi($_POST) > 0) {
        echo "<script>
            alert('User Baru BERHASIL Ditambahkan');
        </script>";
    } else {
        echo "<script>
            alert('User Baru GAGAL Ditambahkan');
        </script>";
        $gagalregis = true;
        echo mysqli_error($db);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Smart Canteen</title>
    <link rel="stylesheet" href="registrasismartcanteen.css">   
</head>
<body>
    <!--Bagian Gambar & Sambutan-->
    <section class="sambutan">
        <img src="LogoCanteen2.png">
        <p>Silahkan Registrasi Smart Canteen</p>    
    </section>        


    <!--Bagian Aksi Registrasi & Kembali Ke Halaman Login-->
    <section class="registrasi">
        <h1>Registrasi Smart Canteen</h1>

        <div class="form">
            <form action="" method="post">
                <div class="form-atas">
                    <table>
                        <input type="checkbox" name="akunJual" id="akunJual">
                        <label for="remember">Buat akun kategori penjual?</label>
                        <p> Kalau bukan, di atas kosongkan saja. </p>
                        <tr>
                            <td><label for="username">Username :</label></td>
                            <td><input type="text" name="username" id="username" pattern="[a-zA-Z0-9]{6,}" required oninvalid="this.setCustomValidity('harus minimal 6 hanya huruf dan angka')" oninput="this.setCustomValidity('')"></td>
                        </tr>
                        <tr>
                            <td><label for="email">Email :</label></td>
                            <td><input type="email" name="email" id="email" required oninvalid="this.setCustomValidity('tolong masukkan email yang valid mengandung @')" oninput="this.setCustomValidity('')"></td>
                        </tr>
                        <tr>
                            <td><label for="password1">Password :</label></td>
                            <td><input type="password" name="password1" id="password1" required></td>
                        </tr>
                        <tr>
                            <td><label for="password2">Konfirmasi :</label></td>
                            <td><input type="password" name="password2" id="password2" required></td>
                        </tr>
                        <tr>
                            <td><label for="noTelpon">No Telepon :</label></td>
                            <td><input type="tel" name="noTelpon" id="noTelpon" pattern="(628|08)[0-9]{10}" required oninvalid="this.setCustomValidity('tolong masukkan nomor yang valid diawali 628 atau 08')" oninput="this.setCustomValidity('')"></td>
                        </tr>
                        <tr>
                            <td><label for="kodeRegis">Kode Registrasi :</label></td>
                            <td><input type="text" name="kodeRegis" id="kodeRegis" placeholder="khusus kategori penjual"></td>
                        </tr>
                    </table>
                </div>
                <!--peringatan error registrasi-->
                <?php if (isset($gagalregis)) : ?>
                            <p class="error-registrasi" style="color: red; font-style: italic;">Mohon isi form dengan data yang valid</p>
                <?php endif; ?> 
                <button type="submit" name="registrasi">Registrasi</button>  
            </form>
        </div>

        <div class="balik-login">
            <a href="index.php">Kembali ke halaman login.</a>
        </div> 
    </section>
</body>
</html>