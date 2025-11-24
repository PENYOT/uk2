<?php
include '../../../config/connection.php';

$id = $_GET['id'];

mysqli_query($connect, "DELETE FROM penggunaan WHERE id_penggunaan = '$id'");

header("Location: index.php?success=deleted");
exit;
