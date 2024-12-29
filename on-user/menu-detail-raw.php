<?php
error_reporting(0);
session_start();
include "../koneksi.php";
include "../random-v2.php";
include "../tgl_indo.php";

$m_active_page = "barang";
$active_page   = "barang_raw"; 

// Pastikan ID satuan tersedia di URL dan valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: 404');
    exit();
}

// Menggunakan fungsi decrypt untuk mendapatkan ID satuan
$id_satuan = encrypt_decrypt2('decrypt', $_GET['id']);

if (!$id_satuan) {
    header('Location: 404');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include "_head.php"; ?>

<body>

  <!-- ======= Header ======= -->
  <?php include "_header.php"; ?>
  <!-- ======= Sidebar ======= -->
  <?php include "_sidebar.php"; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Detail Barang</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="menu-raw">Barang Raw</a></li>
          <li class="breadcrumb-item active">Detail Barang</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <?php include "section-detail-raw.php"; ?>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Daftar Ukuran</h5>
        <table id="example" class="table table-bordered table-striped example">
          <thead>
            <tr>
              <th scope="col">No.</th>
              <th scope="col">Tgl Masuk</th>
              <th scope="col">Ukuran</th>
              <th scope="col">Stok</th>
              <th scope="col">Vendor</th>
              <th scope="col">Penugasan</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $no = 0;
              $result_dokumen = mysqli_query($connect,"SELECT a.id, qty, a.last_created, a.last_created, panjang, lebar, diameter, ketebalan, c.nama as vendor, d.nama as penugasan
              FROM aset_raw a 
              INNER JOIN vendor_aset c on a.id_vendor=c.id 
              INNER JOIN penugasan_aset d on a.id_penugasan=d.id
              WHERE id_tipe=$id_aset order by a.last_created desc");

              // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

              if($result_dokumen) {
                if($result_dokumen->num_rows > 0) {
                  while ($row_ukuran = $result_dokumen->fetch_object()) {
                      $no++;
                      $info1 = format_hari_tanggal2($row_ukuran->last_created);
            ?>
            <tr>
              <th scope="row"><?php echo "$no."; ?></th>
              <td><?php echo $info1;?></td>
              <td>
                <?php 
                  if ($row_dokumen->id_klasifikasi == 141 || $row_dokumen->id_klasifikasi == 143) {
                    echo $row_ukuran->ketebalan." x ".$row_ukuran->panjang." x ".$row_ukuran->lebar;
                  } elseif ($row_dokumen->id_klasifikasi == 142) {
                    echo $row_ukuran->diameter." x ".$row_ukuran->panjang;
                  }
                ?></td>
              <td><?php echo $row_ukuran->qty." ".$row_ukuran->satuan ?></td>
              <td><?php echo $row_ukuran->vendor ?></td>
              <td><?php echo $row_ukuran->penugasan ?></td>
            </tr>
            <?php
                  }
                } 
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include "_footer.php"; ?>
  
  <!-- Handling session success/failure messages -->
  <?php if (isset($_SESSION['sukses'])) { ?>
    <script>
        Swal.fire("Terima Kasih!", "<?php echo $_SESSION['sukses']; ?>", "success");
    </script>
    <?php unset($_SESSION['sukses']); } ?>

  <?php if (isset($_SESSION['gagal'])) { ?>
    <script>
        Swal.fire("Mohon Maaf!", "<?php echo $_SESSION['gagal']; ?>", "error");
    </script>
    <?php unset($_SESSION['gagal']); } ?>

</body>
</html>

<?php
// Tutup koneksi dan statement
$stmt->close();
// $stmt2->close();
$connect->close();
?>