<?php
include '../config/Database.php';
include 'Soal.php';

$database = new Database();
$db = $database->getConnection();
$survey = new Soal($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleted_questions = isset($_POST['deleted_questions']) ? explode(',', $_POST['deleted_questions']) : [];
    $survey_kategori_id = isset($_POST['survey_kategori_id']) ? $_POST['survey_kategori_id'] : die('ERROR: survey_kategori_id not found.');
    $questions = isset($_POST['questions']) ? $_POST['questions'] : [];

    // Hapus pertanyaan yang ditandai untuk dihapus
    foreach ($deleted_questions as $soal_id) {
        if (!empty($soal_id)) {
            $survey->deleteQuestion($soal_id);
        }
    }

    // Update atau insert pertanyaan yang ada
    foreach ($questions as $question) {
        $id = isset($question['soal_id']) ? $question['soal_id'] : null;
        $pertanyaan = $question['pertanyaan'];
        $survey_kriteria_id = $question['survey_kriteria_id'];
        
        if ($id) {
            $survey->updateQuestion($id, $pertanyaan, $survey_kriteria_id);
        } else {
            $responden_id = $survey->getRespondenId($survey_kategori_id);
            $survey->insertQuestion($survey_kategori_id, $responden_id, $pertanyaan, $survey_kriteria_id);
        }
    }

    header('Location: edit-survey.php?survey_kategori_id=' . $survey_kategori_id);
    exit;
} else {
    echo "Invalid request method.";
}
?>

<?php
// include '../config/Database.php';
// include 'Soal.php';


// $database = new Database();
// $db = $database->getConnection();
// $survey = new Soal($db);

// $survey_kategori_id = isset($_POST['survey_kategori_id']) ? $_POST['survey_kategori_id'] : die('ERROR: survey_kategori_id not found.');
// $questions = isset($_POST['questions']) ? $_POST['questions'] : [];


// foreach ($questions as $question) {
//     $id = isset($question['soal_id']) ? $question['soal_id'] : null;
//     $pertanyaan = $question['pertanyaan'];
//     $survey_kriteria_id = $question['survey_kriteria_id'];                             
    
//     if ($id) {
//         $survey->updateQuestion($id, $pertanyaan, $survey_kriteria_id);
//     } else {
//         $responden_id = $survey->getRespondenId($survey_kategori_id);
//         $survey->insertQuestion($survey_kategori_id, $responden_id, $pertanyaan, $survey_kriteria_id);
//     }
// }

// header('Location: edit-survey.php?survey_kategori_id=' . $survey_kategori_id);
// exit;

// echo json_encode(['message' => 'Survey updated successfully']);


// include 'edit-survey.php';

// $database = new Database();
// $db = $database->getConnection();
// $survey = new Soal($db);

// $survey_kategori_id = isset($_POST['survey_kategori_id']) ? $_POST['survey_kategori_id'] : die('ERROR: survey_kategori_id not found.');

// $data = json_decode(file_get_contents("php://input"), true);

// foreach ($data['questions'] as $question) {
//     if (isset($question['id'])) {
//         $survey->updateQuestion($question['id'], $question['pertanyaan'], $question['kriteria']);
//     } else {
//         $responden_id = $survey->getRespondenId($survey_kategori_id);
//         $survey->insertQuestion($survey_kategori_id, $responden_id, $question['pertanyaan'], $question['kriteria']);
//     }
// }

// echo json_encode(['message' => 'Survey updated successfully']);
?>
