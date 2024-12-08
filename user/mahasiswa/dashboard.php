<?php
session_start();
require_once '../../config/Database.php';


// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Membuat objek Dashboard
$dashboard = new Dashboard($db, $_SESSION['user_id'], $_SESSION['role']);

// Verifikasi akses dan cek kelengkapan profil
$dashboard->verifyAccess();

$dashboardData = $dashboard->getDashboardData();

// Tangani permintaan AJAX untuk pengecekan profil
if (isset($_GET['action']) && $_GET['action'] == 'checkProfileCompletion') {
    $dashboard->checkProfileCompletionStatus();
}

class Dashboard {
    private $conn;
    private $user_id;
    private $role;

    public function __construct($db, $user_id, $role) {
        $this->conn = $db;
        $this->user_id = $user_id;
        $this->role = $role;
    }

    public function verifyAccess() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
            header("Location: ../login.php");
            exit();
        }
    }

    // public function checkProfileCompletion(&$first_name, &$last_name, &$email, &$phone_number, &$tahun_masuk, &$prodi, &$nim, &$gender, &$city, &$country) {
    //     $query = "SELECT first_name, last_name, phone_number, tahun_masuk, prodi, nim, email, country, city, gender FROM p_mahasiswa WHERE user_id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bind_param("i", $this->user_id);
    //     $stmt->execute();
    //     $stmt->store_result();
        
    //     if ($stmt->num_rows > 0) {
    //         $stmt->bind_result($first_name, $last_name, $email, $phone_number, $tahun_masuk, $prodi, $nim, $gender, $city, $country);
    //         $stmt->fetch();

    //         if (empty($first_name) || empty($last_name) || empty($email) || empty($phone_number) || empty($tahun_masuk) || empty($prodi) || empty($nim) || empty($gender) || empty($city) || empty($country)) {
    //             return false;
    //         } else {
    //             return true;
    //         }
    //     } else {
    //         return false;
    //     }
    // }

    // public function checkProfileCompletionStatus() {
    //     // Inisialisasi variabel untuk menampung data profil
    //     $first_name = $last_name = $email = $phone_number = $tahun_masuk = $prodi = $nim = $gender = $city = $country = '';

    //     // Cek kelengkapan data profil
    //     $isComplete = $this->checkProfileCompletion($first_name, $last_name, $email, $phone_number, $tahun_masuk, $prodi, $nim, $gender, $city, $country);

    //     // Kembalikan hasil pengecekan dalam bentuk JSON
    //     // header('Content-Type: application/json');
    //     // echo json_encode(['complete' => $isComplete]);

    //     if ($isComplete) {
    //         echo "complete";
    //     } else {
    //         echo "incomplete";
    //     }
    //     exit();
    // }

    public function getDashboardData() {
        $query = "SELECT first_name, last_name, picture FROM p_mahasiswa WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data;
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/../project-survey/css/styleDashboard.css">
</head>
<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex flex-direction-column justify-content-center align-items-center mt-2">
                <button id="toggle-btn" type="button">
                    <img src="/../project-survey/assets/images/logo-polinema.png" alt="logo" class="rounded-circle ms-xl-1" width="50px">
                </button>
                <div class="sidebar-logo">
                    <span class="fw-bold">Survey.<span style="color: #03045E;">Ku</span></span>
                    <span class=" campus d-block fw-semibold">POLITEKNIK NEGERI MALANG</span>
                </div>
            </div>
            
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="dashboard.php" class="sidebar-link bg-white rounded-3 mb-3">
                        <img src="/../project-survey/assets/icon/Home.png" alt="icon-overview" width="18px">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item mb-3">
                    <a href="survey.php" class="sidebar-link">
                    <img src="/../project-survey/assets/icon/logo-survey.png" alt="icon-workspace" width="18px">
                        <span>Survey</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="../../public/logout.php" class="sidebar-footer-link">
                    <img src="/../project-survey/assets/icon/Logout.png" alt="icon-logout" width="18px">
                    <span>Log out</span>
                </a>
            </div>
        </aside>

        <div class="main">
            <div class=" header d-flex justify-content-between ms-5 mt-4 mb-4 me-4">
                <div class="header-info">
                    <h1 class="fw-bold">
                        Selamat Datang, <?php echo htmlspecialchars($dashboardData['first_name'] ?? ''); ?> <?php echo htmlspecialchars($dashboardData['last_name'] ?? ''); ?>
                    </h1>
                    <p class="desc mb-3">Lengkapi profil anda pada halaman profile, Klik di pojok kanan atas</p>
                </div>
                <a href="profile.php">
                    <img src="<?php echo htmlspecialchars($dashboardData['picture'] ?? ''); ?>" alt="profil" width="55px" height="55px" class="rounded-circle">
                </a>
            </div>
            <hr style="border: 2px solid #e6e6e6;">
            <div class="content d-flex justify-content-between gap-5">
                
                <div class="statistics ms-5">
                    <h5 class="fw-semibold">Survey</h5>
                    <p class="desc mb-4">Lakukan Survey untuk menjaga kualitas kampus Politeknik Negeri Malang</p>
                    <div class="group-survey d-flex align-items-baseline gap-5">
                        <div class="card" href="soal-kualitas-pengajaran.php" style="background-image: url(/../project-survey/assets/images/rating.jpg); background-size: cover; background-repeat: no-repeat;">
                            <div class="card-body d-flex">
                              <h6 class="card-title fw-semibold mt-1">Kualitas Pengajaran</h6>
                            </div>
                        </div>
                        <div class="card" style="background-image: url(/../project-survey/assets/images/fasilitas.jpg); background-size: cover; background-repeat: no-repeat;background-position-y: -20px;">
                            <div class="card-body d-flex" href="soal-fasilitas-kampus.php">
                              <h6 class="card-title fw-semibold mt-1">Fasilitas Kampus</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="border: 2px solid #e6e6e6; height: 100%; position: absolute;
                left: 66%; top: 108px;">
                <aside class="me-5">
                    <h5 class="fw-semibold">Survey baru-baru ini</h5>
                    <p class="desc">Survey tersedia pada menu survey</p>
                    <div class="group-survey d-flex flex-column gap-4">
                            <div class="card" style="background-image: url(/../project-survey/assets/images/layanan.jpg); background-size: cover; background-repeat: no-repeat;">
                                <div class="card-body d-flex flex-column justify-content-between" href="soal-layanan-mahasiswa.php">
                                  <h6 class="card-subtitle fw-semibold">Layanan Mahasiswa</h6>
                                </div>
                            </div>
                            <div class="card" style="background-image: url(/../project-survey/assets/images/administrasi.jpg); background-size: cover; background-repeat: no-repeat;background-position-y: -20px;">
                                <div class="card-body d-flex flex-column justify-content-between" href="soal-proses-administrasi.php">
                                  <h6 class="card-subtitle fw-semibold">Proses Administrasi</h6>
                                </div>
                            </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script src="/../project-survey/js/script.js"></script>
</body>
</html>