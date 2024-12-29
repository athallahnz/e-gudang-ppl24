<?php
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set header untuk JSON response
    header('Content-Type: application/json');

    // Ambil nomor aset dengan sanitasi
    $nomor_aset = filter_input(INPUT_POST, 'nomor_aset', FILTER_SANITIZE_STRING);

    if ($nomor_aset) {
        // Query untuk mendapatkan detail aset
        $query = $connect->prepare("
            SELECT 
                a.nama AS nama_aset, 
                qty AS sisa_stok, 
                a.id_material, 
                a.id_klasifikasi
            FROM 
                aset a
            WHERE 
                nomor_aset = ?
        ");
        $query->bind_param("s", $nomor_aset);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_object();
            echo json_encode([
                'success' => true,
                'nama_aset' => $row->nama_aset,
                'sisa_stok' => $row->sisa_stok
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nomor aset tidak ditemukan']);
        }
      
    } else {
        // Input tidak valid
        echo json_encode(['success' => false, 'message' => 'Nomor aset tidak valid']);
    }

    // Tutup koneksi
    $connect->close();
}
?>
