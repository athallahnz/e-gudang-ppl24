<?php
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set header untuk JSON response
    header('Content-Type: application/json');

    // Ambil nomor tipe dengan sanitasi
    $id_tipe = filter_input(INPUT_POST, 'id_tipe', FILTER_SANITIZE_STRING);

    if ($id_tipe) {
        // Query untuk mendapatkan data ukuran barang berdasarkan id_tipe
        $query = $connect->prepare("
            SELECT a.id, b.nama_tipe, a.ketebalan, a.panjang, a.lebar, a.diameter, a.qty 
            FROM aset_raw a
            INNER JOIN tipe_raw b ON a.id_tipe = b.id
            WHERE b.nomor_tipe = ? AND a.qty > 0
        ");
        $query->bind_param("s", $id_tipe);
        $query->execute();
        $result = $query->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'id' => $row['id'],
                'nama_tipe' => $row['nama_tipe'],
                'ketebalan' => $row['ketebalan'],
                'panjang' => $row['panjang'],
                'lebar' => $row['lebar'],
                'diameter' => $row['diameter'],
                'qty' => $row['qty']
            ];
        }

        if (!empty($data)) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Tidak ada data ukuran barang untuk tipe ini']);
        }
      
    } else {
        // Input tidak valid
        echo json_encode(['success' => false, 'message' => 'Nomor tipe tidak valid']);
    }

    // Tutup koneksi
    $connect->close();
}
?>
