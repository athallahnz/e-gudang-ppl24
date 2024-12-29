<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require '../vendor/autoload.php'; 
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

    function generateQrCode($aset) {
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($aset)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->build();

        $namaFile = uniqid() . '.png';
        $qrCodePath = '../qr_codes/' . $namaFile;
        $qrCode->saveToFile($qrCodePath);

        return $namaFile;
    }

    $last_id = 99;
    $nomor_aset = 'AST-' . str_pad($last_id, 6, '0', STR_PAD_LEFT);
    
    // Generate QR Code
    $namaFile = generateQrCode($nomor_aset);
    if (!$namaFile) {
        die('QR Code generation failed');
    }else{
        echo "sukses";
    }
exit();
?>
