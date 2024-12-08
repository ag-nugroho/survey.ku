<?php
class Soal
{
    private $conn;
    private $table_name = "m_survey_soal";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getQuestions($survey_kategori_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE survey_kategori_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $survey_kategori_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateQuestion($soal_id, $pertanyaan, $survey_kriteria_id)
    {
        $query = "UPDATE " . $this->table_name . " SET pertanyaan = ?, survey_kriteria_id = ? WHERE soal_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $pertanyaan, $survey_kriteria_id, $soal_id);
        return $stmt->execute();
    }

    public function insertQuestion($survey_kategori_id, $responden_id, $pertanyaan, $survey_kriteria_id)
    {
        $query = "INSERT INTO " . $this->table_name . " (survey_kategori_id, responden_id, pertanyaan, survey_kriteria_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiss", $survey_kategori_id, $responden_id, $pertanyaan, $survey_kriteria_id);
        return $stmt->execute();
    }

    public function getRespondenId($survey_kategori_id)
    {
        $query = "SELECT responden_id FROM m_survey_kategori WHERE survey_kategori_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $survey_kategori_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['responden_id'];
    }
    public function getKriteria()
    {
        $query = "SELECT * FROM m_survey_kriteria";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteQuestion($soal_id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE soal_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $soal_id);
        $result =  $stmt->execute();
        if ($result) {
            return true;
        } else {
            error_log("Failed to delete question: " . $stmt->error);
            return false;
        }
    }

    public function updateKriteria($survey_kriteria_id, $kode_kriteria, $bobot_kriteria)
    {
        $query = "UPDATE m_survey_kriteria SET kode_kriteria = ?, bobot_kriteria = ? WHERE survey_kriteria_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $kode_kriteria, $bobot_kriteria, $survey_kriteria_id);
        return $stmt->execute();
    }

}
?>