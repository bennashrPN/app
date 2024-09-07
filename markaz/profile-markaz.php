<?php
session_start();

if(!isset($_SESSION["ssLogin"])){
    header("location:../auth/login.php");
    exit;
}
// Panggil semua laman yang diperlukan

require_once  "../config.php";

$title = "Profile - Markaz Al Matuq"; //judul untuk laman markaz al matuq

require_once  "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$sekolah = mysqli_query ($koneksi, "SELECT * FROM tbl_markaz WHERE id = 1"  ); //panggil koneksi dan data
$data = mysqli_fetch_array($sekolah); 

?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 ">
            <h1 class="mt-5">Markaz</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item "><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Profile Markaz</li>
            </ol>
            <!-- tipenya enctype karena kita mau upload gambar -->

            <form action="proses-markaz.php" method="POST" enctype="multipart/form-data"> 
            <div class="card">

                <div class="card-header">
                    <span class="h5"><i class="fa-solid fa-pen-to-square"></i> Data Markaz</span>
                    <button type="simpan" name="simpan" class="btn btn-success float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    <button type="reset" name="reset" class="btn btn-danger float-end me-1"><i class="fa-solid fa-xmark"></i> Reset</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 px-5 ">
                            <input type="hidden" name="gmbrlama" value="<?= $data['gambar']?>">

                            <img src="../asset/image/<?= $data['gambar'] ?>" alt="gambar markaz" class="mb-3" width="30%">
                            <input type="file" name="image" class="form-control form-control-sm" style="width: 30%;">
                            <small class="text-secondary">Pilih gambar PNG,JEPG,</small>
                            <div><small class="text-secondary">Atau JPG ,max 1 MB</small></div>
                        </div>
                        <div class="col-8">
                            <div class="mb-3 row">
                                <label for="nama" class="col-sm-2 col-form-label" style="margin-left: -60px;">Nama</label>
                                <label for="nama" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                                    <input type="text" class="form-control border-0 border-bottom" id="nama" name="nama" 
                                    style="width: 30%;"   value="<?= $data['email']?>" placeholder="Nama Markaz">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="email" class="col-sm-2 col-form-label" style="margin-left: -60px;">Email</label>
                                <label for="email" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                                    <input type="email" class="form-control border-0 border-bottom" id="email" name="email" 
                                    style="width: 30%;"   value="<?= $data['nama']?>" placeholder="Email Markaz">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="status" class="col-sm-2 col-form-label" style="margin-left: -60px;">Status</label>
                                <label for="status" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                            <select name="status" id="status" class="form-select border-0 border-bottom">
                                <option value="Negri" selected>Negri</option>  
                                <option value="Swasta" selected>Swasta</option>
                            </select>   
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="akreditasi" class="col-sm-2 col-form-label" style="margin-left: -60px;">Akreditasi</label>
                                <label for="akreditasi" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                            <select name="akreditasi" id="akreditasi" class="form-select border-0 border-bottom">
                                <option value="A" selected>A</option>  
                                <option value="B" selected>B</option>
                                <option value="C" selected>C</option>
                                <option value="D" selected>D</option>
                            </select>   
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="alamat" class="col-sm-2 col-form-label" style="margin-left: -60px;">ALamat</label>
                                <label for="alamat" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                            <textarea name="alamat" id="alamat" cols="30" 
                            rows="3" class="form-control border-0 border-bottom">
                            <?= $data['alamat']?></textarea>   
                            
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="visimisi" class="col-sm-2 col-form-label" style="margin-left: -60px;">Visi dan Misi</label>
                                <label for="visimisi" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-9" style="margin-left: -50px;">
                            <textarea name="visimisi" id="visimisi" cols="30" 
                            rows="3" class="form-control border-0 border-bottom"><?= $data['visimisi']?></textarea>   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </main>
    <?php

    // tiap laman baru butuh footer

    require_once  "../template/footer.php";

    ?>