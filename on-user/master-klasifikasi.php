<?php
  error_reporting(E_ALL & ~E_NOTICE); // Menangkap semua error kecuali notice
  session_start();

  // Periksa apakah user sudah login dan memiliki level admin
  if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {

    include "../koneksi.php";
    include "../tgl_indo.php";
    include "../random-v2.php";

    $m_active_page = "data-master";
    $active_page   = "master-klasifikasi";
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
      <h1>Klasifikasi</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Data Master</a></li>
          <li class="breadcrumb-item active">Klasifikasi</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Klasifikasi</h5>
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#disablebackdrop">
                <i class="bi bi-plus me-1"></i> Klasifikasi Baru
              </button>
              
              <!-- Modal Tambah Klasifikasi Baru -->
              <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Tambah Klasifikasi Baru</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form class="row g-3 needs-validation" method="POST" action="proses-save-klasifikasi" enctype="multipart/form-data" id="devel-generate-content-form" novalidate>
                        <?php
                          $_SESSION['token'] = bin2hex(random_bytes(35)); // Menghasilkan token CSRF
                        ?>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">

                        <div class="col-md-12 mb-2">
                          <label for="validationTooltip04" class="form-label">Material <span class="text-danger">*</span></label>
                          <select name="material" class="form-select" id="material" required>
                            <option selected disabled value="">-- Pilih --</option>
                            <?php
                              $result_jenis = mysqli_query($connect,"SELECT * FROM material_aset ORDER BY nama asc");
                              if($result_jenis) {
                                if($result_jenis->num_rows > 0) {
                                  while($row_jenis = $result_jenis->fetch_object()) {
                                    echo "<option value='$row_jenis->id'>$row_jenis->nama</option>";
                                  }
                                }
                              }   
                            ?>
                          </select>
                          <div class="invalid-tooltip">
                            Wajib Diisi
                          </div>
                        </div>
                        
                        <div class="col-md-12 mb-2">
                          <label for="klasifikasi" class="form-label">Nama Klasifikasi <span class="text-danger">*</span></label>
                          <input type="text" name="klasifikasi" class="form-control" placeholder="Nama Klasifikasi" required>
                          <div class="invalid-tooltip">Wajib Diisi</div>
                        </div>

                        <div class="col-md-12 mb-2">
                          <label for="deskripsi" class="form-label">Deskripsi</label>
                          <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi"></textarea>
                          <!-- Invalid feedback dihapus karena tidak required -->
                        </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
                    </div>
                  </form><!-- End Custom Styled Validation -->
                  </div>
                </div>
              </div><!-- End Modal Tambah Klasifikasi Baru -->

              <!-- Table Data Klasifikasi -->
              <table id="example" class="table table-striped dt-responsive nowrap example" style="width:100%;">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Material</th>
                    <th scope="col">Klasifikasi</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 0;
                    $result_dokumen = mysqli_query($connect,"SELECT a.id, a.nama as class, a.deskripsi, b.nama as material FROM klasifikasi_aset a INNER JOIN material_aset b ON a.id_material=b.id ORDER BY material, class ASC");

                    if($result_dokumen && $result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                        $no++;
                  ?>
                  <tr>
                    <th scope="row"><?php echo "$no."; ?></th>
                    <td><?php echo htmlspecialchars_decode($row_dokumen->material); ?></td>
                    <td><?php echo htmlspecialchars_decode($row_dokumen->class); ?></td>
                    <td><?php echo htmlspecialchars_decode($row_dokumen->deskripsi); ?></td>
                    <td>
                      <a href='menu-edit-klasifikasi?id=<?php echo urlencode(encrypt_decrypt2('encrypt', $row_dokumen->id)); ?>' class='btn btn-primary btn-sm' title='Edit Klasifikasi'>
                        <i class='bi bi-pencil-square'></i>
                      </a>
                      <a href='menu-detail-klasifikasi?id=<?php echo urlencode(encrypt_decrypt2('encrypt', $row_dokumen->id)); ?>' class='btn btn-primary btn-sm' title='Detail Klasifikasi'>
                        <i class='bi bi-eye'></i>
                      </a>
                      <a href='proses-delete-klasifikasi?id=<?php echo urlencode(encrypt_decrypt2('encrypt', $row_dokumen->id)); ?>' class='btn btn-danger btn-sm alert_delete' title='Hapus Klasifikasi'>
                        <i class='bi bi-trash'></i>
                      </a>
                    </td>
                  </tr>
                  <?php
                      }
                    } else {
                      echo "<tbody>";
                      echo "<tr><td colspan='4' class='text-center'></td></tr>";
                      echo "</tbody>";
                    }
                  ?>
                </tbody>
              </table>
              <!-- End Table Data Klasifikasi -->

            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include "_footer.php"; ?>

  <!-- Session Success/Failure Alerts -->
  <?php if(@$_SESSION['sukses']){ ?>
    <script>
      Swal.fire("Terima Kasih!", "<?php echo $_SESSION['sukses']; ?>", "success");
    </script>
    <?php unset($_SESSION['sukses']); } ?>

  <?php if(@$_SESSION['gagal']){ ?>
    <script>
      Swal.fire("Mohon Maaf!", "<?php echo $_SESSION['gagal']; ?>", "error");
    </script>
    <?php unset($_SESSION['gagal']); } ?>

  <script>
    $('#disablebackdrop').on('hidden.bs.modal', function () {
      document.location.reload();
    });
  </script>

</body>

</html>
<?php
  } else {
    // Redirect ke halaman 404 jika user bukan admin
    header('Location: 404');
    exit();
  }
?>
