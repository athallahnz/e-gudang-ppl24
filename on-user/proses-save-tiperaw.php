<?php
// Tingkatkan Keamanan
error_reporting(E_ALL);
session_start();
include "../koneksi.php";
include "function.php";

if ($_SESSION['level'] == 'admin') {

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
        $tipe = htmlspecialchars($_POST['tipe']);
        $satuan = htmlspecialchars($_POST['satuan']);
        $minqty = htmlspecialchars($_POST['minqty']);
        $deskripsi = htmlspecialchars($_POST['deskripsi']);
        
        // Mulai transaksi MySQL untuk memastikan konsistensi data
        mysqli_begin_transaction($connect);
        
        // Menggunakan prepared statement untuk keamanan
        $stmt = $connect->prepare("INSERT INTO  tipe_raw (id_material, id_klasifikasi, nama_tipe, id_satuan, minimun_qty, deskripsi, created_by, last_created) VALUES (?, ?, ?, ?, ?, ?, ?, now())");
        $stmt->bind_param("iisiisi", $material, $klasifikasi, $tipe, $satuan, $minqty, $deskripsi, $id_user);
        $simpan = $stmt->execute();
        
        $last_id = $connect->insert_id;
        $nomor_aset = 'RAW-' . str_pad($last_id, 6, '0', STR_PAD_LEFT);
        
        // Generate dan Simpan QR Code
        $namaFile = generateQrCode($nomor_aset);

        // Update nomor_tipe dan qrcode
        $update_stmt = $connect->prepare("UPDATE tipe_raw SET nomor_tipe = ?, qrcode = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $nomor_aset, $namaFile, $last_id);
        $update_stmt->execute();

        if ($simpan) {
            // Menyiapkan query log user
            $stmt_log = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES (?, ?, ?, now(), ?, 'sim-barang', 'Master Tipe', ?)");
            $status_log = "User dengan ID ($id_user) menambahkan tipe Raw baru dengan ID ($last_id)";
            $stmt_log->bind_param("isssi", $id_user, $ip, $agent, $status_log, $last_id);
            $logs = $stmt_log->execute();

            // Commit transaksi jika kedua query berhasil
            if ($logs) {
                mysqli_commit($connect);
                $_SESSION['sukses'] = 'Berhasil Disimpan';
                header('Location: master-tipe');
            } else {
                mysqli_rollback($connect); // Rollback jika log gagal
                $_SESSION['gagal'] = 'Log Gagal Disimpan';
                header('Location: master-tipe');
            }
        } else {
            mysqli_rollback($connect); // Rollback jika query utama gagal
            $_SESSION['gagal'] = 'Data Gagal Disimpan';
            header('Location: master-tipe');
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