<?php
  error_reporting(E_ALL & ~E_NOTICE); // Menangkap semua error kecuali notice
  session_start();
  if ($_SESSION['level'] == 'admin') {

  include "../koneksi.php";
  include "../tgl_indo.php";
  include "../random-v2.php";

  $m_active_page = "data-master";
  $active_page   = "master-pengguna";
?>

<!DOCTYPE html>
<html lang="en">

<?php include "_head.php"; ?>

<body>
  <!-- ======= Header ======= -->
  <?php include "_header.php"; ?>
  <!-- ======= Sidebar ======= -->
  <?php include "_sidebar.php"; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Pengguna</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Data Master</a></li>
          <li class="breadcrumb-item active">Pengguna</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Pengguna</h5>
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#disablebackdrop">
                <i class="bi bi-plus me-1"></i> Pengguna Baru
              </button>
              
              <!-- Modal Tambah Pengguna Baru -->
              <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Tambah Pengguna Baru</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form class="row g-3 needs-validation" method="POST" action="proses-save-pengguna" enctype="multipart/form-data" id="devel-generate-content-form" novalidate>
                        <?php
                          $_SESSION['token'] = bin2hex(random_bytes(35)); // Menghasilkan token CSRF
                        ?>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                        
                        <div class="form-group">
                          <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                          <input type="username" name="username" class="form-control" placeholder="Masukkan Username" required>
                          <div class="invalid-feedback">Wajib Diisi</div>
                        </div>
                        
                        <div class="col-md-12 mb-2">
                          <label for="pengguna" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                          <input type="text" name="pengguna" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                          <div class="invalid-feedback">Wajib Diisi</div>
                        </div>

                        <div class="form-group">
                          <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                          <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required>
                          <div class="invalid-feedback">Wajib Diisi</div>
                        </div>
                        
                        <div class="col-md-12 mb-2">
                          <label for="pengguna" class="form-label">Jabatan <span class="text-danger">*</span></label>
                          <input type="text" name="jabatan" class="form-control" placeholder="Masukkan Jabatan" required>
                          <div class="invalid-feedback">Wajib Diisi</div>
                        </div>

                        <div class="col-md-12 mb-2">
                          <label for="no_hp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                          <input type="number" name="no_hp" class="form-control" placeholder="08XXXX" required>
                          <div class="invalid-feedback">Wajib Diisi</div>
                        </div>
                      
                        <div class="form-group">
                          <label for="userRole">Role User <span class="text-danger">*</span></label>
                          <select class="form-select" id="userRole" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="operator">Operator</option>
                            <option value="pekerja">Pekerja</option>
                          </select>
                        </div>                        
                        
                        <div class="form-group">
                          <label for="status">Status Keaktifan <span class="text-danger">*</span></label>
                          <select class="form-select" id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                          </select>
                          <div class="invalid-feedback">Wajib Diisi</div>
                        </div>

                        <div class="form-group">
                          <label for="validationPasswordBaru" class="form-label">Password</label>
                          <div class="input-group">
                              <input type="password" name="password_baru" id="password_baru" class="form-control" required />
                              <div class="invalid-feedback">Wajib diisi</div>
                          </div>
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
                        
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <button class="btn btn-primary" name="submit" id="submitBtn" type="submit" disabled>Simpan</button>
                    </div>
                  </form><!-- End Custom Styled Validation -->
                  </div>
                </div>
              </div><!-- End Modal Tambah Pengguna Baru -->

              <!-- Table Data Pengguna -->
              <table id="example" class="table table-striped dt-responsive nowrap example" style="width:100%;">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Nama</th>
                    <th scope="col">No. HP</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 0;
                    $result_dokumen = mysqli_query($connect,"SELECT * FROM user ORDER BY id DESC");

                    if($result_dokumen && $result_dokumen->num_rows > 0) {
                      while ($row_dokumen = $result_dokumen->fetch_object()) {
                        $no++;

                        if ($row_dokumen->status == 'Aktif') {
                          $span1 = "<span class='badge bg-success'>".$row_dokumen->status."</span>";
                        } else {
                          $span1 = "<span class='badge bg-danger'>".$row_dokumen->status."</span>";
                        }
                        
                  ?>
                  <tr>
                    <th scope="row"><?php echo "$no."; ?></th>
                    <td><?php echo htmlspecialchars($row_dokumen->nama)."<br>".$span1; ?></td>
                    <td><?php echo htmlspecialchars($row_dokumen->no_hp); ?></td>
                    <td>
                      <a href='menu-edit-pengguna?id=<?php echo urlencode(encrypt_decrypt2('encrypt', $row_dokumen->id)); ?>' class='btn btn-primary btn-sm' title='Edit Pengguna'>
                        <i class='bi bi-pencil-square'></i>
                      </a>
                      <a href='menu-detail-pengguna?id=<?php echo urlencode(encrypt_decrypt2('encrypt', $row_dokumen->id)); ?>' class='btn btn-primary btn-sm' title='Detail Pengguna'>
                        <i class='bi bi-eye'></i>
                      </a>
                    </td>
                  </tr>
                  <?php
                      }
                    } else {
                      echo "<tbody>";
                      echo "<tr><td colspan='4' class='text-center'></td></tr>";
                      echo "</tbody>";
                    }
                  ?>
                </tbody>
              </table>
              <!-- End Table Data Pengguna -->

            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include "_footer.php"; ?>

  <!-- Session Success/Failure Alerts -->
  <?php if(@$_SESSION['sukses']){ ?>
    <script>
      Swal.fire("Terima Kasih!", "<?php echo $_SESSION['sukses']; ?>", "success");
    </script>
    <?php unset($_SESSION['sukses']); } ?>

  <?php if(@$_SESSION['gagal']){ ?>
    <script>
      Swal.fire("Mohon Maaf!", "<?php echo $_SESSION['gagal']; ?>", "error");
    </script>
    <?php unset($_SESSION['gagal']); } ?>

  <script>
    $('#disablebackdrop').on('hidden.bs.modal', function () {
      document.location.reload();
    });

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
  }else{
    header('Location: 404');
  }
?>