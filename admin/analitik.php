<?php
include 'data-analitik.php';

$database = new Database();
$db = $database->getConnection();

$analitik = new Analitik($db);

$total_soal = $analitik->getTotalSoal();
$total_survey = $analitik->getTotalSurvey();
$total_jawaban = $analitik->getTotalJawaban();
$jumlah_responden = $analitik->getJumlahResponden();
$kenaikan_responden = $analitik->getKenaikanResponden();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analitik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styleDashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .filter-container {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-tag {
            display: flex;
            align-items: center;
            background-color: #e0e0e0;
            border-radius: 12px;
            padding: 5px 10px;
            font-size: 14px;
        }

        .filter-tag span {
            margin-right: 8px;
        }

        .filter-tag button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }

        .filter-add {
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border: 1px dashed #6c757d;
            border-radius: 12px;
            cursor: pointer;
            font-size: 14px;
            background-color: white;
        }

        .chart-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .chart-card {
            flex: 1;
            width: 100%;
        }

        .full-width-chart {
            flex: 1;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <aside id="sidebar" style="height: 160vh;">
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
                    <a href="ranking-page.php" class="sidebar-link">
                        <img src="../assets/icon/Feedback.png" alt="icon-feedback" width="18px">
                        <span>Ranking</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../admin/analitik.php" class="sidebar-link bg-white rounded-3">
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
                    <h1 class="fw-bold">Analitik</h1>
                    <p class="desc mb-2">Pantau terus perkembangan statistik dari survey anda</p>
                </div>
            </div>
            <div class="content ms-5 me-5 mb-5">
                <div class="statistics mb-5">
                    <div class="group-cards d-flex align-items-baseline gap-4 mt-4">
                        <div class="card" style="width: 294px; height: 144px; border-radius: 12px">
                            <div class="card-body d-flex flex-column-reverse">
                                <h5 class="card-title fw-semibold mt-1"><?php echo $total_soal; ?></h5>
                                <p class="card-text">Soal</p>
                            </div>
                        </div>
                        <div class="card" style="width: 294px; height: 144px; border-radius: 12px">
                            <div class="card-body d-flex flex-column-reverse">
                                <h5 class="card-title fw-semibold mt-1"><?php echo $total_survey; ?></h5>
                                <p class="card-text">Responden</p>
                            </div>
                        </div>
                        <div class="card" style="width: 294px; height: 144px; border-radius: 12px">
                            <div class="card-body d-flex flex-column-reverse">
                                <h5 class="card-title fw-semibold mt-1"><?php echo $total_jawaban; ?></h5>
                                <p class="card-text">Jawaban</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grafik">
                    <section class="py-3 py-md-5">
                        <div class="container">
                            <div class="row chart-container">
                                <div id="respondenChart" class="chart-card full-width-chart">
                                    <div class="card widget-card border-light shadow-sm mb-5">
                                        <div class="card-body p-4">
                                            <div class="d-block d-sm-flex align-items-center justify-content-between mb-3">
                                                <div class="mb-3 mb-sm-0">
                                                    <h5 class="card-title widget-card-title">Data Responden</h5>
                                                </div>
                                            </div>
                                            <div id="bsb-chart-4"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="jumlahChart" class="chart-card full-width-chart">
                                    <div class="card widget-card border-light shadow-sm mb-5">
                                        <div class="card-body p-4">
                                            <div class="d-block d
                                                sm-flex align-items-center justify-content-center mb-3">
                                                <div class="mb-3 mb-sm-0">
                                                    <h5 class="card-title widget-card-title">Jumlah Responden</h5>
                                                </div>
                                            </div>
                                            <div class="card-body d-flex justify-content-center" style="height:60%;">
                                                <canvas id="surveyChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
    </div>

        <script src="../js/script.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                 // Data responden dan jumlahnya dari PHP
                const respondenData = <?php echo json_encode(array_values($jumlah_responden)); ?>;
                const labels = <?php echo json_encode(array_keys($jumlah_responden)); ?>;

                // Debug: Check if data is correctly passed
                console.log('Responden Data:', respondenData); 
                console.log('Labels:', labels); 

                // Format data untuk ApexCharts
                const series = respondenData.map(item => parseInt(item));
                const labelsFormatted = labels.map(item => item);

                // Options untuk ApexCharts
                const options = {
                    chart: {
                        type: 'donut',
                        width: '100%'
                    },
                    series: series,
                    labels: labelsFormatted,
                    colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%'
                            }
                        }
                    },
                    legend: {
                        show: true,
                        position: 'bottom'
                    },
                    dataLabels: {
                        enabled: true
                    }
                };

                const chart = new ApexCharts(document.querySelector("#bsb-chart-4"), options);
                chart.render();

                // Data untuk grafik jumlah responden
                const kenaikanRespondenData = <?php echo json_encode($kenaikan_responden); ?>;
                const tanggalLabels = kenaikanRespondenData.map(data => data.tanggal);
                const jumlahResponden = kenaikanRespondenData.map(data => data.jumlah_responden);

                const dataJumlahResponden = {
                    labels: tanggalLabels,
                    datasets: [{
                        label: 'Jumlah Responden',
                        data: jumlahResponden,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                };

                const optionsJumlahResponden = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                };

                const ctx = document.getElementById('surveyChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: dataJumlahResponden,
                    options: optionsJumlahResponden
                });
            });
        </script>
    </body>
</html>
