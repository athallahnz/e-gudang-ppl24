<?php
  error_reporting(0);
  session_start();
  include "../koneksi.php";
  include "../random-v2.php";
  include "tgl_indo.php";
  $active_page = "home";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

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
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <a href="#" role="button">
                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">IN <span>| Barang</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-activity"></i>
                      </div>
                      <div class="ps-3">
                        <h6>
                          <?php
                            echo round(101, 2);
                            ?>
                        </h6> <span class="text-muted small pt-2 ps-1">Item</span>

                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <a href="#" role="button">
                <div class="card info-card revenue-card">
                  <div class="card-body">
                    <h5 class="card-title">OUT <span>| Barang</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-activity"></i>
                      </div>
                      <div class="ps-3">
                        <h6>
                          <?php
                            echo round(78, 2);
                            ?>
                        </h6><span class="text-muted small pt-2 ps-1">Item</span>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">
              <a href="#" role="button">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">TOTAL <span>| Barang</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-activity"></i>
                      </div>
                      <div class="ps-3">
                        <h6>
                          <?php
                            
                            echo round(179, 2);
                          ?>
                        </h6> <span class="text-muted small pt-2 ps-1">Item</span>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div><!-- End Customers Card -->
          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">
          

        </div><!-- End Right side columns -->

      </div>

    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
 <?php
  include "_footer.php"
 ?>
<script>
    function displayMessage(message) {
            $(".response").html("<div class='success'>"+message+"</div>");
        setInterval(function() { $(".success").fadeOut(); }, 1000);
    }
    
</script>

<?php    
  if (@$_SESSION['sukses-verif']) {?>
    <script>
        Swal.fire("Terimakasih!", "<?php echo $_SESSION['sukses-verif']; ?>", "success");
    </script> 
<?php    
  unset($_SESSION['sukses-verif']); 
    }elseif (@$_SESSION['gagal-verif']) {?>
      <script>
          Swal.fire("Mohon Maaf!", "<?php echo $_SESSION['gagal-verif']; ?>", "error");
      </script> 
<?php
    unset($_SESSION['gagal-verif']); 
  } 
?>
</body>

</html>