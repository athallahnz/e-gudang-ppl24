<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>
    <?php
      $query = mysqli_query($connect, "SELECT * FROM pn_setting where id=1");
      $row = $query->fetch_object();
      echo $row->isi;
      $query2 = mysqli_query($connect, "SELECT * FROM pn_setting where id=3");
      $row2 = $query2->fetch_object();
    ?>
  </title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/<?php echo $row2->isi; ?>" rel="icon">
  <link href="assets/img/<?php echo $row2->isi; ?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="assets/css/font.css" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    /* Style for the radio button container */
    label {
      display: flex;
      align-items: center;
      cursor: pointer;
    }

    /* Style for the custom radio button (circle) */
    input[type="radio"] {
      width: 20px; /* Diameter of the circle */
      height: 20px; /* Diameter of the circle */
      border: 2px solid #333; /* Border color of the circle */
      border-radius: 50%; /* Make it a circle */
      margin-right: 8px; /* Adjust the margin as needed */
    }

    /* Style for the checked state of the radio button */
    input[type="radio"]:checked {
      background-color: #007bff; /* Change the background color as desired */
      border-color: #007bff; /* Change the border color as desired */
    }

    /* Style for the label text when radio button is checked */
    input[type="radio"]:checked + label {
      font-weight: bold; /* Optionally, make the text bold when checked */
    }
  </style>
  <script src="assets/js/sweetalert2.all.min.js"></script>
  <!-- =======================================================
  * Template Name: NiceAdmin - v2.4.1
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

</head>