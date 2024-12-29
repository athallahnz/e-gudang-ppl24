<?php 
  session_start();
  require 'koneksi.php';
  error_reporting(0);

  $agent=$_SERVER['HTTP_USER_AGENT'];
  $ip=$_SERVER['REMOTE_ADDR'];

if (isset($_POST['submit'])) {
  $username = mysqli_real_escape_string($connect, $_POST['username']);
  $password = mysqli_real_escape_string($connect, $_POST['password']);
  
  $sql = "SELECT a.id as id, b.id as id_org, nama, b.kode_org, username, eselon, level, level_rapat, password, email FROM tbl_user a INNER JOIN tbl_organisasi b on a.kode_org=b.kode_org WHERE username = '$username' AND password = '$password' AND status='Aktif'";
 
  $query = mysqli_query($connect,$sql);
  if ($query->num_rows > 0) {

    $row = $query->fetch_assoc();
    $id = $_POST['id'];
    $kode_org = $_POST['kode_org'];

    $result_org = mysqli_query($connect, "SELECT nama_org FROM tbl_organisasi WHERE kode_org = '$kode_org'");
    if($result_org->num_rows > 0) {
        $row_org = $result_org->fetch_object();
        $nama_org = $row_org->nama_org;
    }

    date_default_timezone_set('Asia/Jakarta');
    
    $simpan = mysqli_query($connect,"INSERT INTO tbl_userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi) VALUES ($id, '$ip', '$agent', now(), 'Berhasil Login', 'sim-aset')");

    $_SESSION['id']       = $id;
    $_SESSION['id_org']   = $row['id_org'];
    $_SESSION['nama']     = $row['nama'];
    $_SESSION['kode_org'] = $kode_org;
    $_SESSION['jabatan']  = $nama_org;
    $_SESSION['nama_org'] = $nama_org;
    $_SESSION['username'] = $row['username'];
    $_SESSION['email']    = $row['email'];
    $_SESSION['password'] = $row['password'];
    $_SESSION['eselon']   = $row['eselon'];
    $_SESSION['level']    = $row['level'];
    $_SESSION['level_rapat']    = $row['level_rapat'];

    $date = date('d-m-Y, H:i:s');

    $save_last_login = mysqli_query($connect,"UPDATE tbl_user SET last_login='$date' WHERE username='$username' AND id='$id'");
    
    echo "<meta http-equiv='refresh' content='0; url=on-user'>";
    // if($_SESSION['level'] == 'admin') {
    //     echo "<meta http-equiv='refresh' content='0; url=on-admin'>";
    // } elseif($_SESSION['level'] == 'user') {
    //     echo "<meta http-equiv='refresh' content='0; url=on-user'>";
    // } else {
    //     echo "<script>window.location.href='index.php'</script>";
    // }
  }

  $connect->close();
    exit();  
}else {
	echo "<script>alert('Akses ditolak!');</script>";
	echo "<meta http-equiv='refresh' content='0; url=index.php'>";
}
?>
 
