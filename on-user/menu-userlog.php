<?php
  error_reporting(0);
  session_start();
  if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
   
  include "../koneksi.php";
  include "../random.php";
  include "../random-v2.php";
  // $id_user = $_SESSION['id'];
  // $nama_org = $_SESSION['nama_org'];
  // $id_org = $_SESSION['id_org'];
  // $kode_org2 = $_SESSION['kode_org'];
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
      <h1>User Log Activity</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">User Log Activity</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">User Log Activity</h5>
              <div class="table-responsive">
                <table id="example" class="table table-striped dt-responsive" style="width:100%;">
                  <thead>
                    <tr>
                      <th scope="col">No.</th>
                      <th scope="col">Nama Pengguna</th>
                      <th scope="col">Jenis Perangkat</th>
                      <th scope="col">Aplikasi</th>
                      <th scope="col">Konteks</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $result_dokumen = mysqli_query($connect,"SELECT * FROM userlog a INNER JOIN user b on a.fk_id_user=b.id order by id_log desc LIMIT 1000");

                      // INNER JOIN tbl_organisasi c on a.kode_org=c.kode_org LEFT OUTER JOIN tbl_manual_standar d on a.fk_id_manual_std=d.id_manual_std

                      if($result_dokumen) {
                        if($result_dokumen->num_rows > 0) {
                          while ($row_dokumen = $result_dokumen->fetch_object()) {
                            $no++;
                    ?>
                    <tr>
                        <td><?php echo "$no."; ?></td>
                      <td class="text-left"><?php echo $row_dokumen->nama."<br><span class='badge bg-dark'>".date('l, d F Y, H:i:s', strtotime($row_dokumen->waktu))."</span><br><span class='badge bg-primary'>".$row_dokumen->ip_address."</span>"; ?></td>
                      <td class="text-left"><?php echo $row_dokumen->agent; ?></td>
                      <td class="text-left"><?php echo $row_dokumen->aplikasi; ?></td>
                      <td class="text-left"> 
                        <?php 
                          if ($row_dokumen->komponen == "Presensi Rapat" || $row_dokumen->komponen == "Permintaan Rapat" || $row_dokumen->komponen == "Notulen Rapat" || $row_dokumen->komponen == "Tambah Peserta Rapat") {
                            $query1 = mysqli_query($connect,"SELECT * FROM tbl_rapat where id_rapat=$row_dokumen->event_context");
                            if($query1) {
                              if($query1->num_rows > 0) {
                                $row1 = mysqli_fetch_assoc($query1);
                                echo '<a href="menu-detail-rapat?id='.encrypt_decrypt2('encrypt', $row_dokumen->event_context).'">'.$row_dokumen->komponen.': '.$row1['title'].'</a>';
                              }
                            }
                          } elseif ($row_dokumen->komponen == "Akreditasi S1 ARS") {
                            $query1 = mysqli_query($connect,"SELECT * FROM tbl_akr_ars where id_akr_ars=$row_dokumen->event_context");
                            if($query1) {
                              if($query1->num_rows > 0) {
                                $row1 = mysqli_fetch_assoc($query1);
                                echo '<a href="menu-detail-akreditasi-ars?id='.encrypt_decrypt('encrypt', $row_dokumen->event_context).'">'.$row_dokumen->komponen.': '.$row1['aspek_nilai'].'</a>';
                              }
                            }
                          } elseif ($row_dokumen->komponen == "Akreditasi D3 RMIK") {
                            $query1 = mysqli_query($connect,"SELECT * FROM tbl_akr_rmik where id_akr_rmik=$row_dokumen->event_context");
                            if($query1) {
                              if($query1->num_rows > 0) {
                                $row1 = mysqli_fetch_assoc($query1);
                                echo '<a href="menu-detail-akreditasi-rmik?id='.encrypt_decrypt('encrypt', $row_dokumen->event_context).'">'.$row_dokumen->komponen.': '.$row1['aspek_nilai'].'</a>';
                              }
                            }
                          } elseif ($row_dokumen->komponen == "Akreditasi AIPT") {
                            $query1 = mysqli_query($connect,"SELECT * FROM tbl_akr_aipt where id_akr_aipt=$row_dokumen->event_context");
                            if($query1) {
                              if($query1->num_rows > 0) {
                                $row1 = mysqli_fetch_assoc($query1);
                                echo '<a href="menu-detail-akreditasi-aipt?id='.encrypt_decrypt('encrypt', $row_dokumen->event_context).'">'.$row_dokumen->komponen.': '.$row1['aspek_nilai'].'</a>';
                              }
                            }
                          } elseif ($row_dokumen->komponen == "Capaian Indikator") {
                            $query1 = mysqli_query($connect,"SELECT * FROM tbl_capaian_indikator where id=$row_dokumen->event_context");
                            if($query1) {
                              if($query1->num_rows > 0) {
                                $row1 = mysqli_fetch_assoc($query1);
                                echo '<a href="menu-detail-capaian?id='.encrypt_decrypt2('encrypt', $row_dokumen->event_context).'">'.$row_dokumen->komponen.': '.$row1['indikator'].'</a>';
                              }
                            }
                          } elseif ($row_dokumen->komponen == "IKI Kinerja") {
                            $query1 = mysqli_query($connect,"SELECT * FROM tbl_iki_kinerja where id='$row_dokumen->event_context'");
                            if($query1) {
                              if($query1->num_rows > 0) { 
                                $row1 = mysqli_fetch_assoc($query1);
                                echo '<a href="../../sdm/pages/detail_iki?id='.$row_dokumen->event_context.'">'.$row_dokumen->komponen.': '.$row1['uraian_kegiatan'].'</a>';
                              }
                            }
                          } else {
                            echo '<a href="#">'.$row_dokumen->komponen.'</a>';
                          }
                        ?>
                      </td>
                      <td class="text-left"><?php echo $row_dokumen->status_log; ?></td>
                    </tr>
                    <?php
                          }
                        } else {
                          echo "<tr>";
                            echo "<td colspan='7' class='text-center'>Data Tidak Tersedia</td>";
                          echo "</tr>";
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </div>
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


</body>

</html>
<?php
  }else{
    header('Location: 404');
  }
?>