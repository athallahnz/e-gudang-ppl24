<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../../koneksi.php";

header('Content-Type: application/json');

if (isset($_GET['id_material'])) {
    $id_material = intval($_GET['id_material']);

    // Query untuk mengambil klasifikasi berdasarkan material
    $sql_klasifikasi = "SELECT id, nama FROM klasifikasi_aset WHERE id_material = ?";
    $stmt = $connect->prepare($sql_klasifikasi);
    $stmt->bind_param("i", $id_material);
    $stmt->execute();
    $result_klasifikasi = $stmt->get_result();

    $klasifikasi = [];
    while($row = $result_klasifikasi->fetch_assoc()) {
        $klasifikasi[] = $row;
    }

    // Kembalikan data klasifikasi dalam format JSON
    echo json_encode($klasifikasi);
} else {
    echo json_encode([]);
}
?>
