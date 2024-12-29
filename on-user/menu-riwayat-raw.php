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
      <h1>Riwayat Barang</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="menu-raw">Barang Raw</a></li>
          <li class="breadcrumb-item active">Riwayat Barang</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <?php include "section-detail-raw.php"; ?>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Riwayat Barang</h5>

        <!-- Bordered Tabs Justified -->
        <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">Semua</button>
          </li>
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Masuk</button>
          </li>
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Keluar</button>
          </li>
        </ul>
        <div class="tab-content pt-2" id="borderedTabJustifiedContent">
          <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel" aria-labelledby="home-tab">
            <table id="example1" class="table table-bordered table-striped example">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Tgl Transaksi</th>
                  <th scope="col">Tipe</th>
                  <th scope="col">Stok</th>
                  <th scope="col">Vendor</th>
                  <th scope="col">Penugasan</th>
                  <th scope="col">Aktor</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT a.id, nama_tipe, panjang, lebar, diameter, ketebalan, d.nama as vendor, e.nama as penugasan, stok, b.id_klasifikasi as id_klasifikasi, f.nama as users, jenis_tx, a.last_created as last_created
                  FROM transaksi_raw a 
                  INNER JOIN tipe_raw b on a.fk_id_tipe=b.id
                  INNER JOIN aset_raw c on a.fk_id_aset=c.id
                  INNER JOIN vendor_aset d on c.id_vendor=d.id 
                  INNER JOIN penugasan_aset e on c.id_penugasan=e.id
                  INNER JOIN user f on a.created_by=f.id
                  WHERE id_tipe=$id_aset order by a.last_created desc");

                  // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                  if($result_dokumen) {
                    if($result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                          $no++;
                          if ($row_dokumen->jenis_tx == 'Masuk') {
                            $info1 = "<span class='badge bg-success'>".format_hari_tanggal2($row_dokumen->last_created)."</span>";
                            $minus = "";
                          } else {
                            $info1 = "<span class='badge bg-danger'>".format_hari_tanggal2($row_dokumen->last_created)."</span>";
                            $minus = "-";
                          }
                ?>
                <tr>
                  <th scope="row"><?php echo "$no."; ?></th>
                  <td><?php echo $info1;?></td>
                  <td>
                    <?php 
                      echo $row_dokumen->nama_tipe."<br><span class='badge bg-primary'>";
                      if ($row_dokumen->id_klasifikasi == 141 || $row_dokumen->id_klasifikasi == 143) {
                        echo $row_dokumen->ketebalan." x ".$row_dokumen->panjang." x ".$row_dokumen->lebar;
                      } elseif ($row_dokumen->id_klasifikasi == 142) {
                        echo $row_dokumen->diameter." x ".$row_dokumen->panjang;
                      }
                    ?></span></td>
                  <td>
                    <?php                 
                      if ($row_dokumen->jenis_tx == 'Masuk') {
                        $info2 = "<span class='badge bg-success'>Masuk</span>";
                      } else {
                        $info2 = "<span class='badge bg-danger'>Keluar</span>";
                      }
                      echo $minus.$row_dokumen->stok." ".$row_dokumen->satuan."<br>".$info2;                
                    ?></td>
                  <td><?php echo $row_dokumen->vendor?></td>
                  <td><?php echo $row_dokumen->penugasan?></td>
                  <td><?php echo $row_dokumen->users?></td>
                </tr>
                <?php
                      }
                    } 
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">
            <table id="example2" class="table table-bordered table-striped example">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Tgl Transaksi</th>
                  <th scope="col">Tipe</th>
                  <th scope="col">Stok</th>
                  <th scope="col">Vendor</th>
                  <th scope="col">Penugasan</th>
                  <th scope="col">Aktor</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT a.id, nama_tipe, panjang, lebar, diameter, ketebalan, d.nama as vendor, e.nama as penugasan, stok, b.id_klasifikasi as id_klasifikasi, f.nama as users, jenis_tx, a.last_created as last_created
                  FROM transaksi_raw a 
                  INNER JOIN tipe_raw b on a.fk_id_tipe=b.id
                  INNER JOIN aset_raw c on a.fk_id_aset=c.id
                  INNER JOIN vendor_aset d on c.id_vendor=d.id 
                  INNER JOIN penugasan_aset e on c.id_penugasan=e.id
                  INNER JOIN user f on a.created_by=f.id
                  WHERE id_tipe=$id_aset AND jenis_tx='Masuk' order by a.last_created desc");

                  // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                  if($result_dokumen) {
                    if($result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                          $no++;
                            $info1 = "<span class='badge bg-success'>".format_hari_tanggal2($row_dokumen->last_created)."</span>";
                            $minus = "";
                ?>
                <tr>
                  <th scope="row"><?php echo "$no."; ?></th>
                  <td><?php echo $info1;?></td>
                  <td>
                    <?php 
                      echo $row_dokumen->nama_tipe."<br><span class='badge bg-primary'>";
                      if ($row_dokumen->id_klasifikasi == 141 || $row_dokumen->id_klasifikasi == 143) {
                        echo $row_dokumen->ketebalan." x ".$row_dokumen->panjang." x ".$row_dokumen->lebar;
                      } elseif ($row_dokumen->id_klasifikasi == 142) {
                        echo $row_dokumen->diameter." x ".$row_dokumen->panjang;
                      }
                    ?></span></td>
                  <td>
                    <?php                 
                      $info2 = "<span class='badge bg-success'>Masuk</span>";
                      echo $minus.$row_dokumen->stok." ".$row_dokumen->satuan."<br>".$info2;                
                    ?></td>
                  <td><?php echo $row_dokumen->vendor?></td>
                  <td><?php echo $row_dokumen->penugasan?></td>
                  <td><?php echo $row_dokumen->users?></td>
                </tr>
                <?php
                      }
                    } 
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="bordered-justified-contact" role="tabpanel" aria-labelledby="contact-tab">
            <table id="example3" class="table table-bordered table-striped example">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Tgl Transaksi</th>
                  <th scope="col">Tipe</th>
                  <th scope="col">Stok</th>
                  <th scope="col">Vendor</th>
                  <th scope="col">Penugasan</th>
                  <th scope="col">Aktor</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT a.id, nama_tipe, panjang, lebar, diameter, ketebalan, d.nama as vendor, e.nama as penugasan, stok, b.id_klasifikasi as id_klasifikasi, f.nama as users, jenis_tx, a.last_created as last_created
                  FROM transaksi_raw a 
                  INNER JOIN tipe_raw b on a.fk_id_tipe=b.id
                  INNER JOIN aset_raw c on a.fk_id_aset=c.id
                  INNER JOIN vendor_aset d on c.id_vendor=d.id 
                  INNER JOIN penugasan_aset e on c.id_penugasan=e.id
                  INNER JOIN user f on a.created_by=f.id
                  WHERE id_tipe=$id_aset AND jenis_tx='Keluar' order by a.last_created desc");

                  // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                  if($result_dokumen) {
                    if($result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                          $no++;
                            $info1 = "<span class='badge bg-danger'>".format_hari_tanggal2($row_dokumen->last_created)."</span>";
                            $minus = "-";
                ?>
                <tr>
                  <th scope="row"><?php echo "$no."; ?></th>
                  <td><?php echo $info1;?></td>
                  <td>
                    <?php 
                      echo $row_dokumen->nama_tipe."<br><span class='badge bg-primary'>";
                      if ($row_dokumen->id_klasifikasi == 141 || $row_dokumen->id_klasifikasi == 143) {
                        echo $row_dokumen->ketebalan." x ".$row_dokumen->panjang." x ".$row_dokumen->lebar;
                      } elseif ($row_dokumen->id_klasifikasi == 142) {
                        echo $row_dokumen->diameter." x ".$row_dokumen->panjang;
                      }
                    ?></span></td>
                  <td>
                    <?php                 
                      $info2 = "<span class='badge bg-danger'>Keluar</span>";
                      echo $minus.$row_dokumen->stok." ".$row_dokumen->satuan."<br>".$info2;                
                    ?></td>
                  <td><?php echo $row_dokumen->vendor?></td>
                  <td><?php echo $row_dokumen->penugasan?></td>
                  <td><?php echo $row_dokumen->users?></td>
                </tr>
                <?php
                      }
                    } 
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div><!-- End Bordered Tabs Justified -->

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