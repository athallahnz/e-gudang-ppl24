<?php
error_reporting(0);
session_start();
include "../koneksi.php";
include "../random-v2.php";

// Pastikan ID rak tersedia di URL dan valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: 404');
    exit();
}

// Menggunakan fungsi decrypt untuk mendapatkan ID rak
$id_pengguna = encrypt_decrypt2('decrypt', $_GET['id']);

if (!$id_pengguna) {
    header('Location: 404');
    exit();
}

// Menggunakan prepared statements untuk menghindari SQL Injection
$stmt = $connect->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$result_dokumen = $stmt->get_result();

if ($result_dokumen && $result_dokumen->num_rows > 0) {
    $row_dokumen = $result_dokumen->fetch_object();    
    $userRole = $row_dokumen->level; // role yang tersimpan di database
    $userStatus = $row_dokumen->status; // role yang tersimpan di database

} else {
    // Jika data tidak ditemukan
    header('Location: 404');
    exit();
}
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
      <h1>Detail Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="master-pengguna">Master Pengguna</a></li>
          <li class="breadcrumb-item active">Detail Data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Detail Data</h5>

                <div class="col-md-12">
                  <label for="pengguna" class="form-label">Nama Lengkap </label>
                  <input type="text" class="form-control" name="pengguna" value="<?php echo htmlspecialchars_decode($row_dokumen->nama); ?>" disabled>
                  <div class="invalid-feedback">
                    Nama Rak wajib diisi.
                  </div>
                </div>

                <div class="form-group">
                  <label for="username" class="form-label">Username </label>
                  <input type="username" name="username" class="form-control" placeholder="Masukkan Username" value="<?php echo htmlspecialchars($row_dokumen->username); ?>" disabled>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>

                <div class="form-group">
                  <label for="email" class="form-label">Email </label>
                  <input type="email" name="email" class="form-control" placeholder="Masukkan Email" value="<?php echo htmlspecialchars_decode($row_dokumen->email); ?>" disabled>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>
                
                <div class="col-md-12 mb-2">
                  <label for="pengguna" class="form-label">Jabatan </label>
                  <input type="text" name="jabatan" class="form-control" placeholder="Masukkan Jabatan" value="<?php echo htmlspecialchars_decode($row_dokumen->jabatan); ?>" disabled>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>

                <div class="col-md-12 mb-2">
                  <label for="no_hp" class="form-label">Nomor HP </label>
                  <input type="number" name="no_hp" class="form-control" placeholder="08XXXX" value="<?php echo htmlspecialchars_decode($row_dokumen->no_hp); ?>" disabled>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>
              
                <div class="form-group">
                  <label for="userRole">Role User </label>
                  <select class="form-select" id="userRole" name="role" disabled>
                    <option value="">Pilih Role</option>
                    <option value="admin" <?php if ($userRole == "admin") echo "selected"; ?>>Admin</option>
                    <option value="operator" <?php if ($userRole == "operator") echo "selected"; ?>>Operator</option>
                    <option value="pekerja" <?php if ($userRole == "pekerja") echo "selected"; ?>>Pekerja</option>
                  </select>
                </div>                        
                
                <div class="form-group">
                  <label for="status">Status Keaktifan </label>
                  <select class="form-select" id="status" name="status" disabled>
                    <option value="">Pilih Status</option>
                    <option value="Aktif" <?php if ($userStatus == "Aktif") echo "selected"; ?>>Aktif</option>
                    <option value="Tidak Aktif" <?php if ($userStatus == "Tidak Aktif") echo "selected"; ?>>Tidak Aktif</option>
                  </select>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>
            </div>
          </div>
        </div>
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

    <script>
      // Tampilkan Pass
      const passwordInput1 = document.getElementById('password_baru');
      const showPasswordCheckbox = document.getElementById('showPasswordCheckbox');

      showPasswordCheckbox.addEventListener('change', function () {
          // Toggle tipe antara 'password' dan 'text'
          passwordInput1.type = this.checked ? 'text' : 'password';
      });

      // Validasi Password
      const passwordInput = document.getElementById('password_baru');
      const submitBtn = document.getElementById('submitBtn');
      const requirements = document.getElementById('passwordRequirements');

      passwordInput.addEventListener('input', function () {
          requirements.style.display = 'block';
          validatePassword();
      });

      function validatePassword() {
          const password = passwordInput.value;

          const lengthValid = password.length >= 8;
          const uppercaseValid = /[A-Z]/.test(password);
          const lowercaseValid = /[a-z]/.test(password);
          const numberValid = /[0-9]/.test(password);
          const specialValid = /[!@#$%^&*(),.?":{}|<>]/.test(password);

          // Update requirement status
          document.getElementById('length').className = lengthValid ? 'valid' : 'invalid';
          document.getElementById('uppercase').className = uppercaseValid ? 'valid' : 'invalid';
          document.getElementById('lowercase').className = lowercaseValid ? 'valid' : 'invalid';
          document.getElementById('number').className = numberValid ? 'valid' : 'invalid';
          document.getElementById('special').className = specialValid ? 'valid' : 'invalid';

          // Enable submit button only if all requirements are met
          if (lengthValid && uppercaseValid && lowercaseValid && numberValid && specialValid) {
              submitBtn.disabled = false;
          } else {
              submitBtn.disabled = true;
          }
      }
    </script>
</body>
</html>

<?php
// Tutup koneksi dan statement
$stmt->close();
$connect->close();
?>
