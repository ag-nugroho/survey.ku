<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Profil Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styleProfile.css">
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

                <li class="sidebar- mb-3">
                    <a href="../admin/dashboard-admin.php" class="sidebar-link">
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
                    <a href="../admin/umpan-balik.php" class="sidebar-link">
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
                <a href="../logout.php" class="sidebar-footer-link">
                    <img src="../assets/icon/Logout.png" alt="icon-logout" width="18px">
                    <span>Log out</span>
                </a>
            </div>
        </aside>

        <div class="main">
            <div class="justify-content-center ms-4 mt-4 mb-4">
                <h1 class="fw-bold">
                    Profil Saya
                </h1>
            </div>
            <hr style="border: 2px solid #e6e6e6;">
            <div class="content">
                <div class="card border-black rounded-4 d-flex flex-row justify-content-center align-items-center mx-5 my-4 pt-lg-2 ps-4 pe-5">
                    <img src="../assets/icon/user.png" alt="profile" class="rounded-circle me-xl-5 mb-2" width="98px" height="98px">
                    <div class="card-body d-flex flex-column justify-content-center text-start">
                        <h5 class="fw-semibold mb-3 mt-2">Agung Nugroho</h5>
                        <p class="fw-normal">Admin</p>
                        <p class=" role fw-light">Tim Admin</p>
                    </div>
                    <div class="edit border border-black mb-5 extra-margin">
                        <i class="lni lni-pencil-alt" data-bs-toggle="modal" data-bs-target="#editnama"></i>
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
                                    <p class="fw-medium">Agung</p>
                                </div>
                                <div class="col-4">
                                    <p class="identity mb-2">Nama belakang</p>
                                    <p class="fw-medium">Nugroho</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <p class="identity mb-2">Email</p>
                                    <p class="fw-medium">nugrohoagung757@gmail.com</p>
                                </div>
                                <div class="col-4">
                                    <p class="identity mb-2">Nomor Handphone</p>
                                    <p class="fw-medium">+62 8958 0977 2288</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card rounded-4 border-black mx-5 my-4">
                    <div class="header d-flex flex-row justify-content-between p-4 pb-0">
                        <h5 class="fw-semibold">Alamat</h5>
                        <div class="edit border border-black me-4"><!--👇 Kyk iki yo 👇-->
                            <i class="lni lni-pencil-alt" data-bs-toggle="modal" data-bs-target="#editalamat"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row mb-5">
                                <div class="col-4">
                                    <p class="identity mb-2">Negara</p>
                                    <p class="fw-medium">Indonesia</p>
                                </div>
                                <div class="col-4">
                                    <p class="identity mb-2">Kota</p>
                                    <p class="fw-medium">Malang/Kepanjen</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Iki bagian sing nampilno pop up gawe edit 
            Selain iki, onok maneh ndek bagian logo edit e, iku onok attribut tambahan, tambahno pisan iku-->
        <div class="modal fade" id="editnama" tabindex="-1" aria-labelledby="editnama" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-body d-flex justify-content-between gap-1">
                        <img src="../assets/icon/user.png" alt="profile" width="250px" height="250px">
                        <button style="height: 40px; width:40px; border-radius:8px; position:relative; top:200px; right:65px;" ><img src="../assets/icon/camera.png" alt="edit" width="25px" height="25px"></button>
                        <form action="" class="ms-0">
                        <div class="form-group position-relative mb-3 ms-3">
                            <input type="text" class="form-control border-0 border-bottom rounded-0" id="firstname" value="Agung Nugroho">
                            <p class="fw-normal mt-2">Admin</p>
                            <p class="fw-light mt-0">Tim Admin</p>
                        </div>
                        <div class="form-group position-relative mb-3 ms-3">
                            <label for="gantipassword" class="mb-1 text-secondary" style="font-size: 12px;">Ganti Password</label>
                            <input type="password" class="form-control border-0 border-bottom rounded-0 h-auto" id="password" style="font-size: 12px;">
                        </div>
                        <button type="button" class="btn btn-white border-black rounded mt-4" style="float: right;">Simpan</button>
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
                        <form action="">
                        <div class="form-group position-relative mb-3">
                            <label for="firstname" class="mb-1 text-secondary">Firstname</label>
                            <input type="text" class="form-control border-0 border-bottom rounded-0" id="firstname" value="Agung">
                        </div>
                        <div class="form-group position-relative mb-3">
                            <label for="lastname" class="mb-1 text-secondary">Lastname</label>
                            <input type="text" class="form-control border-0 border-bottom rounded-0" id="lastname" value="Nugroho">
                        </div>
                        <div class="form-group position-relative mb-3">
                            <label for="email" class="mb-1 text-secondary">Email address</label>
                            <input type="email" class="form-control border-0 border-bottom rounded-0" id="email" value="nugrohoagung757@gmail.com">
                        </div>
                        <div class="form-group position-relative mb-3">
                            <label for="nomor_handphone" class="mb-1 text-secondary">Nomor Handphone</label>
                            <input type="text" class="form-control border-0 border-bottom rounded-0" id="nim" value="082820209191">
                        </div>
                        <button type="button" class="btn btn-white border-black rounded mt-4" style="float: right;">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editalamat" tabindex="-1" aria-labelledby="editalamat" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editprofil">Alamat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                        <div class="form-group position-relative mb-3">
                            <label for="firstname" class="mb-1 text-secondary">Negara</label>
                            <input type="text" class="form-control border-0 border-bottom rounded-0" id="firstname" value="Indonesia">
                        </div>
                        <div class="form-group position-relative mb-3">
                            <label for="lastname" class="mb-1 text-secondary">Kota</label>
                            <input type="text" class="form-control border-0 border-bottom rounded-0" id="lastname" value="Malang/Kepanjen">
                        </div>
                        <button type="button" class="btn btn-white border-black rounded mt-4" style="float: right;">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>

</html>