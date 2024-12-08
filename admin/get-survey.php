<?php
class Survey {
    private $conn;
    private $table_name = "m_survey_kategori";

    public $survey_jenis;
    public $responden_id;
    public $survey_judul;
    public $survey_kode;
    public $survey_deskripsi;
    public $background_pic;


    public function __construct($db) {
        $this->conn = $db;
    }

     // Function to create a new survey
     public function createSurvey() {
        $query = "INSERT INTO " . $this->table_name . " (survey_jenis, responden_id, survey_judul, survey_kode, survey_deskripsi, background_pic)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bind_param("sissss", $this->survey_jenis, $this->responden_id, $this->survey_judul, $this->survey_kode, $this->survey_deskripsi, $this->background_pic);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function countSurveysByCategory($responden_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE responden_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $responden_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'];
    }
    
    public function getSurveysForMahasiswa() {
        $query = "SELECT survey_kategori_id, survey_judul, background_pic FROM " . $this->table_name . " WHERE responden_id = 1";
        $result = $this->conn->query($query);
        
        if (!$result) {
            die("Query gagal: " . $this->conn->error);
        }
        
        return $result;
    }

    public function getSurveysForDosen() {
        $query = "SELECT survey_kategori_id, survey_judul, background_pic FROM " . $this->table_name . " WHERE responden_id = 2";
        $result = $this->conn->query($query);
        
        if (!$result) {
            die("Query gagal: " . $this->conn->error);
        }
        
        return $result;
    }

    public function getSurveysForAlumni() {
        $query = "SELECT survey_kategori_id, survey_judul, background_pic FROM " . $this->table_name . " WHERE responden_id = 3";
        $result = $this->conn->query($query);
        
        if (!$result) {
            die("Query gagal: " . $this->conn->error);
        }
        
        return $result;
    }

    public function getSurveysForWaliMhs() {
        $query = "SELECT survey_kategori_id, survey_judul, background_pic FROM " . $this->table_name . " WHERE responden_id = 4";
        $result = $this->conn->query($query);
        
        if (!$result) {
            die("Query gagal: " . $this->conn->error);
        }
        
        return $result;
    }

    public function getSurveysForIndustri() {
        $query = "SELECT survey_kategori_id, survey_judul, background_pic FROM " . $this->table_name . " WHERE responden_id = 5";
        $result = $this->conn->query($query);
        
        if (!$result) {
            die("Query gagal: " . $this->conn->error);
        }
        
        return $result;
    }

    public function getSurveysForTendik() {
        $query = "SELECT survey_kategori_id, survey_judul, background_pic FROM " . $this->table_name . " WHERE responden_id = 6";
        $result = $this->conn->query($query);
        
        if (!$result) {
            die("Query gagal: " . $this->conn->error);
        }
        
        return $result;
    }

    

}

?>
