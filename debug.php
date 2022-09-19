<?php
    //HALAMAN UNTUK MELAKUKAN DEBUG SECURITY DAN KEAMANAN


    //Bagian Ambil Data Dari Database
    $db = mysqli_connect("localhost", "root", "", "preorder_smart_canteen");
    $result = mysqli_query($db, "SELECT * FROM user_pembeli ORDER BY id_pembeli DESC");
    $instances = [];
    while ($instance = mysqli_fetch_assoc($result)) {
        $instances[] = $instance;
    }
?>



 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Debug Security</title>
 </head>
 <body>
    <?= $instances[0]["username_pembeli"] ?>
    <!--kalo bentuknya code maka akan dieksekusi di dalam tag-->
    <script>
        <?= $instances[0]["username_pembeli"] ?> 
    </script>

    <p> <script> alert('kena nih boy 1'); </script> </p> <!--syntax JavaScript dijalankan-->
    <!--Kemungkinan nanti buat saja html-nya ada regex untuk username harus dengan pola tertentu misal hanya boleh angka dan huruf-->
    <p> alert('kena nih boy 2'); </p> <!--syntax JavaScript ini tidak dijalankan-->
    <p> <?php var_dump($instances); ?> </p> <!--syntax PHP dijalankan-->
    <p> var_dump($instances); </p> <!--syntax PHP tidak dijalankan-->
    <p> echo('hello world 1'); </p> <!--syntax PHP tidak dijalankan-->
 </body>
 </html>