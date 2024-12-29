<?php
  error_reporting(E_ALL & ~E_NOTICE); // Menangkap semua error kecuali notice
  session_start();

  // Periksa apakah user sudah login dan memiliki level admin
  if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {

    include "../koneksi.php";
    include "../tgl_indo.php";
    include "../random-v2.php";

    $m_active_page = "data-master";
    $active_page   = "master-tipe";
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
      <h1>Tipe</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Data Master</a></li>
          <li class="breadcrumb-item active">Tipe</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tipe</h5>
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#disablebackdrop">
                <i class="bi bi-plus me-1"></i> Tipe Baru
              </button>
              
              <!-- Modal Tambah Tipe Baru -->
              <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Tambah Tipe Baru</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form class="row g-3 needs-validation" method="POST" action="proses-save-tipe" enctype="multipart/form-data" id="devel-generate-content-form" novalidate>
                        <?php
                          $_SESSION['token'] = bin2hex(random_bytes(35)); // Menghasilkan token CSRF
                        ?>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">

                        <div class="col-md-12 mb-2">
                          <label for="validationTooltip04" class="form-label">Material <span class="text-danger">*</span></label>
                          <select name="material" class="form-select" id="material" required>
                            <?php
                              $result_jenis = mysqli_query($connect,"SELECT * FROM material_aset WHERE id=2 ORDER BY nama asc");
                              if($result_jenis) {
                                if($result_jenis->num_rows > 0) {
                                  while($row_jenis = $result_jenis->fetch_object()) {
                                    echo "<option value='$row_jenis->id' selected>$row_jenis->nama</option>";
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
                          <label for="validationTooltip04" class="form-label">Klasifikasi <span class="text-danger">*</span></label>
                          <select name="klasifikasi" class="form-select" id="klasifikasi" required onchange="toggleSizeInputs()">
                            <option selected disabled value="">-- Pilih --</option>
                            <?php
                              $result_jenis = mysqli_query($connect,"SELECT * FROM klasifikasi_aset WHERE id_material=2 ORDER BY nama asc");
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
                          <label for="tipe" class="form-label">Nama Tipe <span class="text-danger">*</span></label>
                          <input type="text" name="tipe" class="form-control" placeholder="Nama Tipe" required>
                          <div class="invalid-tooltip">Wajib Diisi</div>
                        </div>

                        <!-- <div class="col-md-12 mb-2" id="ketebalan-input" style="display: none;">
                          <label for="ketebalan" class="form-label">Ukuran Ketebalan</label>
                          <div class="input-group">
                              <input type="number" step="0.01" name="ketebalan" class="form-control" placeholder="Ukuran ketebalan">
                              <span class="input-group-text">mm</span> 
                          </div>
                        </div>

                        <div class="col-md-12 mb-2" id="diameter-input" style="display: none;">
                          <label for="diamater" class="form-label">Ukuran Diamater</label>
                          <div class="input-group">
                              <input type="number" step="0.01" name="diameter" class="form-control" placeholder="Ukuran diamater">
                              <span class="input-group-text">mm</span> 
                          </div>
                        </div> -->

                        <div class="col-md-12 mb-2" id="panjang-input" style="display: none;">
                          <label for="panjang" class="form-label">Ukuran Panjang</label>
                          <div class="input-group">
                              <input type="number" step="0.01" name="panjang" class="form-control" placeholder="Ukuran panjang">
                              <span class="input-group-text">mm</span> <!-- Menambahkan tulisan "mm" di sebelah kanan -->
                          </div>
                          <!-- <div class="invalid-tooltip">Wajib Diisi</div> -->
                        </div>

                        <div class="col-md-12 mb-2" id="lebar-input" style="display: none;">
                          <label for="lebar" class="form-label">Ukuran Lebar</label>
                          <div class="input-group">
                              <input type="number" step="0.01" name="lebar" class="form-control" placeholder="Ukuran lebar">
                              <span class="input-group-text">mm</span> <!-- Menambahkan tulisan "mm" di sebelah kanan -->
                          </div>
                          <!-- <div class="invalid-tooltip">Wajib Diisi</div> -->
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
              </div><!-- End Modal Tambah Tipe Baru -->

              <!-- Table Data Tipe -->
              <table id="example" class="table table-striped dt-responsive nowrap" style="width:100%;">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Klasifikasi</th>
                    <th scope="col">Tipe dan Ukuran(mm)</th>
                    <!-- <th scope="col">Ukuran</th> -->
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 0;
                    $result_dokumen = mysqli_query($connect,"SELECT a.id, a.nama as tipe, a.deskripsi, id_klasifikasi, b.nama as klasifikasi,panjang, lebar FROM tipe_aset a INNER JOIN klasifikasi_aset b ON a.id_klasifikasi=b.id ORDER BY klasifikasi, tipe ASC");

                    if($result_dokumen && $result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                        $no++;
                  ?>
                  <tr>
                    <th scope="row"><?php echo "$no."; ?></th>
                    <td><?php echo htmlspecialchars_decode($row_dokumen->klasifikasi); ?></td>
                    <td><?php echo htmlspecialchars_decode($row_dokumen->tipe); 
                    if ($row_dokumen->id_klasifikasi==141) {
                      echo "<br><span class='badge bg-primary'>".$row_dokumen->panjang." x ".$row_dokumen->lebar."</span>";
                    } 
                    // else {
                    //   echo "<br><span class='badge bg-primary'>".$row_dokumen->diameter." x ".$row_dokumen->panjang."</span>";
                    // }
                    ?></td>
                    <td><?php echo htmlspecialchars_decode($row_dokumen->deskripsi); ?></td>
                    <td>
                      <a href='menu-edit-tipe?id=<?php echo urlencode(encrypt_decrypt2('encrypt', $row_dokumen->id)); ?>' class='btn btn-primary btn-sm' title='Edit Tipe'>
                        <i class='bi bi-pencil-square'></i>
                      </a>
                      <a href='menu-detail-tipe?id=<?php echo urlencode(encrypt_decrypt2('encrypt', $row_dokumen->id)); ?>' class='btn btn-primary btn-sm' title='Detail Tipe'>
                        <i class='bi bi-eye'></i>
                      </a>
                      <a href='proses-delete-tipe?id=<?php echo urlencode(encrypt_decrypt2('encrypt', $row_dokumen->id)); ?>' class='btn btn-danger btn-sm alert_delete' title='Hapus Tipe'>
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
              <!-- End Table Data Tipe -->

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

    function toggleSizeInputs() {
      const klasifikasi = document.getElementById("klasifikasi").value;
      
      // Sembunyikan semua input size terlebih dahulu
      // document.getElementById("ketebalan-input").style.display = "none";
      // document.getElementById("diameter-input").style.display = "none";
      document.getElementById("panjang-input").style.display = "none";
      document.getElementById("lebar-input").style.display = "none";

      // Tampilkan input sesuai klasifikasi jika ada pilihan
      if (klasifikasi === "141") {
          document.getElementById("panjang-input").style.display = "block";
          document.getElementById("lebar-input").style.display = "block";
      }
    }

    // Sembunyikan semua input size saat halaman pertama kali dimuat
    document.addEventListener("DOMContentLoaded", function() {
        toggleSizeInputs();
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
