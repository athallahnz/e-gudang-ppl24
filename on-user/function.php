<?php
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

        $namaFile = uniqid() . bin2hex(random_bytes(5)) . '.png';
        $qrCodePath = '../qr_codes/' . $namaFile;
        $qrCode->saveToFile($qrCodePath);

        return $namaFile;
    }
?>