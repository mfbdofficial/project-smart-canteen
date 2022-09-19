<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midtrans Notification</title>
</head>
<body>
    <h1>Ini Halaman Handle Notifikasi Payment Gateaway</h1>
</body>
</html>

<?php
session_start(); //memulai session
require "fungsiPembeli.php"; //koneksi database dan user defined function
$db = mysqli_connect("localhost", "root", "", "preorder_smart_canteen");
//mysqli_query($db, "INSERT INTO midtrans VALUES('', 'percobaan', 'percobaan', 'percobaan', 'percobaan', '')");



require_once('../vendor/midtrans/midtrans-php' . '/Midtrans.php');
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$serverKey = 'SB-Mid-server-95Pj26erGQYFFwGB2BYbFOu-';
$notif = new \Midtrans\Notification();
 
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;
//misal tambah ambil waktu saat user settlement, ambil token dari session
$waktuTransaksi = $notif->transaction_time;
$waktuSettlement = $notif->settlement_time;
$coba = $notif->coba;
// $namaCostumer = $notif->first_name; //ini gabisa



if ($transaction == 'capture') {
  // For credit card transaction, we need to check whether transaction is challenge by FDS or not
  if ($type == 'credit_card') {
    if ($fraud == 'challenge') {
      // TODO set payment status in merchant's database to 'Challenge by FDS'
      // TODO merchant should decide whether this transaction is authorized or not in MAP
      echo "Transaction order_id: " . $order_id ." is challenged by FDS";
    }
    else {
      // TODO set payment status in merchant's database to 'Success'
      echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
    }
  }
}
else if ($transaction == 'settlement') {
  // TODO set payment status in merchant's database to 'Settlement'
  echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;

  //cek apakah dia metodenya yang gopay atau shopeepay?
  /*
  if ($type == "gopay" || $type == "shopeepay") {
    //maka buat table trigger yang nantinya akan dihapus lagi, nanti field-nya itu (id, kode_pesanan, metode_bayar, status_payment, waktu_terbayar)
    mysqli_query($db, "INSERT INTO pemicu VALUES('', '$order_id', '$type', '$transaction', '$waktuSettlement')");
    //die; //agar perintah di bawah tidak dikerjakan
  }
  */

  //update status di table midtrans dan pesanan (jika sudah dibayar)
  mysqli_query($db, "UPDATE midtrans SET status_payment = '$transaction', waktu_terbayar = '$waktuSettlement' WHERE kode_pesanan = '$order_id'");
  mysqli_query($db, "UPDATE pesanan SET status = '$transaction' WHERE kode_pesanan = '$order_id'");

  //tambahkan saldo penjualnya 
  $pesanan = queryData("SELECT * FROM pesanan WHERE kode_pesanan = '$order_id'");
  foreach ($pesanan as $perPesanan) {
    $nominalNambah = $perPesanan["jumlah_transaksi"];
    $idPenjual = $perPesanan["id_penjual"];
    $penjual = queryData("SELECT * FROM user_penjual WHERE id_penjual = $idPenjual");
    $saldoLama = $penjual[0]["saldo_penjual"];
    $saldoBaru = $saldoLama + $nominalNambah;
    mysqli_query($db, "UPDATE user_penjual SET saldo_penjual = $saldoBaru WHERE id_penjual = $idPenjual");
  }
}
else if ($transaction == 'pending') {
  // TODO set payment status in merchant's database to 'Pending'
  echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;

  //cek apakah dia metodenya yang gopay atau shopeepay?
  if ($type == "gopay" || $type == "shopeepay") {
    //maka buat table trigger yang nantinya akan dihapus lagi, nanti field-nya itu (id, kode_pesanan, metode_bayar, status_payment, waktu_terbayar)
    mysqli_query($db, "INSERT INTO pemicu VALUES('', '$order_id', '$type', '$transaction', '$waktuTransaksi')");
    //die; //agar perintah di bawah tidak dikerjakan
  }

  //masukkan ke table midtrans
  mysqli_query($db, "INSERT INTO midtrans VALUES('', '$order_id', '$type', '$transaction', '$waktuTransaksi', '')");
}
else if ($transaction == 'deny') {
  // TODO set payment status in merchant's database to 'Denied'
  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
}
else if ($transaction == 'expire') {
  // TODO set payment status in merchant's database to 'expire'
  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
  //update status di table midtrans dan pesanan (jika sudah expired)
  mysqli_query($db, "UPDATE midtrans SET status_payment = '$transaction' WHERE kode_pesanan = '$order_id'");
  mysqli_query($db, "UPDATE pesanan SET status = '$transaction' WHERE kode_pesanan = '$order_id'");
}
else if ($transaction == 'cancel') {
  // TODO set payment status in merchant's database to 'Denied'
  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
}
?>