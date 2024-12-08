<?php
session_start();
require_once '../config/Database.php';

class Login {
    private $conn;
    private $table_name = "m_user";

    public $username;
    public $password;
    public $user_id;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function authenticate() {
        $query = "SELECT user_id, username, password, role FROM " . $this->table_name . " WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($query);

        // Debugging: Display query
        // echo "Debug: Login query: $query<br>";

        $stmt->bind_param("ss", $this->username, $this->password);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($this->user_id, $this->username, $this->password, $this->role);
            $stmt->fetch();
            return true;
        } else {
            // Debugging: No rows found
            // echo "Debug: No rows found for username: {$this->username}<br>";
        }
        return false;
    }

    public function redirectBasedOnRole() {
        switch ($this->role) {
            case 'admin':
                header("Location: ../admin/dashboard-admin.php");
                break;
            case 'mahasiswa':
                header("Location: ../user/mahasiswa/Dashboard.php");
                break;
            case 'dosen':
                header("Location: ../user/dosen/Dashboard.php");
                break;
            case 'industri':
                header("Location: ../user/industri/Dashboard.php");
                break;
            case 'ortu':
                header("Location: ../user/orangtua/Dashboard.php");
                break;
            case 'alumni':
                header("Location: ../user/alumni/Dashboard.php");
                break;
            case 'tendik':
                header("Location: ../user/tendik/Dashboard.php");
                break;
            default:
                // echo "Invalid role.<br>";
                break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $login = new Login($db);
    $login->username = $_POST['username'];
    $login->password = $_POST['password'];

    // Debugging: Cek nilai input dari form
    // echo "Input username: " . $login->username . "<br>";
    // echo "Input password: " . $login->password . "<br>";

    if ($login->authenticate()) {
        $_SESSION['user_id'] = $login->user_id;
        $_SESSION['role'] = $login->role;
        $login->redirectBasedOnRole();
    } else {
        $error_message = "Login failed. Incorrect username or password.";
        // echo $error_message;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Survey Kepuasan Pelanggan Polinema</title>
    <link rel="stylesheet" href="../css/styleLogin.css">
</head>
<body>
    <div class="container">
        <div class="content">
            <img src="../assets/images/logo-polinema.png" alt="logo-polinema" width="140px">
            <div id="instructions">
                <p>Masukkan Username dan Password<br>(Menggunakan NIM/NIP/NIK & Password)</p>
            </div>
            <form method="POST" action="">
                <input type="text" name="username" id="username" placeholder="Username" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <hr>
                <button type="submit">LOGIN</button>
                <?php if (isset($error_message)) { echo "<p>$error_message</p>"; } ?>
                <p class="register">Doesn't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
        <footer>Sistem Informasi Survey Kepuasan Pelanggan Polinema</footer>
    </div>
</body>
</html>
