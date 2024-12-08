<?php
session_start();
require_once '../../config/Database.php';

class Profile {
    private $conn;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->conn = $db;
        $this->user_id = $user_id;
    }

    public function getProfileData() {
        $query = "SELECT first_name, last_name, gender, nim, email, prodi, phone_number, tahun_masuk, country, city, picture FROM p_mahasiswa WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data;
    }


    public function updateProfileData($data) {
        error_log("updateProfileData called");
        $fields = [];
        $params = [];
        $types = '';

        foreach ($data as $field => $value) {
            $fields[] = "$field = ?";
            $params[] = $value;
            $types .= 's';
        }
        $params[] = $this->user_id;
        $types .= 'i';

        $query = "UPDATE p_mahasiswa SET " . implode(', ', $fields) . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            error_log("Update profile data success for user_id: " . $this->user_id);
            return true;
        } else {
            error_log("Update profile data failed for user_id: " . $this->user_id . " with error: " . $stmt->error);
            return false;
        }
    }

    public function updatePassword($password) {
        error_log("updatePassword called");
        $query = "UPDATE m_user SET password = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $password, $this->user_id);

        if ($stmt->execute()) {
            error_log("Password update success for user_id: " . $this->user_id);
            return true;
        } else {
            error_log("Password update failed for user_id: " . $this->user_id . " with error: " . $stmt->error);
            return false;
        }
    }

    public function updateProfilePicture($picturePath) {
        error_log("updateProfilePicture called");
        $query = "UPDATE p_mahasiswa SET picture = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $picturePath, $this->user_id);

        if ($stmt->execute()) {
            error_log("Profile picture update success for user_id: " . $this->user_id);
            return true;
        } else {
            error_log("Profile picture update failed for user_id: " . $this->user_id . " with error: " . $stmt->error);
            return false;
        }
    }

    public function updateAlamat($country, $city) {
        error_log("updateAlamat called");
        $query = "UPDATE p_mahasiswa SET country = ?, city = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $country, $city, $this->user_id);

        if ($stmt->execute()) {
            error_log("Alamat update success for user_id: " . $this->user_id);
            return true;
        } else {
            error_log("Alamat update failed for user_id: " . $this->user_id . " with error: " . $stmt->error);
            return false;
        }
    }
}

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Dapatkan user_id dari sesi
$user_id = $_SESSION['user_id'];

// Buat objek Profile
$profile = new Profile($db, $user_id);

