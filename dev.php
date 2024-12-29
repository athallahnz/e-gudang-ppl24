<!DOCTYPE html>
<html lang="en">
<?php
  include "_head.php";
  include "koneksi.php";
?>

<body>
  
  <?php 
    error_reporting(0);
    session_start();

  ?>
 
  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <table id="example" class="table table-striped dt-responsive nowrap" style="width:100%;">
        <thead>
            <tr>
            <th scope="col">No.</th>
            <th scope="col">NIM</th>
            <th scope="col">Name</th>
            <th scope="col">Entry Year</th>
            <th scope="col">Address</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $no = 0;
        $result_dokumen = mysqli_query($connectKD2,"SELECT * FROM student");
        if($result_dokumen) {
            if($result_dokumen->num_rows > 0) {
            while ($row_dokumen = $result_dokumen->fetch_object()) {
                $no++;
        ?>
            <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $row_dokumen->nim; ?></td>
            <td><?php echo $row_dokumen->name; ?></td>
            <td><?php echo $row_dokumen->entry_year; ?></td>
            <td><?php echo $row_dokumen->address; ?></td>
            </tr>
        <?php
                }
            } else {
                echo "<tr>";
                echo "<td colspan='4' class='text-center'>Data Tidak Tersedia</td>";
                echo "</tr>";
            }
            $no++;
            }
        ?>
        </tbody>
        </table>
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