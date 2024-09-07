<?php
session_start();
require_once "../config.php";

// If the login button is pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = htmlspecialchars($data['username']);
    $password = htmlspecialchars($data['password']);

    // Fetch user data including the role
    $query = "SELECT * FROM tbl_user WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    $response = array();
    // Check if the user exists
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $row['password'])) {
            $_SESSION["ssLogin"] = true;
            $_SESSION["ssUser"] = $username;
            $_SESSION["role"] = $row['role'];
            $_SESSION["nama"] = $row['nama'];
            $response['status'] = 'success';
            $response['role'] = $row['role'];
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Password Salah';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Username Tidak Terdaftar';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>