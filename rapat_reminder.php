<?php
// Koneksi ke database
include "koneksi.php";
include "random-v2.php";
date_default_timezone_set('Asia/Jakarta');
// $servername = "localhost";
// $username = "username";
// $password = "password";
// $dbname = "nama_database";

// $connect = new mysqli($servername, $username, $password, $dbname);

// if ($connect->connect_error) {
//     die("Connection failed: " . $connect->connect_error);
// }

// Waktu sekarang
$current_time = date("Y-m-d H:i:s");
$reminder_time = date("Y-m-d H:i:s", strtotime($current_time . ' +30 minutes'));
// echo $reminder_time."<br>";
// echo $current_time."<br>";

// Query untuk mendapatkan rapat yang akan datang dalam 1 jam dan belum dikirim pengingat
$sql = "SELECT *, CONCAT(start, ' ', jam) AS datetime_rapat FROM tbl_rapat2 WHERE CONCAT(start, ' ', jam) <= '2024-08-30 10:51:24' AND reminder_sent = 0 AND id_rapat=1242";
$result = $connect->query($sql);
// echo $result->num_rows;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Kirim pengingat (misalnya melalui email)
        // $to = "email@example.com";
        // $subject = "Pengingat Rapat";
        // $message = "Rapat akan dimulai pada " . $row["datetime_rapat"];
        // $headers = "From: reminder@example.com";
        $id = $row["id_rapat"]; 
        $acara = $row["title"]; 
        $ruang = $row["ruangan"];
        $jam = date("H:i", strtotime($row["jam"])); 
        $id_enc = encrypt_decrypt2('encrypt', $row["id_rapat"]);
        // echo $row["id_rapat"];

        // kirim WA Blast
        $peserta = mysqli_query($connect, "SELECT * FROM tbl_peserta_rapat a INNER JOIN tbl_user b ON a.fk_id_peserta=b.id WHERE fk_id_rapat=$id and a.status = 'Aktif' and fk_id_peserta=51");
        if($peserta->num_rows > 0) {
            while ($row_peserta = $peserta->fetch_object()) {
            $nomor_hp[] = $row_peserta->no_hp;
            }
        }
        $nomor = "'" . implode(",", $nomor_hp) . "'";
        // echo $nomor;
        $curl = curl_init();
        $token = "";
        $data = [
        'phone' => $nomor,
        'title' => 'Daftar Hadir',
        'template_type' => 'text',
        'message' => 'Selamat Pagi \n Kami sampaikan untuk DH '.$acara.' hari ini di Ruang '.$ruang.' Jam '.$jam.' WIB dapat mengisi langsung pada SIM Mutu https://sim-mutu.stikes-yrsds.ac.id/verifikasi-presensi/'.$id_enc.'🙏🏻',
        'url_display' => 'Link Presensi Rapat',
        'url_link' => 'https://wablas.com',
        'contact_display' => 'Hubungi Kami',
        'contact_diplay' => '6289699869196',
        'footer' => 'footer template here',
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: udsHs0bgwTn9CV26rozbaIXtyjejyG9hF43FBdVW7AqA5XD18W0AXFLf8CSLr6Vw",
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);
        echo "<pre>";
        print_r($result);
         // limit WA Blast

        // if ($to) {
            // Tandai pengingat telah dikirim
            $update_sql = "UPDATE tbl_rapat2 SET reminder_sent = 1 WHERE id_rapat = " . $id;
            $connect->query($update_sql);
        // }
    }
}

$connect->close();
?>