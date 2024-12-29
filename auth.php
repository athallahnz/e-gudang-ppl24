<?php
// Include file gpconfig
include_once 'gpconfig.php';

if(isset($_GET['code'])){
	$gclient->authenticate($_GET['code']);
	$_SESSION['accessToken'] = $gclient->getAccessToken();
	header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['accessToken'])) {
	$gclient->setAccessToken($_SESSION['accessToken']);
}

if ($gclient->getAccessToken()) {
	include 'koneksi.php';

	// Get user profile data from google
	$gpuserprofile = $google_oauthv2->userinfo->get();

	$nama = $gpuserprofile['given_name']." ".$gpuserprofile['family_name']; // Ambil nama dari Akun Google
	$email = $gpuserprofile['email']; // Ambil email Akun Google nya

	// Buat query untuk mengecek apakah data user dengan email tersebut sudah ada atau belum
	// Jika ada, ambil id, username, dan nama dari user tersebut
	$sql = mysqli_query($connect, "SELECT a.id as id, b.id as id_org, nama, b.kode_org, kode_org_2, kode_org_3, username, eselon, level, password, email FROM tbl_user a INNER JOIN tbl_organisasi b on a.kode_org=b.kode_org WHERE status='Aktif' AND email='".$email."'");
	$row = mysqli_fetch_array($sql); // Ambil datanya dari hasil query tadi

	if(empty($row)){ // Jika User dengan email tersebut belum ada
		// Ambil username dari kata sebelum simbol @ pada email
		// $ex = explode('@', $email); // Pisahkan berdasarkan "@"
		// $username = $ex[0]; // Ambil kata pertama

		// // Lakukan insert data user baru tanpa password
		// mysqli_query($connect, "INSERT INTO tbl_user(username, nama, email) VALUES('".$username."', '".$nama."', '".$email."')");

		// $id = mysqli_insert_id($connect); // Ambil id user yang baru saja di insert
		session_destroy(); // Hapus semua session
        header('Location: index');  
	}else{
		require "menu-check-login.php";
		// header("location: menu_check_login.php");
		// require "menu_check_login.php";

		// $id = $row['id']; // Ambil id pada tabel user
		// $username = $row['username']; // Ambil username pada tabel user
		// $nama = $row['nama']; // Ambil username pada tabel user
	}

	$_SESSION['id'] = $id;
	$_SESSION['username'] = $username;
	$_SESSION['nama'] = $nama;
    $_SESSION['email'] = $email;
	
    // header("location: welcome.php");
} else {
	$authUrl = $gclient->createAuthUrl();
	header("location: ".$authUrl);
}
?>
