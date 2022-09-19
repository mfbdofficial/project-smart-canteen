<?php
//SEBAGAI HALAMAN PENJUAL (MENANGANI AKSI UPDATE MENU)


//Bagian Memulai Session & Koneksi Module
session_start(); //memulai session
require "fungsiPenjual.php"; //koneksi database dan user defined function


//Bagian Mengecek Session Penjual (Untuk Memastikan Sudah Login)
//cek apakah tidak ada session?, dibawah itu !isset artinya jika tidak ada
if (!isset($_SESSION["loginPenjual"])) {
    //kembalikan ke halaman login
    header("Location: ../index.php");
    exit;
}


//Bagian Menyimpan Data Session
$username = $_SESSION["username"];
$idPenjual = $_SESSION["idPenjual"];
//ambil data id dari URL :
$id = $_GET["id"];


//Bagian Mengambil Data Untuk Ditampilakan
//query data menu berdasarkan id :
$menuDipilih = queryData("SELECT * FROM menu WHERE id_menu = $id");
//ada array di dalam array (array associative di dalam array angka)
//var_dump($makanandipilih);


//Bagian Handle Aksi Mengupdate Menu
//cek apakah sudah menekan? :
if (isset($_POST["tekan"])) {
    //pakai function ubahMenu() yang sudah dibuat di fungsiPenjual.php
    //cek apakah hasil yang dikembalikan function ubahMenu() lebih dari 0 (setidaknya minimal 1 baris terpenuhi) :
    if (ubahMenu($_POST) > 0) {
        //misal notif pakai fungsi javascript "alert" untuk munculin kotak pesan (pop-up)
        //fungsi "document.location.href='halaman_dituju'" adalah fungsi redirect javascript
        echo "<script>
            alert('Data menu BERHASIL diubah.');
            document.location.href='menuPenjual.php';
        </script>";
    } else {
        echo "<script>
            alert('Data menu GAGAL diubah.');
            document.location.href='menuPenjual.php';
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
    <title>Edit Menu</title>
    <style>
        body {
            background: url('../admin/background-makanan-admin.jpg');
        }
        

        .aksi-ubah-menu li {
            padding: 0.5rem;
        }
        

        img {
            width: 200px;
            height: 200px;
        }
    </style>
</head>
<body>
    <!--Bagian Tulisan Ubah Data Menu-->
    <h1>Ubah Data Menu</h1>

    
    <!--Bagian Form Aksi Update Menu-->
    <!--pakai metode request POST-->
    <section class="aksi-ubah-menu">
        <form actions="" method="post" enctype="multipart/form-data">
            <!--pakai form tidak terlihat untuk tetap mengirim data, jadi id tetap dikirim ulang-->
            <input type="hidden" name="idMenu" value="<?php echo $menuDipilih[0]["id_menu"]; ?>">
            <!--gambar tetap dikirim ulang, kalo yang lain tidak karena sudah ada value nilai lama di inputnya-->
            <input type="hidden" name="gambarlama" value="<?php echo $menuDipilih[0]["gambar"]; ?>">
            <ul>
                <!--pakai "required" untuk input agar data harus diisi-->
                <!--pakai "values" agar data sudah ada di form, nampilin (tertulis) data sebelumnya-->
                <li>
                    <label for="kategori">Kategori :</label>
                    <input type="text" name="kategori" id="kategori" required value="<?php echo $menuDipilih[0]["kategori"]; ?>" >
                </li>
                <li>
                    <label for="nama">Nama Menu :</label>
                    <input type="text" name="nama" id="nama" pattern="[a-zA-Z ]+" required oninvalid="this.setCustomValidity('Nama makanan hanya boleh huruf')" oninput="this.setCustomValidity('')" value="<?php echo $menuDipilih[0]["nama_menu"]; ?>" >
                </li>
                <li>
                    <label for="harga">Harga Menu :</label>
                    <input type="text" name="harga" id="harga" pattern="[0-9]+" required oninvalid="this.setCustomValidity('Harga makanan hanya boleh angka')" oninput="this.setCustomValidity('')" value="<?php echo $menuDipilih[0]["harga"]; ?>" >
                </li>
                <li>
                    <label for="info">Informasi Menu :</label>
                    <input type="text" name="info" id="info" value="<?php echo $menuDipilih[0]["info"]; ?>" >
                </li>
                <!--Mulai bagian input untuk upload bentuk file, hapus atribute value karena ga perlu lagi tampilkan tulisan-->
                <li>
                    <label for="gambar">File Gambar Makanan :</label>
                    <br>
                    <img src="img/<?php echo $menuDipilih[0]["gambar"]; ?>" alt="">
                    <br>
                    <input type="file" name="gambar" id="gambar">
                </li>
                <li>
                    <button type="submit" name="tekan">Ubah Menu</button>
                </li>
            </ul>
        </form>   
    </section>
</body>
</html>