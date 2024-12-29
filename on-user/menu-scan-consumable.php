<?php
error_reporting(0);
session_start();
if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin' || $_SESSION['level'] == 'pekerja') {
  include "../koneksi.php";
  include "../random-v2.php";
  $m_active_page = "scan";
  $active_page   = "scan"; 
?>

<!DOCTYPE html>
<html lang="en">

<?php

  // Script Pihak Ketiga ada disini
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
      <h1>Scan Consumable</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="menu-scan">Scan</a></li>
          <li class="breadcrumb-item active">Consumable</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Scan Consumable</h5>

              <div class="card-body text-center">
                <!-- <button id="toggleScan" class="btn btn-success mb-3" data-bs-toggle="collapse" data-bs-target="#scanner-section" aria-expanded="false" onclick="toggleScanner()">Mulai Scan</button> -->
                <!-- Area scanner dengan collapse -->
                <!-- <div id="scanner-section" class="collapse"> -->
                  <div id="reader" style="width: 400px; height: 350px; margin: auto;"></div>
                <!-- </div> -->
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

                <div id="asset-details">
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
  <!-- <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script> -->
  <script>
    function onScanSuccess(decodedText, decodedResult) {
      // Tampilkan hasil secepat mungkin
      document.getElementById('nomor_aset').value = decodedText;

      // Hanya panggil data jika belum diproses
      if (decodedText) {
          fetchAssetDetails(decodedText);
      }
    }

    function onScanFailure(error) {
      console.warn(`Scan error: ${error}`);
    }

    window.onload = function () {
      const html5QrcodeScanner = new Html5Qrcode("reader");
      html5QrcodeScanner.start(
        { facingMode: "environment" }, // Pilih kamera belakang
        {
          fps: 15,    // Naikkan frame rate (default biasanya 10)
          qrbox: { width: 200, height: 200 }  // Kurangi ukuran kotak
        },
        onScanSuccess,
        onScanFailure
      ).catch(err => {
        console.error('Error starting scanner:', err);
      });
    };

    const assetCache = {};

    function fetchAssetDetails(nomor_aset) {
      if (assetCache[nomor_aset]) {
          updateAssetDetails(assetCache[nomor_aset]);
          return;
      }

      $.ajax({
        url: 'get_asset_details.php',
        type: 'POST',
        data: { nomor_aset: nomor_aset },
        success: function(data) {
            console.log('Respons Server:', data); // Log untuk debugging
            if (data.success) {
                updateAssetDetails(data);
            } else {
                alert('Aset tidak ditemukan.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
      });
    }

    function updateAssetDetails(data) {
      const namaAset = document.getElementById('nama_aset');
      const sisaStok = document.getElementById('sisa_stok');
      const assetDetails = document.getElementById('asset-details');

      if (namaAset && sisaStok) {
          namaAset.value = data.nama_aset || 'Tidak ada';
          sisaStok.value = data.sisa_stok || 0;
      } else {
          console.error('Elemen tidak ditemukan atau tidak valid.');
      } 
    }
    const worker = new Worker('worker.js');

    worker.onmessage = function(e) {
        const decodedData = e.data;
        if (decodedData) {
            onScanSuccess(decodedData);
        }
    };

    function processFrame(frameData) {
        worker.postMessage(frameData);
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