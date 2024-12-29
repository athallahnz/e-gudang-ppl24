self.onmessage = function(e) {
    // Proses frame gambar untuk QR decoding
    const decodedData = decodeQrCode(e.data);
    postMessage(decodedData);
};
