<?php
error_reporting(E_ALL);
session_start();
if ($_SESSION['level'] == 'admin') {
include "../koneksi.php";
include "../random-v2.php";

if (isset($_POST['submit'])) {

    date_default_timezone_set('Asia/Jakarta');

    $agent = $_SERVER['HTTP_USER_AGENT'];
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Menggunakan fungsi decrypt untuk mendapatkan ID rak yang di-edit
    $id = encrypt_decrypt2('decrypt', $_POST['id']);
    
    // Sanitize input
    $id_user = $_SESSION['id'];
    $username = htmlspecialchars($_POST['username']);
    $pengguna = htmlspecialchars($_POST['pengguna']);
    $email = htmlspecialchars($_POST['email']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $password_baru = htmlspecialchars($_POST['password_baru']);
 
    // Cek apakah password_baru diisi
    if (!empty($password_baru)) {
        // Jika password baru diisi, hash password
        $hashedPassword = password_hash($password_baru, PASSWORD_BCRYPT);
        // Update query dengan password baru
        $update = mysqli_query($connect, "UPDATE user SET username='$username', nama='$pengguna', email='$email', jabatan='$jabatan', no_hp='$no_hp', level='$role', status='$status', password='$hashedPassword', edited_by=$id_user, last_edited=now() WHERE id=$id");
    } else {
        // Jika password kosong, update tanpa password
        $update = mysqli_query($connect, "UPDATE user SET username='$username', nama='$pengguna', email='$email', jabatan='$jabatan', no_hp='$no_hp', level='$role', status='$status', edited_by=$id_user, last_edited=now() WHERE id=$id");
    }
 
    if ($update) {
        // Commit transaksi jika berhasil
        mysqli_commit($connect);
        // Insert log ke dalam userlog
        $stmt_log = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES (?, ?, ?, now(), ?, 'sim-barang', 'Master Pengguna', ?)");
        $status_log = "User dengan ID ($id_user) mengedit pengguna dengan ID ($id)";
        $stmt_log->bind_param("isssi", $id_user, $ip, $agent, $status_log, $id);
        $logs = $stmt_log->execute();

        // Jika kedua query berhasil, commit transaksi
        if ($logs) {
            mysqli_commit($connect);
            $_SESSION['sukses'] = 'Data Berhasil Disimpan';
            header('Location: menu-edit-pengguna?id=' . encrypt_decrypt2('encrypt', $id));
        } else {
            // Rollback jika log gagal
            mysqli_rollback($connect);
            $_SESSION['gagal'] = 'Log Gagal Disimpan';
            header('Location: menu-edit-pengguna?id=' . encrypt_decrypt2('encrypt', $id));
        }
    } else {
        // Rollback jika update gagal
        mysqli_rollback($connect);
        $_SESSION['gagal'] = 'Data Gagal Disimpan';
        header('Location: menu-edit-pengguna?id=' . encrypt_decrypt2('encrypt', $id));
    }

    // Tutup statement
    $stmt->close();
    $stmt_log->close();
} else {
    header('Location: 404');
}

$connect->close();
exit();

  }else{
    header('Location: 404');
  }
?>