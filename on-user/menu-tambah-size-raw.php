<?php
error_reporting(0);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
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

// Menggunakan prepared statement untuk menghindari SQL Injection
$stmt = $connect->prepare("SELECT * FROM tipe_raw WHERE id = ?");
$stmt->bind_param("i", $id_satuan);
$stmt->execute();
$result_dokumen = $stmt->get_result();

if ($result_dokumen && $result_dokumen->num_rows > 0) {
  $row_dokumen = $result_dokumen->fetch_object();
  $id_tipe = htmlspecialchars($row_dokumen->id);
  $id_klasifikasi  = htmlspecialchars($row_dokumen->id_klasifikasi);

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
      <h1>Tambah Ukuran Tipe</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="menu-raw">Barang Raw</a></li>
          <li class="breadcrumb-item active">Tambah Ukuran Tipe</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tambah Ukuran Tipe</h5>
              <!-- Hidden field for ID -->
              <form action="proses-save-size-raw" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                <?php
                  $_SESSION['token'] = bin2hex(random_bytes(35));
                ?>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                <input type="hidden" name="id_tipe" value="<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>" />

                <div class="col-md-12 mb-2">
                  <label for="nama" class="form-label">Nama Tipe <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->nama_tipe); ?>" disabled required>
                </div>

                <div class="col-md-12 mb-2" id="ketebalan-input" style="display: none;">
                  <label for="ketebalan" class="form-label">Ukuran Ketebalan</label>
                  <select name="ketebalan" class="form-select" id="uk_tebal">
                    <option selected disabled value="">-- Pilih Ketebalan --</option>
                    <option value="others">Lainnya</option> <!-- Add 'Others' option -->
                    <?php
                    $sql_vendor = "SELECT * FROM uk_ketebalan";
                    $result_vendor = mysqli_query($connect, $sql_vendor) or die(mysqli_error($connect));
                    while($row_vendor = mysqli_fetch_assoc($result_vendor)) { ?>
                        <option value="<?= $row_vendor['ketebalan']; ?>"><?= htmlspecialchars_decode($row_vendor['ketebalan']); ?></option>
                    <?php } ?>
                  </select>
                  <div class="input-group mt-2" id="tebal_lainnya" style="display:none">
                    <input type="number" step="0.01" name="ketebalan_lainnya" class="form-control" placeholder="Ukuran ketebalan">
                    <span class="input-group-text">mm</span>
                  </div>
                </div>
                <!-- Ukuran Diameter -->
                <div class="col-md-12 mb-2" id="diameter-input" style="display: none;">
                <label for="diameter" class="form-label">Ukuran Diameter</label>
                <select name="diameter" class="form-select" id="uk_diameter">
                  <option selected disabled value="">-- Pilih Diameter --</option>
                  <option value="others">Lainnya</option> <!-- Add 'Others' option -->
                  <?php
                  $sql_vendor = "SELECT * FROM uk_diameter";
                  $result_vendor = mysqli_query($connect, $sql_vendor) or die(mysqli_error($connect));
                  while ($row_vendor = mysqli_fetch_assoc($result_vendor)) { ?>
                      <option value="<?= $row_vendor['diameter']; ?>"><?= htmlspecialchars_decode($row_vendor['diameter']); ?></option>
                  <?php } ?>
                </select>
                <div class="input-group mt-2" id="diameter_lainnya" style="display:none">
                  <input type="number" step="0.01" name="diameter_lainnya" class="form-control" placeholder="Ukuran diameter">
                  <span class="input-group-text">mm</span>
                </div>
                </div>

                 <!-- Dropdown Panjang -->
                <div class="col-md-12 mb-3" id="panjang-input" style="display: none;">
                  <label for="uk_panjang" class="form-label">Ukuran Panjang</label>
                  <select name="panjang" class="form-select" id="uk_panjang">
                    <option selected disabled value="">-- Pilih Panjang --</option>
                    <option value="others">Lainnya</option> <!-- Add 'Others' option -->
                    <?php
                    $sql_vendor = "SELECT * FROM uk_panjang";
                    $result_vendor = mysqli_query($connect, $sql_vendor) or die(mysqli_error($connect));
                    while ($row_vendor = mysqli_fetch_assoc($result_vendor)) { ?>
                        <option value="<?= $row_vendor['panjang']; ?>"><?= htmlspecialchars_decode($row_vendor['panjang']); ?></option>
                    <?php } ?>
                  </select>
                  <!-- Input Tambahan untuk 'Lainnya' -->
                  <div class="input-group mt-2" id="panjang_lainnya" style="display:none">
                    <input type="number" step="0.01" name="panjang_lainnya" class="form-control" placeholder="Ukuran panjang">
                    <span class="input-group-text">mm</span>
                  </div>
                </div>

                <!-- Ukuran Lebar -->
                <div class="col-md-12 mb-2" id="lebar-input" style="display: none;">
                  <label for="lebar" class="form-label">Ukuran Lebar</label>
                  <select name="lebar" class="form-select" id="uk_lebar">
                    <option selected disabled value="">-- Pilih Lebar --</option>
                    <option value="others">Lainnya</option> <!-- Add 'Others' option -->
                    <?php
                    $sql_vendor = "SELECT * FROM uk_lebar";
                    $result_vendor = mysqli_query($connect, $sql_vendor) or die(mysqli_error($connect));
                    while ($row_vendor = mysqli_fetch_assoc($result_vendor)) { ?>
                        <option value="<?= $row_vendor['lebar']; ?>"><?= htmlspecialchars_decode($row_vendor['lebar']); ?></option>
                    <?php } ?>
                  </select>
                  <div class="input-group mt-2" id="lebar_lainnya" style="display:none">
                    <input type="number" step="0.01" name="lebar_lainnya" class="form-control" placeholder="Ukuran lebar">
                    <span class="input-group-text">mm</span>
                  </div>
                </div>
                
                <div class="col-md-12 mb-2">
                  <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" name="qty" required>
                  <div class="invalid-feedback">
                      Wajib Diisi
                  </div>
                </div>

                <div class="col-md-12 mb-2" id="spk-wrapper">
                  <label for="spk" class="form-label">Nomor SPK <span class="text-danger">*</span></label>
                  <input type="text" name="spk" class="form-control" placeholder="Masukkan Nomor SPK" required>
                  <div class="invalid-feedback">
                      Wajib Diisi
                  </div>
                </div>

                <!-- Dropdown Nama Vendor -->
                <div class="col-md-12 mb-2" id="vendor-wrapper">
                  <label for="vendor" class="form-label">Nama Vendor <span class="text-danger">*</span></label>
                  <select name="vendor" class="form-select" id="nama_vendor" required>
                      <option selected disabled value="">-- Pilih Vendor --</option>
                      <?php
                      $sql_vendor = "SELECT * FROM vendor_aset";
                      $result_vendor = mysqli_query($connect, $sql_vendor) or die(mysqli_error($connect));
                      while($row_vendor = mysqli_fetch_assoc($result_vendor)) { ?>
                          <option value="<?= $row_vendor['id']; ?>"><?= htmlspecialchars_decode($row_vendor['nama']); ?></option>
                      <?php } ?>
                  </select>
                  <input type="text" id="vendor_lainnya" class="form-control mt-2 d-none" placeholder="Masukkan Nama Vendor Baru" />
                  <div class="invalid-feedback">
                      Wajib Diisi
                  </div>
                </div>

                <!-- Dropdown Nama Penugasan -->
                <div class="col-md-12 mb-2" id="penugasan-wrapper">
                  <label for="penugasan" class="form-label">Nama Penugasan <span class="text-danger">*</span></label>
                  <select name="penugasan" class="form-select" id="nama_penugasan" required>
                      <option selected disabled value="">-- Pilih Penugasan --</option>
                      <?php
                      $sql_penugasan = "SELECT * FROM penugasan_aset";
                      $result_penugasan = mysqli_query($connect, $sql_penugasan) or die(mysqli_error($connect));
                      while($row_penugasan = mysqli_fetch_assoc($result_penugasan)) { ?>
                          <option value="<?= $row_penugasan['id']; ?>"><?= htmlspecialchars_decode($row_penugasan['nama']); ?></option>
                      <?php } ?>
                  </select>
                  <div class="invalid-feedback">
                      Wajib Diisi
                  </div>
                </div>

                <div class="col-md-12 mb-2">
                  <label for="deskripsi" class="form-label">Deskripsi</label>
                  <input type="text" name="deskripsi" class="form-control" placeholder="Masukkan Deskripsi">
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
<script>
  const idKlasifikasi = <?= json_encode($id_klasifikasi); ?>;
