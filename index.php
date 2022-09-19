<?php
//SEBAGAI HALAMAN LOGIN 


//Bagian Memulai Session & Koneksi Module
session_start(); //memulai session
require "fungsismartcanteen.php"; //koneksi database dan user defined function


//Bagian Cookie (Paling Pertama Dicek)
//cek dulu apakah ada cookie?
if (isset($_COOKIE["id_user"]) && isset($_COOKIE["username"])) {
    $id_user = $_COOKIE["id_user"];
    $username = $_COOKIE["username"];

    //kalo cookie ternyata ada maka kita bandingin bagian username pakai id yang sesuai di database (KHUSUS PEMBELI)
    //query untuk SQL tidak bisa membaca variable array, query di bawah bagian WHERE statement ga pake tanda kutip karena $id_user itu tipe data integer
    $result1 = mysqli_query($db, "SELECT * FROM user_pembeli WHERE id_pembeli = $id_user");
    $row1 = mysqli_fetch_assoc($result1);
    //cek username dari cookie dan username yang dari database
    if ($username === hash("sha256", $row1["username_pembeli"])) {
        $_SESSION["loginPembeli"] = true;
        $_SESSION["username"] = $row1["username_pembeli"];
        $_SESSION["idPembeli"] = $row1["id_pembeli"];
    }

    //kalo cookie ternyata ada maka kita bandingin bagian username pakai id yang sesuai di database (KHUSUS PENJUAL)
    //query untuk SQL tidak bisa membaca variable array, query di bawah bagian WHERE statement ga pake tanda kutip karena $id_user itu tipe data integer
    $result2 = mysqli_query($db, "SELECT * FROM user_penjual WHERE id_penjual = $id_user");
    $row2 = mysqli_fetch_assoc($result2);
    //cek username dari cookie dan username yang dari database
    if ($username === hash("sha256", $row2["username_penjual"])) {
        $_SESSION["loginPenjual"] = true;
        $_SESSION["username"] = $row2["username_penjual"];
        $_SESSION["idPenjual"] = $row2["id_penjual"];
    }
}


//Bagian Cek Jika Sudah Login
//untuk cek apakah sudah login (sudah ada session)? cek untuk ada 3 bagian
if (isset($_SESSION["loginAdmin"])) {
    header("Location: admin/saldoAdmin.php");
    exit;
}
if (isset($_SESSION["loginPembeli"]) && isset($_SESSION["username"]) && isset($_SESSION["idPembeli"])) {
    header("Location: pembeli/homePembeli.php");
    exit;
}
if (isset($_SESSION["loginPenjual"]) && isset($_SESSION["username"]) && isset($_SESSION["idPenjual"])) {
    header("Location: penjual/menuPenjual.php");
    exit;
}


