<?php
  session_start();
  if(!isset($_SESSION['id'])) {
    header('location:../index');
  } 
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>
    <?php 
      $query = mysqli_query($connect, "SELECT * FROM pn_setting where id=1");
      $row = $query->fetch_object();
      echo $row->isi; 

      $query1 = mysqli_query($connect, "SELECT * FROM pn_setting where id=3");
      $row1 = $query1->fetch_object();
    ?>
  </title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/<?php echo $row1->isi ?>" rel="icon">
  <link href="../assets/img/<?php echo $row1->isi ?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="../assets/css/font.css" rel="stylesheet">
  
  <!-- Select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  
  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  <!-- Datatables -->
  <link rel="stylesheet" href="../assets/css/dataTables.bootstrap5.min.css"/>
  <link rel="stylesheet" href="../assets/css/responsive.bootstrap5.min.css"/>
  <link rel="stylesheet" type="text/css" href="../assets/css/datatables.min.css"/>
  <link rel="stylesheet" href="../libraries/fullcalendar/fullcalendar.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  
  <style>
    .valid {
    color: green;
    }
    .invalid {
        color: red;
    }
    .custom-input {
        display: none; /* Initial state is hidden */
    }
  </style>
  <!-- Select2 -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

  <script src="../assets/js/sweetalert2.all.min.js"></script>
  <script src="../assets/js/dataTables.bootstrap5.min.js"></script>
  <!-- <script src="../assets/js/jquery-3.5.1.js"></script> -->
  <script src="../assets/js/jquery.dataTables.min.js"></script>
  <script src="../assets/js/dataTables.responsive.min.js"></script>
  <script src="../assets/js/responsive.bootstrap5.min.js"></script>
  <script src="../assets/js/datatables.min.js"></script>
  <script src="../assets/js/dselect.js"></script>
  <!-- <script src="../libraries/fullcalendar/lib/jquery.min.js"></script> -->
  <script src="../libraries/fullcalendar/lib/moment.min.js"></script>
  <script src="../libraries/fullcalendar/fullcalendar.min.js"></script>
  <script src = "../assets/js/daterangepicker.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!-- Scan QR Code -->
  <script src="../assets/js/html5-qrcode.min.js"></script>
  <!-- DataTables Buttons -->
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

  <script>
    $('.alert_notif').on('click',function(){
      var getLink = $(this).attr('href');
      Swal.fire({
        title: "Apa Anda Yakin?",
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

    $('.alert_delete').on('click',function(){
      var getLink = $(this).attr('href');
      Swal.fire({
        title: "Data tidak dapat dipulihkan setelah dihapus. Anda Yakin Ingin Hapus Data ini?",
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
  
  <style>
      #scroll-progress {
        /* position: fixed;
        top: 0;
        width: 0%;
        height: 4px;
        background: #4154f1;
        z-index: 10000; */
        position: fixed;
        top: 0;
        width: 0%;
        height: 4px;
        background: linear-gradient(to right, #4154f1, #049AF7);
        z-index: 10000;
        transition: width 0.2s ease-out;
      }

      #calendar {
        width: auto;
        margin: 0 auto;
      }

      .response {
          height: 5px;
      }

      .success {
          background: #cdf3cd;
          padding: 10px 60px;
          border: #c3e6c3 1px solid;
          display: inline-block;
      }
      .dt-wrap {
            white-space: normal !important;
        }
  </style>
  
  <!-- =======================================================
  * Template Name: NiceAdmin - v2.4.1
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>