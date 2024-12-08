<?php
include_once '../config/Database.php';
include_once 'get-survey.php';

// Instantiate database and survey object
$database = new Database();
$db = $database->getConnection();

$survey = new Survey($db);


// $response = ['success' => false, 'message' => ''];

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $survey->survey_jenis = $_POST['survey_jenis'];
//     $survey->responden_id = $_POST['responden_id'];
//     $survey->survey_judul = $_POST['survey_judul'];
//     $survey->survey_kode = $_POST['survey_kode'];
//     $survey->survey_deskripsi = $_POST['survey_deskripsi'];

//     // Handle file upload
//     if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
//         $targetDir = "uploads/";
//         $fileName = basename($_FILES['fileUpload']['name']);
//         $targetFilePath = $targetDir . $fileName;

//         if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $targetFilePath)) {
//             $survey->background_pic = $targetFilePath;

//             if ($survey->createSurvey()) {
//                 $response['success'] = true;
//                 $response['message'] = 'Survei berhasil disimpan!';
//                 $response['picture'] = $targetFilePath;
//             } else {
//                 $response['message'] = 'Gagal menyimpan survei.';
//             }
//         } else {
//             $response['message'] = 'File upload failed.';
//         }
//     } else {
//         $response['message'] = 'No file uploaded.';
//     }

//     echo json_encode($response);
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $survey->survey_jenis = $_POST['survey_jenis'];
    $survey->responden_id = $_POST['responden_id'];
    $survey->survey_judul = $_POST['survey_judul'];
    $survey->survey_kode = $_POST['survey_kode'];
    $survey->survey_deskripsi = $_POST['survey_deskripsi'];

    // Handle file upload
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);
    if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
        $survey->background_pic = $target_file;
    } else {
        $survey->background_pic = null;
    }

    if ($survey->createSurvey()) {
        echo "<script>alert('Survei berhasil disimpan!');</script>";
    } else {
        echo "<script>alert('Gagal menyimpan survei.');</script>";
    }
}



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
                <h4>Buat Survey</h4>
            </div>
            <div class="card-body">
                <form id="surveyForm" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="survey_jenis" class="form-label">Jenis Survey</label>
                        <select class="form-select" id="survey_jenis" name="survey_jenis"  required>
                            <option disabled selected>Pilih jenis survey</option>
                            <option value="kualitas">Kualitas</option>
                            <option value="fasilitas">Fasilitas</option>
                            <option value="pelayanan">Pelayanan</option>
                            <option value="lulusan">Lulusan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="responden_id" class="form-label">Responden</label>
                        <select class="form-select" id="responden_id" name="responden_id" required>
                            <option disabled selected>Pilih responden survey</option>
                            <option value="1">Mahasiswa</option>
                            <option value="2">Dosen</option>
                            <option value="3">Alumni</option>
                            <option value="4">Wali Mahasiswa</option>
                            <option value="5">Industri</option>
                            <option value="6">Tenaga Kependidikan</option>
                        </select>
                    </div>
                     <div class="mb-3">
                        <label for="survey_judul" class="form-label">Judul Survey</label>
                        <input type="text" class="form-control" id="survey_judul" name="survey_judul" placeholder="Masukkan judul survey" required>
                    </div>
                    <div class="mb-3">
                        <label for="survey_kode" class="form-label">Kode Survey</label>
                        <input type="text" class="form-control" id="survey_kode" name="survey_kode" placeholder="Masukkan kode survey" required>
                    </div>
                    <div class="mb-3">
                        <label for="survey_deskripsi" class="form-label">Deskripsi Survey</label>
                        <textarea class="form-control" id="survey_deskripsi" name="survey_deskripsi" rows="3" placeholder="Masukkan deskripsi survey"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="background_pic" class="form-label">Gambar Background Survey</label>
                        <input type="file" class="form-control" name="fileUpload" id="fileUpload" accept="image/*" required>
                    </div> 
                    <button type="submit" class="btn btn-success">Simpan Survei</button>
                </form>
            </div>
        </div>
    </div>

    <!-- <script>
        document.getElementById('surveyForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Optionally, handle the successful submission further
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script> -->

    <!-- <script>
        document.getElementById('surveyForm').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Survei berhasil disimpan!');
        });
    </script> -->
</body>

</html>
