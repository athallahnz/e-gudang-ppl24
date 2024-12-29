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
  $m_active_page = "laporan";
  $active_page   = "lap_out";  
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
      <h1>Transaksi Keluar</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
          <li class="breadcrumb-item active">Transaksi Keluar</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Transaksi Keluar</h5>
              <form class = "row g-3" method = "GET" action = "#">          
                <div class = "col-md-2">
                  <select class = "form-select" name = "tahun" id = "validationDefault04" onchange="this.form.submit()" required>
                  <?php
                  if(isset($_GET['tahun'])) {
                    echo '<option selected value = "'.$_GET['tahun'].'">'.$_GET['tahun'].'</option>';
                  }else{
                    echo '<option selected disabled value = "">Pilih Tahun Masuk</option>';
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
              </form>
              <?php
                if(isset($_GET['tahun'])) {
                  echo "<br>";
                  $tahun = htmlspecialchars($_GET['tahun']);
              ?>
              <table class="table table-striped dt-responsive example1" style="width:100%;">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Tgl. Transaksi Keluar</th>
                    <th scope="col">Stok Keluar</th>
                    <th scope="col">Aktor</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 0;
                    $result_dokumen = mysqli_query($connect,"SELECT id_aset, a.nama as barang, nomor_aset, b.nama as material, qrcode, e.stok as stok, jenis_tx, e.last_created, f.nama as users, e.last_created, qrcode, c.nama as satuan, d.nama as klasifikasi, g.nama as tipe, ketebalan, diameter, a.panjang as panjangaset, a.lebar as lebaraset, g.panjang as panjangtipe, g.lebar as lebartipe, a.id_klasifikasi as id_klasifikasi, a.id_material
                    FROM aset a 
                    INNER JOIN material_aset b on a.id_material=b.id
                    INNER JOIN satuan_aset c on a.id_satuan=c.id
                    INNER JOIN klasifikasi_aset d on a.id_klasifikasi=d.id
                    INNER JOIN transaksi e on a.id_aset=e.fk_id_aset
                    INNER JOIN user f on f.id=e.created_by
                    LEFT JOIN tipe_aset g on a.id_tipe=g.id
                    WHERE YEAR(tgl_tx)=$tahun AND jenis_tx='Keluar' order by e.last_created desc");

                    if($result_dokumen) {
                      if($result_dokumen->num_rows > 0) {
                        while ($row_dokumen = $result_dokumen->fetch_object()) {
                            $no++;
                            if ($row_dokumen->jenis_tx == 'Masuk') {
                              $info1 = "<span class='badge bg-success'>".format_hari_tanggal2($row_dokumen->last_created)."</span>";
                              $minus = "";
                            } else {
                              $info1 = "<span class='badge bg-danger'>".format_hari_tanggal2($row_dokumen->last_created)."</span>";
                              $minus = "-";
                            }
                  ?>
                  <tr>
                    <th scope="row"><?php echo "$no."; ?></th>
                    <td>
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
                        echo $hasilInput."<br><span class='badge bg-info'>".$row_dokumen->klasifikasi."</span><br>"."<span class='badge bg-primary'>".$row_dokumen->material."</span>"
                      ?></td>
                    <td><?php echo $info1;?></td>
                    <td><?php echo "<span class='badge bg-danger'>".$row_dokumen->stok." ".$row_dokumen->satuan."</span>"?></td>
                    <td><?php echo $row_dokumen->users?></td>
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