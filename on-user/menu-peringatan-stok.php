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
  $m_active_page = "peringatan";
  $active_page   = "peringatan";  
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
      <h1>Peringatan Stok</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Peringatan Stok</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->


    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Peringatan Stok</h5>

        <!-- Bordered Tabs Justified -->
        <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">Consumable 
            <?php
              // Eksekusi query
              $result = mysqli_query($connect, 
                "SELECT COUNT(*) AS jumlah_stok_kurang
                FROM aset a
                WHERE a.qty <= qty_minimum");

              // Cek apakah query berhasil
              if ($result) {
                $row = mysqli_fetch_assoc($result);
                $jumlah_stok_kurang = $row['jumlah_stok_kurang'];
                // Jika jumlah stok kurang lebih dari 0, tampilkan badge danger
                if ($jumlah_stok_kurang > 0) {
                    echo "<span class='badge bg-danger'>".$jumlah_stok_kurang."</span>";
                }
              }
            ?>




            </button>
          </li>
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Raw
            <?php
              // Eksekusi query
              $result2 = mysqli_query($connect,
              "SELECT COUNT(*) AS jumlah_stok_kurang
              FROM aset_raw a 
              INNER JOIN tipe_raw b on a.id_tipe=b.id
              WHERE qty <= minimun_qty");

              // Cek apakah query berhasil
              if ($result2) {
                $row2 = mysqli_fetch_assoc($result2);
                $jumlah_stok_kurang2 = $row2['jumlah_stok_kurang'];
                // Jika jumlah stok kurang lebih dari 0, tampilkan badge danger
                if ($jumlah_stok_kurang2 > 0) {
                    echo "<span class='badge bg-danger'>".$jumlah_stok_kurang2."</span>";
                }
              }
            ?>


            </button>
          </li>
          <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Intermediate Goods</button>
          </li>
        </ul>
        <div class="tab-content pt-2" id="borderedTabJustifiedContent">
          <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel" aria-labelledby="home-tab">
            <table id="example1" class="table table-striped dt-responsive example" style="width:100%;">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Klasifikasi</th>
                  <th scope="col">Item</th>
                  <th scope="col">Stok</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $no = 0;
                  $result_dokumen = mysqli_query($connect,"SELECT id_aset, a.nama, tgl_masuk, nomor_aset, b.nama as material, qty, qty_minimum, c.nama as satuan, d.nama as klasifikasi
                  FROM aset a 
                  INNER JOIN material_aset b on a.id_material=b.id
                  INNER JOIN satuan_aset c on a.id_satuan=c.id
                  INNER JOIN klasifikasi_aset d on a.id_klasifikasi=d.id
                  WHERE a.qty <= qty_minimum order by qty desc");

                  // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                  if($result_dokumen) {
                    if($result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                          $no++;
                ?>
                <tr>
                  <th scope="row"><?php echo "$no."; ?></th>
                  <td><?php echo $row_dokumen->klasifikasi ?></td>
                  <td><?php echo $row_dokumen->nama?></td>
                  <td>
                    <?php 
                      echo "<span class='badge bg-danger'>Stok skrg: ".$row_dokumen->qty." ".$row_dokumen->satuan."</span><br>";
                      echo "<span class='badge bg-success'>Min. stok: ".$row_dokumen->qty_minimum." ".$row_dokumen->satuan."</span>";
                    ?>
                  </td>
                  <td><a href="javascript:void(0)" onclick="location.href='menu-tambah-stok?id=<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id_aset); ?>'" class="btn btn-primary btn-sm" title='Tambah Stok'><i class="bi bi-plus-square"></i></a></td>
                </tr>
                <?php
                      }
                    } 
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">
          <table id="example2" class="table table-bordered table-striped example" style="width:100%;">
            <thead>
              <tr>
                <th scope="col">No.</th>
                <th scope="col">Klasifikasi</th>
                <th scope="col">Tipe</th>
                <th scope="col">Stok</th>
                <th scope="col">Vendor</th>
                <th scope="col">Penugasan</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $no = 0;
                $result_dokumen = mysqli_query($connect,"SELECT a.id, qty, a.last_created, a.last_created, panjang, lebar, diameter, ketebalan, c.nama as vendor, d.nama as penugasan, nama_tipe, b.id_klasifikasi, e.nama as klasifikasi, minimun_qty
                FROM aset_raw a 
                INNER JOIN tipe_raw b on a.id_tipe=b.id
                INNER JOIN vendor_aset c on a.id_vendor=c.id 
                INNER JOIN penugasan_aset d on a.id_penugasan=d.id
                INNER JOIN klasifikasi_aset e on b.id_klasifikasi=e.id
                WHERE qty <= minimun_qty order by a.last_created desc");

                // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                if($result_dokumen) {
                  if($result_dokumen->num_rows > 0) {
                    while ($row_ukuran = $result_dokumen->fetch_object()) {
                        $no++;
              ?>
              <tr>
                <th scope="row"><?php echo "$no."; ?></th>
                <td><?php echo $row_ukuran->klasifikasi ?></td>
                <td>
                  <?php 
                    echo $row_ukuran->nama_tipe."<br><span class='badge bg-primary'>";
                    if ($row_ukuran->id_klasifikasi == 141 || $row_ukuran->id_klasifikasi == 143) {
                      echo $row_ukuran->ketebalan." x ".$row_ukuran->panjang." x ".$row_ukuran->lebar;
                    } elseif ($row_ukuran->id_klasifikasi == 142) {
                      echo $row_ukuran->diameter." x ".$row_ukuran->panjang;
                    }
                    echo "</span>"
                  ?></td>
                <td>
                  <?php 
                     echo "<span class='badge bg-danger'>Stok skrg: ".$row_ukuran->qty." ".$row_ukuran->satuan."</span><br>";
                     echo "<span class='badge bg-success'>Min. stok: ".$row_ukuran->minimun_qty." ".$row_ukuran->satuan."</span>";
                  ?></td>
                <td><?php echo $row_ukuran->vendor ?></td>
                <td><?php echo $row_ukuran->penugasan ?></td>
              </tr>
              <?php
                    }
                  } 
                }
              ?>
            </tbody>
          </table>

          </div>
          <div class="tab-pane fade" id="bordered-justified-contact" role="tabpanel" aria-labelledby="contact-tab">
           
          </div>
        </div><!-- End Bordered Tabs Justified -->

      </div>
    </div>
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

  $(document).ready(function() {
    $('#material').on('change', function() {
        var materialId = $(this).val();
        var klasifikasiSelect = $('#klasifikasi');
        var loading = $('#loading-klasifikasi');
        var penugasanWrapper = $('#penugasan-wrapper');
        var spkWrapper = $('#spk-wrapper');
        var vendorWrapper = $('#vendor-wrapper');

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

                // Kosongkan dropdown klasifikasi
                klasifikasiSelect.html('<option selected disabled value="">Pilih Klasifikasi</option>');

                // Tambahkan pilihan klasifikasi yang diterima dari server
                $.each(data, function(index, klasifikasi) {
                    klasifikasiSelect.append($('<option>', {
                        value: klasifikasi.id,
                        text: klasifikasi.nama
                    }));
                });
            },
            error: function(xhr, status, error) {
                // Sembunyikan animasi loading
                loading.hide();
                console.error('Error fetching klasifikasi:', error);
                alert('Gagal memuat klasifikasi');
            }
        });

        // Tampilkan dropdown penugasan, input SPK, dan dropdown vendor jika material adalah raw material (2) atau intermediate goods (3)
        if (materialId == '2' || materialId == '3') {
            penugasanWrapper.show();
            spkWrapper.show();
            vendorWrapper.show();
        } else {
            penugasanWrapper.hide();
            spkWrapper.hide();
            vendorWrapper.hide();
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