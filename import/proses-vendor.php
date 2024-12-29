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
        $vendorName = $sheet->getCell('A' . $row)->getValue();
        $vendorAlamat = $sheet->getCell('B' . $row)->getValue();
        $vendorPos = $sheet->getCell('C' . $row)->getValue();
        $vendorKota = $sheet->getCell('D' . $row)->getValue();

        if (!empty($vendorName)) {
            $stmt = $connect->prepare("INSERT INTO vendor_aset (nama, alamat, kode_pos, kota, deskripsi, created_by, last_created) VALUES (?, ?, ?, ?, '', 1, now())");
            $stmt->bind_param("ssss", $vendorName, $vendorAlamat, $vendorPos, $vendorKota);
            $stmt->execute();
        }
    }

    echo "Import data berhasil!";
} else {
    echo "Pilih file Excel untuk diunggah.";
}

$connect->close();
?>
