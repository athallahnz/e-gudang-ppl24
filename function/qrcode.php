<?php
    // Fungsi untuk generate QR Code
    function generateQrCode($aset) {
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($aset)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->build();

        // Simpan QR Code ke dalam folder qr_codes
        $namaFile = uniqid() . '.png';
        $qrCodePath = '../qr_codes/' . $namaFile;
        $qrCode->saveToFile($qrCodePath);

        return $namaFile;
    }
?>