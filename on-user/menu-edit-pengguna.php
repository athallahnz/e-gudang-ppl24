<?php
error_reporting(0);
session_start();
if ($_SESSION['level'] == 'admin') {
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
      <h1>Edit Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="master-pengguna">Master Pengguna</a></li>
          <li class="breadcrumb-item active">Edit Data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Edit Data</h5>

              <form action="proses-edit-pengguna" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                <?php
                  $_SESSION['token'] = bin2hex(random_bytes(35)); // Menghasilkan token CSRF
                ?>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                <!-- Hidden field for ID -->
                <input type="hidden" name="id" value="<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>" />

                <div class="col-md-12">
                  <label for="pengguna" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="pengguna" value="<?php echo htmlspecialchars_decode($row_dokumen->nama); ?>" required>
                  <div class="invalid-feedback">
                    Nama Rak wajib diisi.
                  </div>
                </div>

                <div class="form-group">
                  <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                  <input type="username" name="username" class="form-control" placeholder="Masukkan Username" value="<?php echo htmlspecialchars($row_dokumen->username); ?>" required>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>

                <div class="form-group">
                  <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control" placeholder="Masukkan Email" value="<?php echo htmlspecialchars_decode($row_dokumen->email); ?>" required>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>
                
                <div class="col-md-12 mb-2">
                  <label for="pengguna" class="form-label">Jabatan <span class="text-danger">*</span></label>
                  <input type="text" name="jabatan" class="form-control" placeholder="Masukkan Jabatan" value="<?php echo htmlspecialchars_decode($row_dokumen->jabatan); ?>" required>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>

                <div class="col-md-12 mb-2">
                  <label for="no_hp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                  <input type="number" name="no_hp" class="form-control" placeholder="08XXXX" value="<?php echo htmlspecialchars_decode($row_dokumen->no_hp); ?>" required>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>
              
                <div class="form-group">
                  <label for="userRole">Role User <span class="text-danger">*</span></label>
                  <select class="form-select" id="userRole" name="role" required>
                    <option value="">Pilih Role</option>
                    <option value="admin" <?php if ($userRole == "admin") echo "selected"; ?>>Admin</option>
                    <option value="operator" <?php if ($userRole == "operator") echo "selected"; ?>>Operator</option>
                    <option value="pekerja" <?php if ($userRole == "pekerja") echo "selected"; ?>>Pekerja</option>
                  </select>
                </div>                        
                
                <div class="form-group">
                  <label for="status">Status Keaktifan <span class="text-danger">*</span></label>
                  <select class="form-select" id="status" name="status" required>
                    <option value="">Pilih Status</option>
                    <option value="Aktif" <?php if ($userStatus == "Aktif") echo "selected"; ?>>Aktif</option>
                    <option value="Tidak Aktif" <?php if ($userStatus == "Tidak Aktif") echo "selected"; ?>>Tidak Aktif</option>
                  </select>
                  <div class="invalid-feedback">Wajib Diisi</div>
                </div>

                <div class="form-group">
                  <label for="validationPasswordBaru" class="form-label">Password</label>
                  <div class="input-group">
                      <input type="password" name="password_baru" id="password_baru" class="form-control" />
                      <div class="invalid-feedback">Wajib diisi</div>
                  </div>
                  <!-- Teks panduan tentang cara mengubah atau tidak mengubah password -->
                  <small class="form-text text-muted">
                      Kosongkan jika tidak ingin mengubah password. Isi jika ingin mengganti password.
                  </small>
                  <div class="form-check mt-2">
                      <input type="checkbox" class="form-check-input" id="showPasswordCheckbox">
                      <label class="form-check-label" for="showPasswordCheckbox">Tampilkan Password</label>
                  </div>
                  <div id="passwordRequirements" class="mt-2" style="display: none;">
                      <ul>
                        <li id="length" class="invalid">Minimal 8 karakter</li>
                        <li id="uppercase" class="invalid">Setidaknya 1 huruf besar</li>
                        <li id="lowercase" class="invalid">Setidaknya 1 huruf kecil</li>
                        <li id="number" class="invalid">Setidaknya 1 angka</li>
                        <li id="special" class="invalid">Setidaknya 1 karakter spesial (contoh: @, #, $)</li>
                      </ul>
                  </div>
                </div>
                
                <div class="col-12">
                  <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
                </div>
              </form>

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
  }else{
    header('Location: 404');
  }
?>