</script>
<script>
  // Select2
  $(document).ready(function() {
    // Initialize Select2
    $('#uk_tebal').select2();
    $('#uk_lebar').select2();
    $('#uk_panjang').select2();
    $('#nama_vendor').select2();
    $('#nama_penugasan').select2();

    // Event listener for Option A (when "Others" is selected)
    $('#uk_diameter').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue === 'others') {
            $('#diameter_lainnya').show();  // Show input for "Others"
        } else {
            $('#diameter_lainnya').hide();  // Hide input for "Others"
        }
    });
    // Event listener for Option A (when "Others" is selected)
    $('#uk_tebal').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue === 'others') {
            $('#tebal_lainnya').show();  // Show input for "Others"
        } else {
            $('#tebal_lainnya').hide();  // Hide input for "Others"
        }
    });
    // Event listener for Option A (when "Others" is selected)
    $('#uk_panjang').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue === 'others') {
            $('#panjang_lainnya').show();  // Show input for "Others"
        } else {
            $('#panjang_lainnya').hide();  // Hide input for "Others"
        }
    });
    // Event listener for Option A (when "Others" is selected)
    $('#uk_lebar').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue === 'others') {
            $('#lebar_lainnya').show();  // Show input for "Others"
        } else {
            $('#lebar_lainnya').hide();  // Hide input for "Others"
        }
    });

    // Event listener for Option B (when "Others" is selected)
    $('#select2Example2').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue === 'others') {
            $('#customInputB').show();  // Show input for "Others"
        } else {
            $('#customInputB').hide();  // Hide input for "Others"
        }
    });
  });

  document.addEventListener("DOMContentLoaded", () => {
      // Tampilkan input berdasarkan idKlasifikasi
      if (idKlasifikasi == 141 || idKlasifikasi == 143) {
          document.getElementById("ketebalan-input").style.display = "block";
          document.getElementById("panjang-input").style.display = "block";
          document.getElementById("lebar-input").style.display = "block";
      } else if (idKlasifikasi == 142) {
          document.getElementById("diameter-input").style.display = "block";
          document.getElementById("panjang-input").style.display = "block";
      }
  });
</script>

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