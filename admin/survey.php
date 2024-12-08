<?php
include_once '../config/Database.php';
include_once 'get-survey.php';

// Instantiate database and survey object
$database = new Database();
$db = $database->getConnection();

$survey = new Survey($db);

$surveyCounts = [
    'Mahasiswa' => $survey->countSurveysByCategory(1),
    'Dosen' => $survey->countSurveysByCategory(2),
    'Alumni' => $survey->countSurveysByCategory(3),
    'Orang Tua' => $survey->countSurveysByCategory(4),
    'Industri' => $survey->countSurveysByCategory(5),
    'Tendik' => $survey->countSurveysByCategory(6),
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
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
                    <a href="../admin/survey.php" class="sidebar-link bg-white rounded-3">
                        <img src="../assets/icon/logo-survey.png" alt="icon-workspace" width="18px">
                        <span>Survey</span>
                    </a>
                </li>
                <li class="sidebar-item mb-3">
                <a href="ranking-page.php" class="sidebar-link">
                <img src="../assets/icon/Feedback.png" alt="icon-feedback" width="18px">
                        <span>Ranking</span>
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
                <a href="../public/logout.php" class="sidebar-footer-link">
                    <img src="../assets/icon/Logout.png" alt="icon-logout" width="18px">
                    <span>Log out</span>
                </a>
            </div>
        </aside>

        <div class="main">
            <div class=" header d-flex justify-content-between ms-5 mt-4 mb-4 me-4">
                <div class="header-info">
                    <h1 class="fw-bold">
                        Survey
                    </h1>
                    <p class="desc mb-3">Temukan, buat, dan publikasikan ruang Anda</p>
                </div>
                <button class="btn btn-primary rounded-3 " style="width: 160px; height: 38px;" type="button" onclick="window.location.href='buat-survey.php'">Buat Survey</button>

            </div>
            <hr style="border: 2px solid #e6e6e6;">
            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <a href="../admin/survey-mhs.php">
                            <div class="card mb-3" style="width: 300px; height: 230px; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25) inset; border-radius: 6px; border: 1px #E6E6E6 solid; background-image: url(../assets/images/img-mhs.png); background-size: cover; background-repeat: no-repeat;">
                                <div class="card-body d-flex flex-column justify-content-end">
                                    <h6 class="card-title fw-semibold mt-1 mb-1">Mahasiswa</h6>
                                    <p class="card-text mb-1">Deskripsi</p>
                                    <p class="card-text"><?php echo $surveyCounts['Mahasiswa']; ?> Survey</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="../admin/survey-industri.php">
                            <div class="card mb-3" style="width: 300px; height: 230px; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25) inset; border-radius: 6px; border: 1px #E6E6E6 solid; background-image: url(../assets/images/img-industry.png); background-size: cover; background-repeat: no-repeat;">
                                <div class="card-body d-flex flex-column justify-content-end text-light">
                                    <h6 class="card-title fw-semibold mt-1 mb-1">Industri</h6>
                                    <p class="card-text mb-1">Deskripsi</p>
                                    <p class="card-text"><?php echo $surveyCounts['Industri']; ?> Survey</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="../admin/survey-dosen.php">
                            <div class="card mb-3" style="width: 300px; height: 230px; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25) inset; border-radius: 6px; border: 1px #E6E6E6 solid; background-image: url(../assets/images/img-dosen.png); background-size: cover; background-repeat: no-repeat;">
                                <div class="card-body d-flex flex-column justify-content-end text-light">
                                    <h6 class="card-title fw-semibold mt-1 mb-1">Dosen</h6>
                                    <p class="card-text mb-1">Deskripsi</p>
                                    <p class="card-text"><?php echo $surveyCounts['Dosen']; ?> Survey</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <a href="../admin/survey-alumni.php">
                            <div class="card" style="width: 300px; height: 230px; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25) inset; border-radius: 6px; border: 1px #E6E6E6 solid; background-image: url(../assets/images/img-alumni.png); background-size: cover; background-repeat: no-repeat;">
                                <div class="card-body d-flex flex-column justify-content-end">
                                    <h6 class="card-title fw-semibold mt-1 mb-1">Alumni</h6>
                                    <p class="card-text mb-1">Deskripsi</p>
                                    <p class="card-text"><?php echo $surveyCounts['Alumni']; ?> Survey</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="../admin/survey-ortu.php">
                            <div class="card" style="width: 300px; height: 230px; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25) inset; border-radius: 6px; border: 1px #E6E6E6 solid; background-image: url(../assets/images/img-ortu.png); background-size: cover; background-repeat: no-repeat;">
                                <div class="card-body d-flex flex-column justify-content-end text-light">
                                    <h6 class="card-title fw-semibold mt-1 mb-1">Orang Tua</h6>
                                    <p class="card-text mb-1">Deskripsi</p>
                                    <p class="card-text"><?php echo $surveyCounts['Orang Tua']; ?> Survey</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="../admin/survey-tendik.php">
                            <div class="card" style="width: 300px; height: 230px; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25) inset; border-radius: 6px; border: 1px #E6E6E6 solid; background-image: url(../assets/images/img-tendik.png); background-size: cover; background-repeat: no-repeat;">
                                <div class="card-body d-flex flex-column justify-content-end text-light">
                                    <h6 class="card-title fw-semibold mt-1 mb-1">Tendik</h6>
                                    <p class="card-text mb-1">Deskripsi</p>
                                    <p class="card-text"><?php echo $surveyCounts['Tendik']; ?> Survey</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>

</html>