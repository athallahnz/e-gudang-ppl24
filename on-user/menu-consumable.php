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
  $m_active_page = "barang";
  $active_page   = "barang_consumable";  
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
      <h1>Barang Consumable</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Barang</a></li>
          <li class="breadcrumb-item active">Barang Consumable</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Barang Consumable</h5>
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#disablebackdrop"><i class="bi bi-plus me-1" ></i> Barang Baru</button>
               <!-- Modal Rapat Baru -->
               <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="true">
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
                            $result_jenis = mysqli_query($connect,"SELECT * FROM material_aset WHERE id=1 ORDER BY nama asc");
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
                          <option selected disabled value="">-- Pilih --</option>
                          <?php
                            $result_jenis = mysqli_query($connect,"SELECT * FROM klasifikasi_aset WHERE id_material=1 ORDER BY nama asc");
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
                  </form><!-- End Custom Styled Validation -->
                  </div>
                </div>
              </div>
              <!-- End Modal Dok Baru-->
              <table id="example" class="table table-striped dt-responsive example" style="width:100%;">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">QR Code</th>
                    <th scope="col">Klasifikasi</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Stok Terkini</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 0;
                    $result_dokumen = mysqli_query($connect,"SELECT id_aset, a.nama, tgl_masuk, nomor_aset, b.nama as material, qrcode, qty, c.nama as satuan, d.nama as klasifikasi
                    FROM aset a 
                    INNER JOIN material_aset b on a.id_material=b.id
                    INNER JOIN satuan_aset c on a.id_satuan=c.id
                    INNER JOIN klasifikasi_aset d on a.id_klasifikasi=d.id
                    WHERE a.id_material=1 order by id_aset desc");

                    // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                    if($result_dokumen) {
                      if($result_dokumen->num_rows > 0) {
                        while ($row_dokumen = $result_dokumen->fetch_object()) {
                            $no++;
                  ?>
                  <tr>
                    <th scope="row"><?php echo "$no."; ?></th>
                    <td><img src="../qr_codes/<?php echo htmlspecialchars_decode($row_dokumen->qrcode); ?>" alt="QR Code" width="100"></td>
                    <td><?php echo $row_dokumen->klasifikasi."<br>"."<span class='badge bg-success'>".$row_dokumen->material."</span>" ?></td>
                    <td><?php echo $row_dokumen->nama."<br>"."<b>Tgl Masuk: </b>".format_hari_tanggal($row_dokumen->tgl_masuk);          
                    
                    ?></td>
                    <td><?php echo $row_dokumen->qty." ".$row_dokumen->satuan ?></td>
                    <td>
                      <a href="javascript:void(0)" onclick="location.href='menu-tambah-stok?id=<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id_aset); ?>'" class="btn btn-primary btn-sm" title='Tambah Stok'><i class="bi bi-plus-square"></i></a>
                      <?php 
                        echo"<a href='#?id=".encrypt_decrypt2('encrypt', $row_dokumen->id_aset)."' class='btn btn-primary btn-sm' title='Edit Barang'><i class='bi bi-pencil-square'></i></a>";
                    ?>
                      <a href="javascript:void(0)" onclick="location.href='menu-riwayat-consumable?id=<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id_aset); ?>'" class="btn btn-primary btn-sm" title='Riwayat Barang'><i class="bi bi-clock-history"></i></a>

                      <a href="javascript:void(0)" onclick="location.href='menu-detail-barang?id=<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id_aset); ?>'" class="btn btn-primary btn-sm" title='Detail Barang'><i class="bi bi-eye"></i></a>
                      
                      <a href="javascript:void(0)" onclick="location.href='#?id=<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id_aset); ?>'" class="btn btn-danger btn-sm" title='Cetak Barcode'><i class='bi bi-printer-fill'></i></a>
                    </td>
                  </tr>
                  <?php
                        }
                      } 
                    }
                  ?>
                </tbody>
              </table>
              <!-- End Default Table Example -->

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
  });
</script>
</body>
</html>
<?php
  }else{
    header('Location: 404');
  }
?>