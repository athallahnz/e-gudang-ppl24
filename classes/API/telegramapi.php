<?php
 
define('BOT_TOKEN', '1879107436:AAEZ7moahx9zYkV0G2YQVgi3CLgZ4UbB8wg');
define('CHAT_ID','1292939385');

//HEMIK
define('CHAT_ID2','1090428865');

//Alief
define('CHAT_ID3','1187997674');

//NANO
define('CHAT_ID4','1481372819');

function kirimTelegram($pesan, $id_chat) {
    $pesan = json_encode($pesan);
    $API = "https://api.telegram.org/bot".BOT_TOKEN."/sendmessage?chat_id=".$id_chat."&text=$pesan";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_URL, $API);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

    include "koneksi.php";
    $result = mysqli_query($connect,"SELECT DISTINCT(kode_rapat), title, a.status, jam, notulen_file_type, foto_file_type, regulasi_file_type, undangan_file_type, kode_link, nama_link, kode_rapat, nama_sesi, nama_und, peserta, hadir, d.nama_org as penanggungjawab, b.nama as pimpinan, h.nama_org as unit_kerja, start, c.nama_org as notulis, undangan_file, notulen_file, daftar_file, absensi_file, id_rapat, link_record 
    FROM tbl_rapat a 
    INNER JOIN tbl_user b on a.pimpinan_by=b.id 
    INNER JOIN tbl_organisasi h on b.kode_org=h.kode_org
    INNER JOIN tbl_organisasi c on a.notulis=c.id 
    INNER JOIN tbl_organisasi d on a.penanggungjawab=d.id 
    INNER JOIN tbl_jenis_und e on e.id_und=a.fk_id_und 
    INNER JOIN tbl_sesi_rapat f on a.sesi=f.id_sesi
    INNER JOIN tbl_link_rapat g on g.id_link=a.kode_link WHERE id_rapat NOT IN ('1') AND a.status NOT IN ('Ditolak', 'Nonaktif') AND start=curdate() order by start=curdate() desc, start desc, jam desc");

    while($r = mysqli_fetch_assoc($result)) {
        $kode_rapat = "Kode Undangan : ".$r["kode_rapat"];
        $acara = "Acara : ".$r["title"];
        $tgl_rapatjam = "Tgl/Jam : ".tgl_indo($r["start"])." | ".$r["jam"];
        $nama_sesi = "Pelaksanaan : ".$r["nama_sesi"];
        $penanggungjawab = "Pngg. jawab : ".$r["penanggungjawab"];
        $pimpinan = "Pimp. Rapat : ".$r["pimpinan"];
        $notulis = "Notulis : ".$r["notulis"];
        $status = "Status : ".$r["status"];
        
        $txt = urlencode($kode_rapat."\n".$acara."\n".$nama_sesi."\n".$tgl_rapatjam."\n".$penanggungjawab."\n".$pimpinan."\n".$notulis."\n".$status);
        kirimTelegram($txt,CHAT_ID);
        kirimTelegram($txt,CHAT_ID2);
        kirimTelegram($txt,CHAT_ID3);
        kirimTelegram($txt,CHAT_ID4);
    }
