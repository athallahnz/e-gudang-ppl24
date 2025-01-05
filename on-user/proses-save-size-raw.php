<?php
// Tingkatkan Keamanan
error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../error_log.txt');
session_start();
include "../koneksi.php";
include "../random-v2.php";

if ($_SESSION['level'] == 'admin') {
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

    
    $id_tipe = htmlspecialchars(encrypt_decrypt2('decrypt', $_POST['id_tipe']));
    
    $ketebalan = htmlspecialchars($_POST['ketebalan']);
    $diameter = htmlspecialchars($_POST['diameter']);
    $panjang = htmlspecialchars($_POST['panjang']);
    $lebar = htmlspecialchars($_POST['lebar']);
    
    $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
    $penugasan = filter_input(INPUT_POST, 'penugasan', FILTER_VALIDATE_INT);
    $nomor_spk = filter_input(INPUT_POST, 'spk', FILTER_SANITIZE_STRING);
    $vendor = filter_input(INPUT_POST, 'vendor', FILTER_VALIDATE_INT);
    $deskripsi = filter_input(INPUT_POST, 'deskripsi', FILTER_SANITIZE_STRING);
    
    $jenis_tx = "Masuk";
    
    if ($ketebalan == "others") {
        $ketebalan_lainnya = number_format(floatval($_POST['ketebalan_lainnya']), 2, '.', '');;

        // Tambah ketebalan di master uk_tebal
        $stmt2 = $connect->prepare("INSERT INTO uk_ketebalan (ketebalan, created_by, last_created) 
        VALUES (?, ?, NOW())");
        $stmt2->bind_param("di", $ketebalan_lainnya, $id_user);
        $ketebalan = $ketebalan_lainnya;
        // Jika eksekusi gagal
        if (!$stmt2->execute()) {
            $_SESSION['gagal'] = 'Data Gagal Disimpan';
            header('Location: menu-raw');
            exit;
        }
    }
    
    
    if ($lebar == "others") {
        $lebar_lainnya = number_format(floatval($_POST['lebar_lainnya']), 2, '.', '');;

        // $stmtCheck2 = $connect->prepare("SELECT COUNT(*) FROM uk_lebar WHERE lebar = ?");
        // $stmtCheck2->bind_param("d", $lebar_lainnya);
        // $stmtCheck2->execute();
        // $stmtCheck2->bind_result($count2);
        // $stmtCheck2->fetch();

        // if ($count2 > 0) {
        //     $_SESSION['gagal'] = 'Ukuran lebar sudah ada sebelumnya.';
        //     header('Location: menu-raw');
        //     exit;
        // }
        // Tambah lebar di master uk_tebal
        $stmt2 = $connect->prepare("INSERT INTO uk_lebar (lebar, created_by, last_created) 
        VALUES (?, ?, NOW())");
        $stmt2->bind_param("di", $lebar_lainnya, $id_user);
        $lebar = $lebar_lainnya;
        // Jika eksekusi gagal
        if (!$stmt2->execute()) {
            $_SESSION['gagal'] = 'Data Gagal Disimpan';
            header('Location: menu-raw');
            exit;
        }
    }
    if ($panjang == "others") {
        // Periksa apakah ukuran panjang sudah ada
        $panjang_lainnya = number_format(floatval($_POST['panjang_lainnya']), 2, '.', '');
    
        // $stmtCheck3 = $connect->prepare("SELECT COUNT(*) FROM uk_panjang WHERE panjang = ?");
        // $stmtCheck3->bind_param("d", $panjang_lainnya);
        // $stmtCheck3->execute();
        // $stmtCheck3->bind_result($count3);
        // $stmtCheck3->fetch();

        // if ($count3 > 0) {
        //     $_SESSION['gagal'] = 'Ukuran panjang sudah ada sebelumnya.';
        //     header('Location: menu-raw');
        //     exit;
        // }
        // Tambah panjang di master uk_tebal
        $query3 = "INSERT INTO uk_panjang (panjang, created_by, last_created) 
        VALUES ($panjang_lainnya, $id_user, NOW())";

        $stmt3 = $connect->prepare("INSERT INTO uk_panjang (panjang, created_by, last_created) 
        VALUES (?, ?, NOW())");
        $stmt3->bind_param("di", $panjang_lainnya, $id_user);
        $panjang = $panjang_lainnya;
        // Jika eksekusi gagal
        // echo "Panjang lainnya: $panjang_lainnya, User ID: $id_user";
        // echo $query3;
        
        if (!$stmt3->execute()) {
            $_SESSION['gagal'] = 'Data Gagal Disimpan';
            header('Location: menu-raw');
            exit;
        }
    }

    if ($diameter == "others") {
        $diameter_lainnya = number_format(floatval($_POST['diameter_lainnya']), 2, '.', '');;
        
        // $stmtCheck4 = $connect->prepare("SELECT COUNT(*) FROM uk_diameter WHERE diameter = ?");
        // $stmtCheck4->bind_param("d", $diameter_lainnya);
        // $stmtCheck4->execute();
        // $stmtCheck4->bind_result($count4);
        // $stmtCheck4->fetch();

        // if ($count4 > 0) {
        //     $_SESSION['gagal'] = 'Ukuran diameter sudah ada sebelumnya.';
        //     header('Location: menu-raw');
        //     exit;
        // }
        // Tambah diameter di master uk_tebal
        $stmt4 = $connect->prepare("INSERT INTO uk_diameter (diameter, created_by, last_created) 
        VALUES (?, ?, NOW())");
        $stmt4->bind_param("di", $diameter_lainnya, $id_user);
        $diameter = $diameter_lainnya;
        // Jika eksekusi gagal
        if (!$stmt4->execute()) {
            $_SESSION['gagal'] = 'Data Gagal Disimpan';
            header('Location: menu-raw');
            exit;
        }
    }
    
    // Menggunakan Prepared Statements
    $stmt = $connect->prepare("INSERT INTO aset_raw (id_tipe, ketebalan, diameter, panjang, lebar, id_vendor, id_penugasan, no_spk, deskripsi, qty, created_by, last_created) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iddddiissii", $id_tipe, $ketebalan, $diameter, $panjang, $lebar, $vendor, $penugasan, $nomor_spk, $deskripsi, $qty, $id_user);
    if ($stmt->execute()) {;
        $last_id = $connect->insert_id;
        
        // Simpan transaksi
        $simpanTransaksi_stmt = $connect->prepare("INSERT INTO transaksi_raw (fk_id_tipe, fk_id_aset, stok, jenis_tx, created_by, last_created) VALUES (?, ?, ?, ?, ?, NOW())");
        $simpanTransaksi_stmt->bind_param("iiisi", $id_tipe, $last_id, $qty, $jenis_tx, $id_user);
        $simpanTransaksi_stmt->execute();

        // Log aktivitas
        $log_stmt = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES (?, ?, ?, NOW(), ?, 'sim-barang', 'Transaksi In Raw', ?)");
        $status_log = "User dengan ID ($id_user) menambahkan barang raw baru dengan ID ($last_id)";
        $log_stmt->bind_param("isssi", $id_user, $ip, $agent, $status_log, $last_id);
        $log_stmt->execute();
        
        $_SESSION['sukses'] = 'Berhasil Disimpan';
        header('Location: menu-raw');
        exit;
    } else {
        $_SESSION['gagal'] = 'Data Gagal Disimpan';
        error_log("Data gagal disimpan untuk ID Material: $material dengan ID User: $id_user\n", 3, '../error_log.txt');
        header('Location: menu-raw');
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