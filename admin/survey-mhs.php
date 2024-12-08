<?php
// Include database and survey class files
include_once '../config/Database.php';
include_once 'get-survey.php';

// Instantiate database and survey object
$database = new Database();
$db = $database->getConnection();

$survey = new Survey($db);
$result = $survey->getSurveysForMahasiswa();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Survey Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/styleSurvey.css">
</head>
<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex flex-direction-column justify-content-center align-items-center mt-2">
                <button id="toggle-btn" type="button">
                    <img src="../assets/images/logo-polinema.png" alt="logo" class="rounded-circle ms-xl-1" width="50px">
                </button>
                <div class="sidebar-logo">
                    <span class="fw-bold">Survey.<span style="color: #03045E;">Ku</span></span>
                    <span class=" campus d-block fw-semibold">POLITEKNIK NEGERI MALANG</span>
                </div>
            </div>
            
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="../admin/dashboard-admin.php" class="sidebar-link mb-3">
                        <img src="../assets/icon/Home.png" alt="icon-overview" width="18px">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item mb-3">
                    <a href="../admin/survey.php" class="sidebar-link has-dropdown collapsed bg-white rounded-3">
                    <img src="../assets/icon/logo-survey.png" alt="icon-workspace" width="18px">
                        <span>Survey</span>
                    </a>
                </li>
                <li class="sidebar-item mb-3">
                    <a href="../admin/ranking-page.php" class="sidebar-link">
                        <img src="../assets/icon/Feedback.png" alt="icon-feedback" width="18px">
                        <span>Rangking</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../admin/analitik.php" class="sidebar-link">
                        <img src="../assets/icon/Analytics.png" alt="icon-analytics" width="18px">
                        <span>Analitik</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" class="sidebar-footer-link">
                    <img src="../assets/icon/Logout.png" alt="icon-logout" width="18px">
                    <span>Log out</span>
                </a>
            </div>
        </aside>

        <div class="main">
            <div class=" header d-flex justify-content-between ms-5 mt-4 mb-4 me-4">
                <div class="header-info">
                    <h1 class="fw-bold">
                        Mahasiswa
                    </h1>
                    <p class="desc mb-3">Temukan, buat, dan publikasikan ruang Anda</p>
                </div>
                <!-- <button class="btn btn-primary rounded-3 " style="width: 160px; height: 38px;" type="button" onclick="window.location.href='buat-survey.php'">Buat Survey</button> -->
                <button class="btn btn-primary rounded-3 " style="width: 160px; height: 38px;" type="button" onclick="window.location.href='edit-kriteria.php'">Edit Kriteria</button>

            </div>
            <hr style="border: 2px solid #e6e6e6;">
            <div class="content active d-flex justify-content-center gap-4">
                <?php
                while ($row = $result->fetch_assoc()) {
                    $survey_judul = $row['survey_judul'];
                    // $background_pic = base64_encode($row['background_pic']); // Encode image to base64
                    $background_pic = $row['background_pic']; 
                    $survey_kategori_id = $row['survey_kategori_id']; 
                ?>
                <a href="edit-survey.php?survey_kategori_id=<?php echo $survey_kategori_id; ?>">
                <!-- <div class="card" style="width: 229px; height: 128px; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25) inset; border-radius: 6px; border: 2px black solid; 
                background-image: url('data:image/jpeg;base64,<?php echo $background_pic; ?>'); background-size: cover; background-repeat: no-repeat;"> -->
                 <div class="card" style="width: 229px; height: 128px; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25) inset; border-radius: 6px; border: 2px black solid; 
                background-image: url('<?php echo htmlspecialchars($background_pic); ?>'); background-size: cover; background-repeat: no-repeat;">
                <div class="card-body d-flex">
                          <h6 class="card-title fw-semibold mt-1 mb-1"><?php echo $survey_judul; ?></h6>
                        </div>
                    </div>
                </a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>
