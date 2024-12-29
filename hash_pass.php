<?php
    include 'koneksi.php';

    // Password baru yang akan di-set untuk semua user
    $new_password = 'TolongUbahSandi*';

    // Ambil semua user
    $sql = "SELECT id FROM user WHERE id=2";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Hash password baru dengan bcrypt untuk setiap user
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update password setiap user
            $update_sql = "UPDATE user SET password = ? WHERE id = ?";
            $stmt = $connect->prepare($update_sql);
            $stmt->bind_param("si", $hashed_password, $row['id']);

            if ($stmt->execute()) {
                echo "Password pengguna dengan ID " . $row['id'] . " berhasil diubah.<br>";
            } else {
                echo "Error: " . $connect->error . "<br>";
            }

            $stmt->close();
        }
    } else {
        echo "Tidak ada pengguna yang ditemukan.";
    }

    $connect->close();
?>
