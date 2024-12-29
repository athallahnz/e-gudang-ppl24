<?php
error_reporting(0);
session_start();
include "../koneksi.php";
include "../random-v2.php";
include "../tgl_indo.php";

// Pastikan ID penugasan tersedia di URL dan valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: 404');
    exit();
}

// Menggunakan fungsi decrypt untuk mendapatkan ID penugasan
$id_penugasan = encrypt_decrypt2('decrypt', $_GET['id']);

if (!$id_penugasan) {
    header('Location: 404');
    exit();
}

// Menggunakan prepared statement untuk menghindari SQL Injection
$stmt = $connect->prepare("SELECT * FROM penugasan_aset WHERE id = ?");
$stmt->bind_param("i", $id_penugasan);
$stmt->execute();
$result_dokumen = $stmt->get_result();

if ($result_dokumen && $result_dokumen->num_rows > 0) {
  $row_dokumen = $result_dokumen->fetch_object();

  // Query untuk mendapatkan nama user pembuat dan pengedit data sekaligus
  $stmt2 = $connect->prepare("SELECT id, nama FROM user WHERE id IN (?, ?)");
  $stmt2->bind_param("ii", $row_dokumen->created_by, $row_dokumen->edited_by);
  $stmt2->execute();
  $result_user = $stmt2->get_result();
  
  // Inisialisasi variabel nama user
  $namaUser = $namaUser2 = "Tidak diketahui";

  while ($row_user = $result_user->fetch_object()) {
    if ($row_user->id == $row_dokumen->created_by) {
      $namaUser = $row_user->nama;
    }
    if ($row_user->id == $row_dokumen->edited_by) {
      $namaUser2 = $row_user->nama;
    }
  }

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
      <h1>Detail</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="master-penugasan">Master Penugasan</a></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Detail</h5>
              <!-- Hidden field for ID -->
              <input type="hidden" name="id" value="<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>" />

              <div class="col-md-12 mb-2">
                <label for="nama" class="form-label">Nama Penugasan </label>
                <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->nama); ?>" disabled>
              </div>
              
              <div class="col-md-12 mb-2">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" disabled><?php echo htmlspecialchars($row_dokumen->deskripsi); ?></textarea>
              </div>
              
              <div class="col-md-12 mb-2">
                <label for="created" class="form-label">Dibuat pada </label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars(format_hari_tanggal2($row_dokumen->last_created)); ?>" disabled>
              </div>

              <div class="col-md-12 mb-2">
                <label for="created_by" class="form-label">Dibuat oleh </label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($namaUser); ?>" disabled>
              </div>

              <div class="col-md-12 mb-2">
                <label for="edited" class="form-label">Tepenugasanhir diubah pada </label>
                <input type="text" class="form-control" value="<?php if ($row_dokumen->last_edited != null) {
                  echo htmlspecialchars(format_hari_tanggal2($row_dokumen->last_edited)); } ?>" disabled>
              </div>

              <div class="col-md-12 mb-2">
                <label for="edited_by" class="form-label">Tepenugasanhir diubah oleh </label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($namaUser2); ?>" disabled>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

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
$stmt2->close();
$connect->close();
?>