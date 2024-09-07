<?php 

// buat koneksi

$koneksi = mysqli_connect("localhost","root","","absensi");

// cek koneksi
// if  (mysqli_connect_errno()) {
//     echo "Koneksi database gagal";

// } else {
//     echo "Koneksi database sukses";
// }

// main url

$main_url = "http://localhost/absensi/";

// fungi buat halaman lain yang memerlukan 

function uploadimg ($url){   //ini fungsi upload gambar

    $namafile       = $_FILES['image']['name'];
    $ukuran         = $_FILES['image']['size'];
    $error          = $_FILES['image']['error'];
    $tmp            = $_FILES['image']['tmp_name'];


    // menentukan file yang boleh di upload

    $validExtension  = ['jpg','jpeg','png'];
    $fileExtension   = explode('.', $namafile);
    $fileExtension   = strtolower(end($fileExtension));

    // mencocokan file yang akan diupload dengan file yang boleh diupload

    if(!in_array($fileExtension, $validExtension)) {
        header("location:". $url . '?msg=notimage' );
        echo "<script>window.history.back();</script>";
        die;
    }

    // generate nama file gambar
    
    $namafilebaru =rand(1,1000) .'.' . $namafile;

    // Upload gambar
    if (!move_uploaded_file($tmp, '../asset/image/' . $namafilebaru)) {
        error_log("Gagal mengunggah file: " . $namafilebaru);
    }

    //upload gambar
    move_uploaded_file($tmp, '../asset/image/'.$namafilebaru); 
    return $namafilebaru;    
}

