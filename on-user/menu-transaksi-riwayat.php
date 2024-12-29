<?php
  error_reporting(0);
  session_start();
  include "../koneksi.php";
  include "../tgl_indo.php";
  include "../random-v2.php";
  // $id_user = $_SESSION['id'];
  // $nama_org = $_SESSION['nama_org'];
  // $id_org = $_SESSION['id_org'];
  // $kode_org2 = $_SESSION['kode_org'];
  $m_active_page = "transaksi";
  $active_page   = "transaksi_riwayat";  
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
      <h1>Riwayat Transaksi</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
          <li class="breadcrumb-item active">Riwayat Transaksi</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Riwayat Transaksi</h5>
              <form class = "row g-3" method = "GET" action = "#">          
                <div class = "col-md-2">
                  <select class = "form-select" name = "tahun" id = "validationDefault04" required>
                  <?php
                  if(isset($_GET['tahun'])) {
                    echo '<option selected value = "'.$_GET['tahun'].'">'.$_GET['tahun'].'</option>';
                  }else{
                    echo '<option selected disabled value = "">Pilih Tahun</option>';
                  }
                      $sql = "SELECT DISTINCT(YEAR(tgl_masuk)) as tahun FROM aset";
                      $resultset = mysqli_query($connect, $sql) or die("database error:". mysqli_error($connect));
                      while( $rows = mysqli_fetch_assoc($resultset) ) { 
                    ?>
                      <option value="<?php 
                      echo $rows["tahun"]; ?>"><?php echo $rows["tahun"]; ?></option>
                    <?php }	?>
                  </select>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary"><i class="bi bi-search" ></i></button>
                </div>
              </form>
              <?php
                if(isset($_GET['tahun'])) {
                  echo "<br>";
                  $tahun = $_GET['tahun'];
              ?>
              <table id="example" class="table table-striped dt-responsive nowrap" style="width:100%;">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">QR Code</th>
                    <th scope="col">Item</th>
                    <th scope="col">Klasifikasi</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 0;
                    $result_dokumen = mysqli_query($connect,"SELECT id_aset, a.nama, tgl_masuk, nomor_aset, b.nama as material, qrcode, stok, c.nama as satuan, d.nama as klasifikasi, jenis_tx, e.last_created
                    FROM aset a 
                    INNER JOIN material_aset b on a.id_material=b.id
                    INNER JOIN satuan_aset c on a.id_satuan=c.id
                    INNER JOIN klasifikasi_aset d on a.id_klasifikasi=d.id
                    INNER JOIN transaksi e on a.id_aset=e.fk_id_aset
                    WHERE YEAR(tgl_masuk)=$tahun order by e.last_created desc");

                    // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                    if($result_dokumen) {
                      if($result_dokumen->num_rows > 0) {
                        while ($row_dokumen = $result_dokumen->fetch_object()) {
                            $no++;
                  ?>
                  <tr>
                    <th scope="row"><?php echo "$no."; ?></th>
                    <td><img src="../qr_codes/<?php echo htmlspecialchars($row_dokumen->qrcode); ?>" alt="QR Code" width="100"></td>
                    <td><?php echo $row_dokumen->nama."<br>"."<b>Tgl Masuk: </b>".format_hari_tanggal($row_dokumen->tgl_masuk);
                    ?></td>
                    <td><?php echo $row_dokumen->klasifikasi."<br>"."<span class='badge bg-primary'>".$row_dokumen->material."</span>" ?></td>
                    <td><?php 
                    
                    if ($row_dokumen->jenis_tx == 'Masuk') {
                      $info = "<span class='badge bg-success'>Masuk</span>";
                      $minus = "";
                    } else {
                      $info = "<span class='badge bg-danger'>Keluar</span>";
                      $minus = "-";
                    }

                    echo $minus.$row_dokumen->stok." ".$row_dokumen->satuan."<br>".$info;

                    
                    ?></td>
                    <td><?php 
                      if ($_SESSION['level'] == 'admin') {
                        echo"<a href='menu-rapat-persetujuan?id=".encrypt_decrypt2('encrypt', $row_dokumen->id_aset)."' class='btn btn-primary btn-sm' title='Persetujuan'><i class='bi bi-pencil-square'></i></a>";
                      }
                    ?>
                      <a href="javascript:void(0)" onclick="location.href='export_presensi.php?id=<?php echo $row_dokumen->id_aset; ?>'" class="btn btn-danger btn-sm" title='Cetak Presensi'><i class='bi bi-printer-fill'></i></a></td>
                  </tr>
                  <?php
                        }
                      } 
                    }
                  ?>
                </tbody>
              </table>
              <!-- End Default Table Example -->
              <?php 
                } 
              ?>

            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
 <?php
  include "_footer.php"
 ?>

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
	})

  let html5QrcodeScanner;

  // Fungsi untuk memulai ulang scanner QR code
  function startQrCodeScanner() {
      // Mulai scanner saat collapse dibuka
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

  function onScanSuccess(decodedText, decodedResult) {
      // Isi input nomor_aset dengan hasil scan QR code
      document.getElementById('nomor_aset').value = decodedText;

      // Hentikan scanner QR code setelah QR code berhasil discan
      html5QrcodeScanner.stop().then(() => {
          console.log("Camera stopped successfully.");

          // Tutup collapse setelah QR code discan
          let collapseElement = document.getElementById('scannerCollapse');
          let bsCollapse = new bootstrap.Collapse(collapseElement, {
              toggle: true
          });
          bsCollapse.hide(); // Menutup collapse dengan animasi ke atas
      }).catch(err => {
          console.error("Unable to stop the camera.", err);
      });

      // Fokus ke input jumlah barang keluar setelah scan
      document.getElementById('qty_out').focus();
  }

  function onScanFailure(error) {
      // Jika gagal scan
      console.warn(`Scan error: ${error}`);
  }
</script>

</body>

</html>