<?php
error_reporting(0);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
include "../koneksi.php";
include "../random-v2.php";

// Pastikan ID klasifikasi tersedia di URL dan valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: 404');
    exit();
}

// Menggunakan fungsi decrypt untuk mendapatkan ID klasifikasi
$id_klasifikasi = encrypt_decrypt2('decrypt', $_GET['id']);

if (!$id_klasifikasi) {
    header('Location: 404');
    exit();
}

// Menggunakan prepared statements untuk menghindari SQL Injection
$stmt = $connect->prepare("SELECT * FROM klasifikasi_aset WHERE id = ?");
$stmt->bind_param("i", $id_klasifikasi);
$stmt->execute();
$result_dokumen = $stmt->get_result();

if ($result_dokumen && $result_dokumen->num_rows > 0) {
    $row_dokumen = $result_dokumen->fetch_object();
} else {
    // Jika data tidak ditemukan
    header('Location: 404');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php
  include "_head.php";
?>
<body>

  <!-- ======= Header ======= -->
  <?php
    include "_header.php";
  ?>
  <!-- ======= Sidebar ======= -->
  <?php
    include "_sidebar.php";
  ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="master-klasifikasi">Master Klasifikasi</a></li>
          <li class="breadcrumb-item active">Edit Data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Edit Data</h5>

              <form action="proses-edit-klasifikasi" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                <!-- Hidden field for ID -->
                <input type="hidden" name="id" value="<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>" />

                <div class="col-md-12 mb-2">
                  <label for="validationTooltip04" class="form-label">Material <span class="text-danger">*</span></label>
                  <select name="material" class="form-select" id="material" required>
                    <option selected disabled value="">-- Pilih --</option>
                    <?php
                      // Ambil data semua material
                      $result_jenis = mysqli_query($connect,"SELECT * FROM material_aset ORDER BY nama asc");
                      if($result_jenis) {
                        if($result_jenis->num_rows > 0) {
                          while($row_jenis = $result_jenis->fetch_object()) {
                            // Cek apakah material ini yang sudah dipilih sebelumnya
                            $selected = ($row_jenis->id == $row_dokumen->id_material) ? 'selected' : '';
                            echo "<option value='$row_jenis->id' $selected>$row_jenis->nama</option>";
                          }
                        }
                      }   
                    ?>
                  </select>
                  <div class="invalid-tooltip">
                    Wajib Diisi
                  </div>
                </div>

                <div class="col-md-12">
                  <label for="nama" class="form-label">Nama Klasifikasi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars_decode($row_dokumen->nama); ?>" required>
                  <div class="invalid-feedback">
                    Nama Klasifikasi wajib diisi.
                  </div>
                </div>

                <div class="col-md-12">
                  <label for="deskripsi" class="form-label">Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" rows="3"><?php echo htmlspecialchars_decode($row_dokumen->deskripsi); ?></textarea>
                </div>
                
                <div class="col-12">
                  <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php
    include "_footer.php";
  ?>
  
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
$connect->close();

  }else{
    header('Location: 404');
  }
?>