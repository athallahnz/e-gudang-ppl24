<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select2 with Bootstrap Card</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .card-body {
            max-width: 500px;
            margin: auto;
        }
        .custom-input {
            display: none; /* Initial state is hidden */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Select2 with Bootstrap</h4>
            </div>
            <div class="card-body">
                <label for="select2Example">Choose Option A</label>
                <select id="select2Example" class="js-example-basic-single form-select" style="width: 100%;">
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                    <option value="5">Option 5</option>
                    <option value="6">Option 6</option>
                    <option value="7">Option 7</option>
                    <option value="others">Lainnya</option> <!-- Add 'Others' option -->
                </select>
                <div id="customInputA" class="custom-input mt-3">
                    <label for="otherOptionA">Input your custom option:</label>
                    <input type="text" id="otherOptionA" class="form-control">
                </div>

                <label for="select2Example2" class="mt-4">Choose Option B</label>
                <select id="select2Example2" class="js-example-basic-single2 form-select" style="width: 100%;">
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                    <option value="5">Option 5</option>
                    <option value="6">Option 6</option>
                    <option value="7">Option 7</option>
                    <option value="others">Lainnya</option> <!-- Add 'Others' option -->
                </select>
                <div id="customInputB" class="custom-input mt-3">
                    <label for="otherOptionB">Input your custom option:</label>
                    <input type="text" id="otherOptionB" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (necessary for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#select2Example').select2();
            $('#select2Example2').select2();

            // Event listener for Option A (when "Others" is selected)
            $('#select2Example').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'others') {
                    $('#customInputA').show();  // Show input for "Others"
                } else {
                    $('#customInputA').hide();  // Hide input for "Others"
                }
            });

            // Event listener for Option B (when "Others" is selected)
            $('#select2Example2').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'others') {
                    $('#customInputB').show();  // Show input for "Others"
                } else {
                    $('#customInputB').hide();  // Hide input for "Others"
                }
            });
        });
    </script>
</body>
</html>
