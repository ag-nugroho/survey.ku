<?php
include '../config/Database.php';
include 'Soal.php';

$database = new Database();
$db = $database->getConnection();
$survey = new Soal($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kriteria = isset($_POST['kriteria']) ? $_POST['kriteria'] : [];

    foreach ($kriteria as $survey_kriteria_id => $data) {
        $kode_kriteria = $data['kode_kriteria'];
        $bobot_kriteria = $data['bobot_kriteria'];
        
        $survey->updateKriteria($survey_kriteria_id, $kode_kriteria, $bobot_kriteria);
    }

    header('Location: edit-kriteria.php');
    exit;
} else {
    echo "Invalid request method.";
}
?>
