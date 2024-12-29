<?php
  error_reporting(0);
  session_start();
  if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
  include "../koneksi.php";
  include "../tgl_indo.php";
  include "../random-v2.php";
  // $id_user = $_SESSION['id'];
  // $nama_org = $_SESSION['nama_org'];
  // $id_org = $_SESSION['id_org'];
  // $kode_org2 = $_SESSION['kode_org'];
  $m_active_page = "laporan";
  $active_page   = "lap_in";  
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
      <h1>Transaksi Masuk</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
          <li class="breadcrumb-item active">Transaksi Masuk</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Transaksi Masuk</h5>
              <!-- <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#disablebackdrop"><i class="bi bi-plus me-1" ></i> Barang Baru</button> -->
              <form class="row g-3" method="GET" action="#">
                <div class="col-md-2">
                  <!-- <label for="tahun" class="form-label">Pilih Tahun:</label>    -->
                  <select class="form-select" name="tahun" id="tahun" required onchange="this.form.submit()">
                    <?php
                    // Tentukan tahun yang akan digunakan: dari GET atau default ke tahun sekarang
                    $tahun_terpilih = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
                    
                    // Opsi pertama adalah tahun terpilih atau tahun sekarang
                    echo '<option selected value="' . $tahun_terpilih . '">' . $tahun_terpilih . '</option>';
                    
                    // Query untuk mendapatkan tahun yang unik dari database
                    $sql = "SELECT DISTINCT YEAR(tgl_masuk) AS tahun FROM aset ORDER BY tahun DESC";
                    $resultset = mysqli_query($connect, $sql) or die("database error:" . mysqli_error($connect));
                    
                    // Loop untuk menampilkan tahun yang ada di database, kecuali tahun yang sudah ditampilkan di atas
                    while ($rows = mysqli_fetch_assoc($resultset)) { 
                      $tahun_db = $rows["tahun"];
                      
                      // Hanya tampilkan tahun jika berbeda dari tahun terpilih
                      if ($tahun_db != $tahun_terpilih) {
                        echo '<option value="' . $tahun_db . '">' . $tahun_db . '</option>';
                      }
                    }
                    ?>
                  </select>
                </div>
              </form>
              <!-- Modal Rapat Baru -->
              <!-- <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form class="row g-3 needs-validation" method="POST" action="proses-save-transaksi-in" enctype="multipart/form-data" id="devel-generate-content-form" novalidate>
                      <?php
                      $_SESSION['token'] = bin2hex(random_bytes(35));
                      ?>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                    
                    <div class="col-md-12 mb-2">
                      <label for="validationaset" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                      <input type="date" class="form-control" id="validationaset" value="<?php echo date('Y-m-d') ?>" name="tgl_masuk" placeholder="Tanggal Masuk" required>
                      <div class="invalid-feedback">
                        Wajib diisi
                      </div>
                    </div>

                    <div class="col-md-12 mb-2">
                      <label for="validationTooltip04" class="form-label">Material <span class="text-danger">*</span></label>
                      <select name="material" class="form-select" id="material" required>
                        <option selected disabled value="">-- Pilih --</option>
                        <?php
                          $result_jenis = mysqli_query($connect,"SELECT * FROM material_aset ORDER BY nama asc");
                          if($result_jenis) {
                            if($result_jenis->num_rows > 0) {
                              while($row_jenis = $result_jenis->fetch_object()) {
                                echo "<option value='$row_jenis->id'>".htmlspecialchars_decode($row_jenis->nama)."</option>";
                              }
                            }
                          }   
                        ?>
                      </select>
                      <div class="invalid-feedback">
                        Wajib Diisi
                      </div>
                    </div>

                    <div class="col-md-12 mb-2">
                      <label for="validationTooltip04" class="form-label">Klasifikasi <span class="text-danger">*</span></label>
                      <select name="klasifikasi" class="form-select" id="klasifikasi" required>
                        <option value="" disabled>Pilih Klasifikasi</option>
                      </select>
                      <div id="loading-klasifikasi" style="display: none;">
                        <img src="../assets/animation/loading.gif" alt="Loading" style="width: 30px;"> Memuat data...
                      </div>
                      <div class="invalid-feedback">
                        Wajib Diisi
                      </div>
                    </div>

                    <div class="col-md-12 mb-2" id="penugasan-wrapper" style="display:none;">
                      <label for="validationTooltip04" class="form-label">Pilih Penugasan <span class="text-danger">*</span></label>
                      <select name="penugasan" class="form-select" id="nama_penugasan" required>
                      <option selected disabled value="">-- Pilih Penugasan --</option>
                      <?php
                      $sql_penugasan = "SELECT id, nama FROM penugasan_aset";
                      $result_penugasan = mysqli_query($connect, $sql_penugasan) or die(mysqli_error($connect));
                      while($row_penugasan = mysqli_fetch_assoc($result_penugasan)) { ?>
                          <option value="<?= $row_penugasan['id']; ?>"><?= htmlspecialchars_decode($row_penugasan['nama']); ?></option>
                      <?php } ?>
                      </select>
                      <div id="loading-penugasan" style="display: none;">
                        <img src="../assets/animation/loading.gif" alt="Loading" style="width: 30px;"> Memuat data...
                      </div>
                      <div class="invalid-feedback">
                        Wajib Diisi
                      </div>
                    </div>

                    <div class="col-md-12 mb-2" id="spk-wrapper" style="display:none;">
                      <label for="spk" class="form-label">Nomor SPK <span class="text-danger">*</span></label>
                      <input type="text" name="spk" class="form-control" id="spk" placeholder="Masukkan Nomor SPK">
                      <div class="invalid-feedback">
                          Wajib Diisi
                      </div>
                    </div>

                    <div class="col-md-12 mb-2" id="vendor-wrapper" style="display:none;">
                      <label for="vendor" class="form-label">Nama Vendor <span class="text-danger">*</span></label>
                      <select name="vendor" class="form-select" id="nama_vendor">
                          <option selected disabled value="">-- Pilih Vendor --</option>
                          <?php
                          $sql_vendor = "SELECT * FROM vendor_aset";
                          $result_vendor = mysqli_query($connect, $sql_vendor) or die(mysqli_error($connect));
                          while($row_vendor = mysqli_fetch_assoc($result_vendor)) { ?>
                              <option value="<?= $row_vendor['id']; ?>"><?= htmlspecialchars_decode($row_vendor['nama']); ?></option>
                          <?php } ?>
                      </select>
                      <div class="invalid-feedback">
                          Wajib Diisi
                      </div>
                    </div>
                    
                    <div class="col-md-12 mb-2">
                      <label for="validationaset" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="validationaset" name="aset" placeholder="Nama Barang" required>
                      <div class="invalid-feedback">
                        Wajib diisi
                      </div>
                    </div>


                    <div class="col-md-12 mb-2">
                      <label for="validation-no-seri" class="form-label">Nomor Seri</label>
                      <input type="text" class="form-control" id="validation-no-seri" name="no_seri" placeholder="Nomor Seri">
                      <div class="invalid-feedback">
                        Wajib diisi
                      </div>
                    </div>


                    <div class="col-md-12 mb-2">
                      <label for="validationTooltip04" class="form-label">Lokasi <span class="text-danger">*</span></label>
                      <select name="lokasi" class="form-select" id="validationTooltip04" required>
                        <option selected disabled value="">-- Pilih --</option>
                        <?php
                          $result_jenis = mysqli_query($connect,"SELECT * FROM lokasi_aset ORDER BY nama asc");
                          if($result_jenis) {
                            if($result_jenis->num_rows > 0) {
                              while($row_jenis = $result_jenis->fetch_object()) {
                                echo "<option value='$row_jenis->id'>".htmlspecialchars_decode($row_jenis->nama)."</option>";
                              }
                            }
                          }   
                        ?>
                      </select>
                      <div class="invalid-feedback">
                        Wajib Diisi
                      </div>
                    </div>

                    <div class="col-md-12 mb-2">
                      <label for="validation-berat-aset" class="form-label">Stok <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" id="validation-berat-aset" name="qty" placeholder="Stok" required>
                      <div class="invalid-feedback">
                        Wajib diisi
                      </div>
                    </div>

                    <div class="col-md-12 mb-2">
                      <label for="validation-berat-aset" class="form-label">Stok Minimun <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" id="validation-berat-aset" name="qty_min" placeholder="Stok Minimun" required>
                      <div class="invalid-feedback">
                        Wajib diisi
                      </div>
                    </div>

                    <div class="col-md-12 mb-2">
                      <label for="validation-berat-aset" class="form-label">Berat Aset (kilogram)<span class="text-danger">*</span></label>
                      <input type="number" class="form-control" step="0.01" min="0" id="validation-berat-aset" name="berat" placeholder="Berat Aset" required>
                      <div class="invalid-feedback">
                        Wajib diisi
                      </div>
                    </div>

                    <div class="col-md-12 mb-2">
                      <label for="validationTooltip04" class="form-label">Satuan <span class="text-danger">*</span></label>
                      <select name="satuan" class="form-select" id="validationTooltip04" required>
                        <option selected disabled value="">-- Pilih --</option>
                        <?php
                          $result_jenis = mysqli_query($connect,"SELECT * FROM satuan_aset ORDER BY nama asc");
                          if($result_jenis) {
                            if($result_jenis->num_rows > 0) {
                              while($row_jenis = $result_jenis->fetch_object()) {
                                echo "<option value='$row_jenis->id'>".htmlspecialchars_decode($row_jenis->nama)."</option>";
                              }
                            }
                          }   
                        ?>
                      </select>
                      <div class="invalid-feedback">
                        Wajib Diisi
                      </div>
                    </div>  

                    <div class="col-md-12 mb-2">
                      <label for="validation-no-seri" class="form-label">Deskripsi</label>
                      <input type="text" class="form-control" id="validation-no-seri" name="deskripsi" placeholder="Deskripsi">
                      <div class="invalid-feedback">
                        Wajib diisi
                      </div>
                    </div>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
                  </div>
                </form>
                </div>
              </div>
              </div> -->
              <!-- End Modal Dok Baru-->


              <?php
                if(isset($_GET['tahun'])) {
                  echo "<br>";
                  $tahun = $_GET['tahun'];
              ?>
            <table class="table table-striped dt-responsive example1" style="width:100%;">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">Tgl. Transaksi Masuk</th>
                  <th scope="col">Stok Masuk</th>
                  <th scope="col">Aktor</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT id_aset, a.nama as barang, tgl_masuk, nomor_aset, b.nama as material, qrcode, e.stok as stok, jenis_tx, e.last_created, f.nama as users, e.last_created, qrcode, c.nama as satuan, d.nama as klasifikasi, g.nama as tipe, ketebalan, diameter, a.panjang as panjangaset, a.lebar as lebaraset, g.panjang as panjangtipe, g.lebar as lebartipe, a.id_klasifikasi as id_klasifikasi, a.id_material
                  FROM aset a 
                  INNER JOIN material_aset b on a.id_material=b.id
                  INNER JOIN satuan_aset c on a.id_satuan=c.id
                  INNER JOIN klasifikasi_aset d on a.id_klasifikasi=d.id
                  INNER JOIN transaksi e on a.id_aset=e.fk_id_aset
                  INNER JOIN user f on f.id=e.created_by
                  LEFT JOIN tipe_aset g on a.id_tipe=g.id
                  WHERE YEAR(tgl_tx)=$tahun AND jenis_tx='Masuk' order by e.last_created desc");

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
                      echo $hasilInput."<br><span class='badge bg-info'>".$row_dokumen->klasifikasi."</span><br>"."<span class='badge bg-primary'>".$row_dokumen->material."</span>"?></td>
                      <td><?php echo $info1;?></td>
                      <td><?php echo "<span class='badge bg-success'>".$row_dokumen->stok." ".$row_dokumen->satuan."</span>"
                    ?></td>
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
  $(document).ready(function() {
    // Fungsi untuk me-reload halaman saat modal ditutup
    $('#disablebackdrop').on('hidden.bs.modal', function () {
        document.location.reload();
    });

    $('#material').on('change', function() {
        var materialId = $(this).val();
        var klasifikasiSelect = $('#klasifikasi');
        var loading = $('#loading-klasifikasi');
        var penugasanWrapper = $('#penugasan-wrapper');
        var spkWrapper = $('#spk-wrapper');
        var vendorWrapper = $('#vendor-wrapper');
        var spkInput = $('#spk'); // ID dari input spk
        var penugasanInput = $('#penugasan'); // ID dari input spk
        var vendorSelect = $('#nama_vendor'); // ID dari dropdown vendor

        // Tampilkan elemen spk-wrapper dan tambahkan atribut required jika diperlukan
        spkWrapper.show();
        penugasanWrapper.show();
        spkInput.prop('required', true);
        penugasanInput.prop('required', true);

        // Tampilkan animasi loading
        loading.show();
        klasifikasiSelect.html('<option value="" disabled>Pilih Klasifikasi</option>');

        // Ambil klasifikasi berdasarkan material terpilih
        $.ajax({
            url: 'data/get_klasifikasi.php',
            type: 'GET',
            data: { id_material: materialId },
            dataType: 'json',
            success: function(data) {
                // Sembunyikan animasi loading
                loading.hide();

                // Kosongkan dropdown klasifikasi dan tambahkan pilihan
                klasifikasiSelect.html('<option selected disabled value="">Pilih Klasifikasi</option>');
                $.each(data, function(index, klasifikasi) {
                    klasifikasiSelect.append($('<option>', {
                        value: klasifikasi.id,
                        text: klasifikasi.nama
                    }));
                });
            },
            error: function(xhr, status, error) {
                loading.hide();
                console.error('Error fetching klasifikasi:', error);
                alert('Gagal memuat klasifikasi');
            }
        });

        // Tampilkan atau sembunyikan elemen berdasarkan materialId
        if (materialId == 2 || materialId == 3) {
            spkWrapper.show();
            penugasanWrapper.show();
            vendorWrapper.show();
            spkInput.prop('required', true);
            penugasanInput.prop('required', true);
            vendorSelect.prop('required', true);
        } else {
            spkWrapper.hide();
            penugasanWrapper.hide();
            vendorWrapper.hide();
            spkInput.prop('required', false);
            penugasanInput.prop('required', false);
            vendorSelect.prop('required', false);
        }
    });
  });
</script>


</body>

</html>
<?php
  }else{
    header('Location: 404');
  }
?>