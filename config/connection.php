<?php
$host = "localhost";
$username = "root";
$password = "";
$dbName = "listrik_db";

// membuat koneksi ke database
$connect = mysqli_connect($host, $username, $password, $dbName);

// cek apakah koneksi berhasil
if (!$connect) {
    echo "Database gagal tersambung";
}
