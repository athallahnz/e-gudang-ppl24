<?php
error_reporting(0);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
include "../koneksi.php";
include "../random-v2.php";

if (isset($_GET['id'])) {
    // Dekripsi ID satuan
    $id = encrypt_decrypt2('decrypt', $_GET['id']);
    $id_user = $_SESSION['id'];
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $ip = $_SERVER['REMOTE_ADDR'];
    
    date_default_timezone_set('Asia/Jakarta');

    // Menggunakan prepared statement untuk memilih satuan yang terkait dengan barang
    $stmt = $connect->prepare("SELECT * FROM aset WHERE id_satuan = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $select_row = $stmt->get_result();

    // Periksa apakah satuan masih terkait dengan barang lain
    if ($select_row->num_rows < 1) {
        // Menggunakan prepared statement untuk menghapus satuan
        $stmt_delete = $connect->prepare("DELETE FROM satuan_aset WHERE id = ?");
        $stmt_delete->bind_param("i", $id);
        $delete_success = $stmt_delete->execute();

        // Logging aktivitas user
        $log_status = 'User dengan ID (' . $id_user . ') menghapus satuan dengan ID (' . $id . ')';
        $stmt_log = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES (?, ?, ?, NOW(), ?, 'sim-barang', 'Master Satuan', ?)");
        $stmt_log->bind_param("isssi", $id_user, $ip, $agent, $log_status, $id);
        $log_success = $stmt_log->execute();

        if ($delete_success && $log_success) {
            $_SESSION['sukses'] = 'Data Berhasil Dihapus';
        } else {
            $_SESSION['gagal'] = 'Data Gagal Dihapus';
        }

        header('Location: master-satuan');
        exit();

    } else {
        // Jika satuan masih terhubung dengan barang lain
        $_SESSION['gagal'] = 'Penghapusan gagal! Satuan ini masih digunakan oleh barang lain. Anda perlu memutuskan semua relasi dengan barang tersebut sebelum melanjutkan penghapusan.';
        header('Location: master-satuan');
        exit();
    }
	
} else {
	header('Location: 404');
}

$connect->close();
exit();

  }else{
    header('Location: 404');
  }
?>