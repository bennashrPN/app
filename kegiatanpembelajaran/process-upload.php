<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config.php";
require_once __DIR__ . '/../asset/lib/PhpSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Disable error display but enable error logging
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/php_errors.log'); // Adjust path as needed

if (isset($_POST['upload'])) {
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['excelFile']['tmp_name'];
        $fileName = $_FILES['excelFile']['name'];
        $fileSize = $_FILES['excelFile']['size'];
        $fileType = $_FILES['excelFile']['type'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Check file extension
        if ($fileExtension !== 'xls' && $fileExtension !== 'xlsx') {
            header('Location: upload-akp.php?msg=not_excel');
            exit;
        }

        // Load the Excel file
        try {
            $spreadsheet = IOFactory::load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            $guru = $_SESSION["nama"];

            // Prepare the query to fetch classes based on the teacher and lesson
            $kelasPelajaranQuery = "SELECT kelas FROM tbl_jadwalpelajaran WHERE guru = ? AND pelajaran = ?";
            if ($kelasPelajaranStmt = mysqli_prepare($koneksi, $kelasPelajaranQuery)) {
                
                // Prepare insert query
                $insertQuery = "INSERT INTO tbl_dataakp (kelas, guru, pelajaran, materi_pembelajaran, kegiatan_pembelajaran) VALUES (?, ?, ?, ?, ?)";
                $insertStmt = mysqli_prepare($koneksi, $insertQuery);

                foreach ($rows as $row) {
                    if (empty($row[0])) continue;

                    $pelajaran = $row[1];

                    // Bind and execute the query to get the classes
                    mysqli_stmt_bind_param($kelasPelajaranStmt, 'ss', $guru, $pelajaran);
                    mysqli_stmt_execute($kelasPelajaranStmt);
                    $kelasResult = mysqli_stmt_get_result($kelasPelajaranStmt);

                    while ($kelasRow = mysqli_fetch_assoc($kelasResult)) {
                        $kelas = $kelasRow['kelas'];

                        // Insert the data into tbl_dataakp
                        mysqli_stmt_bind_param($insertStmt, 'sssss', $kelas, $guru, $pelajaran, $row[2], $row[3]);
                        if (!mysqli_stmt_execute($insertStmt)) {
                            error_log("Failed to insert data for class: $kelas, lesson: $pelajaran");
                        }
                    }
                }

                // Close the statements
                mysqli_stmt_close($kelasPelajaranStmt);
                mysqli_stmt_close($insertStmt);

                header('Location: upload-akp.php?msg=added');

            } else {
                header('Location: upload-akp.php?msg=error_prepare_kelas_pelajaran');
            }

        } catch (Exception $e) {
            // Log the error for debugging but show a user-friendly message
            error_log('File processing error: ' . $e->getMessage());
            header('Location: upload-akp.php?msg=error_file');
        }

    } else {
        header('Location: upload-akp.php?msg=upload_failed');
    }
}
?>
