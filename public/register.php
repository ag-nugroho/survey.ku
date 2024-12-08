<?php 
require_once '../config/Database.php';

class Register {
    private $conn;
    private $role;
    private $first_name;
    private $last_name;
    private $email;
    private $username;
    private $password;
    private $phone_number;
    private $city;
    private $country;

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function setData($data) {
        $this->role = htmlspecialchars($data['role']);
        $this->first_name = htmlspecialchars($data['first_name']);
        $this->last_name = htmlspecialchars($data['last_name']);
        $this->email = htmlspecialchars($data['email']);
        $this->username = htmlspecialchars($data['username']);
        // $this->password = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->password = htmlspecialchars($data['password']); // Password tidak di-hash
        $this->phone_number = htmlspecialchars($data['phone_number']);
        $this->city = htmlspecialchars($data['city']);
        $this->country = htmlspecialchars($data['country']);
    }

    public function registerUser() {
        // echo "Memulai proses registrasi...<br>";
        
        $query_user = "INSERT INTO m_user (username, password, role) VALUES (?, ?, ?)";
        $stmt_user = $this->conn->prepare($query_user);
        $stmt_user->bind_param("sss", $this->username, $this->password, $this->role);

        if ($stmt_user->execute()) {
            // echo "User berhasil dimasukkan ke m_user!<br>";
            $user_id = $this->conn->insert_id;

            $query_role = "";
            switch ($this->role) {
                case 'mahasiswa':
                    $query_role = "INSERT INTO p_mahasiswa (user_id, first_name, last_name, email, phone_number, city, country) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    break;
                case 'dosen':
                    $query_role = "INSERT INTO p_dosen (user_id, first_name, last_name, email, phone_number, city, country) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    break;
                case 'tendik':
                    $query_role = "INSERT INTO p_tendik (user_id, first_name, last_name, email, phone_number, city, country) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    break;
                case 'ortu':
                    $query_role = "INSERT INTO p_ortu (user_id, first_name, last_name, email, phone_number, city, country) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    break;
                case 'alumni':
                    $query_role = "INSERT INTO p_alumni (user_id, first_name, last_name, email, phone_number, city, country) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    break;
                case 'industri':
                    $query_role = "INSERT INTO p_industri (user_id, first_name, last_name, email, phone_number, city, country) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    break;
                default:
                    echo "Role tidak dikenal!<br>";
                    return false;
            }

            $stmt_role = $this->conn->prepare($query_role);
            $stmt_role->bind_param("issssss", $user_id, $this->first_name, $this->last_name, $this->email, $this->phone_number, $this->city, $this->country);

            if ($stmt_role->execute()) {
                // echo "Data berhasil dimasukkan ke tabel $this->role!<br>";
                return true;
            } else {
                // echo "Error dalam memasukkan data ke tabel $this->role: " . $stmt_role->error . "<br>";
                return false;
            }
        } else {
            // echo "Error dalam memasukkan data ke m_user: " . $stmt_user->error . "<br>";
            return false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $register = new Register($database);
    $register->setData($_POST);
    $register->registerUser();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <link rel="stylesheet" href="../css/styleRegist.css">
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src="../assets/images/polinema.reg.png" alt="Gambar">
        </div>
        <div class="form-container">
            <form method="post" action="register.php" class="form" >
                <header>Registration Form</header>
                    <div class="input-box">
                        <div class="select-box">
                            <select id="role" name="role">
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                            <option value="tendik">Tenaga Kependidikan</option>
                            <option value="industri">Industri</option>
                            <option value="alumni">Alumni</option>
                            <option value="ortu">Orang Tua</option>
                            </select>
                        </div>
                    </div>
                    <div class="column">
                        <div class="input-box">
                          <input name="first_name" type="text" placeholder="First Name" required />
                        </div>
                        <div class="input-box">
                          <input name="last_name" type="text" placeholder="Last Name" required />
                        </div>
                    </div>
                    <div class="input-box">
                        <input name="email" type="email" placeholder="Email Address" required />
                      </div>
                      <div class="column">
                        <div class="input-box">
                          <input name="username" type="text" placeholder="Username" required />
                        </div>
                        <div class="input-box">
                          <input name="password" type="password" placeholder="Password" required />
                        </div>
                      </div>
                      <div class="input-box">
                          <input name="phone_number" type="number" placeholder="Phone Number" required />
                      </div>
                      <div class="column">
                        <div class="input-box">
                          <input name="city" type="text" placeholder="City" required />
                        </div>
                        <div class="input-box">
                          <input name="country" type="text" placeholder="Country" required />
                        </div>
                    </div> 
                <button type="submit" name="register" class="btn btn-success">Register</button>
                <?php
                if (!isset($error_message)) {
                    echo '<div class="login-link">Already have an account?  <a href="login.php">Login here</a></div>';
                }
                ?>
            </form>
        
            
        </div>
    </div>
</body>
</html>
