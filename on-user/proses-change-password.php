
<?php
error_reporting(0);
session_start();
include "../koneksi.php";
include "../random-v2.php";

$id = encrypt_decrypt2('decrypt', $_POST['id']);
$password_lama = $_POST['password_lama'];
$password_baru = $_POST['password_baru'];
$konfirm_password_baru = $_POST['konfirm_password_baru'];

// Ambil password yang ada di database
$result_password = mysqli_query($connect, "SELECT password FROM user WHERE id=$id");
if ($result_password) {
    if ($result_password->num_rows > 0) {
        $row_result = $result_password->fetch_object();
        $get_password_lama = $row_result->password;
        // Verifikasi password lama dengan password_verify
        if (!password_verify($password_lama, $get_password_lama)) {
            $_SESSION['gagal'] = 'Password Lama Tidak Sesuai';
            header('Location: menu-profil'); 
            exit();
        }
    }
}

// Cek apakah konfirmasi password baru sama
if ($password_baru !== $konfirm_password_baru) {
    $_SESSION['gagal'] = 'Password Baru Tidak Sesuai Dengan Konfirmasi Password Baru';
    header('Location: menu-profil'); 
    exit();
}

// Hash password baru dengan bcrypt
$hashed_password_baru = password_hash($password_baru, PASSWORD_BCRYPT);

// Update password di database
$update_password = mysqli_query($connect, "UPDATE user SET password='$hashed_password_baru' WHERE id='$id'");

if ($update_password) {
    $_SESSION['sukses'] = 'Berhasil Ubah Kata Sandi';
    header('Location: menu-profil'); 
} else {
    $_SESSION['gagal'] = 'Kata Sandi Gagal diubah';
    header('Location: menu-profil'); 
}

$connect->close();
exit();
?>
