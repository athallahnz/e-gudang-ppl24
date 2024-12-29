<?php
error_reporting(0);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
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

$query = $connect->prepare("SELECT * FROM aset WHERE id_aset = ?");
$query->bind_param("i", $id_satuan);
$query->execute();
$hasil = $query->get_result();
$row1 = $hasil->fetch_object();
if ($row1->id_material ==2) {
  $addQuery=", e.nama as tipe, panjang, lebar, a.id_klasifikasi as id_klasifikasi";
  $addQuery2="INNER JOIN tipe_aset e on a.id_tipe=e.id ";
} else {
  $addQuery="";
  $addQuery2="";
}


// Menggunakan prepared statement untuk menghindari SQL Injection
$stmt = $connect->prepare("SELECT id_aset, a.nama as barang, tgl_masuk, nomor_aset, b.nama as material, qty, c.nama as satuan, d.nama as klasifikasi, a.id_klasifikasi as id_klasifikasi, a.id_material
                  FROM aset a 
                  INNER JOIN material_aset b on a.id_material=b.id
                  INNER JOIN satuan_aset c on a.id_satuan=c.id
                  INNER JOIN klasifikasi_aset d on a.id_klasifikasi=d.id
                  INNER JOIN transaksi e on a.id_aset=e.fk_id_aset
                  INNER JOIN user f on f.id=e.created_by
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
      <h1>Tambah Stok</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Tambah Stok</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tambah Stok</h5>
              <!-- Hidden field for ID -->
              <form action="proses-tambah-stok" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                <input type="hidden" name="id" value="<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id_aset); ?>" />

                <div class="col-md-12 mb-2">
                  <label for="nama" class="form-label">Nomor Barang </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->nomor_aset); ?>" disabled>
                </div>

                <div class="col-md-12 mb-2">
                  <label for="nama" class="form-label">Nama Barang </label>
                  <?php

                if ($row_dokumen->id_material == 1 || $row_dokumen->id_material == 3) {
                  $hasilInput = htmlspecialchars_decode($row_dokumen->barang);
                } else {
                  if ($row_dokumen->id_klasifikasi==141) {
                      $hasilInput = $row_dokumen->tipe." (".$row_dokumen->ketebalan."mm x".$row_dokumen->panjangtipe."mm x ".$row_dokumen->lebartipe.")";
                  } else if($row_dokumen->id_klasifikasi==142) {
                      $hasilInput = $row_dokumen->tipe." (".$row_dokumen->diameter."mm x".$row_dokumen->panjangaset.")";
                  } else if($row_dokumen->id_klasifikasi==143) {
                    $hasilInput = $row_dokumen->tipe." (".$row_dokumen->ketebalan."mm x".$row_dokumen->panjangaset."mm x".$row_dokumen->lebaraset.")";
                  }
                } 
                echo '<input type="text" class="form-control" name="nama" value="'.$hasilInput.'" disabled>';
                
                
                ?>
                </div>
                
                <div class="col-md-12 mb-2">
                  <label for="nama" class="form-label">Jenis Klasifikasi Material </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->klasifikasi)." (".htmlspecialchars($row_dokumen->material).")"; ?>" disabled>
                </div>

                <div class="col-md-12 mb-2">
                  <label for="nama" class="form-label">Stok Terkini </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->qty)." ".htmlspecialchars($row_dokumen->satuan); ?>" disabled>
                </div>
                
                <div class="col-md-12 mb-2">
                  <label for="stok" class="form-label">Tambah Stok </label>
                  <input type="number" class="form-control" name="stok">
                </div>

                <div class="col-md-12 mb-2">
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

  }else{
    header('Location: 404');
  }
?>