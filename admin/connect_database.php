<?php
$connect_database = mysqli_connect("localhost", "root", "", "banhang");

if (!$connect_database) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($connect_database, "utf8");
?>
