<?php

class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbname = "pelanggan_survey";
    private $conn;

    public function __construct() {
        // Debug: Menampilkan pesan saat mencoba untuk terhubung ke database
        // echo "Mencoba terhubung ke database...<br>";
        
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        // Debug: Menampilkan pesan koneksi berhasil atau gagal
        // if ($this->conn->connect_error) {
        //     die("Koneksi gagal: " . $this->conn->connect_error);
        // } else {
        //     echo "Koneksi berhasil!<br>";
        // }
    }

    public function getConnection() {
        // Debug: Menampilkan pesan saat mengembalikan koneksi database
        // echo "Mengembalikan koneksi database...<br>";
        return $this->conn;
    }
}

?>  