//Bagian Aksi Login (Tekan Tombol)
//cek apakah tombol sign in sudah ditekan?
if (isset($_POST["login"])) {
    //pindahin data dari form ke variable lain (data sebenarnya sudah ada di $_POST, ini supaya lebih mudah saja)
    $username = mysqli_real_escape_string($db, strip_tags($_POST["username"]));
    $password = mysqli_real_escape_string($db, $_POST["password"]);

    //cek validasi form input password agar aman 
    if ($password !== strip_tags($password)) {
        echo "<script>
            alert('Dilarang memasukkan code pada form');
        </script>";
    }

    //cek apakah dia admin? (KHUSUS ADMIN)
    if ($username == "LordAdmin123" & $password == "JosephJoestar") {
        $_SESSION["loginAdmin"] = true;
        header("Location: admin/saldoAdmin.php");
    }

    //cek apakah username yang diinput di form ada di database?
    //pertama query dulu data yang sesuai dengan input username (KHUSUS PEMBELI)
    $result1 = mysqli_query($db, "SELECT * FROM user_pembeli WHERE username_pembeli = '$username'");
    //pakai fungsi "mysqli_num_rows(variable_hasil_query)" untuk hitung berapa baris yang dikembalikan dari suatu query. 1 kalo ada, 0 kalo gak ada
    if (mysqli_num_rows($result1) === 1) {
        //nah kalo ada maka cek password
        $row = mysqli_fetch_assoc($result1); //username yang ada 1 itu sudah diambil, ditarik datanya dan jadi array 
        //fungsi "password_verify(string_dimasukkan, bentuk_hash_nya)" untuk cek apakah sebuah string sama dengan hasil hash-nya?
        if (password_verify($password, $row["password_pembeli"])) {
            //set session untuk dicek di tiap - tiap halaman
            $_SESSION["loginPembeli"] = true;
            $_SESSION["username"] = $row["username_pembeli"];
            $_SESSION["idPembeli"] = $row["id_pembeli"];
            //MULAI BAGIAN COOKIE (Fasilitas Remember Me)
            //cek remember me apakah dicentang? (bila ingin pakai cookie)
            if (isset($_POST["remember"])) {
                //buat cookie (tapi nama cookie dibawah sebaiknya diubah saja yang lain jangan "id_user" dan "username" supaya ga susah ditebak)
                //fungsi "hash('nama_algoritma', 'isi_yang_diacak')" berguna untuk mengenkripsi 
                //sha256 merupakan salah satu algoritma hash, fungsi "hash('algoritma', 'data')
                setcookie("id_user", $row["id_pembeli"],  time() + 600); //600 detik = 10 menit
                setcookie("username", hash("sha256", $row["username_pembeli"]), time() + 600);
            }
            //kalo password dimasukkan cocok dengan hash-nya maka di-redirect
            header("Location: pembeli/homePembeli.php");
            exit;
        }
    }    
    
    //cek apakah username yang diinput di form ada di database? (KHUSUS PENJUAL)
    $result2 = mysqli_query($db, "SELECT * FROM user_penjual WHERE username_penjual = '$username'");
    //pakai fungsi "mysqli_num_rows(variable_hasil_query)" untuk hitung berapa baris yang dikembalikan dari suatu query. 1 kalo ada, 0 kalo gak ada
    if (mysqli_num_rows($result2) === 1) {
        //nah kalo ada maka cek password
        $row = mysqli_fetch_assoc($result2); //username yang ada 1 itu sudah diambil, ditarik datanya dan jadi array 
        //fungsi "password_verify(string_dimasukkan, bentuk_hash_nya)" untuk cek apakah sebuah string sama dengan hasil hash-nya?
        if (password_verify($password, $row["password_penjual"])) {
            //set session untuk dicek di tiap - tiap halaman
            $_SESSION["loginPenjual"] = true;
            $_SESSION["username"] = $row["username_penjual"];
            $_SESSION["idPenjual"] = $row["id_penjual"];
            //MULAI BAGIAN COOKIE (Fasilitas Remember Me)
            //cek remember me apakah dicentang? (bila ingin pakai cookie)
            if (isset($_POST["remember"])) {
                //buat cookie (tapi nama cookie dibawah sebaiknya diubah saja yang lain jangan "id_user" dan "username" supaya ga susah ditebak)
                //fungsi "hash('nama_algoritma', 'isi_yang_diacak')" berguna untuk mengenkripsi 
                //sha256 merupakan salah satu algoritma hash, fungsi "hash('algoritma', 'data')
                setcookie("id_user", $row["id_penjual"],  time() + 120);
                setcookie("username", hash("sha256", $row["username_penjual"]), time() + 120);
            }
            //kalo password dimasukkan cocok dengan hash-nya maka di-redirect
            header("Location: penjual/menuPenjual.php");
            exit;
        }
    } 

    //kalo gaada maka set variable gaada (sebagai error-nya true)
    $gaada = true; 
    if ($gaada == true) {
        echo "<script>alert('Username atau Password yang anda masukkan salah')</script>";
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
    <link rel="stylesheet" href="loginsmartcanteen.css">
</head>
<body>
    <!--Bagian Gambar & Sambutan-->
    <section class="sambutan">
        <img src="LogoCanteen2.png">
        <p>Selamat Datang di Smart Canteen<p>    
    </section>        


    <!--Bagian Aksi Input Login-->
    <section class="login">
        <h1>Login Smart Canteen</h1>

        <div class="form">
            <form action="" method="post">
                <div class="form-atas">
                    <table>
                        <tr>
                            <td><label for="username">Username :</label></td>
                            <td><input type="text" name="username" id="username" pattern="[a-zA-Z0-9]+" required oninvalid="this.setCustomValidity('username yang dimasukkan tidak valid')" oninput="this.setCustomValidity('')"></td>
                        </tr>
                        <tr>
                            <td><label for="password">Password :</label></td>
                            <td><input type="password" name="password" id="password" required></td>
                        </tr>
                    </table>
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <button type="submit" name="login">Login!</button>  
            </form>
        </div>

        <div class="akuno">
            <a href="registrasismartcanteen.php">Belum memiliki akun?</a>
        </div>   
    </section>
</body>
</html>