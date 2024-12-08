<?php
include '../config/Database.php';

class Analitik {
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function getTotalSoal() {
        $sql = "SELECT COUNT(*) as total_soal FROM m_survey_soal";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total_soal'];
    }

    public function getTotalSurvey() {
        $sql = "SELECT COUNT(*) as total_survey FROM m_survey";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total_survey'];
    }

    public function getTotalJawaban() {
        $sql = "
            SELECT 
                (SELECT COUNT(*) FROM t_jawaban_mahasiswa) +
                (SELECT COUNT(*) FROM t_jawaban_dosen) +
                (SELECT COUNT(*) FROM t_jawaban_alumni) +
                (SELECT COUNT(*) FROM t_jawaban_ortu) +
                (SELECT COUNT(*) FROM t_jawaban_industri) +
                (SELECT COUNT(*) FROM t_jawaban_tendik) as total_jawaban";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total_jawaban'];
    }

    public function getJumlahResponden() {
        $data = [];
        // Query untuk mendapatkan jumlah responden untuk masing-masing kategori
        $sql_mahasiswa = "SELECT COUNT(DISTINCT user_id) as jumlah FROM t_jawaban_mahasiswa";
        $sql_dosen = "SELECT COUNT(DISTINCT user_id) as jumlah FROM t_jawaban_dosen";
        $sql_alumni = "SELECT COUNT(DISTINCT user_id) as jumlah FROM t_jawaban_alumni";
        $sql_ortu = "SELECT COUNT(DISTINCT user_id) as jumlah FROM t_jawaban_ortu";
        $sql_industri = "SELECT COUNT(DISTINCT user_id) as jumlah FROM t_jawaban_industri";
        $sql_tendik = "SELECT COUNT(DISTINCT user_id) as jumlah FROM t_jawaban_tendik";

        $data['Mahasiswa'] = $this->conn->query($sql_mahasiswa)->fetch_assoc()['jumlah'];
        $data['Dosen'] = $this->conn->query($sql_dosen)->fetch_assoc()['jumlah'];
        $data['Alumni'] = $this->conn->query($sql_alumni)->fetch_assoc()['jumlah'];
        $data['Wali Mahasiswa'] = $this->conn->query($sql_ortu)->fetch_assoc()['jumlah'];
        $data['Industri'] = $this->conn->query($sql_industri)->fetch_assoc()['jumlah'];
        $data['Tenaga Kependidikan'] = $this->conn->query($sql_tendik)->fetch_assoc()['jumlah'];

        return $data;
    }

    public function getKenaikanResponden() {
        $sql = "SELECT survey_tanggal, COUNT(survey_id) as jumlah_responden 
                FROM m_survey 
                GROUP BY survey_tanggal 
                ORDER BY survey_tanggal";
        $result = $this->conn->query($sql);
        
        $data = [];
        $previous_count = null;
    
        while ($row = $result->fetch_assoc()) {
            $tanggal = $row['survey_tanggal'];
            $jumlah_responden = $row['jumlah_responden'];
            
            // if ($previous_count !== null) {
            //     if ($jumlah_responden > $previous_count) {
            //         $status = 'naik';
            //     } elseif ($jumlah_responden < $previous_count) {
            //         $status = 'turun';
            //     } else {
            //         $status = 'tetap';
            //     }
            // } else {
            //     $status = 'naik'; // hari pertama dianggap naik karena tidak ada data sebelumnya
            // }
    
            $data[] = [
                'tanggal' => $tanggal,
                'jumlah_responden' => $jumlah_responden,
                // 'status' => $status
            ];
    
            $previous_count = $jumlah_responden;
        }
    
        return $data;
    }
    

    // public function getJumlahResponden() {
    //     $data = [];
    //     // Query untuk mendapatkan jumlah responden untuk masing-masing kategori
    //     $sql = "
    //         SELECT responden_id, COUNT(user_id) as jumlah
    //         FROM m_survey
    //         GROUP BY responden_id";
    //     $result = $this->conn->query($sql);

    //     while ($row = $result->fetch_assoc()) {
    //         switch ($row['responden_id']) {
    //             case 1:
    //                 $data['Mahasiswa'] = $row['jumlah'];
    //                 break;
    //             case 2:
    //                 $data['Dosen'] = $row['jumlah'];
    //                 break;
    //             case 3:
    //                 $data['Alumni'] = $row['jumlah'];
    //                 break;
    //             case 4:
    //                 $data['Wali Mahasiswa'] = $row['jumlah'];
    //                 break;
    //             case 5:
    //                 $data['Industri'] = $row['jumlah'];
    //                 break;
    //             case 6:
    //                 $data['Tenaga Kependidikan'] = $row['jumlah'];
    //                 break;
    //             default:
    //                 break;
    //         }
    //     }


    //     return $data;
    // }

    public function __destruct() {
        $this->conn->close();
    }
}
?>
