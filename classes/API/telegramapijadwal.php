<?php

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
    $id_user = 41;
    include "koneksi.php";
    $result = mysqli_query($connect,"SELECT acara, id_rapat, nama_sesi, fk_id_rapat, a.jam_selesai, jam, kode_rapat, nama_und, peserta, hadir, d.nama_org as penanggungjawab, b.nama_org as pimpinan, tgl_rapat, tgl_selesai, c.nama_org as notulis, undangan_file, notulen_file, daftar_file, absensi_file, id_rapat, hadir_rapat, absen_rapat 
    FROM tbl_rapat a INNER JOIN tbl_organisasi b on a.pimpinan=b.id INNER JOIN tbl_organisasi c on a.notulis=c.id INNER JOIN tbl_organisasi d on a.penanggungjawab=d.id INNER JOIN tbl_jenis_und e on e.id_und=a.fk_id_und INNER JOIN tbl_peserta_rapat f on f.fk_id_rapat=a.id_rapat INNER JOIN tbl_user g on g.id=f.fk_id_peserta INNER JOIN tbl_sesi_rapat h on a.sesi=h.id_sesi 
    WHERE fk_id_peserta=".$id_user." AND a.status NOT IN ('Draf', 'Ditolak', 'Nonaktif') 
    order by tgl_rapat desc");
    
    while($row = mysqli_fetch_array($result))
     {
        print_r($row);
     } 
    