<?php
error_reporting(E_ALL);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
include "../koneksi.php";

$agent = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];
$id_user = $_SESSION['id'];

$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
if (!$token || $token !== $_SESSION['token']) {
    // Mengembalikan kode status HTTP 405 Method Not Allowed jika token salah
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
} else {
    if (isset($_POST['submit'])) {
        date_default_timezone_set('Asia/Jakarta');
        
        // Menyiapkan prepared statement untuk menyimpan data secara aman
        $material = htmlspecialchars($_POST['material']);
        $klasifikasi = htmlspecialchars($_POST['klasifikasi']);
        $deskripsi = htmlspecialchars($_POST['deskripsi']);
        
        // Mulai transaksi MySQL untuk memastikan konsistensi data
        mysqli_begin_transaction($connect);

        // Menggunakan prepared statement untuk keamanan
        $stmt = $connect->prepare("INSERT INTO  klasifikasi_aset (id_material, nama, deskripsi, created_by, last_created) VALUES (?, ?, ?, ?, now())");
        $stmt->bind_param("issi", $material, $klasifikasi, $deskripsi, $id_user);
        $simpan = $stmt->execute();

        if ($simpan) {
            $last_id = $connect->insert_id;

            // Menyiapkan query log user
            $stmt_log = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES (?, ?, ?, now(), ?, 'sim-barang', 'Master Klasifikasi', ?)");
            $status_log = "User dengan ID ($id_user) menambahkan klasifikasi baru dengan ID ($last_id)";
            $stmt_log->bind_param("isssi", $id_user, $ip, $agent, $status_log, $last_id);
            $logs = $stmt_log->execute();

            // Commit transaksi jika kedua query berhasil
            if ($logs) {
                mysqli_commit($connect);
                $_SESSION['sukses'] = 'Berhasil Disimpan';
                header('Location: master-klasifikasi');
            } else {
                mysqli_rollback($connect); // Rollback jika log gagal
                $_SESSION['gagal'] = 'Log Gagal Disimpan';
                header('Location: master-klasifikasi');
            }
        } else {
            mysqli_rollback($connect); // Rollback jika query utama gagal
            $_SESSION['gagal'] = 'Data Gagal Disimpan';
            header('Location: master-klasifikasi');
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