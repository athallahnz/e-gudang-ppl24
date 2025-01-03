<?php
error_reporting(E_ALL);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
include "../koneksi.php";
include "../random-v2.php";

if (isset($_POST['submit'])) {

    date_default_timezone_set('Asia/Jakarta');
    $tgl_masuk = date("Y-m-d");

    $agent = $_SERVER['HTTP_USER_AGENT'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $id_user = $_SESSION['id'];

    // Menggunakan fungsi decrypt untuk mendapatkan ID satuan yang di-edit
    $id = encrypt_decrypt2('decrypt', $_POST['id']);

    // Sanitize input
    $stok_baru = htmlspecialchars($_POST['stok']);
    
    // Mulai transaksi
    mysqli_begin_transaction($connect);
    
    
    $result_dokumen = mysqli_query($connect, "SELECT * FROM aset WHERE id_aset=$id");
    if($result_dokumen->num_rows > 0) {
        $row_dokumen = $result_dokumen->fetch_object();
        $tambah_qty = $row_dokumen->qty + $stok_baru;
        echo $tambah_qty;
    }

    // Update satuan_aset dengan prepared statement untuk mencegah SQL Injection
    $stmt = $connect->prepare("UPDATE aset SET qty=? WHERE id_aset=?");
    $stmt->bind_param("ii", $tambah_qty, $id);
    $update = $stmt->execute();

    if ($update) {
        // Simpan transaksi
        $transaksi = mysqli_query($connect, "INSERT INTO transaksi (fk_id_aset, stok, tgl_tx, jenis_tx, created_by, last_created) VALUES ($id, $stok_baru, '$tgl_masuk', 'Masuk', $id_user, now())") or die(mysqli_error($connect));

        // Insert log ke dalam userlog
        $stmt_log = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES (?, ?, ?, now(), ?, 'sim-barang', 'Master Satuan', ?)");
        $status_log = "User dengan ID ($id_user) mengedit satuan dengan ID ($id)";
        $stmt_log->bind_param("isssi", $id_user, $ip, $agent, $status_log, $id);
        $logs = $stmt_log->execute();

        // Jika kedua query berhasil, commit transaksi
        if ($logs && $transaksi) {
            mysqli_commit($connect);
            $_SESSION['sukses'] = 'Data Berhasil Disimpan';
            header('Location: menu-tambah-stok?id=' . encrypt_decrypt2('encrypt', $id));
        } else {
            // Rollback jika log gagal
            mysqli_rollback($connect);
            $_SESSION['gagal'] = 'Log Gagal Disimpan';
            header('Location: menu-tambah-stok?id=' . encrypt_decrypt2('encrypt', $id));
        }
    } else {
        // Rollback jika update gagal
        mysqli_rollback($connect);
        $_SESSION['gagal'] = 'Data Gagal Disimpan';
        header('Location: menu-tambah-stok?id=' . encrypt_decrypt2('encrypt', $id));
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