<?php
session_start();
require_once '../../config/Database.php';

date_default_timezone_set('Asia/Jakarta'); // Sesuaikan dengan zona waktu Anda

// Membuat koneksi ke database
$database = new Database();
$conn = $database->getConnection();

// Ambil user_id dari session
if (!isset($_SESSION['user_id'])) {
    die("User ID tidak ditemukan dalam sesi.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Memulai proses penyimpanan survei...<br>";

    $waktu_selesai = (new DateTime())->format('Y-m-d H:i:s'); // Ambil waktu sekarang sebagai waktu selesai
    $survey_tanggal = (new DateTime())->format('Y-m-d H:i:s');

    $survey_kategori_id = isset($_POST['survey_kategori_id']) ? $_POST['survey_kategori_id'] : die('ERROR: survey_kategori_id not found.');

    // Masukkan data ke tabel m_survey
    $query_survey = "INSERT INTO m_survey (user_id, responden_id, survey_tanggal, waktu_selesai) VALUES ('$user_id', '1', '$survey_tanggal', '$waktu_selesai')";
    $result_survey = $conn->query($query_survey);

    if ($result_survey) {
        echo "Data survei berhasil dimasukkan ke m_survey!<br>";
        $survey_id = $conn->insert_id;

        // Loop melalui semua input yang diterima
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'pertanyaan') === 0) {
                // Ambil ID soal dari nama input
                $soal_id = str_replace('pertanyaan', '', $key);

                // Menangkap nilai jawaban yang dipilih
                $jawaban = $value;

                // Masukkan jawaban ke tabel t_jawaban_mahasiswa
                $query_jawaban = "INSERT INTO t_jawaban_tendik (user_id, survey_id, survey_kategori_id, soal_id, jawaban) VALUES ('$user_id', '$survey_id', '$survey_kategori_id', '$soal_id', '$jawaban')";
                $result_jawaban = $conn->query($query_jawaban);

                if ($result_jawaban) {
                    echo "Jawaban untuk soal $soal_id berhasil dimasukkan ke t_jawaban_mahasiswa!<br>";
                } else {
                    echo "Error dalam memasukkan jawaban untuk soal $soal_id: " . $conn->error . "<br>";
                }
            }
        }

        // Redirect ke halaman sukses atau halaman lain
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error dalam memasukkan data ke m_survey: " . $conn->error . "<br>";
    }

}

?>
