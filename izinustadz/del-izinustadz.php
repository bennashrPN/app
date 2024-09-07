<?php
// bismillah session start
// ingat spasi titik dua jarak antara huruf besar dan kecil  

session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location:../hudhuur/auth/login.php");
    exit;
}

// call file yang berkaitan  
require_once "../config.php";

$id = $_GET["id"];

mysqli_query($koneksi, "DELETE FROM tbl_izinguru WHERE id = $id");
if ($foto != "default.png") {
}
header("Location:izinustadz.php?msg=deleted");



?>