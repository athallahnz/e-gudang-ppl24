<?php
error_reporting(0);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin' || $_SESSION['level'] == 'pekerja') {
  include "../koneksi.php";
  include "../random-v2.php";
  $m_active_page = "transaksi";
  $active_page   = "scan"; 
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
      <h1>Scan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
          <li class="breadcrumb-item active">Scan</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Scan</h5>

              <div class="card-body text-center">
                <button id="toggleScan" class="btn btn-success mb-3" data-bs-toggle="collapse" data-bs-target="#scanner-section" aria-expanded="false" onclick="toggleScanner()">Mulai Scan</button>
                <!-- Area scanner dengan collapse -->
                <div id="scanner-section" class="collapse">
                  <div id="reader" style="width: 400px; height: 350px; margin: auto;"></div>
                </div>
              </div>

              <form class="row g-3 needs-validation" method="POST" action="proses-save-transaksi-out" enctype="multipart/form-data" id="formOut" novalidate>
                <?php
                  $_SESSION['token'] = bin2hex(random_bytes(35));
                ?>

                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                
                <!-- <input type="text" id="nomor_aset" name="nomor_aset"> -->

                <div class="col-md-12 mb-2">
                  <label for="validationaset" class="form-label">Nomor Aset <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="nomor_aset" name="nomor_aset" placeholder="Nomor Aset" required>
                  <div class="invalid-feedback">
                    Wajib diisi
                  </div>
                </div>

                <div id="asset-details" style="display: none;">
                  <div class="col-md-12 mb-2">
                    <label for="validationaset" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" placeholder="Nama Barang" id="nama_aset" disabled>
                  </div>
                  <div class="col-md-12 mb-2">
                    <label for="validationaset" class="form-label">Stok Barang</label>
                    <input type="number" class="form-control" placeholder="Stok Barang" id="sisa_stok" disabled>
                  </div>
                </div>

                <div class="col-md-12 mb-2">
                  <label for="validationaset" class="form-label">Jumlah yang dikeluarkan <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="qty_out" name="qty_out" min="1" max="100" placeholder="Jumlah" required>
                  <div class="invalid-feedback">
                    Wajib diisi
                  </div>
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
<script>
  let html5QrcodeScanner;
  let scannerActive = false;  // Status untuk mengecek apakah scanner aktif

  function toggleScanner() {
      if (!scannerActive) {
          // Jika scanner belum aktif, mulai scanner dan ubah teks tombol
          startQrCodeScanner();
          document.getElementById('toggleScan').innerText = 'Tutup Scanner';
          scannerActive = true;
      } else {
          // Jika scanner sudah aktif, hentikan scanner dan ubah kembali teks tombol
          stopQrCodeScanner();
          document.getElementById('toggleScan').innerText = 'Mulai Scan';
          scannerActive = false;
      }
  }

  function startQrCodeScanner() {
      html5QrcodeScanner = new Html5Qrcode("reader");
      html5QrcodeScanner.start(
          { facingMode: "environment" }, // Pilih kamera belakang
          {
              fps: 10,    // frame per second
              qrbox: 250  // Ukuran kotak pemindaian
          },
          onScanSuccess,
          onScanFailure
      );
  }
  function stopQrCodeScanner() {
    html5QrcodeScanner.stop().then(() => {
        console.log("Scanner stopped successfully.");
    }).catch(err => {
        console.error("Unable to stop the camera.", err);
    });
  }

  function onScanSuccess(decodedText, decodedResult) {
    console.log("QR Code berhasil terbaca: " + decodedText);
    
    // Isi input nomor_aset dengan hasil scan QR code
    document.getElementById('nomor_aset').value = decodedText;
    
    // Panggil fungsi untuk mengambil detail aset
    fetchAssetDetails(decodedText);

    // Hentikan scanner setelah QR code berhasil discan
    html5QrcodeScanner.stop().then(() => {
        console.log("Scanner stopped after successful scan.");
    }).catch(err => {
        console.error("Unable to stop the camera.", err);
    });

    // Fokus ke input jumlah barang keluar setelah scan
    document.getElementById('qty_out').focus();
    
    // Tutup bagian scanner setelah scan berhasil
    $('#scanner-section').collapse('hide');
  }

  function onScanFailure(error) {
      console.warn(`Scan error: ${error}`);
  }
  // Fungsi untuk mengambil detail aset dari server menggunakan AJAX
  function fetchAssetDetails(nomor_aset) {
    $.ajax({
      url: 'get_asset_details.php',  // Endpoint PHP untuk mengambil data aset
      type: 'POST',
      data: { nomor_aset: nomor_aset },  // Kirim nomor aset yang discan
      success: function(response) {
        // Parsing data JSON dari response
        const data = JSON.parse(response);

        if (data.success) {
          // Jika data ditemukan, tampilkan detailnya
          document.getElementById('nama_aset').value = data.nama_aset;
          document.getElementById('sisa_stok').value = data.sisa_stok;
          
          // Tampilkan div dengan detail barang
          document.getElementById('asset-details').style.display = 'block';
        } else {
          alert('Aset tidak ditemukan.');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error fetching asset details:', error);
      }
    });
  }
</script>
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