<?php
    error_reporting(0);
    session_start();
    include "koneksi.php";
    include "random-v2.php";

    // echo encrypt_decrypt2('encrypt', 17179);

    $agent=$_SERVER['HTTP_USER_AGENT'];
    $ip=$_SERVER['REMOTE_ADDR'];

    date_default_timezone_set('Asia/Jakarta');
    $id = encrypt_decrypt2('decrypt', $_GET['id']);

    if (isset($id)) {

        $result_rapat = mysqli_query($connect, "SELECT * FROM tbl_peserta_rapat WHERE id_peserta=$id");
        if ($result_rapat) {
            if ($result_rapat->num_rows > 0) {
                $row_rapat  = $result_rapat->fetch_object();
                $id_peserta = $row_rapat->fk_id_peserta;
                $id_rapat = $row_rapat->fk_id_rapat;
            }
        }
        $update = mysqli_query($connect, "UPDATE tbl_peserta_rapat SET hadir_rapat=1, last_edited=now() WHERE id_peserta='$id'");	

	    $logs = mysqli_query($connect,"INSERT INTO tbl_userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES ($id_peserta, '$ip', '$agent', now(), 'User dengan ID ($id_peserta) mengisi presensi rapat dengan ID ($id_rapat)', 'sim-mutu', 'Presensi Rapat', $id_rapat)");

        if ($update && $logs) {
            $_SESSION['sukses-verif'] = 'Telah Menghadiri Rapat';
            header('Location: index');  
        } else {
            $_SESSION['gagal-verif'] = 'Data Gagal Disimpan';
            header('Location: index');  
        }
    } else {
       header('Location: 404');
    }

    // Mendapatkan protokol
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    // Mendapatkan hostname
    $hostname = $_SERVER['HTTP_HOST'];
    // Mendapatkan URI
    $uri = $_SERVER['REQUEST_URI'];
    // Menggabungkan semuanya untuk mendapatkan URL lengkap
    $full_url = $protocol . $hostname . $uri;
    echo $full_url;
    
?>