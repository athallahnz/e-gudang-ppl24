<?php
  // Menggunakan prepared statement untuk menghindari SQL Injection
  $stmt = $connect->prepare("SELECT a.id as id, nama_tipe, nomor_tipe, b.nama as satuan, minimun_qty, c.nama as klasifikasi, d.nama as material, a.id_klasifikasi as id_klasifikasi, e.nama as satuan, qty
  FROM tipe_raw a 
  INNER JOIN satuan_aset b ON a.id_satuan=b.id 
  INNER JOIN klasifikasi_aset c on a.id_klasifikasi=c.id 
  INNER JOIN material_aset d on a.id_material=d.id 
  INNER JOIN satuan_aset e on a.id_satuan=e.id 
  INNER JOIN aset_raw f on f.id_tipe=a.id 
  WHERE a.id = ?");

  $stmt->bind_param("i", $id_satuan);
  $stmt->execute();
  $result_dokumen = $stmt->get_result();

  if ($result_dokumen && $result_dokumen->num_rows > 0) {
    $row_dokumen = $result_dokumen->fetch_object();
    $id_aset = htmlspecialchars($row_dokumen->id);

  } else {
      // Jika data tidak ditemukan
      header('Location: 404');
      exit();
  }
?>

<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Detail Barang</h5>
              <!-- Hidden field for ID -->
              <input type="hidden" name="id" value="<?php echo encrypt_decrypt2('encrypt', $row_dokumen->id); ?>" />

              <div class="row">
                <div class="col-md-4 mb-2">
                  <label for="nama" class="form-label">Nomor QR Code </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->nomor_tipe); ?>" disabled>
                </div>
  
                <div class="col-md-4 mb-2">
                  <label for="nama" class="form-label">Jenis Klasifikasi Material </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row_dokumen->klasifikasi)." (".htmlspecialchars($row_dokumen->material).")"; ?>" disabled>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="nama" class="form-label">Nama Barang </label>
                  <?php
                    echo '<input type="text" class="form-control" name="nama" value="'.htmlspecialchars_decode($row_dokumen->nama_tipe).'" disabled>';
                  ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <label for="nama" class="form-label">Minimun Stok </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars_decode($row_dokumen->minimun_qty." ".$row_dokumen->satuan)?>" disabled>
                </div>
                <div class="col-md-6 mb-2">
                  <label for="nama" class="form-label">Stok Terkini </label>
                  <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars_decode($row_dokumen->qty." ".$row_dokumen->satuan)?>" disabled>
                </div>
              </div>              
            </div>
          </div>
        </div>
      </div>
    </section>