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
  include "_head.php";
?>
<body>

  <?php include "_header.php"; ?>
  <?php include "_sidebar.php"; ?>

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
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Scan Consumable</h5>
              <div class="card-body text-center">
                <div id="reader" style="width: 400px; height: 350px; margin: auto;"></div>
              </div>

              <form class="row g-3 needs-validation" method="POST" action="proses-save-trx-out-raw" enctype="multipart/form-data" id="formOut" novalidate>
                <?php $_SESSION['token'] = bin2hex(random_bytes(35)); ?>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">

                <div class="col-md-12 mb-2">
                  <label for="validationaset" class="form-label">Nomor Tipe <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="id_tipe" name="id_tipe" placeholder="Nomor Tipe" required readonly>
                  <div class="invalid-feedback">Wajib diisi</div>
                </div>

                <div id="asset-details" class="col-md-12 mb-2">
                  <label for="asset-options" class="form-label">Ukuran Barang</label>
                  <select name="id_tipe_selected" class="form-select" id="asset-options" required>
                    <option value="" disabled selected>Pilih ukuran barang</option>
                  </select>
                  <div class="invalid-feedback">Pilih ukuran barang yang sesuai</div>
                </div>

                <div class="col-md-12 mb-2">
                  <label for="qty_out" class="form-label">Jumlah yang dikeluarkan <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="qty_out" name="qty_out" min="1" placeholder="Jumlah" required>
                  <div class="invalid-feedback">Wajib diisi</div>
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
  </main>

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
    function onScanSuccess(decodedText, decodedResult) {
      document.getElementById('id_tipe').value = decodedText;

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
        { facingMode: "environment" },
        {
          fps: 15,
          qrbox: { width: 200, height: 200 }
        },
        onScanSuccess,
        onScanFailure
      ).catch(err => {
        console.error('Error starting scanner:', err);
      });
    };

    function fetchAssetDetails(id_tipe) {
      $.ajax({
        url: 'get_asset_details_raw.php',
        type: 'POST',
        data: { id_tipe: id_tipe },
        success: function(response) {
          if (response.success && response.data.length > 0) {
            populateAssetOptions(response.data);
          } else {
            alert('Tidak ada data ukuran barang untuk tipe ini.');
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    }

    function populateAssetOptions(data) {
      const assetOptions = document.getElementById('asset-options');
      assetOptions.innerHTML = '<option value="" disabled selected>Pilih ukuran barang</option>';
      data.forEach(item => {
        const option = document.createElement('option');
        const ukuran = item.ketebalan 
          ? `${item.ketebalan} x ${item.panjang} x ${item.lebar}`
          : `${item.diameter} x ${item.panjang}`;
        option.value = item.id;
        option.textContent = `${item.nama_tipe} (${ukuran}) Sisa Stok: ${item.qty}`;
        assetOptions.appendChild(option);
      });
    }
  </script>

</body>
</html>

<?php
$stmt->close();
$connect->close();
} else {
  header('Location: 404');
}
?>