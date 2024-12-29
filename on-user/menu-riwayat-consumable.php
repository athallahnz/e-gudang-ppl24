<?php
error_reporting(0);
session_start();
include "../koneksi.php";
include "../random-v2.php";
include "../tgl_indo.php";
$m_active_page = "barang";
$active_page   = "barang_consumable";  

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

// Menggunakan prepared statement untuk menghindari SQL Injection
// beda stmt
$tambahan = "";

$query = $connect->prepare("SELECT * FROM aset WHERE id_aset = ?");
$query->bind_param("i", $id_satuan);
$query->execute();
$hasil = $query->get_result();
$row1 = $hasil->fetch_object();
if ($row1->id_material ==2) {
  $addQuery=", e.nama as tipe, ketebalan, diameter, panjang, lebar, a.id_klasifikasi as id_klasifikasi";
  $addQuery2="INNER JOIN tipe_aset e on a.id_tipe=e.id ";
} else {
  $addQuery="";
  $addQuery2="";
}

$stmt = $connect->prepare("SELECT id_aset, nomor_aset, a.nama as barang, b.nama as satuan, qty, c.nama as klasifikasi, a.id_material as id_material, d.nama as material $addQuery FROM aset a 
INNER JOIN satuan_aset b ON a.id_satuan=b.id 
INNER JOIN klasifikasi_aset c on a.id_klasifikasi=c.id 
INNER JOIN material_aset d on a.id_material=d.id 
$addQuery2
WHERE id_aset = ?");

$stmt->bind_param("i", $id_satuan);
$stmt->execute();
$result_dokumen = $stmt->get_result();

if ($result_dokumen && $result_dokumen->num_rows > 0) {
  $row_dokumen = $result_dokumen->fetch_object();
  $id_aset = htmlspecialchars($row_dokumen->id_aset);

} else {
    // Jika data tidak ditemukan
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
          <li class="breadcrumb-item"><a href="menu-transaksi-in">Transaksi Masuk</a></li>
          <li class="breadcrumb-item active">Riwayat Barang</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Ringkasan Barang</h5>
              <!-- Hidden field for ID -->
              <input type="hidden" name="id" value="<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>" />

              <div class="col-md-12 mb-2">
                <label for="nama" class="form-label">Nomor Barang </label>
                <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->nomor_aset); ?>" disabled>
              </div>

              <div class="col-md-12 mb-2">
                <label for="nama" class="form-label">Nama Barang </label>
                <?php
                if ($row_dokumen->id_material == 1 || $row_dokumen->id_material == 3) {
                  echo '<input type="text" class="form-control" name="nama" value="'.htmlspecialchars($row_dokumen->barang).'" disabled>';
                } else {
                  if ($row_dokumen->id_klasifikasi==141 || $row_dokumen->id_klasifikasi==143) {
                    $hasilInput = $row_dokumen->tipe." (".$row_dokumen->ketebalan." x ".$row_dokumen->panjang." x ".$row_dokumen->lebar.")";
                  } else {
                    $hasilInput = $row_dokumen->tipe." (".$row_dokumen->diameter." x ".$row_dokumen->panjang.")";
                  }  
                  echo '<input type="text" class="form-control" name="nama" value="'.$hasilInput.'" disabled>';
                }
                
                ?>
              </div>

              <div class="col-md-12 mb-2">
                <label for="nama" class="form-label">Stok Terkini </label>
                <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->qty)." ".htmlspecialchars($row_dokumen->satuan); ?>" disabled>
              </div>
              <div class="col-md-12 mb-2">
                <label for="nama" class="form-label">Jenis Klasifikasi Material </label>
                <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->klasifikasi)." (".htmlspecialchars($row_dokumen->material).")"; ?>" disabled>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </section>

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

          <table class="table table-bordered table-striped example1">
            <thead>
              <tr>
                <th scope="col">No.</th>
                <th scope="col">Tgl. Transaksi</th>
                <th scope="col">Aktor</th>
                <th scope="col">Stok</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $no = 0;
                $result_dokumen = mysqli_query($connect,"SELECT id_aset, a.nama, tgl_masuk, nomor_aset, b.nama as material, qrcode, stok, jenis_tx, e.last_created, f.nama as users, e.last_created
                FROM aset a 
                INNER JOIN material_aset b on a.id_material=b.id
                INNER JOIN transaksi e on a.id_aset=e.fk_id_aset
                INNER JOIN user f on f.id=e.created_by
                WHERE id_aset=$id_aset order by e.last_created desc");

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
                <td><?php echo $row_dokumen->users?></td>
                <td><?php 
                
                if ($row_dokumen->jenis_tx == 'Masuk') {
                  $info2 = "<span class='badge bg-success'>Masuk</span>";
                } else {
                  $info2 = "<span class='badge bg-danger'>Keluar</span>";
                }

                echo $minus.$row_dokumen->stok." ".$row_dokumen->satuan."<br>".$info2;

                
                ?></td>
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
            <table class="table table-striped dt-responsive nowrap example1" style="width:100%;">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Tgl. Transaksi</th>
                  <th scope="col">Aktor</th>
                  <th scope="col">Stok</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT id_aset, a.nama, tgl_masuk, nomor_aset, b.nama as material, qrcode, stok, jenis_tx, e.last_created, f.nama as users, e.last_created
                  FROM aset a 
                  INNER JOIN material_aset b on a.id_material=b.id
                  INNER JOIN transaksi e on a.id_aset=e.fk_id_aset
                  INNER JOIN user f on f.id=e.created_by
                  WHERE id_aset=$id_aset AND jenis_tx='Masuk' order by e.last_created desc");

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
                  <td><?php echo $row_dokumen->users?></td>
                  <td><?php 
                  
                  if ($row_dokumen->jenis_tx == 'Masuk') {
                    $info2 = "<span class='badge bg-success'>Masuk</span>";
                  } else {
                    $info2 = "<span class='badge bg-danger'>Keluar</span>";
                  }

                  echo $minus.$row_dokumen->stok." ".$row_dokumen->satuan."<br>".$info2;

                  
                  ?></td>
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
          <table class="table table-striped dt-responsive nowrap example1" style="width:100%;">
            <thead>
              <tr>
                <th scope="col">No.</th>
                <th scope="col">Tgl. Transaksi</th>
                <th scope="col">Aktor</th>
                <th scope="col">Stok</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $no = 0;
                $result_dokumen = mysqli_query($connect,"SELECT id_aset, a.nama, tgl_masuk, nomor_aset, b.nama as material, qrcode, stok, jenis_tx, e.last_created, f.nama as users, e.last_created
                FROM aset a 
                INNER JOIN material_aset b on a.id_material=b.id
                INNER JOIN transaksi e on a.id_aset=e.fk_id_aset
                INNER JOIN user f on f.id=e.created_by
                WHERE id_aset=$id_aset AND jenis_tx='Keluar' order by e.last_created desc");

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
                <td><?php echo $row_dokumen->users?></td>
                <td><?php 
                
                if ($row_dokumen->jenis_tx == 'Masuk') {
                  $info2 = "<span class='badge bg-success'>Masuk</span>";
                } else {
                  $info2 = "<span class='badge bg-danger'>Keluar</span>";
                }

                echo $minus.$row_dokumen->stok." ".$row_dokumen->satuan."<br>".$info2;

                
                ?></td>
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