<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Ukuran Material</title>
    <style>
        .input-group { margin-bottom: 10px; }
        .input-group label { display: inline-block; width: 100px; }
        .input-fields { display: none; } /* Semua div input-fields awalnya disembunyikan */
    </style>
</head>
<body>
    <h2>Form Input Ukuran Material</h2>

    <!-- Dropdown Jenis Barang -->
    <div class="input-group">
        <label for="jenis-barang">Jenis Barang:</label>
        <select id="jenis-barang" onchange="showInputFields()">
            <option value="">Pilih Jenis Barang</option>
            <option value="plate">Plate</option>
            <option value="plate-bar">Plate Bar</option>
            <option value="round-bar">Round Bar</option>
        </select>
    </div>

    <!-- Input Fields untuk Plate (Ketebalan x Panjang x Lebar) -->
    <div id="plate-fields" class="input-fields">
        <div class="input-group">
            <label for="ketebalan-plate">Ketebalan:</label>
            <input type="number" id="ketebalan-plate" name="ketebalan-plate" step="0.01"> mm
        </div>
        <div class="input-group">
            <label for="panjang-plate">Panjang:</label>
            <input type="number" id="panjang-plate" name="panjang-plate" step="0.01"> mm
        </div>
        <div class="input-group">
            <label for="lebar-plate">Lebar:</label>
            <input type="number" id="lebar-plate" name="lebar-plate" step="0.01"> mm
        </div>
    </div>

    <!-- Input Fields untuk Plate Bar (Ketebalan x (Panjang x Lebar)) -->
    <div id="plate-bar-fields" class="input-fields">
        <div class="input-group">
            <label for="ketebalan-plate-bar">Ketebalan:</label>
            <input type="number" id="ketebalan-plate-bar" name="ketebalan-plate-bar" step="0.01"> mm
        </div>
        <div class="input-group">
            <label for="panjang-plate-bar">Panjang:</label>
            <input type="number" id="panjang-plate-bar" name="panjang-plate-bar" step="0.01"> mm
        </div>
        <div class="input-group">
            <label for="lebar-plate-bar">Lebar:</label>
            <input type="number" id="lebar-plate-bar" name="lebar-plate-bar" step="0.01"> mm
        </div>
    </div>

    <!-- Input Fields untuk Round Bar (Diameter x Panjang) -->
    <div id="round-bar-fields" class="input-fields">
        <div class="input-group">
            <label for="diameter-round-bar">Diameter:</label>
            <input type="number" id="diameter-round-bar" name="diameter-round-bar" step="0.01"> mm
        </div>
        <div class="input-group">
            <label for="panjang-round-bar">Panjang:</label>
            <input type="number" id="panjang-round-bar" name="panjang-round-bar" step="0.01"> mm
        </div>
    </div>

    <!-- JavaScript untuk Menampilkan Input Fields yang Sesuai -->
    <script>
        function showInputFields() {
            // Sembunyikan semua div input-fields
            document.getElementById("plate-fields").style.display = "none";
            document.getElementById("plate-bar-fields").style.display = "none";
            document.getElementById("round-bar-fields").style.display = "none";
            
            // Dapatkan nilai pilihan dari dropdown jenis barang
            const jenisBarang = document.getElementById("jenis-barang").value;
            
            // Tampilkan input fields yang sesuai berdasarkan pilihan jenis barang
            if (jenisBarang === "plate") {
                document.getElementById("plate-fields").style.display = "block";
            } else if (jenisBarang === "plate-bar") {
                document.getElementById("plate-bar-fields").style.display = "block";
            } else if (jenisBarang === "round-bar") {
                document.getElementById("round-bar-fields").style.display = "block";
            }
        }
    </script>
</body>
</html>
