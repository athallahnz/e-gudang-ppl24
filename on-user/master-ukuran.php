<?php
  error_reporting(0);
  session_start();
  include "../koneksi.php";
  include "../random-v2.php";
  include "../tgl_indo.php";
  $m_active_page = "data-master";
  $active_page   = "master-ukuran";
      // Periksa apakah user sudah login dan memiliki level admin
  if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
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
      <h1>Ukuran Barang</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Data Master</a></li>
          <li class="breadcrumb-item active">Ukuran Barang</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Ukuran Barang</h5>

        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalpanjang"><i class="bi bi-plus me-1" ></i> Ukuran Baru</button>
         <!-- Modal Ukuran Baru -->
         <div class="modal fade" id="modalpanjang" tabindex="-1" data-bs-backdrop="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Tambah Ukuran Baru</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form class="row g-3 needs-validation" method="POST" action="proses-save-ukuran" enctype="multipart/form-data" id="devel-generate-content-form" novalidate>
                    <?php
                    $_SESSION['token'] = bin2hex(random_bytes(35));
                    ?>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                    <input type="hidden" name="ukuran" value="panjang">

                    <div class="col-md-12 mb-2">
                      <label for="validationTooltip04" class="form-label">Jenis Ukuran <span class="text-danger">*</span></label>
                      <select name="jenis" class="form-select" required>
                        <option value='Panjang'>Panjang</option>
                        <option value='Lebar'>Lebar</option>
                        <option value='Diameter'>Diameter</option>
                        <option value='Ketebalan'>Ketebalan</option>
                      </select>
                      <div class="invalid-feedback">
                        Wajib Diisi
                      </div>
                    </div>
                
                    <div class="col-md-12 mb-2">
                      <label for="ukuran" class="form-label">Ukuran <span class="text-danger">*</span></label>
                      <div class="input-group">
                          <input type="number" step="0.01" name="ukuran" class="form-control" placeholder="Ukuran" required>
                          <span class="input-group-text">mm</span> <!-- Menambahkan tulisan "mm" di sebelah kanan -->
                      </div>
                      <div class="invalid-feedback">
                          Wajib Diisi
                      </div>
                    </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                  <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
                </div>
                </form><!-- End Custom Styled Validation -->
              </div>
            </div>
          </div>
          <!-- End Modal Ukuran Baru-->
        
        <!-- Bordered Tabs Justified -->
        <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100 active" id="home-panjang" data-bs-toggle="tab" data-bs-target="#bordered-justified-panjang" type="button" role="tab" aria-controls="home" aria-selected="true">Panjang</button>
          </li>
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100" id="lebar-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-lebar" type="button" role="tab" aria-controls="profile" aria-selected="false">Lebar</button>
          </li>
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100" id="diameter-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Diameter</button>
          </li>
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100" id="ketebalan-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-ketebalan" type="button" role="tab" aria-controls="contact" aria-selected="false">Ketebalan</button>
          </li>
        </ul>
        <div class="tab-content pt-2" id="borderedTabJustifiedContent">
          <div class="tab-pane fade show active" id="bordered-justified-panjang" role="tabpanel" aria-labelledby="home-panjang">
            <table id="example" class="table table-bordered table-striped example">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Panjang (mm)</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT * FROM uk_panjang order by panjang desc");

                  // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                  if($result_dokumen) {
                    if($result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                          $no++;
                ?>
                <tr>
                  <th scope="row"><?php echo "$no."; ?></th>
                  <td><?php echo $row_dokumen->panjang?></td>
                </tr>
                <?php
                      }
                    } 
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="bordered-justified-lebar" role="tabpanel" aria-labelledby="lebar-tab">
            <table id="example2" class="table table-bordered table-striped example">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Lebar (mm)</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT * FROM uk_lebar order by lebar desc");

                  // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                  if($result_dokumen) {
                    if($result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                          $no++;
                ?>
                <tr>
                  <th scope="row"><?php echo "$no."; ?></th>
                  <td><?php echo $row_dokumen->lebar?></td>
                </tr>
                <?php
                      }
                    } 
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="bordered-justified-contact" role="tabpanel" aria-labelledby="diameter-tab">           
            <table id="example3" class="table table-bordered table-striped example">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Diameter (mm)</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT * FROM uk_diameter order by diameter desc");

                  // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                  if($result_dokumen) {
                    if($result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                          $no++;
                ?>
                <tr>
                  <th scope="row"><?php echo "$no."; ?></th>
                  <td><?php echo $row_dokumen->diameter?></td>
                </tr>
              <?php
                    }
                  } 
                }
              ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="bordered-justified-ketebalan" role="tabpanel" aria-labelledby="ketebalan-tab">
            <table id="example4" class="table table-bordered table-striped example">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Ketebalan (mm)</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT * FROM uk_ketebalan order by ketebalan desc");

                  // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                  if($result_dokumen) {
                    if($result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                          $no++;
                ?>
                <tr>
                  <th scope="row"><?php echo "$no."; ?></th>
                  <td><?php echo $row_dokumen->ketebalan?></td>
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
} else {
  // Redirect ke halaman 404 jika user bukan admin
  header('Location: 404');
  exit();
}