<?php
error_reporting(0);
session_start();
include "../koneksi.php";
include "tgl_indo_2.php";
include "../random-v2.php";

$m_active_page = "profil";
$active_page   = "profil";
$id_user       = $_SESSION['id'];

$result_dokumen = mysqli_query($connect, "SELECT * FROM user WHERE id=$id_user");
if ($result_dokumen) {
    if ($result_dokumen->num_rows > 0) {
        $row_rapat = $result_dokumen->fetch_object();
        $username = $row_rapat->username; // Pastikan Anda mengisi variabel nama_user
        $nama_user = $row_rapat->nama; // Pastikan Anda mengisi variabel nama_user
        $email = $row_rapat->email; // Pastikan Anda mengisi variabel email
        $nohp = $row_rapat->no_hp; // Pastikan Anda mengisi variabel email
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
            <h1>Profil</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index">Home</a></li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Profil</h5>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Username</b></div>
                                <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars_decode($username); ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Nama Lengkap</b></div>
                                <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars_decode($nama_user); ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Kantor</b></div>
                                <div class="col-lg-9 col-md-8">
                                <?php 
                                    $query = mysqli_query($connect, "SELECT * FROM pn_setting where id=2");
                                    $row = $query->fetch_object();
                                    echo $row->isi; 
                                ?></div>
                            </div>

                            <!-- <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Jabatan</b></div>
                                <div class="col-lg-9 col-md-8">
                                    <?php 
                                    $jabatan = $_SESSION['jabatan'];
                                    if (strpos($jabatan, 'Ketua') !== false) {
                                        echo "Wakil " . $jabatan;
                                    } else {
                                        echo $jabatan;  
                                    }
                                    ?>
                                </div>
                            </div> -->

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Email</b></div>
                                <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($email); ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>No. Handphone</b></div>
                                <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($nohp); ?></div>
                            </div> <br>

                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#disablebackdrop"><i class="bi bi-key-fill"></i> Ubah Kata Sandi</button>
                            </div>
                            <!-- Modal Ubah Kata Sandi -->
                            <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ubah Kata Sandi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="row g-3 needs-validation" id="changePasswordForm" action="proses-change-password" method="POST" novalidate>
                                                <input type="hidden" name="id" value="<?php echo encrypt_decrypt2('encrypt', $id_user); ?>" />
                                                <div class="col-md-12">
                                                    <label for="validationPasswordLama" class="form-label">Password Lama</label>
                                                    <input type="password" name="password_lama" class="form-control" required />
                                                    <div class="invalid-feedback">Wajib diisi</div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="validationPasswordBaru" class="form-label">Password Baru</label>
                                                    <input type="password" name="password_baru" id="password_baru" class="form-control" required />
                                                    <div class="invalid-feedback">Wajib diisi</div>
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

                                                <div class="col-md-12">
                                                    <label for="validationKonfirmPasswordBaru" class="form-label">Konfirmasi Password Baru</label>
                                                    <input type="password" name="konfirm_password_baru" id="konfirm_password_baru" class="form-control" required />
                                                    <div class="invalid-feedback">Wajib diisi</div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                            <button name='submit' class="btn btn-primary" type="submit" id="submitBtn" disabled>Simpan</button>
                                        </div>
                                        </form><!-- End Custom Styled Validation -->
                                    </div>
                                </div>
                            </div><!-- End Modal Ubah Kata Sandi-->
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <?php include "_footer.php"; ?>
    
    <?php if (isset($_SESSION['sukses'])) { ?>
        <script>
            Swal.fire("Terima Kasih!", "<?php echo $_SESSION['sukses']; ?>", "success");
        </script>
        <?php unset($_SESSION['sukses']); 
    } ?>
    <?php if (isset($_SESSION['gagal'])) { ?>
        <script>
            Swal.fire("Mohon Maaf!", "<?php echo $_SESSION['gagal']; ?>", "error");
        </script>
        <?php unset($_SESSION['gagal']); 
    } ?>

    <script>
        // Validasi Password
        const passwordInput = document.getElementById('password_baru');
        const confirmPasswordInput = document.getElementById('konfirm_password_baru');
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

            document.getElementById('length').className = lengthValid ? 'valid' : 'invalid';
            document.getElementById('uppercase').className = uppercaseValid ? 'valid' : 'invalid';
            document.getElementById('lowercase').className = lowercaseValid ? 'valid' : 'invalid';
            document.getElementById('number').className = numberValid ? 'valid' : 'invalid';
            document.getElementById('special').className = specialValid ? 'valid' : 'invalid';

            // Enable submit button if all requirements are met and passwords match
            if (lengthValid && uppercaseValid && lowercaseValid && numberValid && specialValid && (password === confirmPasswordInput.value)) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        confirmPasswordInput.addEventListener('input', function () {
            validatePassword();
        });
    </script>
</body>

</html>
<?php
    } else {
        header('Location: 404');
        exit();
    }
} else {
    header('Location: 404');
    exit();
}
?>
