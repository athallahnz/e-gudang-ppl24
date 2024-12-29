<?php
$active = 'class="active"';
  session_start();
?>
<script>
  $('.alert_notif').on('click',function(){
    var getLink = $(this).attr('href');
    Swal.fire({
      title: "Yakin ingin keluar?",
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
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <?php
         
        ?>
          <a class="nav-link <?php if($active_page != "home"){ echo "collapsed";} ?>" href="index">
          <i class="bi bi-grid"></i>
          <span>
          Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link <?php if($m_active_page != "profil") echo "collapsed" ?>" href="menu-profil" <?php if($active_page == "profil")echo $active ?>>
          <i class="bi bi-person"></i>
          <span>Profil</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <?php
        if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
      ?>
      <li class="nav-item">
        <a class="nav-link <?php if($m_active_page != "data-master") echo "collapsed" ?>" data-bs-target="#datamaster-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear-wide-connected"></i><span>Data Master</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="datamaster-nav" class="nav-content collapse <?php if($m_active_page == "data-master") echo "show"?>" data-bs-parent="#sidebar-nav">
        <?php
          if ($_SESSION['level'] == 'admin') {
        ?>
          <li>
            <a href="master-pengguna" <?php if($active_page == "master-pengguna") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Pengguna</span>
            </a>
          </li>
        <?php
          }
        ?>
          <li>
            <a href="master-rak" <?php if($active_page == "master-rak") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Rak</span>
            </a>
          </li>
          <li>
            <a href="master-material" <?php if($active_page == "master-material") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Material</span>
            </a>
          </li>
          <li>
            <a href="master-klasifikasi" <?php if($active_page == "master-klasifikasi") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Klasifikasi</span>
            </a>
          </li>
          <li>
            <a href="master-tipe" <?php if($active_page == "master-tipe") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Tipe</span>
            </a>
          </li>
          <li>
            <a href="master-ukuran" <?php if($active_page == "master-ukuran") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Ukuran</span>
            </a>
          </li>
          <li>
            <a href="master-satuan" <?php if($active_page == "master-satuan") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Satuan</span>
            </a>
          </li>
          <!-- <li>
            <a href="master-status" <?php if($active_page == "master-status") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Status</span>
            </a>
          </li> -->
          <li>
            <a href="master-lokasi" <?php if($active_page == "master-lokasi") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Lokasi</span>
            </a>
          </li>
          <li>

            <a href="master-vendor" <?php if($active_page == "master-vendor") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Vendor</span>
            </a>
          </li>
          <li>
            <a href="master-penugasan" <?php if($active_page == "master-penugasan") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Penugasan</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->
      

      <li class="nav-item">
        <a class="nav-link <?php if($m_active_page != "barang") echo "collapsed" ?>" data-bs-target="#barang-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-motherboard"></i><span>Barang</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="barang-nav" class="nav-content collapse <?php if($m_active_page == "barang") echo "show"?>" data-bs-parent="#sidebar-nav">
          <li>
            <a href="menu-consumable" <?php if($active_page == "barang_consumable") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Consumable</span>
            </a>
          </li>
          <li>
            <a href="menu-raw" <?php if($active_page == "barang_raw") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Raw </span>
            </a>
          </li>
          <li>  
            <a href="#" <?php if($active_page == "barang_intermediate") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Standard Part</span>
            </a>
          </li>
        </ul>
      </li>  
      <?php
        }
      ?> 
      
      <!-- Scan -->
      <li class="nav-item">
        <a class="nav-link <?php if($m_active_page != "scan") echo "collapsed" ?>" href="menu-scan" <?php if($active_page == "scan")echo $active ?>>
          <i class="bi bi-qr-code"></i>
          <span>Scan</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <?php
        if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
      ?>
      <!-- Stok -->
      <li class="nav-item">
        <a class="nav-link <?php if($m_active_page != "peringatan") echo "collapsed" ?>" href="menu-peringatan-stok" <?php if($active_page == "peringatan")echo $active ?>>
          <i class="bi bi-exclamation-triangle"></i>
          <span>Peringatan Stok</span> &nbsp;
          <?php
            // Eksekusi query
            $result = mysqli_query($connect, 
            "SELECT COUNT(*) AS jumlah_stok_kurang
            FROM aset a
            WHERE a.qty <= qty_minimum");
              // Eksekusi query
            $result2 = mysqli_query($connect,
            "SELECT COUNT(*) AS jumlah_stok_kurang
              FROM aset_raw a 
              INNER JOIN tipe_raw b on a.id_tipe=b.id
              WHERE qty <= minimun_qty");

              
              // Cek apakah query berhasil
              if ($result && $result2) {
                $row = mysqli_fetch_assoc($result);
                $row2 = mysqli_fetch_assoc($result2);
                $jumlah_stok_kurang2 = $row2['jumlah_stok_kurang'];
                $jumlah_stok_kurang = $row['jumlah_stok_kurang'];
                
                $total = $jumlah_stok_kurang + $jumlah_stok_kurang2;
                if ($total > 0) {
                  echo "<span class='badge bg-danger'>".$total."</span>";
                } 
              // Jika jumlah stok kurang lebih dari 0, tampilkan badge danger
              }
          ?>

        </a>
      </li><!-- End Capaian Page Nav -->
      <?php
        if ($_SESSION['level'] == 'operator' || $_SESSION['level'] == 'admin') {
      ?>
      <!-- Laporan -->
      <li class="nav-item">
        <a class="nav-link <?php if($m_active_page != "laporan") echo "collapsed" ?>" data-bs-target="#lap-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-receipt"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="lap-nav" class="nav-content collapse <?php if($m_active_page == "laporan") echo "show"?>" data-bs-parent="#sidebar-nav">
          <li>
            <a href="menu-transaksi-in?tahun=<?php echo date('Y')?>" <?php if($active_page == "lap_in") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Masuk</span>
            </a>
          </li>
          <li>
            <a href="menu-transaksi-out?tahun=<?php echo date('Y')?>" <?php if($active_page == "lap_out") echo $active; ?>>
              <i class="bi bi-circle"></i><span>Keluar</span>
            </a>
          </li>
        </ul>
      </li> 
      <?php
        }
      ?>
      

      <li class="nav-item">
        <a class="nav-link <?php if($m_active_page != "log") echo "collapsed" ?>" href="menu-userlog" <?php if($active_page == "log")echo $active ?>>
        <i class="bi bi-activity"></i>
          <span>Log Aktifitas</span>
        </a>
      </li>
      <li>
      <?php
        }
      ?>
      <li class="nav-item">
        <a href="check-logout" class="nav-link collapsed alert_notif">
          <i class="bi bi-box-arrow-right"></i>
          <span>Logout</span>
        </a>
      </li><!-- End Profile Page Nav -->


      <!-- <li class="nav-item">
      <a class="nav-link collapsed alert_notif" href="check-logout">
          <i class="bi bi-box-arrow-right"></i>
          <span>Keluar</span>
        </a>
      </li> -->


    </ul>
  </aside>