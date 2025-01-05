<?php
// Tingkatkan Keamanan
error_reporting(E_ALL);
ini_set('display_errors', 0); // Nonaktifkan di produksi
ini_set('log_errors', 1);
ini_set('error_log', '../error_log.txt');
session_start();
include "../koneksi.php";
include "function.php";

if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
// Cek Koneksi Database
if ($connect->connect_error) {
    die('Database connection failed: ' . $connect->connect_error);
}

// Cek token CSRF
$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
if (!isset($_SESSION['token']) || $token !== $_SESSION['token']) {
    http_response_code(403);
    exit('Invalid token');
}

$agent = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];
$id_user = $_SESSION['id'];

if (isset($_POST['submit'])) {
    date_default_timezone_set('Asia/Jakarta');

    // Sanitasi Input POST
    $tgl_masuk = filter_input(INPUT_POST, 'tgl_masuk', FILTER_SANITIZE_STRING);
    $no_seri = filter_input(INPUT_POST, 'no_seri', FILTER_SANITIZE_STRING);
    $lokasi = filter_input(INPUT_POST, 'lokasi', FILTER_VALIDATE_INT);
    $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
    $qty_min = filter_input(INPUT_POST, 'qty_min', FILTER_VALIDATE_INT);

    $ketebalan = htmlspecialchars($_POST['ketebalan']);
    $diameter = htmlspecialchars($_POST['diameter']);
    $panjang = htmlspecialchars($_POST['panjang']);
    $lebar = htmlspecialchars($_POST['lebar']);

    $berat = filter_input(INPUT_POST, 'berat', FILTER_VALIDATE_FLOAT);
    $satuan = filter_input(INPUT_POST, 'satuan', FILTER_VALIDATE_INT);
    $material = filter_input(INPUT_POST, 'material', FILTER_VALIDATE_INT);
    $klasifikasi = filter_input(INPUT_POST, 'klasifikasi', FILTER_VALIDATE_INT);
    $deskripsi = filter_input(INPUT_POST, 'deskripsi', FILTER_SANITIZE_STRING);
    $jenis_tx = "Masuk";
    
    if ($material == 1 || $material == 3) {
        $aset = filter_input(INPUT_POST, 'aset', FILTER_SANITIZE_STRING);
    }
    if ($material == 2 || $material == 3) {
        $penugasan = filter_input(INPUT_POST, 'penugasan', FILTER_SANITIZE_STRING);
        $nomor_spk = filter_input(INPUT_POST, 'spk', FILTER_SANITIZE_STRING);
        $vendor = filter_input(INPUT_POST, 'vendor', FILTER_SANITIZE_STRING);
    }
    if($material == 2){
        $tipe = filter_input(INPUT_POST, 'tipe', FILTER_VALIDATE_INT);
    }

    // Menggunakan Prepared Statements
    if ($material == 1) {        
        $stmt = $connect->prepare("INSERT INTO aset (nama, tgl_masuk, nomor_seri, id_lokasi, qty, qty_minimum, berat, id_satuan, id_material, id_klasifikasi, deskripsi, created_by, last_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssiidiiiisi", $aset, $tgl_masuk, $no_seri, $lokasi, $qty, $qty_min, $berat, $satuan, $material, $klasifikasi, $deskripsi, $id_user);
    } elseif($material == 2) {       
        // echo 123; 
        $stmt = $connect->prepare("INSERT INTO aset (tgl_masuk, nomor_seri, id_lokasi, qty, qty_minimum, berat, ketebalan, diameter, panjang, lebar, id_satuan, id_material, id_klasifikasi, id_tipe, id_vendor, id_penugasan, no_spk, deskripsi, created_by, last_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssiidddddiiiiiiissi", $tgl_masuk, $no_seri, $lokasi, $qty, $qty_min, $berat, $ketebalan, $diameter, $panjang, $lebar, $satuan, $material, $klasifikasi, $tipe, $vendor, $penugasan, $nomor_spk, $deskripsi, $id_user);
    }elseif($material == 3){
        $stmt = $connect->prepare("INSERT INTO aset (nama, tgl_masuk, nomor_seri, id_lokasi, qty, qty_minimum, berat, id_satuan, id_material, id_klasifikasi, id_vendor, id_penugasan, no_spk, deskripsi, created_by, last_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssiidiiiiiissi", $aset, $tgl_masuk, $no_seri, $lokasi, $qty, $qty_min, $berat, $satuan, $material, $klasifikasi, $vendor, $penugasan, $nomor_spk, $deskripsi, $id_user);
    }
    // echo 123;
    
    if ($stmt->execute()) {
        $last_id = $connect->insert_id;
        $nomor_aset = 'AST-' . str_pad($last_id, 6, '0', STR_PAD_LEFT);

        // Generate dan Simpan QR Code
        $namaFile = generateQrCode($nomor_aset);

        // Update nomor_aset dan qrcode
        $update_stmt = $connect->prepare("UPDATE aset SET nomor_aset = ?, qrcode = ? WHERE id_aset = ?");
        $update_stmt->bind_param("ssi", $nomor_aset, $namaFile, $last_id);
        $update_stmt->execute();

        // Simpan transaksi
        $simpanTransaksi_stmt = $connect->prepare("INSERT INTO transaksi (fk_id_aset, stok, tgl_tx, jenis_tx, created_by, last_created) VALUES (?, ?, ?, ?, ?, NOW())");
        $simpanTransaksi_stmt->bind_param("iissi", $last_id, $qty, $tgl_masuk, $jenis_tx, $id_user);
        $simpanTransaksi_stmt->execute();

        // Log aktivitas
        $log_stmt = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES (?, ?, ?, NOW(), ?, 'sim-barang', 'Transaksi In', ?)");
        $status_log = "User dengan ID ($id_user) menambahkan barang baru dengan ID ($last_id)";
        $log_stmt->bind_param("isssi", $id_user, $ip, $agent, $status_log, $last_id);
        $log_stmt->execute();
        
        $_SESSION['sukses'] = 'Berhasil Disimpan';
        header('Location: ' . ($material == 1 ? 'menu-consumable' : ($material == 2 ? 'menu-raw' : ($material == 3 ? 'menu-intermediate' : 'menu-intermediate'))));
        exit;
    } else {
        $_SESSION['gagal'] = 'Data Gagal Disimpan';
        error_log("Data gagal disimpan untuk ID Material: $material dengan ID User: $id_user\n", 3, '../error_log.txt');
        header('Location: ' . ($material == 1 ? 'menu-consumable' : ($material == 2 ? 'menu-raw' : ($material == 3 ? 'menu-intermediate' : 'menu-intermediate'))));
        exit;
    }

    $stmt->close();
    $connect->close();
} else {
    header('Location: 404');
}

exit();

  }else{
    header('Location: 404');
  }
?>