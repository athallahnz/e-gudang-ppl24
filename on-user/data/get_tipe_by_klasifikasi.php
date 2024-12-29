<?php
// Include koneksi ke database
include '../../koneksi.php';

if (isset($_GET['id_klasifikasi'])) {
    $id_klasifikasi = intval($_GET['id_klasifikasi']); // Pastikan ini integer untuk keamanan
    $sql_tipe = "SELECT id, id_klasifikasi, nama, panjang, lebar FROM tipe_aset WHERE id_klasifikasi = $id_klasifikasi";
    $result_tipe = mysqli_query($connect, $sql_tipe);

    if ($result_tipe) {
        while ($row_tipe = mysqli_fetch_assoc($result_tipe)) {              
            if ($row_tipe['id_klasifikasi']==141) {
                $hasilInput =" (".$row_tipe['panjang']."mm x ".$row_tipe['lebar']."mm)";
            } 
            // else {
            //     $hasilInput =" (".$row_tipe['diameter']." x ".$row_tipe['panjang'].")";
            // }  
            
            echo "<option value='{$row_tipe['id']}'>" . htmlspecialchars_decode($row_tipe['nama'])." ".$hasilInput. "</option>";
        }
    } else {
        echo "<option value=''>Tidak ada tipe yang tersedia</option>";
    }
} else {
    echo "<option value=''>Tidak ada klasifikasi yang dipilih</option>";
}
?>
