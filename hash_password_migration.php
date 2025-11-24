<?php
include 'config/connection.php';

// =============================
// HASH PASSWORD ADMIN
// =============================
$qAdmin = mysqli_query($connect, "SELECT * FROM admin");

while ($a = mysqli_fetch_assoc($qAdmin)) {

    if (strlen($a['password']) < 20) {  // kalau masih plain text
        $hashed = password_hash($a['password'], PASSWORD_DEFAULT);

        mysqli_query($connect, "
            UPDATE admin SET password = '$hashed'
            WHERE id_admin = ".$a['id_admin']
        );
    }
}

// =============================
// HASH PASSWORD PELANGGAN
// =============================
$qUser = mysqli_query($connect, "SELECT * FROM pelanggan");

while ($u = mysqli_fetch_assoc($qUser)) {

    if (strlen($u['password']) < 20) {  // plain text
        $hashed = password_hash($u['password'], PASSWORD_DEFAULT);

        mysqli_query($connect, "
            UPDATE pelanggan SET password = '$hashed'
            WHERE id_pelanggan = ".$u['id_pelanggan']
        );
    }
}

echo "Password sudah di-hash semuanya!";
