<?php
error_reporting(0);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
include "../koneksi.php";
include "../random-v2.php";

// Pastikan ID material tersedia di URL dan valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: 404');
    exit();
}

// Menggunakan fungsi decrypt untuk mendapatkan ID material
$id_material = encrypt_decrypt2('decrypt', $_GET['id']);

if (!$id_material) {
    header('Location: 404');
    exit();
}

// Menggunakan prepared statements untuk menghindari SQL Injection
$stmt = $connect->prepare("SELECT * FROM material_aset WHERE id = ?");
$stmt->bind_param("i", $id_material);
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
          <li class="breadcrumb-item"><a href="master-material">Master Material</a></li>
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

              <form action="proses-edit-material" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                <!-- Hidden field for ID -->
                <input type="hidden" name="id" value="<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>" />

                <div class="col-md-12">
                  <label for="nama" class="form-label">Nama Material <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->nama); ?>" required disabled>
                  <div class="invalid-feedback">
                    Nama Material wajib diisi.
                  </div>
                </div>

                <div class="col-md-12">
                  <label for="deskripsi" class="form-label">Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" rows="3"><?php echo htmlspecialchars($row_dokumen->deskripsi); ?></textarea>
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