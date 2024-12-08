<?php
include '../config/Database.php';
include 'Soal.php';

$database = new Database();
$db = $database->getConnection();

$database = new Database();
$db = $database->getConnection();
$survey = new Soal($db);

$kriteria_list = $survey->getKriteria();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Survey</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="position:relative; right:117px; top:40px;">
            <button class="ms-2" style="border: none; background-color: transparent; width: 35px;" onclick="window.history.back()">
                <i class="lni lni-arrow-left pt-1 fs-4"></i>
            </button>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Edit Kriteria Survey</h4>
            </div>
            <div class="card-body">
                <!-- <form id="kriteriaForm" method="POST"> -->
                <form id="kriteriaForm" action="update-kriteria.php" method="POST">
                    <div id="questionsContainer">
                    <?php foreach ($kriteria_list as $index => $kriteria) : ?>
                        <div class="card question-card">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Kriteria <?php echo $index + 1; ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Kriteria</label>
                                    <input type="text" class="form-control" placeholder="Masukkan Nama Kriteria" value="<?php echo $kriteria['nama_kriteria']; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kode Kriteria</label>
                                        <input type="text" class="form-control" name="kriteria[<?php echo $kriteria['survey_kriteria_id']; ?>][kode_kriteria]" value="<?php echo $kriteria['kode_kriteria']; ?>" required>
                                        <!-- <input type="text" class="form-control" placeholder="Masukkan kode kriteria" value="" required> -->
                                </div>
                                <div class="options-container mb-3">
                                    <label class="form-label">Bobot Kriteria</label>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control bobot-kriteria" name="kriteria[<?php echo $kriteria['survey_kriteria_id']; ?>][bobot_kriteria]" value="<?php echo $kriteria['bobot_kriteria']; ?>" required>
                                        <!-- <input type="text" class="form-control" placeholder="Masukkan bobot kriteria" value="" required> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>                     
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('kriteriaForm').addEventListener('submit', function(event) {
            // Cek total bobot kriteria
            var totalBobot = 0;
            document.querySelectorAll('.bobot-kriteria').forEach(function(input) {
                totalBobot += parseFloat(input.value);
            });

            // Jika total bobot melebihi 1.00, cegah form dari submit dan tampilkan pesan error
            if (totalBobot > 1.00) {
                alert('Total bobot kriteria tidak boleh lebih dari 1.00.');
                event.preventDefault(); // Cegah pengiriman form
                return false;
            }

            alert('Survei berhasil disimpan!');
        });
    </script>
</body>

</html>
