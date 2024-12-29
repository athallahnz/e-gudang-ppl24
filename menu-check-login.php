<?php
    error_reporting(0);
    session_start();
    require 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<?php
  include "_head.php"
?>

<body>
  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <?php
                include "_logo.php"
              ?>

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Mengakses website ini sebagai</h5>
                    <p class="text-center small">Silahkan pilih unit kerja anda</p>
                  </div>

                  <form action="pilih" method="POST" class="row g-3 needs-validation" novalidate>
                    <input type="hidden" name="id"  value="<?php echo $row['id']; ?>" />
                    <input type="hidden" name="username"  value="<?php echo $row['username']; ?>" />
                    <input type="hidden" name="password"  value="<?php echo $row['password']; ?>" />
                    <div class="col-12">
                      <?php
                        if ($row['kode_org']!=null) {
                          $result_org = mysqli_query($connect, "SELECT kode_org, nama_org FROM tbl_organisasi WHERE kode_org='".$row['kode_org']."'");
                          if($result_org->num_rows > 0) {
                              $row_org = $result_org->fetch_object();
                              $nama_org = $row_org->nama_org;
                              $kode_org = $row_org->kode_org;
                              echo '<input type="radio" name="kode_org" class="form-check-input" id="yourUsername" value="'.$kode_org.'" required> <label class="form-check-label" for="gridRadios1">'.$nama_org.'</label> <br>';
                              // echo '<label class="radio-inline"><input type="radio" name="kode_org" value ="'.$kode_org.'" checked>'.$nama_org.'</label><br>';
                          }
                        } 
                        if ($row['kode_org_2']!=null) {
                          $result_org = mysqli_query($connect, "SELECT kode_org, nama_org FROM tbl_organisasi WHERE kode_org='".$row['kode_org_2']."'");
                          if($result_org->num_rows > 0) {
                              $row_org = $result_org->fetch_object();
                              $nama_org = $row_org->nama_org;
                              $kode_org = $row_org->kode_org;
                              echo '<input type="radio" name="kode_org" class="form-check-input" id="yourUsername" value="'.$kode_org.'" required> <label class="form-check-label" for="gridRadios1">'.$nama_org.'</label> <br>';
                          }
                        } 
                        if ($row['kode_org_3']!=null) {
                          $result_org = mysqli_query($connect, "SELECT kode_org, nama_org FROM tbl_organisasi WHERE kode_org='".$row['kode_org_3']."'");
                          if($result_org->num_rows > 0) {
                              $row_org = $result_org->fetch_object();
                              $nama_org = $row_org->nama_org;
                              $kode_org = $row_org->kode_org;
                              echo '<input type="radio" name="kode_org" class="form-check-input" id="yourUsername" value="'.$kode_org.'" required> <label class="form-check-label" for="gridRadios1">'.$nama_org.'</label> <br>';
                          }
                        }                     
                      ?>
                    </div>
                    <div class="col-12">
                      <input name="submit" class="btn btn-primary w-100" type="submit" value="Masuk">
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
</body>

</html>