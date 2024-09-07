<?php 

session_start();

if(!isset($_SESSION["ssLogin"])){
    header("location:../auth/login.php");
    exit;
}
require_once "../config.php";

// jika tombol simpan ditekan

if(isset($_POST['simpan'])) {

    // ambil velue yang diposting

    $id         = $_POST['id'];
    $nama       = trim(htmlspecialchars($_POST['nama']));
    $email      = trim(htmlspecialchars($_POST['email']));
    $status     = $_POST ['status']; 
    $akreditasi = $_POST ['akreditasi']; //tidak perlu validasi karena sudah pake kombobox
    $alamat     = trim(htmlspecialchars($_POST['alamat']));
    $visimisi   = trim(htmlspecialchars($_POST['visimisi']));
    $gbr     = trim(htmlspecialchars($_POST['gbrlama']));
    
    
    //cek apakah ada gambar yang diupload user

    if ($_FILES['image']['error']== 4) {  
        $gbrSekolah =  $gbr ;
    }else { 
        $url ='profile-sekolah.php';
        $gbrSekolah = uploadimg ($url);
        @unlink ('asset/image/'.$gbr);
    }
}

