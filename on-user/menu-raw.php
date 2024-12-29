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
  $active_page   = "barang_raw";  
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
      <h1>Barang Raw</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Barang Raw</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Barang Raw</h5>

              <table class="table table-striped dt-responsive example" style="width:100%;">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">QR Code</th>
                    <th scope="col">Klasifikasi</th>
                    <th scope="col">Nama Tipe</th>
                    <th scope="col">Min. Stok</th>
                    <th scope="col">Aksi</th>
                  </tr>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 0;
                    $result_dokumen = mysqli_query($connect,"SELECT a.id, qrcode, b.nama as klasifikasi, nama_tipe, a.deskripsi, minimun_qty, c.nama as satuan FROM tipe_raw a INNER JOIN klasifikasi_aset b ON a.id_klasifikasi=b.id INNER JOIN satuan_aset c ON a.id_satuan=c.id ORDER BY klasifikasi, nama_tipe ASC");

                    // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                    if($result_dokumen) {
                      if($result_dokumen->num_rows > 0) {
                        while ($row_dokumen = $result_dokumen->fetch_object()) {
                            $no++;
                  ?>
                  <tr>
                  <th scope="row"><?php echo "$no."; ?></th>
                    <td><img src="../qr_codes/<?php echo htmlspecialchars_decode($row_dokumen->qrcode); ?>" alt="QR Code" width="100"></td>
                    <td><?php echo htmlspecialchars_decode($row_dokumen->klasifikasi); ?></td>
                    <td><?php echo htmlspecialchars_decode($row_dokumen->nama_tipe); ?></td>
                    <td><?php echo htmlspecialchars_decode($row_dokumen->minimun_qty)." ".htmlspecialchars_decode($row_dokumen->satuan); ?></td>
                    <td> 
                      <a href="javascript:void(0)" onclick="location.href='menu-tambah-size-raw?id=<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>'" class="btn btn-primary btn-sm" title='Tambah Size Tipe'><i class="bi bi-diagram-3"></i></a>
                      <a href="javascript:void(0)" onclick="location.href='menu-riwayat-raw?id=<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>'" class="btn btn-primary btn-sm" title='Riwayat Barang'><i class="bi bi-clock-history"></i></a>
                      <a href="javascript:void(0)" onclick="location.href='menu-detail-raw?id=<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>'" class="btn btn-primary btn-sm" title='Detail Barang'><i class="bi bi-eye"></i></a>
                      <a href="javascript:void(0)" onclick="location.href='#?id=<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>'" class="btn btn-danger btn-sm" title='Cetak Barcode'><i class='bi bi-printer-fill'></i></a>
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
  function getTipeByKlasifikasi(idKlasifikasi) {
    // Meminta data tipe berdasarkan id klasifikasi yang dipilih
    fetch(`data/get_tipe_by_klasifikasi.php?id_klasifikasi=${idKlasifikasi}`)
    .then(response => response.text())
    .then(data => {
      // Masukkan data tipe ke dalam dropdown tipe
      document.getElementById("tipe-wrapper").innerHTML = `
          <label for="validationTooltip04" class="form-label">Pilih Tipe <span class="text-danger">*</span></label>
          <select name="tipe" class="form-select" required>
              <option selected disabled value="">-- Pilih --</option>
              ${data}
          </select>
          <div class="invalid-feedback">Wajib Diisi</div>
      `;
    })
    .catch(error => console.error('Error:', error));

    const klasifikasi = document.getElementById("klasifikasi").value;
    
    document.getElementById("ketebalan-input").style.display = "none";
    document.getElementById("diameter-input").style.display = "none";
    document.getElementById("panjang-input").style.display = "none";
    document.getElementById("lebar-input").style.display = "none";

    // Tampilkan input sesuai klasifikasi jika ada pilihan
    if (klasifikasi === "141") {
      document.getElementById("ketebalan-input").style.display = "block";
    } else if (klasifikasi === "142") {
      document.getElementById("diameter-input").style.display = "block";
      document.getElementById("panjang-input").style.display = "block";
    } else if(klasifikasi === "143"){
      document.getElementById("ketebalan-input").style.display = "block";
      document.getElementById("panjang-input").style.display = "block";
      document.getElementById("lebar-input").style.display = "block";
    }
  }

</script>


</body>

</html>
<?php
  }else{
    header('Location: 404');
  }
?>