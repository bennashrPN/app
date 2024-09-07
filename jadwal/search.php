<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header('Location:../auth/login.php');
    exit;
}

require_once "../config.php";
$output = '';
$query = "SELECT * FROM tbl_guru WHERE nama LIKE '%".$_POST["query"]."%'";
$result = mysqli_query($koneksi, $query);
$output = '<ul class="list-unstyled">';
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
        $output .= '<li>'.$row["nama"].'</li>';
    }
} else {
    $output .= '<li>Nama Ustadz tidak ditemukan</li>';
}
$output .= '</ul>';
echo $output;
