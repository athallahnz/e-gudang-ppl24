<footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>PLN Pusharlis</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information:    -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
    </div>
</footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

  <script>
    $('.alert_notif').on('click',function(){
      var getLink = $(this).attr('href');
      Swal.fire({
        title: "Anda Yakin Ingin Keluar?",
        // text: "Anda tidak akan dapat mengembalikan ini!",            
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Ya',
        cancelButtonColor: '#d33',
        cancelButtonText: "Batal"
      }).then((result) => {
        //jika klik ya maka arahkan ke proses.php
        if(result.isConfirmed){
            window.location.href = getLink
        } else if (result.isDenied) {
          Swal.fire('Changes are not saved', '', 'info')
        }
      })
      return false;
    });
  </script>
<script>
  $(document).on('click', '.alert_delete', function(e){
    e.preventDefault();
    var getLink = $(this).attr('href');
    Swal.fire({
      title: "Anda yakin ingin menghapus data ini? Data yang dihapus tidak bisa dipulihkan.",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Ya',
      cancelButtonColor: '#d33',
      cancelButtonText: "Batal"
    }).then((result) => {
      if(result.isConfirmed){
          window.location.href = getLink;
      } else if (result.isDenied) {
          Swal.fire('Changes are not saved', '', 'info')
      }
    });
  });

  $(document).on('click', '.alert_delete2', function(e){
    e.preventDefault();
    var getLink = $(this).attr('href');
    Swal.fire({
      title: "Semua proses audit (plan, checklist, temuan, dan PTK) didalam Audit Plan ini akan dihapus permanen. Anda yakin ingin menghapus data ini?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Ya',
      cancelButtonColor: '#d33',
      cancelButtonText: "Batal"
    }).then((result) => {
      if(result.isConfirmed){
          window.location.href = getLink;
      } else if (result.isDenied) {
          Swal.fire('Changes are not saved', '', 'info')
      }
    });
  });
  $(document).on('click', '.alert_delete3', function(e){
    e.preventDefault();
    var getLink = $(this).attr('href');
    Swal.fire({
      title: "Semua proses audit (checklist, temuan, dan PTK) kecuali Audit Plan yang terkait akan dihapus permanen. Anda yakin ingin menghapus data ini?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Ya',
      cancelButtonColor: '#d33',
      cancelButtonText: "Batal"
    }).then((result) => {
      if(result.isConfirmed){
          window.location.href = getLink;
      } else if (result.isDenied) {
          Swal.fire('Changes are not saved', '', 'info')
      }
    });
  });
</script>


  <script>
    // $(document).ready(function() {
    //     $('#example').DataTable();
    // });
    $(function() {
      $(".example").each(function() {
          $(this).DataTable({
              "responsive": true, // Responsif
              "lengthChange": false, // Hilangkan dropdown "Show x entries"
              "autoWidth": false, // Tidak auto-width
              "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"] // Tombol ekspor dan visibilitas kolom
          }).buttons().container()
          .appendTo($(this).closest('.dataTables_wrapper').find('.col-md-6:eq(0)')); // Tempatkan tombol di lokasi tertentu
      });
    });

    var select_box_element = document.querySelector('#select_box');
    dselect(select_box_element, {
      search: true
    });

    $('.date-own').datepicker({
      minViewMode: 2,
      format: 'yyyy'
    });
  </script>

<script>
  // When the user scrolls the page, execute myFunction 
  window.onscroll = function() {myFunction()};

  function myFunction() {
    var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    var scrolled = (winScroll / height) * 100;
    document.getElementById("scroll-progress").style.width = scrolled + "%";
  }
</script>