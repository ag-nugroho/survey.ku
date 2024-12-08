<?php
require_once 'show-soal.php';

date_default_timezone_set('Asia/Jakarta'); // Sesuaikan dengan zona waktu Anda

$survey_kategori_id = isset($_GET['survey_kategori_id']) ? intval($_GET['survey_kategori_id']) : 2; // by kategori soal
$showSoal = new ShowSoal();
$pertanyaan = $showSoal->getPertanyaanByCategory($survey_kategori_id);

// Simpan waktu_mulai dalam variabel menggunakan DateTime
$date = new DateTime();
$waktu_mulai = $date->format('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />

    <style>
        body {
            overflow-x: hidden;
        }
        .survey-container {
            width: 951px;
            height: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            border: 0.5px solid black;
        }
        .header {
            background: url('/../project-survey/assets/images/gambar-header.jpeg') no-repeat;
            background-size: cover;
            background-position-y: -230px;
            padding: 20px;
            color: #fff;
            text-align: left;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 16px;
        }
        .header h1, p {
            position: relative;
            left: 150px;
        }
        .form-check-inline {
            display: inline-block;
            margin-right: 10px;
        }
        .form-label {
            max-width: 600px;
            overflow-wrap: break-word;
            display: block;
            margin-bottom: 10px;
        }
        .pertanyaan {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            margin-bottom: 20px;
            margin: auto;
            max-width: 600px;
        }
        .pertanyaan-number {
            margin-bottom: 5px;
        }
        .radio-options {
            margin-left: 15px;
        }
    </style>

</head>
<body>

<div class="header">
    <h1>Politeknik Negeri Malang</h1>
    <p>Survey Kualitas Aplikasi Politeknik Negeri Malang</p>
</div>

<a href="survey.php">
    <button class="ms-2 mt-2" style="border: none; background-color:transparent; width: 35px">
        <i class="lni lni-arrow-left pt-1 fs-4"></i>
    </button>
</a>

<div class="container mt-0 mb-4">
    <div class="survey-container mx-auto col-md-8">
        <h4 class="text-center mb-4">Fasilitas Kampus</h4>
        <form id="survey_form" action="submit-survey.php" method="post">
        <input type="hidden" name="survey_kategori_id" value="<?php echo $survey_kategori_id; ?>">
            <?php foreach ($pertanyaan as $index => $item): ?>
                <div class="mb-4 pertanyaan">
                    <div class="pertanyaan-number"><?php echo ($index + 1) . '. ' . htmlspecialchars($item['pertanyaan']); ?></div>
                    <div class="radio-options" name="jawaban">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pertanyaan<?php echo $item['soal_id']; ?>" id="q<?php echo $item['soal_id']; ?>-sangat-buruk" value="1">
                            <label class="form-check-label" for="q<?php echo $item['soal_id']; ?>-sangat-buruk">Sangat Buruk</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pertanyaan<?php echo $item['soal_id']; ?>" id="q<?php echo $item['soal_id']; ?>-buruk" value="2">
                            <label class="form-check-label" for="q<?php echo $item['soal_id']; ?>-buruk">Buruk</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pertanyaan<?php echo $item['soal_id']; ?>" id="q<?php echo $item['soal_id']; ?>-cukup" value="3">
                            <label class="form-check-label" for="q<?php echo $item['soal_id']; ?>-cukup">Cukup</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pertanyaan<?php echo $item['soal_id']; ?>" id="q<?php echo $item['soal_id']; ?>-baik" value="4">
                            <label class="form-check-label" for="q<?php echo $item['soal_id']; ?>-baik">Baik</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pertanyaan<?php echo $item['soal_id']; ?>" id="q<?php echo $item['soal_id']; ?>-sangat-baik" value="5">
                            <label class="form-check-label" for="q<?php echo $item['soal_id']; ?>-sangat-baik">Sangat Baik</label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <input type="hidden" id="survey_kategori_page" name="survey_kategori_page" value="soal-fasilitas-kampus.php">

            <div class="text-left mt-5">
                <button type="submit" class="btn btn-primary ps-4 pe-4">Kirim</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
