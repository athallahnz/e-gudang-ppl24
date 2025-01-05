<?php
error_reporting(E_ALL);
session_start();
include "../koneksi.php";

if (!isset($_SESSION['id'])) {
    header('Location: ../index');
    exit;
}

$agent = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];
$id_user = $_SESSION['id'];

$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
if (!$token || $token !== $_SESSION['token']) {
    // return 405 http status code
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        // Sanitize input
        $id_tipe_selected = htmlspecialchars($_POST['id_tipe_selected']);
        $qty_out = filter_input(INPUT_POST, 'qty_out', FILTER_VALIDATE_INT);
        
        // Validasi qty_out apakah valid
        if ($qty_out === false || $qty_out <= 0) {
            $_SESSION['gagal'] = 'Jumlah barang keluar tidak valid';
            header('Location: menu-scan-raw');
            exit;
        }
        
        // Query untuk mendapatkan stok berdasarkan nomor aset (gunakan prepared statement)
        $query = $connect->prepare("SELECT * FROM aset_raw WHERE id = ?");
        $query->bind_param("i", $id_tipe_selected);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $current_qty = $row['qty'];
            $id = $row['id'];
            $id_tipe = $row['id_tipe'];
            
            // Cek apakah stok cukup
            if ($current_qty >= $qty_out) {
                // Update stok dengan prepared statement
                $new_qty = $current_qty - $qty_out;
                $update = $connect->prepare("UPDATE aset_raw SET qty = ? WHERE id = ?");
                $update->bind_param("ii", $new_qty, $id_tipe_selected);
                
                if ($update->execute()) {
                    // Catat transaksi keluar
                    $insert_out = $connect->prepare("INSERT INTO transaksi_raw (fk_id_tipe, fk_id_aset, stok, jenis_tx, last_created, created_by) VALUES (?, ?, ?, 'Keluar', now(), ?)");
                    $insert_out->bind_param("iiii", $id_tipe, $id_tipe_selected, $qty_out, $id_user);
                    $insert_out->execute();
                    echo 123;

                    // Logging aktivitas
                    $log_message = "User dengan ID ($id_user) mengeluarkan $qty_out barang Raw $id_tipe_selected.";
                    $log = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES (?, ?, ?, now(), ?, 'sim-barang', 'Transaksi Out', ?)");
                    $log->bind_param("issss", $id_user, $ip, $agent, $log_message, $id_tipe_selected);
                    $log->execute();

                    $_SESSION['sukses'] = 'Barang berhasil dikeluarkan';

                } else {
                    $_SESSION['gagal'] = 'Gagal memperbarui stok';
                }
            } else {
                $_SESSION['gagal'] = 'Stok tidak mencukupi';
            }
        } else {
            $_SESSION['gagal'] = 'Nomor tipe tidak ditemukan';
        }

        // Redirect kembali ke halaman transaksi
        header('Location: menu-scan-raw');
        exit();
    } else {
        header('Location: 404');
        exit();
    }
}

$connect->close();
?>
