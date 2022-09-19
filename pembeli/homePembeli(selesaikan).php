<?php
//MENANGANI AKSI SELESAIKAN PESANAN YANG BERJALAN
//kalo attribute "action" dari metode request POST menuju halaman lain, maka dia juga akan sekaligus redirect


require "fungsiPembeli.php"; //koneksi database dan user defined function


//Mengecek Data Post Yang Dikirim
//kalo langsung akses tanpa ada metode POST dari halaman homePembeli.php maka tendang kembali
if(!isset($_POST["idPesanan"])) {
    echo '<script>location="homePembeli.php"</script>';
}


//Mengambil Data Diperlukan
$idPesanan = $_POST["idPesanan"];
//Panggil Data Berdasarkan id_pesanan-nya
$iniPesanan = queryData("SELECT * FROM pesanan WHERE id_pesanan = $idPesanan");


//Masukkan Ke Riwayat Dan Hapus Pesanan
//bikin dahulu semua info untuk di riwayat
$idPembeli = $iniPesanan[0]["id_pembeli"]; 
$idPenjual = $iniPesanan[0]["id_penjual"];
$menuDipesan = $iniPesanan[0]["menu_dipesan"];
$jumlahPesanan = $iniPesanan[0]["jumlah_pesanan"];
$jumlahTransaksi = $iniPesanan[0]["jumlah_transaksi"];
$keterangan = "berhasil";
$alasan = "";
masukkanRiwayat($idPembeli, $idPenjual, $menuDipesan, $jumlahPesanan, $jumlahTransaksi, $keterangan, $alasan);
//masukkan dahulu baru hapus, agar datanya belum hilang


//Menghapus Pesanan Dari Table "midtrans" & "pesanan"
//Khusus untuk metode Payment Gateaway maka hapus juga di table midtrans
if ($iniPesanan[0]["tipe_pesanan"] == "payment gateaway") {
    $kodePesanan = $iniPesanan[0]["kode_pesanan"];
    hapusMidtrans($kodePesanan);
}
//hapus dari table pesanan
hapusPesanan($idPesanan);


//Proses Selesai, Maka Redirect
echo '<script>
        alert("Anda SUDAH menyelesaikan pesanan.");
        location="homePembeli.php";
    </script>';