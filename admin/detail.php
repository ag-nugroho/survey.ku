<?php

include 'saw.php';

// Ambil data dari URL
$kategori_id = $_GET['kategori_id'] ?? null;

if ($kategori_id === null) {
    die("Kategori ID tidak ditemukan.");
}

$database = new Database();
$db = $database->getConnection();

$saw = new SAW($db);
$kategori = $saw->getKategori();
$kriteria = $saw->getKriteria();
$soal = $saw->getSoal($kategori_id);

// Hitung SAW untuk kategori tertentu
$results = $saw->calculateSAW();
$weights = $saw->getBobot();

// $detail_result = $results[$kategori_id] ?? null;

// if ($detail_result === null) {
//     die("Data tidak ditemukan untuk kategori ID: " . htmlspecialchars($kategori_id));
// }

$detail_result = [];
if (isset($results[$kategori_id])) {
    foreach ($soal as $s) {
        $soal_id = $s['soal_id'];
        $detail_result[$soal_id] = $results[$kategori_id][$soal_id] ?? 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penilaian</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }
        .card-body {
            padding: 1.25rem;
        }
        .card-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        .table th, .table td {
            border-top: none;
        }
        .table thead th {
            border-bottom: 2px solid #dee2e6;
        }
        .table tbody td {
            border-bottom: 1px solid #dee2e6;
        }
        .table th, .table td {
            padding: 0.75rem;
        }
        .table tr:last-child th, .table tr:last-child td {
            border-bottom: 0;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .criteria-row {
            border-bottom: 2px solid #dee2e6;
        }
        .criteria-row small {
            display: block;
            margin-top: -0.5rem;
            color: #6c757d;
        }
        .back-icon {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            color: #000;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="dashboard-admin.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Detail Penilaian</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">Data Pertanyaan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($soal as $s): ?>
                                <tr class="criteria-row">
                                    <td id="pertanyaan"><?php echo htmlspecialchars($s['pertanyaan']); ?></td>
                                    <!-- <td class="text-right" id="skor"><?php echo number_format((float)($detail_result[$s['soal_id']] ?? 0), 3); ?></td> -->
                                </tr>
                                <tr>
                                <td id="nama_kriteria"><small>Kriteria <?php echo htmlspecialchars($kriteria[$s['survey_kriteria_id']]['nama_kriteria'] ?? 'Unknown'); ?></small></td>
                                <td class="text-right" id="bobot_kriteria"><small><?php echo number_format((float)($weights[$s['survey_kriteria_id']] ?? 0), 3); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <!-- <td colspan="2" class="text-right"><strong>Total Skor</strong></td> -->
                                <!-- <td colspan="2" class="text-right"><strong><?php echo number_format(array_sum($detail_result), 3); ?></strong></td> -->
                            </tr>
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>
