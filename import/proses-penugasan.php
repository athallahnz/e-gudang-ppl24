<?php
require '../vendor/autoload.php'; // Pastikan lokasi file autoload benar jika menggunakan Composer

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Konfigurasi koneksi ke database
require '../koneksi.php';

if (isset($_FILES['file']['name'])) {
    $fileName = $_FILES['file']['tmp_name'];

    // Load spreadsheet
    $spreadsheet = IOFactory::load($fileName);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();

    // Looping data dari Excel dan masukkan ke database
    for ($row = 2; $row <= $highestRow; $row++) { // Mulai dari baris kedua untuk melewati header
        $penugasanName = $sheet->getCell('A' . $row)->getValue();

        if (!empty($penugasanName)) {
            $stmt = $connect->prepare("INSERT INTO penugasan_aset (nama, deskripsi, created_by, last_created) VALUES (?, '', 1, now())");
            $stmt->bind_param("s", $penugasanName);
            $stmt->execute();
        }
    }

    echo "Import data berhasil!";
} else {
    echo "Pilih file Excel untuk diunggah.";
}

$connect->close();
?>