// Ambil data profil
$profileData = $profile->getProfileData();

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    error_log("Action received: $action");

    switch ($action) {
        case 'updateProfile':
            if (isset($_POST['first_name']) || isset($_POST['last_name']) || isset($_POST['gender']) || isset($_POST['nim']) 
            || isset($_POST['email']) || isset($_POST['prodi']) || isset($_POST['phone_number']) || isset($_POST['tahun_masuk']) ){
                $profileData = [];
                if (isset($_POST['first_name'])) $profileData['first_name'] = $_POST['first_name'];
                if (isset($_POST['last_name'])) $profileData['last_name'] = $_POST['last_name'];
                if (isset($_POST['gender'])) $profileData['gender'] = $_POST['gender'];
                if (isset($_POST['nim'])) $profileData['nim'] = $_POST['nim'];
                if (isset($_POST['email'])) $profileData['email'] = $_POST['email'];
                if (isset($_POST['prodi'])) $profileData['prodi'] = $_POST['prodi'];
                if (isset($_POST['phone_number'])) $profileData['phone_number'] = $_POST['phone_number'];
                if (isset($_POST['tahun_masuk'])) $profileData['tahun_masuk'] = $_POST['tahun_masuk'];
                $success = $profile->updateProfileData($profileData);
                echo json_encode(['success' => $success]);
            }else {
                echo json_encode(['success' => false, 'message' => 'Update data failed.']);
            }
        break;

            case 'updateProfilePicture':
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                    $targetDir = "../../uploads/";
                    $fileName = basename($_FILES['profile_picture']['name']);
                    $targetFilePath = $targetDir . $fileName;
    
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                        $success = $profile->updateProfilePicture($targetFilePath);
                        $_SESSION['profile_picture'] = $targetFilePath; // Update session profile picture
                        echo json_encode(['success' => $success, 'picture' => $targetFilePath]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'File upload failed.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
                }
                if (isset($_POST['nim'])) {
                    $profile->updateProfileData(['nim' => $_POST['nim']]);
                }

                if (isset($_POST['password'])) {
                    $profile->updatePassword($_POST['password']);
                }
            break;
    
            case 'updateAlamat':
                if (isset($_POST['country']) && isset($_POST['city'])) {
                    $country = $_POST['country'];
                    $city = $_POST['city'];
                    $success = $profile->updateAlamat($country, $city);
                    echo json_encode(['success' => $success]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Country or City not provided.']);
                }
            break;

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Profil Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../../css/styleProfile.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex flex-direction-column justify-content-center align-items-center mt-2">
                <button id="toggle-btn" type="button">
                    <img src="../../assets/images/logo-polinema.png" alt="logo" class="rounded-circle ms-xl-1" width="50px">
                </button>
                <div class="sidebar-logo">
                    <span class="fw-bold">Survey.<span style="color: #03045E;">Ku</span></span>
                    <span class="campus d-block fw-semibold">POLITEKNIK NEGERI MALANG</span>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item mb-3">
                    <a href="../mahasiswa/Dashboard.php" class="sidebar-link">
                        <img src="../../assets/icon/Home.png" alt="icon-overview" width="18px">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item mb-3">
                    <a href="../mahasiswa/survey.php" class="sidebar-link">
                        <img src="../../assets/icon/logo-survey.png" alt="icon-workspace" width="18px">
                        <span>Survey</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="../../public/logout.php" class="sidebar-footer-link">
                    <img src="../../assets/icon/Logout.png" alt="icon-logout" width="18px">
                    <span>Log out</span>
                </a>
            </div>
        </aside>

        <div class="main">
            <div class="justify-content-center ms-4 mt-4 mb-4">
                <h1 class="fw-bold">Profil Saya</h1>
            </div>
            <hr style="border: 2px solid #e6e6e6;">
            <div class="content">
                <div class="card border-black rounded-4 d-flex flex-row justify-content-center align-items-center mx-5 my-4 pt-lg-2 ps-4 pe-5">
                    <img src="<?php echo htmlspecialchars($profileData['picture'] ?? ''); ?>" alt="profile" class="rounded-circle me-xl-5 mb-2" width="98px" height="98px">
                    <div class="card-body d-flex flex-column justify-content-center text-start">
                        <h5 class="fw-semibold mb-3 mt-2"><?php echo htmlspecialchars($profileData['first_name'] ?? ''); ?> <?php echo htmlspecialchars($profileData['last_name'] ?? ''); ?></h5>
                        <p class="fw-normal">Mahasiswa</p>
                        <p class="role fw-light" name="nim"><?php echo htmlspecialchars($profileData['nim'] ?? ''); ?></p>
                    </div>
                    <div class="edit border border-black mb-5 extra-margin">
                        <i class="lni lni-pencil-alt" data-bs-toggle="modal" data-bs-target="#editpicture"></i>
                    </div>
                </div>

                <div class="card rounded-4 border-black mx-5 my-4 ">
                    <div class="header d-flex flex-row justify-content-between p-4 pb-0">
                        <h5 class="fw-semibold">Informasi Pribadi</h5>
                        <div class="edit border border-black me-4">
                            <i class="lni lni-pencil-alt" data-bs-toggle="modal" data-bs-target="#editprofil"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row mb-4">
                                <div class="col-4">
                                    <p class="identity mb-2">Nama Depan</p>
                                    <p class="fw-medium" name="first_name"><?php echo htmlspecialchars($profileData['first_name'] ?? ''); ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="identity mb-2">Nama Belakang</p>
                                    <p class="fw-medium" name="last_name"><?php echo htmlspecialchars($profileData['last_name'] ?? ''); ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="identity mb-2">Jenis Kelamin</p>
                                    <p class="fw-medium" name="gender"><?php echo htmlspecialchars($profileData['gender'] ?? ''); ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <p class="identity mb-2">Tahun Masuk</p>
                                    <p class="fw-medium" name="tahun_masuk"><?php echo htmlspecialchars($profileData['tahun_masuk'] ?? ''); ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="identity mb-2">Email</p>
                                    <p class="fw-medium" name="email"><?php echo htmlspecialchars($profileData['email'] ?? ''); ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="identity mb-2">Program Studi</p>
                                    <p class="fw-medium" name="prodi"><?php echo htmlspecialchars($profileData['prodi'] ?? ''); ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="identity mb-2">Nomor Handphone</p>
                                    <p class="fw-medium" name="phone_number"><?php echo htmlspecialchars($profileData['phone_number'] ?? ''); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card rounded-4 border-black mx-5 my-4">
                    <div class="header d-flex flex-row justify-content-between p-4 pb-0">
                        <h5 class="fw-semibold">Alamat</h5>
                        <div class="edit border border-black me-4">
                            <i class="lni lni-pencil-alt" data-bs-toggle="modal" data-bs-target="#updateAlamatModal"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row mb-5">
                                <div class="col-4">
                                    <p class="identity mb-2">Negara</p>
                                    <p class="fw-medium" name="country"><?php echo htmlspecialchars($profileData['country'] ?? ''); ?></p>
                                </div>
                                <div class="col-4">
                                    <p class="identity mb-2">Kota</p>
                                    <p class="fw-medium" name="city"><?php echo htmlspecialchars($profileData['city'] ?? ''); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editpicture" tabindex="-1" aria-labelledby="editpicture" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered ">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <div class="modal-content">
                    <div class="modal-body d-flex justify-content-between gap-1">
                        <form action="" id="formEditPicture" enctype="multipart/form-data" method="post">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <img id="profile-pic" src="<?php echo htmlspecialchars($profileData['picture'] ?? ''); ?>" alt="profile" width="150px" height="150px">
                                    <button type="button" id="upload-btn">
                                        <img src="../../assets/icon/camera.png" alt="edit" width="20px" height="20px">
                                    </button>
                                    <input type="file" id="file-input" name="profile_picture" style="display: none;" accept="image/*">
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group position-relative mb-3">
                                        <label for="name" class="mb-1 text-secondary"><?php echo htmlspecialchars($profileData['first_name'] ?? ''); ?> <?php echo htmlspecialchars($profileData['last_name'] ?? ''); ?></label>
                                    </div>
                                    <div class="form-group position-relative mb-3">
                                        <label for="mahasiswa" class="mb-1 text-secondary">Mahasiswa</label>
                                    </div>
                                    <div class="form-group position-relative mb-3">
                                        <label for="nim" class="mb-1 text-secondary">NIM</label>
                                        <input type="text" class="form-control border-0 border-bottom rounded-0" id="nim" name="nim" value="<?php echo htmlspecialchars($profileData['nim'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group position-relative mb-3">
                                        <label for="gantipassword" class="mb-1 text-secondary" style="font-size: 12px;">Ganti Password</label>
                                        <input type="password" class="form-control border-0 border-bottom rounded-0 h-auto" id="password" name="password" style="font-size: 12px;">
                                    </div>
                                    <button type="submit" class="btn btn-white border-black rounded mt-4" style="float: right;">Simpan</button>   
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editprofil" tabindex="-1" aria-labelledby="editprofil" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editprofil">Informasi Pribadi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="formEditProfil" enctype="multipart/form-data" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <div class="form-group position-relative mb-3">
                                <label for="firstname" class="mb-1 text-secondary">Nama Depan</label>
                                <input type="text" class="form-control border-0 border-bottom rounded-0" id="firstname" name="first_name" value="<?php echo htmlspecialchars($profileData['first_name'] ?? ''); ?>">
                            </div>
                            <div class="form-group position-relative mb-3">
                                <label for="lastname" class="mb-1 text-secondary">Nama Belakang</label>
                                <input type="text" class="form-control border-0 border-bottom rounded-0" id="lastname" name="last_name" value="<?php echo htmlspecialchars($profileData['last_name'] ?? ''); ?>">
                            </div>
                            <div class="form-group position-relative mb-3">
                                <label for="gender" class="mb-1 text-secondary">Jenis Kelamin</label>
                                <input type="text" class="form-control border-0 border-bottom rounded-0" id="gender" name="gender" value="<?php echo htmlspecialchars($profileData['gender'] ?? ''); ?>">
                            </div>
                            <div class="form-group position-relative mb-3">
                                <label for="tahun_masuk" class="mb-1 text-secondary">Tahun Masuk</label>
                                <input type="text" class="form-control border-0 border-bottom rounded-0" id="tahun_masuk" name="tahun_masuk" value="<?php echo htmlspecialchars($profileData['tahun_masuk'] ?? ''); ?>">
                            </div>
                            <div class="form-group position-relative mb-3">
                                <label for="email" class="mb-1 text-secondary">Email</label>
                                <input type="email" class="form-control border-0 border-bottom rounded-0" id="email" name="email" value="<?php echo htmlspecialchars($profileData['email'] ?? ''); ?>">
                            </div>
                            <div class="form-group position-relative mb-3">
                                <label for="prodi" class="mb-1 text-secondary">Program Studi</label>
                                <input type="text" class="form-control border-0 border-bottom rounded-0" id="prodi" name="prodi" value="<?php echo htmlspecialchars($profileData['prodi'] ?? ''); ?>">
                            </div>
                            <div class="form-group position-relative mb-3">
                                <label for="phone_number" class="mb-1 text-secondary">Nomor Handphone</label>
                                <input type="text" class="form-control border-0 border-bottom rounded-0" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($profileData['phone_number'] ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-white border-black rounded mt-4" style="float: right;">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateAlamatModal" tabindex="-1" aria-labelledby="editalamat" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <form id="formEditAlamat" method="post">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editalamat">Alamat</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <div class="form-group position-relative mb-3">
                                <label for="country" class="mb-1 text-secondary">Negara</label>
                                <input type="text" class="form-control border-0 border-bottom rounded-0" id="country" name="country" value="<?php echo htmlspecialchars($profileData['country'] ?? ''); ?>">
                            </div>
                            <div class="form-group position-relative mb-3">
                                <label for="city" class="mb-1 text-secondary">Kota</label>
                                <input type="text" class="form-control border-0 border-bottom rounded-0" id="city" name="city" value="<?php echo htmlspecialchars($profileData['city'] ?? ''); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-white border-black rounded mt-4" style="float: right;">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js/script.js"></script>
    <script>
        $(document).ready(function() {
            $('#upload-btn').click(function() {
                $('#file-input').click();
            });

            function showLoading() {
                $('#loading-icon').show();
            }

            function hideLoading() {
                $('#loading-icon').hide();
            }

            function showAlert(message, type) {
                var alertId = type === 'success' ? '#success-alert' : '#error-alert';
                $('.alert').hide(); // Hide any existing alerts
                $(alertId).text(message).fadeIn();
                setTimeout(function() {
                    $(alertId).fadeOut();
                }, 3000);
            }

            function closeModal(modalId) {
                $(modalId).modal('hide');
            }

            function reloadPage() {
                setTimeout(function() {
                    location.reload();
                }, 500); // Delay sedikit sebelum reload untuk memastikan perubahan data telah selesai
            }

            $('#formEditPicture').submit(function(event) {
                event.preventDefault();
                showLoading();
                var formData = new FormData(this);
                formData.append('action', 'updateProfilePicture');
                formData.append('nim', $('#nim').val());
                formData.append('password', $('#password').val());

                $.ajax({
                    type: 'POST',
                    url: 'profile.php',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert('Profile picture updated successfully.');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Failed to update profile picture.');
                    }
                });
            });

            $('#formEditAlamat').submit(function(event) {
                event.preventDefault();
                showLoading();
                var formData = {
                    action: 'updateAlamat',
                    country: $('#country').val(),
                    city: $('#city').val()
                };

                $.ajax({
                    type: 'POST',
                    url: 'profile.php',
                    data: formData,
                    success: function(response) {
                        alert('Address updated successfully.');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Failed to update address.');
                    }

                });
            });

            $('#formEditProfil').submit(function(event) {
                event.preventDefault();
                showLoading();
                var formData = {
                    action: 'updateProfile',
                    first_name: $('#firstname').val(),
                    last_name: $('#lastname').val(),
                    gender: $('#gender').val(),
                    tahun_masuk: $('#tahun_masuk').val(),
                    email: $('#email').val(),
                    prodi: $('#prodi').val(),
                    phone_number: $('#phone_number').val()
                };

                $.ajax({
                    type: 'POST',
                    url: 'profile.php',
                    data: formData,
                    success: function(response) {
                        alert('Profile updated successfully.');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Failed to update address.');
                    }
                });
            });
        });
    </script>
</body>
</html>

