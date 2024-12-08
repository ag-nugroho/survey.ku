  <?php
include '../../config/Database.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['status' => 'error', 'message' => 'Invalid Request'];

    if (isset($_POST['form_id'])) {
        $formId = $_POST['form_id'];

        switch ($formId) {
            case 'formEditPicture':
                if (isset($_POST['user_id'], $_POST['nim'], $_POST['password']) && isset($_FILES['picture'])) {
                    $userId = $_POST['user_id'];
                    $nim = $_POST['nim'];
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
                    $picture = $_FILES['picture'];

                    // Handle file upload
                    $targetDir = "uploads/";
                    $targetFile = $targetDir . basename($picture["name"]);
                    if (move_uploaded_file($picture["tmp_name"], $targetFile)) {
                        $picturePath = $targetFile;

                        // Update m_user
                        $sqlUser = "UPDATE m_user SET password = ? WHERE user_id = ?";
                        $stmtUser = $conn->prepare($sqlUser);
                        $stmtUser->bind_param('si', $password, $userId);
                        $stmtUser->execute();

                        // Update p_mahasiswa
                        $sqlMahasiswa = "UPDATE p_mahasiswa SET nim = ?, picture = ? WHERE user_id = ?";
                        $stmtMahasiswa = $conn->prepare($sqlMahasiswa);
                        $stmtMahasiswa->bind_param('ssi', $nim, $picturePath, $userId);
                        $stmtMahasiswa->execute();

                        $response = ['status' => 'success', 'message' => 'Data updated successfully'];
                    } else {
                        $response['message'] = 'Failed to upload picture';
                    }
                }
                break;

            case 'formEditProfile':
                if (isset($_POST['user_id'], $_POST['nim'], $_POST['first_name'], $_POST['last_name'], $_POST['prodi'], $_POST['phone_number'], $_POST['tahun_masuk'], $_POST['email'], $_POST['gender'])) {
                    $userId = $_POST['user_id'];
                    $nim = $_POST['nim'];
                    $firstName = $_POST['first_name'];
                    $lastName = $_POST['last_name'];
                    $prodi = $_POST['prodi'];
                    $phoneNumber = $_POST['phone_number'];
                    $tahunMasuk = $_POST['tahun_masuk'];
                    $email = $_POST['email'];
                    $gender = $_POST['gender'];

                    // Update p_mahasiswa
                    $sql = "UPDATE p_mahasiswa SET nim = ?, first_name = ?, last_name = ?, prodi = ?, phone_number = ?, tahun_masuk = ?, email = ?, gender = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssssssssi', $nim, $firstName, $lastName, $prodi, $phoneNumber, $tahunMasuk, $email, $gender, $userId);
                    $stmt->execute();

                    $response = ['status' => 'success', 'message' => 'Profile updated successfully'];
                }
                break;

            case 'formEditAlamat':
                if (isset($_POST['user_id'], $_POST['country'], $_POST['city'])) {
                    $userId = $_POST['user_id'];
                    $country = $_POST['country'];
                    $city = $_POST['city'];

                    // Update p_mahasiswa
                    $sql = "UPDATE p_mahasiswa SET country = ?, city = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssi', $country, $city, $userId);
                    $stmt->execute();

                    $response = ['status' => 'success', 'message' => 'Address updated successfully'];
                }
                break;
        }
    }

    echo json_encode($response);
}
?>
