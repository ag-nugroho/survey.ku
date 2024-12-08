<?php

require_once '../../config/Database.php';

class ShowSoal {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getPertanyaanByCategory($survey_kategori_id) {
        // Siapkan pernyataan SQL
        $stmt = $this->conn->prepare('SELECT soal_id, pertanyaan FROM m_survey_soal WHERE survey_kategori_id = ?');
        $stmt->bind_param('i', $survey_kategori_id);
        $stmt->execute();
        
        // Dapatkan hasil
        $result = $stmt->get_result();
        
        // Ambil semua pertanyaan
        $pertanyaan = $result->fetch_all(MYSQLI_ASSOC);
        
        // Tutup pernyataan
        $stmt->close();
        
        return $pertanyaan;
    }
}

