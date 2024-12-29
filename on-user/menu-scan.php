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
      <h1>Scan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Scan</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-4">
              <a href="menu-scan-consumable" role="button">
                <div class="card info-card sales-card bg-danger bg-gradient">
                  <div class="card-body">
                    <h5 class="card-title">CONSUMABLE <span>| Material</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-qr-code-scan"></i>
                      </div>
                      <div class="ps-3">
                        <h6>Scan</h6> 
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-3">
              <a href="menu-scan-raw" role="button">
                <div class="card info-card revenue-card bg-warning bg-gradient">
                  <div class="card-body">
                    <h5 class="card-title">RAW <span>| Material</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-qr-code-scan"></i>
                      </div>
                      <div class="ps-3">
                        <h6>Scan</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-md-5">
              <a href="#" role="button">
                <div class="card info-card customers-card bg-success bg-gradient">
                  <div class="card-body">
                    <h5 class="card-title">STANDARD PART<span> | Material</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-qr-code-scan"></i>
                      </div>
                      <div class="ps-3">
                        <h6>
                         Scan
                        </h6>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div><!-- End Customers Card -->
          </div>
        </div><!-- End Left side columns -->

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