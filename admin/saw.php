<?php
require_once '../config/Database.php';

class SAW {
    private $conn;
    private $kriteria;
    private $kategori;
    private $jawaban;
    private $weights;

    public function __construct($db) {
        $this->conn = $db;
        echo "Initializing SAW...<br>"; // Debug statement
        $this->kriteria = $this->getData("SELECT * FROM m_survey_kriteria");
        $this->kategori = $this->getData("SELECT * FROM m_survey_kategori");
        $this->jawaban = $this->getAllJawaban();
        $this->weights = $this->getWeights();
        echo "Initialization complete.<br>"; // Debug statement

    }

    // private function getData($query) {
    //     $result = $this->conn->query($query);
    //     if (!$result) {
    //         die("Error in query: " . $this->conn->error. " | Query: " . $query);
    //     }
    //     $data = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $data[] = $row;
    //     }
    //     return $data;
    // }
    private function getData($query) {
        // echo "Executing query: $query<br>"; // Debug statement to print the query
        if (!$result = $this->conn->query($query)) {
            die("Error executing query: " . $this->conn->error . " | Query: " . $query);
        }
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    


    private function getAllJawaban() {
        $tables = ['t_jawaban_mahasiswa', 't_jawaban_dosen', 't_jawaban_alumni', 't_jawaban_ortu', 't_jawaban_industri', 't_jawaban_tendik'];
        $jawaban = [];
        foreach ($tables as $table) {
            $jawaban = array_merge($jawaban, $this->getData("SELECT user_id, survey_id, survey_kategori_id, soal_id, jawaban FROM $table"));
        }
        return $jawaban;
    }

    private function getWeights() {
        $weights = [];
        foreach ($this->kriteria as $kri) {
            $weights[$kri['survey_kriteria_id']] = $kri['bobot_kriteria'];
        }
        return $weights;
    }


    public function getSoal($kategori_id) {
        return $this->getData("SELECT * FROM m_survey_soal WHERE survey_kategori_id = $kategori_id");
    }


    public function calculateSAW($table = null) {
        // Menghitung nilai rata-rata untuk setiap kategori dan kriteria
        $nilai_matriks = [];
        if ($table) {
            $jawaban = $this->getData("SELECT user_id, survey_id, survey_kategori_id, soal_id, jawaban FROM $table");
        } else {
            $jawaban = $this->jawaban;
        }
    
        foreach ($jawaban as $jawab) {
            $survey_kategori_id = $jawab['survey_kategori_id'];
            $soal_id = $jawab['soal_id'];
            $nilai = $jawab['jawaban'];
    
            if (empty($soal_id)) {
                continue; // Lewati jika soal_id kosong
            }
    
            $soal_query = "SELECT * FROM m_survey_soal WHERE soal_id = $soal_id";
            $soal = $this->getData($soal_query)[0];
            $survey_kriteria_id = $soal['survey_kriteria_id'];
    
            if (!isset($nilai_matriks[$survey_kategori_id])) {
                $nilai_matriks[$survey_kategori_id] = [];
            }
            if (!isset($nilai_matriks[$survey_kategori_id][$survey_kriteria_id])) {
                $nilai_matriks[$survey_kategori_id][$survey_kriteria_id] = [];
            }
    
            $nilai_matriks[$survey_kategori_id][$survey_kriteria_id][] = $nilai;
        }
    
        // Menghitung rata-rata nilai untuk setiap kriteria dan kategori
        foreach ($nilai_matriks as $kat_id => $kriteria_vals) {
            foreach ($kriteria_vals as $kri_id => $nilai_vals) {
                $nilai_matriks[$kat_id][$kri_id] = array_sum($nilai_vals) / count($nilai_vals);
            }
        }
    
        // Menormalisasi matriks menggunakan rumus benefit
        $nilai_max = [];
        foreach ($this->kriteria as $kri) {
            $kri_id = $kri['survey_kriteria_id'];
            $nilai_kri = array_column($nilai_matriks, $kri_id);
    
            if (!empty($nilai_kri)) {
                $nilai_max[$kri_id] = max($nilai_kri);
            } else {
                $nilai_max[$kri_id] = 1; // Default to 1 to avoid division by zero
            }
        }
    
        $normalisasi_matriks = [];
        foreach ($nilai_matriks as $kat_id => $kriteria_vals) {
            foreach ($kriteria_vals as $kri_id => $nilai) {
                if ($nilai_max[$kri_id] == 0) {
                    $normalisasi_matriks[$kat_id][$kri_id] = 0; // Avoid division by zero
                } else {
                    $normalisasi_matriks[$kat_id][$kri_id] = $nilai / $nilai_max[$kri_id];
                }
            }
        }
    
        // Menghitung skor akhir
        $skor_akhir = [];
        foreach ($normalisasi_matriks as $kat_id => $kriteria_vals) {
            $skor_akhir[$kat_id] = 0;
            foreach ($kriteria_vals as $kri_id => $nilai) {
                $skor_akhir[$kat_id] += $nilai * $this->weights[$kri_id];
            }
        }
    
        // Mengurutkan hasil berdasarkan skor tertinggi
        arsort($skor_akhir);
        return $skor_akhir;
    }
    
    
    public function getKriteria() {
        return $this->kriteria;
    }

    public function getKategori() {
        return $this->kategori;
    }


    public function getBobot() {
        return $this->weights;
    }

    // public function calculateSAW() {
    //     if (empty($this->jawaban)) {
    //         return "Belum ada data jawaban. Silakan periksa kembali atau masukkan data jawaban.";
    //     }
    
    //     $nilai_matriks = [];
    //     foreach ($this->jawaban as $jawab) {
    //         $survey_kategori_id = $jawab['survey_kategori_id'];
    //         $soal_id = $jawab['soal_id'];
    //         $nilai = $jawab['jawaban'];
    
    //         if (empty($soal_id)) {
    //             continue; // Lewati jika soal_id kosong
    //         }
    
    //         $soal_query = "SELECT * FROM m_survey_soal WHERE soal_id = $soal_id";
    //         $soal = $this->getData($soal_query)[0];
    //         $survey_kriteria_id = $soal['survey_kriteria_id'];
    
    //         if (!isset($nilai_matriks[$survey_kategori_id])) {
    //             $nilai_matriks[$survey_kategori_id] = [];
    //         }
    //         if (!isset($nilai_matriks[$survey_kategori_id][$survey_kriteria_id])) {
    //             $nilai_matriks[$survey_kategori_id][$survey_kriteria_id] = [];
    //         }
    
    //         $nilai_matriks[$survey_kategori_id][$survey_kriteria_id][] = $nilai;
    //     }
    
    //     $nilai_max = [];
    //     foreach ($this->kriteria as $kri) {
    //         $kri_id = $kri['survey_kriteria_id'];
    //         $nilai_kri = [];
            
    //         foreach ($nilai_matriks as $kat_id => $kriteria_vals) {
    //             if (isset($kriteria_vals[$kri_id])) {
    //                 $nilai_kri = array_merge($nilai_kri, $kriteria_vals[$kri_id]);
    //             }
    //         }
    
    //         if (!empty($nilai_kri)) {
    //             $nilai_max[$kri_id] = max($nilai_kri);
    //         } else {
    //             $nilai_max[$kri_id] = 1; // Default to avoid division by zero
    //         }
    //     }
    
    //     $normalisasi_matriks = [];
    //     foreach ($nilai_matriks as $kat_id => $kriteria_vals) {
    //         foreach ($kriteria_vals as $kri_id => $nilai_vals) {
    //             foreach ($nilai_vals as $nilai) {
    //                 if (isset($nilai_max[$kri_id]) && $nilai_max[$kri_id] != 0) {
    //                     $normalisasi_matriks[$kat_id][$kri_id][] = $nilai / $nilai_max[$kri_id];
    //                 } else {
    //                     $normalisasi_matriks[$kat_id][$kri_id][] = 0;
    //                 }
    //             }
    //         }
    //     }
    
    //     $skor_akhir = [];
    //     foreach ($normalisasi_matriks as $kat_id => $kriteria_vals) {
    //         if (!isset($skor_akhir[$kat_id])) {
    //             $skor_akhir[$kat_id] = 0;
    //         }
    //         foreach ($kriteria_vals as $kri_id => $nilai_vals) {
    //             foreach ($nilai_vals as $nilai) {
    //                 if (isset($this->weights[$kri_id])) {
    //                     $skor_akhir[$kat_id] += $nilai * $this->weights[$kri_id];
    //                 }
    //             }
    //         }
    //     }
    
    //     arsort($skor_akhir);
    //     return $skor_akhir;
    // }
    

    
    // public function calculateSAW() {
    //     if (empty($this->jawaban)) {
    //         return "Belum ada data jawaban"; // Informasi jika tidak ada data
    //     }
    
    //     $nilai_matriks = [];
    //     foreach ($this->jawaban as $jawab) {
    //         $survey_kategori_id = $jawab['survey_kategori_id'];
    //         $soal_id = $jawab['soal_id'];
    //         $nilai = $jawab['jawaban'];
    
    //         if (empty($soal_id)) {
    //             continue; // Skip jika soal_id kosong
    //         }
    
    //         $soal_query = "SELECT * FROM m_survey_soal WHERE soal_id = $soal_id";
    //         $soal = $this->getData($soal_query)[0];
    //         $survey_kriteria_id = $soal['survey_kriteria_id'];
    
    //         $nilai_matriks[$survey_kategori_id][$survey_kriteria_id][] = $nilai;
    //     }
    
    //     $nilai_max = [];
    //     foreach ($this->kriteria as $kri) {
    //         $kri_id = $kri['survey_kriteria_id'];
    //         if (!empty($nilai_matriks[$kri_id])) {
    //             $nilai_max[$kri_id] = max(array_column($nilai_matriks, $kri_id));
    //         } else {
    //             $nilai_max[$kri_id] = 1; // Default untuk menghindari pembagian dengan nol
    //         }
    //     }
    
    //     $normalisasi_matriks = [];
    //     foreach ($nilai_matriks as $kat_id => $kriteria_vals) {
    //         foreach ($kriteria_vals as $kri_id => $nilai_vals) {
    //             foreach ($nilai_vals as $nilai) {
    //                 if ($nilai_max[$kri_id] != 0) {
    //                     $normalisasi_matriks[$kat_id][$kri_id][] = $nilai / $nilai_max[$kri_id];
    //                 } else {
    //                     $normalisasi_matriks[$kat_id][$kri_id][] = 0;
    //                 }
    //             }
    //         }
    //     }
    
    //     // Menghitung skor akhir
    //     $skor_akhir = [];
    //     foreach ($normalisasi_matriks as $kat_id => $kriteria_vals) {
    //         $skor_akhir[$kat_id] = 0;
    //         foreach ($kriteria_vals as $kri_id => $nilai_vals) {
    //             foreach ($nilai_vals as $nilai) {
    //                 if (isset($this->weights[$kri_id])) {
    //                     $skor_akhir[$kat_id] += $nilai * $this->weights[$kri_id];
    //                 }
    //             }
    //         }
    //     }
    
    //     arsort($skor_akhir);
    //     return $skor_akhir;
    // }
    

    // public function calculateSAW() {
    //     // Menghitung nilai rata-rata untuk setiap kategori dan kriteria
    //     $nilai_matriks = [];
    //     foreach ($this->jawaban as $jawab) {
    //         $survey_kategori_id = $jawab['survey_kategori_id'];
    //         $soal_id = $jawab['soal_id'];
    //         $nilai = $jawab['jawaban'];
    //         $soal = $this->getData("SELECT * FROM m_survey_soal WHERE soal_id = $soal_id")[0];
    //         $survey_kriteria_id = $soal['survey_kriteria_id'];

    //         if (!isset($nilai_matriks[$survey_kategori_id])) {
    //             $nilai_matriks[$survey_kategori_id] = [];
    //         }
    //         if (!isset($nilai_matriks[$survey_kategori_id][$survey_kriteria_id])) {
    //             $nilai_matriks[$survey_kategori_id][$survey_kriteria_id] = [];
    //         }

    //         $nilai_matriks[$survey_kategori_id][$survey_kriteria_id][] = $nilai;
    //     }

    //     // Menghitung rata-rata nilai untuk setiap kriteria dan kategori
    //     foreach ($nilai_matriks as $kat_id => $kriteria_vals) {
    //         foreach ($kriteria_vals as $kri_id => $nilai_vals) {
    //             $nilai_matriks[$kat_id][$kri_id] = array_sum($nilai_vals) / count($nilai_vals);
    //         }
    //     }

    //     // Menormalisasi matriks
    //     $nilai_max = [];
    //     foreach ($this->kriteria as $kri) {
    //         $kri_id = $kri['survey_kriteria_id'];
    //         $nilai_max[$kri_id] = max(array_column($nilai_matriks, $kri_id));
    //     }

    //     $normalisasi_matriks = [];
    //     foreach ($nilai_matriks as $kat_id => $kriteria_vals) {
    //         foreach ($kriteria_vals as $kri_id => $nilai) {
    //             $normalisasi_matriks[$kat_id][$kri_id] = $nilai / $nilai_max[$kri_id];
    //         }
    //     }

    //     // Menghitung skor akhir
    //     $skor_akhir = [];
    //     foreach ($normalisasi_matriks as $kat_id => $kriteria_vals) {
    //         $skor_akhir[$kat_id] = 0;
    //         foreach ($kriteria_vals as $kri_id => $nilai) {
    //             $skor_akhir[$kat_id] += $nilai * $this->weights[$kri_id];
    //         }
    //     }

    //     // Mengurutkan hasil berdasarkan skor tertinggi
    //     arsort($skor_akhir);
    //     return $skor_akhir;
    // }

}
?>
