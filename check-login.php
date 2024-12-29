<?php
error_reporting(0);
session_start();

require 'koneksi.php';

// Konfigurasi rate limiting
$max_attempts = 5; // Batas maksimal percobaan login
$lockout_time = 10 * 60; // Waktu lockout dalam detik (10 menit)

// Fungsi untuk memeriksa jumlah percobaan login
function isLockedOut($connect, $ip_address) {
    global $lockout_time, $max_attempts;

    $stmt = $connect->prepare("SELECT COUNT(*) AS attempts FROM login_attempts WHERE ip_address = ? AND attempt_time > (NOW() - INTERVAL ? SECOND)");
    $stmt->bind_param("si", $ip_address, $lockout_time);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    return $data['attempts'] >= $max_attempts;
}

// Fungsi untuk mencatat percobaan login
function logLoginAttempt($connect, $ip_address, $email) {
    $stmt = $connect->prepare("INSERT INTO login_attempts (ip_address, email, attempt_time) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $ip_address, $email);
    $stmt->execute();
}

// Dapatkan alamat IP pengguna
$ip_address = $_SERVER['REMOTE_ADDR'];

// Periksa apakah pengguna diblokir karena terlalu banyak percobaan login
if (isLockedOut($connect, $ip_address)) {
    $_SESSION['gagalLogin'] = 'Terlalu banyak percobaan login. Silakan coba lagi setelah beberapa waktu.';
    header('Location: index');
    exit;
}

// Periksa token CSRF
$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
if (!$token || $token !== $_SESSION['token']) {
    // Return 405 http status code jika token tidak sesuai
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}

// Proses login
if (isset($_POST['submit'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = mysqli_real_escape_string($connect, $_POST['username']);
        $password = $_POST['password']; // Tidak perlu escape karena akan diproses oleh password_verify

        // Gunakan prepared statements untuk mencegah SQL injection
        $stmt = $connect->prepare("SELECT * FROM user WHERE (username = ? OR email =?) AND status = 'Aktif'");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifikasi password jika user ditemukan
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verifikasi password menggunakan password_verify()
            if (password_verify($password, $row['password'])) {
                date_default_timezone_set('Asia/Jakarta');
                $agent = $_SERVER['HTTP_USER_AGENT'];

                // Set session dengan informasi user
                $_SESSION['id'] = $row['id'];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['level'] = $row['level'];

                // Log aktivitas user
                $simpan = $connect->prepare("INSERT INTO userlog (fk_id_user, ip_address, agent, waktu, status_log) VALUES (?, ?, ?, NOW(), 'Berhasil Login')");
                $simpan->bind_param("iss", $row['id'], $ip_address, $agent);
                $simpan->execute();

                // Update last login
                $save_last_login = $connect->prepare("UPDATE user SET last_login = NOW() WHERE id = ?");
                $save_last_login->bind_param("i", $row['id']);
                $save_last_login->execute();
                
                if ($row['level'] == 'pekerja') {
                    echo "<meta http-equiv='refresh' content='0; url=on-user/menu-scan'>";
                }else{
                    echo "<meta http-equiv='refresh' content='0; url=on-user'>";
                }

            } else {
                // Jika password salah
                logLoginAttempt($connect, $ip_address, $username); // Catat percobaan login yang gagal
                $_SESSION['gagalLogin'] = 'Gagal Login';
                header('Location: index');
            }
        } else {
            // Jika user tidak ditemukan
            logLoginAttempt($connect, $ip_address, $username); // Catat percobaan login yang gagal
            $_SESSION['gagalLogin'] = 'Gagal Login';
            header('Location: index');
        }

        $stmt->close();
        $connect->close();
        exit();
    } else {
        $_SESSION['aksesTolak'] = 'Akses ditolak!';
        header('Location: index');
    }
}
?>