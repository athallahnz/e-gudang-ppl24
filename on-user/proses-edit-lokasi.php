<?php
error_reporting(E_ALL);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
include "../koneksi.php";
include "../random-v2.php";

$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
if (!$token || $token !== $_SESSION['token']) {
    // Mengembalikan kode status HTTP 405 Method Not Allowed jika token salah
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
} else {
    if (isset($_POST['submit'])) {

        date_default_timezone_set('Asia/Jakarta');

        $agent = $_SERVER['HTTP_USER_AGENT'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $id_user = $_SESSION['id'];

        // Menggunakan fungsi decrypt untuk mendapatkan ID lokasi yang di-edit
        $id = encrypt_decrypt2('decrypt', $_POST['id']);

        // Sanitize input
        $nama = htmlspecialchars($_POST['nama']);
        $alamat = htmlspecialchars($_POST['alamat']);
        $deskripsi = htmlspecialchars($_POST['deskripsi']);

        // Mulai transaksi
        mysqli_begin_transaction($connect);

        // Update rak_aset dengan prepared statement untuk mencegah SQL Injection
        $stmt = $connect->prepare("UPDATE lokasi_aset SET nama=?, alamat=?, deskripsi=?, last_edited=now(), edited_by=? WHERE id=?");
        $stmt->bind_param("sssii", $nama, $alamat, $deskripsi, $id_user, $id);
        $update = $stmt->execute();

        if ($update) {
            // Insert log ke dalam userlog
            $stmt_log = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) 
                                        VALUES (?, ?, ?, now(), ?, 'sim-barang', 'Master Lokasi', ?)");
            $status_log = "User dengan ID ($id_user) mengedit lokasi dengan ID ($id)";
            $stmt_log->bind_param("isssi", $id_user, $ip, $agent, $status_log, $id);
            $logs = $stmt_log->execute();

            // Jika kedua query berhasil, commit transaksi
            if ($logs) {
                mysqli_commit($connect);
                $_SESSION['sukses'] = 'Data Berhasil Disimpan';
                header('Location: menu-edit-lokasi?id=' . encrypt_decrypt2('encrypt', $id));
            } else {
                // Rollback jika log gagal
                mysqli_rollback($connect);
                $_SESSION['gagal'] = 'Log Gagal Disimpan';
                header('Location: menu-edit-lokasi?id=' . encrypt_decrypt2('encrypt', $id));
            }
        } else {
            // Rollback jika update gagal
            mysqli_rollback($connect);
            $_SESSION['gagal'] = 'Data Gagal Disimpan';
            header('Location: menu-edit-lokasi?id=' . encrypt_decrypt2('encrypt', $id));
        }

        // Tutup statement
        $stmt->close();
        $stmt_log->close();
    } else {
        header('Location: 404');
    }
}

$connect->close();
exit();

  }else{
    header('Location: 404');
  }
?>