<?php
session_start();
require_once '../config/Database.php';
require_once 'saw.php';

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi kelas SAW
$saw = new SAW($db);

$results = $saw->calculateSAW();

// Dapatkan kategori dari m_survey_kategori
$kategori = $saw->getKategori();

function getKategoriNameById($kategori, $id) {
    foreach ($kategori as $item) {
        if ($item['survey_kategori_id'] == $id) {
            return $item['survey_judul'];
        }
    }
    return 'Unknown';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/styleDashboard.css">
    <style>
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .feedback-card {
            margin-bottom: 20px;
            border: 1px solid #e6e6e6;
            border-radius: 12px;
        }
        .feedback-card .card-body {
            padding: 20px;
        }
        .nav-tabs .nav-link {
            font-size: 14px;
            color: black;
            position: relative;
            transition: color 0.3s;
        }
        .nav-tabs .nav-link.active {
            color: black;
            border: none !important;
            font-weight: 700;
        }
        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -5px;
            transform: translateX(-50%);
            width: 30%;
            height: 3px;
            background-color: #0085FF;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <aside id="sidebar" style="height: 170vh;">
            <div class="d-flex flex-direction-column justify-content-center align-items-center mt-2">
                <button id="toggle-btn" type="button">
                    <img src="../assets/images/logo-polinema.png" alt="logo" class="rounded-circle ms-xl-1" width="50px">
                </button>
                <div class="sidebar-logo">
                    <span class="fw-bold">Survey.<span style="color: #03045E;">Ku</span></span>
                    <span class="campus d-block fw-semibold">POLITEKNIK NEGERI MALANG</span>
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
                    <a href="../admin/survey.php" class="sidebar-link">
                        <img src="../assets/icon/logo-survey.png" alt="icon-workspace" width="18px">
                        <span>Survey</span>
                    </a>
                </li>
                <li class="sidebar-item mb-3">
                    <a href="../admin/umpan-balik.php" class="sidebar-link bg-white rounded-3">
                        <img src="../assets/icon/Feedback.png" alt="icon-feedback" width="18px">
                        <span>Umpan Balik</span>
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
            <div class="header d-flex justify-content-between ms-5 mt-4 mb-4 me-4">
                <div class="header-info">
                    <h1 class="fw-bold">Ranking</h1>
                    <p class="desc mb-3">Perankingan Kategori Survey terbaik</p>
                </div>
            </div>
            <div class="container-fluid mt-4 mb-4">
                <?php if (is_string($results)): ?>
                    <p><?php echo $results; ?></p> <!-- Tampilkan pesan jika tidak ada data -->
                <?php else: ?>
                    <div class="table-responsive border border-secondary-subtle rounded-2" style="height: 580px;">
                        <table class="table table-borderless align-middle text-center rounded-1" style="width: 100%; font-size: 15px;">
                            <thead>
                                <tr class="border-bottom">
                                    <th scope="col" style="width: 5%;">Rank</th>
                                    <th scope="col" style="width: 10%;">Kategori ID</th>
                                    <th scope="col" style="width: 50%;">Nama Survey</th>
                                    <th scope="col" style="width: 25%;">Total Skor</th>
                                    <th scope="col" style="width: 10%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $rank = 1;
                                foreach ($results as $kategori_id => $skor): ?>
                                    <tr>
                                        <td><?php echo $rank++; ?></td>
                                        <td><?php echo $kategori_id; ?></td> <!-- Sesuaikan dengan nama kategori sebenarnya -->
                                        <td><?php echo getKategoriNameById($kategori, $kategori_id); ?></td>
                                        <td><?php echo number_format($skor, 3); ?></td>
                                        <td><button class="btn btn-primary" onclick="window.location.href='detail.php?kategori_id=<?php echo $kategori_id; ?>'">Detail</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.nav-link');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    let currentActiveTab = document.querySelector('.nav-link.active');
                    let currentActiveContent = document.querySelector('.tab-pane.show.active');

                    currentActiveTab.classList.remove('active');
                    currentActiveContent.classList.remove('show', 'active');

                    tab.classList.add('active');
                    document.querySelector(tab.getAttribute('data-bs-target')).classList.add('show', 'active');
                });
            });
        });
    </script>
</body>
</html>
