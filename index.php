<!DOCTYPE html>
<html lang="en">
<?php
  include 'koneksi.php';
  include "_head.php";

  // Mengatur beberapa header keamanan
  header('X-Frame-Options: DENY'); // Mencegah clickjacking
  header('X-Content-Type-Options: nosniff'); // Mencegah MIME-type sniffing
  header('Referrer-Policy: no-referrer'); // Menyembunyikan referer
  header('X-XSS-Protection: 1; mode=block'); // Mengaktifkan XSS Protection pada browser lama

  // Mulai sesi dengan pengaturan keamanan tambahan
  session_start([
      'cookie_lifetime' => 0, // Sesi akan berakhir ketika browser ditutup
      'cookie_secure' => isset($_SERVER['HTTPS']), // Mengamankan cookie (hanya HTTPS)
      'cookie_httponly' => true, // Melindungi cookie dari JavaScript
      'use_strict_mode' => true, // Menghindari sesi ID tidak sah
      'use_only_cookies' => true, // Hanya gunakan cookie untuk menyimpan sesi
      'sid_bits_per_character' => 6,
      'sid_length' => 48,
  ]);

  // Periksa apakah pengguna sudah login
  if (isset($_SESSION['id'])) {
    echo "<script>window.location = 'on-user';</script>";
    exit();
  }
?>

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <?php include "_logo.php"; ?>

              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Masuk ke Akun Anda</h5>
                    <p class="text-center small">Masukkan nama pengguna & kata sandi Anda untuk masuk</p>
                  </div>

                  <form action="check-login" method="POST" class="row g-3 needs-validation" novalidate>
                    <?php
                      // Generate token CSRF
                      $_SESSION['token'] = bin2hex(random_bytes(35));
                    ?>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-fill"></i></span>
                        <input type="email" name="username" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Silakan masukkan nama pengguna Anda.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Kata Sandi</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-key-fill"></i></span>
                        <input type="password" name="password" class="form-control" id="yourPassword" required>
                        <div class="invalid-feedback">Silakan masukkan kata sandi Anda!</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <input name="submit" class="btn btn-primary w-100" type="submit" value="Masuk">
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                <!-- Design and credits -->
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<?php 
// Menampilkan pesan error atau konfirmasi menggunakan SweetAlert
if (isset($_SESSION['gagalLogin'])) { ?>
    <script>
        Swal.fire("Mohon Maaf!", "<?php echo $_SESSION['gagalLogin']; ?>", "error");
    </script>
<?php 
  unset($_SESSION['gagalLogin']); 
} elseif (isset($_SESSION['lockedOut'])) { ?>
    <script>
        Swal.fire("Terlalu Banyak Percobaan!", "Anda telah mencapai batas maksimum percobaan login. Silakan coba lagi nanti.", "error");
    </script>
<?php 
  unset($_SESSION['lockedOut']);
} elseif (isset($_SESSION['aksesTolak'])) { ?>
    <script>
        Swal.fire("Mohon Maaf!", "<?php echo $_SESSION['aksesTolak']; ?>", "error");
    </script> 
<?php    
  unset($_SESSION['aksesTolak']); 
} elseif (isset($_SESSION['sukses-verif'])) { ?>
    <script>
        Swal.fire("Terimakasih!", "<?php echo $_SESSION['sukses-verif']; ?>", "success");
    </script> 
<?php    
  unset($_SESSION['sukses-verif']); 
} 
?>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
