<?php
//MENANGANI AKSI MENAMBAHKAN KE KERANJANG
//Shopping Cart Dengan Session (Untuk Di Halaman Yang Beda)


session_start(); //memulai session
require "fungsiPembeli.php"; //koneksi database dan user defined function


//Untuk Menambahkan Ke Keranjang
//cek apakah orangnya sudah pencet beli produk? cek apakah _GET["id"] itu sudah ada dan valuenya benar angka?
if(isset($_GET["id"]) && is_numeric($_GET["id"])) {
    //menangkap id dari barang yang diklik (method GET) dari URL
    $id_produk = $_GET["id"];

    //Lakukan Proses Pengecekan Stock Lalu Pengurangan Stock
    //cek apakah stock-nya itu minimal masih ada 1?
    $infoMenus = queryData("SELECT * FROM menu WHERE id_menu = $id_produk");
    $infoMenu = $infoMenus[0];
    if ($infoMenu["stock"] == 0) {
        echo '<script>alert("Menu yang ingin anda pesan ternyata sudah habis.")</script>'; 
        echo '<script>location="pesanPembeli.php"</script>';
    } else {
        //melakukan pengurangan stock menu
        $jumlahSekarang = $infoMenu["stock"];
        $stockBaru = $jumlahSekarang - 1;
        ubahStock($id_produk, $stockBaru);

        //kita cek apakah sudah ada produk itu di keranjang? kalo udah maka plus 1 jumlah produknya
        //cek apakah session untuk cart yang key-nya id itu sudah ada?
        if(isset($_SESSION["keranjangMenu"][$id_produk])) {
            $_SESSION["keranjangMenu"][$id_produk] += 1;
            //kalo proses sudah maka berikan pemberitahuan
            echo '<script>alert("Anda MENAMBAHKAN menu ke keranjang.")</script>'; 
            echo '<script>location="pesanPembeli.php"</script>'; 
        } else {
            $_SESSION["keranjangMenu"][$id_produk] = 1; //kalo belum ada maka bikin dan value awalnya diisi 1
            //kalo proses sudah maka berikan pemberitahuan
            echo '<script>alert("Anda MENAMBAHKAN menu ke keranjang.")</script>'; 
            echo '<script>location="pesanPembeli.php"</script>';
        }
    }
}


//Untuk Menghapus Dari Keranjang
//cek apakah orangnya sudah pencet hapus? cek apakah $_GET["idHapus"] itu sudah ada dan valuenya benar angka?
if(isset($_GET["idHapus"]) && is_numeric($_GET["idHapus"])) {
    //menangkap id dari barang yang diklik (method GET) dari URL
    $id_produk_hapus = $_GET["idHapus"];

    //Lakukan Proses Penambahan Stock
    $infoMenusHapusKeranjang = queryData("SELECT * FROM menu WHERE id_menu = $id_produk_hapus");
    $infoMenuHapusKeranjang = $infoMenusHapusKeranjang[0];
    $jumlahDikembalikan = $_SESSION["keranjangMenu"][$id_produk_hapus];
    $jumlahSekarangMenuHapus = $infoMenuHapusKeranjang["stock"];
    $stockBaruHapus = $jumlahSekarangMenuHapus + $jumlahDikembalikan;
    ubahStock($id_produk_hapus, $stockBaruHapus);

    //kalo bisa nekan tombol hapus berarti sudah ada $_SESSION["keranjangMenu"], maka sekarang langsung proses hapus saja
    unset($_SESSION["keranjangMenu"][$id_produk_hapus]);
    //kalo proses sudah maka berikan pemberitahuan
    echo '<script>alert("Anda MENGHAPUS menu dari keranjang.")</script>'; 
    echo '<script>location="pesanPembeli.php"</script>';
}