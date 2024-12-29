<?php
    error_reporting(0);
    session_start();
    if (isset($_POST['submit'])) {
    require 'koneksi.php';

    if (isset($_POST['username'])) { // check apakah ada pengiriman data
        $username = mysqli_real_escape_string($connect, $_POST['username']);
        $password = mysqli_real_escape_string($connect, md5($_POST['password']));

        $sql = "SELECT a.id as id, b.id as id_org, nama, b.kode_org, kode_org_2, kode_org_3, username, eselon, level, password FROM tbl_user a INNER JOIN tbl_organisasi b on a.kode_org=b.kode_org WHERE username = '$username' AND password = '$password' AND status='Aktif'";

        $query = mysqli_query($connect,$sql);
        // if ($_SESSION["vercode"] != $_POST["vercode"]) {
        //     $_SESSION['gagalVerif'] = 'Gagal Login';
        //     header('Location: index');  
        // }else{
        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc(); 
            // require "_header.php";
?>

<!doctype html>
<html lang="en">
<head>
  <title>SIM-Mutu</title>
  <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" name="viewport"/>
  <meta content="MJFauzy" name="author"/>
  <link rel="icon" href="images/Logo Yayasan.png">
  <link href="css/bootstrap_2.css" rel="stylesheet">
  <!-- Include IconCaptcha stylesheet -->
  <link href="css/icon-captcha.min.css" rel="stylesheet" type="text/css">
  <link href="assets/demo.css" rel="stylesheet" type="text/css">
  
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"> <img alt="Brand" src="images/Logo Yayasan.png" style="width: 40px;"> SIM-Mutu</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      <li><a href="index"><span class="glyphicon glyphicon-home"></span> Beranda</a></li>
      <li><a href="#" data-toggle="modal" data-target="#ModalProfil"><span class="glyphicon glyphicon-info-sign"></span> Profil</a></li>
      <li><a href="#" data-toggle="modal" data-target="#ModalTentangUjm"><span class="glyphicon glyphicon-bookmark"></span> Tentang</a></li> 
      <li><a href="#" data-toggle="modal" data-target="#ModalKontak"><span class="glyphicon glyphicon-earphone"></span> Kontak</a></li> 
      <li><a href="#" data-toggle="modal" data-target="#ModalLoginForm"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

        <!-- Banner -->
        <section id="banner">
        <div class="inner">
          <h2>SISTEM INFORMASI MANAJEMEN MUTU</h2>
          <h3> Yayasan Rumah Sakit Dr. Soetomo Surabaya</h3>
          <ul class="actions">
            <li><a href="#" data-toggle="modal" data-target="#ModalLoginForm" class="button big special"><span class="glyphicon glyphicon-log-in"></span> LOGIN</a></li>
            <!-- <li><a onClick="location.href='files/panduan/Panduan Aplikasi Rapat v2.pdf'"  target="_blank" class="button big alt"><span class="glyphicon glyphicon-book"></span> Panduan</a></li> -->
          </ul>
        </div>
            <!-- Modal Tentang UJM -->
            <center>
        <div id="myModalAccess" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="width:300px;">
            <div class="modal-content">
            <div class="modal-header">
                <div class="logo-login text-center" style="color: black">
                <h1 class="modal-title" style="font-size: 20px;">Pilih Unit Kerja</h1>
                </div>
            </div>
            <div class="modal-body" style="color: black; text-align:left;">
                <div class="row">
                <div class="col-md-12">
                    Mengakses website ini sebagai:<br><br>
                    
                    <form action="pilih" name="modal_popup" enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="id"  value="<?php echo $row['id']; ?>" />
                    <input type="hidden" name="username"  value="<?php echo $row['username']; ?>" />
                    <input type="hidden" name="password"  value="<?php echo $row['password']; ?>" />
                    <?php
                            if ($row['kode_org']!=null) {
                                $result_org = mysqli_query($connect, "SELECT kode_org, nama_org FROM tbl_organisasi WHERE kode_org='".$row['kode_org']."'");
                                if($result_org->num_rows > 0) {
                                    $row_org = $result_org->fetch_object();
                                    $nama_org = $row_org->nama_org;
                                    $kode_org = $row_org->kode_org;
                                    echo '<label class="radio-inline"><input type="radio" name="kode_org" value ="'.$kode_org.'" checked>'.$nama_org.'</label><br>';
                                }
                            } 
                            if ($row['kode_org_2']!=null) {
                                $result_org = mysqli_query($connect, "SELECT kode_org, nama_org FROM tbl_organisasi WHERE kode_org='".$row['kode_org_2']."'");
                                if($result_org->num_rows > 0) {
                                    $row_org = $result_org->fetch_object();
                                    $nama_org = $row_org->nama_org;
                                    $kode_org = $row_org->kode_org;
                                    echo '<label class="radio-inline"><input type="radio" name="kode_org" value ="'.$kode_org.'" checked>'.$nama_org.'</label><br>';
                                }
                            } 
                            if ($row['kode_org_3']!=null) {
                                $result_org = mysqli_query($connect, "SELECT kode_org, nama_org FROM tbl_organisasi WHERE kode_org='".$row['kode_org_3']."'");
                                if($result_org->num_rows > 0) {
                                    $row_org = $result_org->fetch_object();
                                    $nama_org = $row_org->nama_org;
                                    $kode_org = $row_org->kode_org;
                                    echo '<label class="radio-inline"><input type="radio" name="kode_org" value ="'.$kode_org.'" checked>'.$nama_org.'</label><br>';
                                }
                            }                     
                    ?>
                </div>
                </div>
            </div>
            <div class="modal-footer text-right">
                <button class="btn btn-success" name="submit" type="submit" title="Simpan Dokumen"><span class="glyphicon glyphicon-share-alt"></span> Lanjut</button>                
            </div>
            </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        </center>
        </section>


            

            <!-- 
                $id = $row['id'];
            $kode_org = $row['kode_org'];
            $result_org = mysqli_query($connect, "SELECT nama_org FROM tbl_organisasi WHERE kode_org='$kode_org'");
            if($result_org->num_rows > 0) {
                $row_org = $result_org->fetch_object();
                $nama_org = $row_org->nama_org;
            }
                $_SESSION['id'] = $id;
            $_SESSION['id_org'] = $row['id_org'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['kode_org'] = $row['kode_org'];
            $_SESSION['jabatan'] = $nama_org;
            $_SESSION['nama_org'] = $nama_org;
            $_SESSION['username'] = $row['username'];
            $_SESSION['eselon'] = $row['eselon'];
            $_SESSION['level'] = $row['level'];

            $date = date('d-m-Y, H:i:s');

            $save_last_login = mysqli_query($connect,"UPDATE tbl_user SET last_login='$date' WHERE username='$username' AND id='$id'");

            if($_SESSION['level'] == 'admin') {
                echo "<meta http-equiv='refresh' content='0; url=on-admin'>";
            } elseif($_SESSION['level'] == 'user') {
                echo "<meta http-equiv='refresh' content='0; url=on-user'>";
            } else {
                echo "<script>window.location.href='index.php'</script>";
            } -->
            
    <?php
    require "_footer.php";
        } else {
            $_SESSION['gagalLogin'] = 'Gagal Login';
            header('Location: index');  
        }
        $connect->close();
        exit();  
    // }
}
}else {
    $_SESSION['aksesTolak'] = 'Akses ditolak!';
    header('Location: index');
}
?>