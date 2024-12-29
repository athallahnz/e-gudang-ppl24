<?php
error_reporting(0);
session_start();
include "../koneksi.php";
include "../random-v2.php";
include "../tgl_indo.php";

// Pastikan ID satuan tersedia di URL dan valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: 404');
    exit();
}

// Menggunakan fungsi decrypt untuk mendapatkan ID satuan
$id_aset = encrypt_decrypt2('decrypt', $_GET['id']);

if (!$id_aset) {
    header('Location: 404');
    exit();
}

// Menggunakan prepared statement untuk menghindari SQL Injection
$stmt = $connect->prepare("SELECT id_aset, nomor_aset, a.nama as barang, b.nama as satuan, qty, c.nama as klasifikasi, d.nama as material, f.nama as lokasi, berat, g.nama as created_by, a.last_created, qty_minimum FROM aset a 
INNER JOIN satuan_aset b ON a.id_satuan=b.id 
INNER JOIN klasifikasi_aset c on a.id_klasifikasi=c.id 
INNER JOIN material_aset d on a.id_material=d.id 
INNER JOIN lokasi_aset f on a.id_lokasi=f.id 
INNER JOIN user g on a.created_by=g.id 

WHERE id_aset = ?");
$stmt->bind_param("i", $id_aset);
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
      <h1>Detail Barang</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="menu-transaksi-in">Transaksi Masuk</a></li>
          <li class="breadcrumb-item active">Detail Barang</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Detail Barang</h5>
              <!-- Hidden field for ID -->
              <input type="hidden" name="id" value="<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>" />

              <div class="row">
                <div class="col-md-4 mb-2">
                  <label for="nama" class="form-label">Nomor Barang </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->nomor_aset); ?>" disabled>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="nama" class="form-label">Jenis Klasifikasi Material </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->klasifikasi)." (".htmlspecialchars($row_dokumen->material).")"; ?>" disabled>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="nama" class="form-label">Nama Barang </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->barang); ?>" disabled>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-2">
                  <label for="nama" class="form-label">Minimun Stok </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->qty_minimum)." ".htmlspecialchars($row_dokumen->satuan); ?>" disabled>
                </div>
  
                <div class="col-md-6 mb-2">
                  <label for="nama" class="form-label">Stok Terkini </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->qty)." ".htmlspecialchars($row_dokumen->satuan); ?>" disabled>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-2">
                  <label for="nama" class="form-label">Berat Barang </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->berat)." Kilogram"; ?>" disabled>
                </div>
                <div class="col-md-6 mb-2">
                  <label for="nama" class="form-label">Lokasi Barang </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->lokasi); ?>" disabled>
                </div>
              </div>


              <!-- <div class="col-md-12 mb-2">
                <label for="nama" class="form-label">Rak Barang </label>
                <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->rak); ?>" disabled>
              </div> -->
              
              
              <div class="col-md-12 mb-2">
                <label for="nama" class="form-label">Ditambahkan oleh </label>
                <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->created_by).", pada ".format_hari_tanggal(htmlspecialchars($row_dokumen->last_created)); ?>" disabled>
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
// $stmt2->close();
$connect->close();
?>