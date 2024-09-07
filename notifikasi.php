<?php
// Koneksi ke database
require_once "../config.php";
// Query untuk mendapatkan jadwal saat ini
$currentTime = date('H:i:s');
$query = "SELECT id, guru, pelajaran, waktu_mulai, nomor_whatsapp FROM tbl_jadwalpelajaran WHERE waktu_mulai = '$currentTime'";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id_jadwal = $row['id'];
        $guru = $row['guru'];
        $pelajaran = $row['pelajaran'];
        $waktu_mulai = $row['waktu_mulai'];
        $nomor_whatsapp = $row['nomor_whatsapp'];

        // Menyiapkan pesan
        $pesan = "Pengingat: Anda akan mengajar $pelajaran pada $waktu_mulai.";

        // Insert ke tbl_notifikasi dengan status 'belum_dikirim'
        $stmt = $mysqli->prepare("INSERT INTO tbl_notifikasi (id_jadwal, nomor_whatsapp, pesan, status_notifikasi) VALUES (?, ?, ?, 'belum_dikirim')");
        $stmt->bind_param("iss", $id_jadwal, $nomor_whatsapp, $pesan);
        $stmt->execute();
        $id_notifikasi = $stmt->insert_id;
        $stmt->close();

        // Menjalankan script Python untuk mengirim pesan
        $command = escapeshellcmd("python3 /path/to/your/python_script.py $nomor_whatsapp \"$pesan\" $id_notifikasi");
        $output = shell_exec($command);

        // Asumsikan bahwa script Python mengembalikan status 'terkirim' atau 'gagal'
        $status = trim($output); // 'terkirim' atau 'gagal'
        $waktu_kirim = date('Y-m-d H:i:s');

        // Update status_notifikasi berdasarkan output dari Python
        $update_stmt = $mysqli->prepare("UPDATE tbl_notifikasi SET status_notifikasi = ?, waktu_kirim = ? WHERE id_notifikasi = ?");
        $update_stmt->bind_param("ssi", $status, $waktu_kirim, $id_notifikasi);
        $update_stmt->execute();
        $update_stmt->close();

        echo "Pesan dikirim ke $guru untuk pelajaran $pelajaran pada $waktu_mulai. Status: $status\n";
    }
} else {
    echo "Tidak ada jadwal pada waktu ini.";
}

$mysqli->close();
?>
