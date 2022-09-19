<?php
//MENANGANI AKSI LOGOUT


//untuk menghilangkan session, ada sampai 3 proses itu untuk memastikan agar benar - benar terhapus atau hilang
session_start();
$_SESSION = [];
session_unset();
session_destroy();


//di dokumentasi php.net nulisannya 1 jam = 3600 detik, walaupun mundur 1 detik saja sebenarnya bisa
//setcookie() value-nya pakai tanda kutip dua maupun tanda kutip satu bisa dijalankan
setcookie("username", '', time() - 3600);
setcookie("id_user", "", time() - 3600);


//kembalikan ke halaman login
header("Location: index.php");
?>