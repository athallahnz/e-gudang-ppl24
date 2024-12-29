<?php

$dbhost = 'localhost';
$dbuser = 'stikesyr_sim-mutu';
$dbpass = 'Stikes@1+';
$dbname = 'stikesyr_db_sijamu';

$connect = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

if($connect -> connect_error) {
	die('Koneksi gagal: '.$connect->connect_error);
}

?>