<?php
    session_start();
    error_reporting(0);
    require '../koneksi.php';
    //include_once '../gpconfig.php';
    
    $agent=$_SERVER['HTTP_USER_AGENT'];
    $ip=$_SERVER['REMOTE_ADDR'];


    $id=$_SESSION['id'];

    date_default_timezone_set('Asia/Jakarta');
        
    $simpan = mysqli_query($connect, "INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi) VALUES ($id, '$ip', '$agent', now(), 'Berhasil Logout', 'sim-aset')");

    // $gclient->revokeToken();

    session_destroy();

    header('location:../index');
    exit();

?>