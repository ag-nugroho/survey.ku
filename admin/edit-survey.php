<?php
include '../config/Database.php';
include 'Soal.php';

// $database = new Database();
// $db = $database->getConnection();

$database = new Database();
$db = $database->getConnection();
$survey = new Soal($db);


$survey_kategori_id = isset($_GET['survey_kategori_id']) ? $_GET['survey_kategori_id'] : die('ERROR: survey_kategori_id not found.');
$result = $survey->getQuestions($survey_kategori_id);
$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
$kriteria_list = $survey->getKriteria();
?>  


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Survey</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="position:relative; right:117px; top:40px;">
            <button class="ms-2" style="border: none; background-color: transparent; width: 35px;" onclick="window.history.back()">
                <i class="lni lni-arrow-left pt-1 fs-4"></i>
            </button>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Edit Survey</h4>
            </div>
            <div class="card-body">
                <form id="surveyForm" action="update-survey.php" method="POST">
                    <input type="hidden" name="survey_kategori_id" value="<?php echo $survey_kategori_id; ?>">
                    <input type="hidden" name="responden_id" value="<?php echo $responden_id; ?>">
                    <input type="hidden" id="deletedQuestionsInput" name="deleted_questions" value="">
                    <div id="questionsContainer">
                        <?php
                        foreach ($questions as $index => $question) : ?>
                            <div class="card question-card">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Pertanyaan <?php echo $index + 1; ?></span>
                                        <button type="button" class="btn-close" aria-label="Close" onclick="removeQuestion(this , <?php echo $question['soal_id']; ?>)"></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Pertanyaan</label>
                                        <input type="text" class="form-control" name="questions[<?php echo $index; ?>][pertanyaan]" value="<?php echo $question['pertanyaan']; ?>" required>
                                        <input type="hidden" name="questions[<?php echo $index; ?>][soal_id]" value="<?php echo $question['soal_id']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kriteria</label>
                                        <select class="form-select" name="questions[<?php echo $index; ?>][survey_kriteria_id]">
                                            <option value="0" disabled>Pilih Kriteria</option>
                                            <?php foreach ($kriteria_list as $kriteria) : ?>
                                                <option value="<?php echo $kriteria['survey_kriteria_id']; ?>" 
                                                    <?php echo $question['survey_kriteria_id'] == $kriteria['survey_kriteria_id'] ? 'selected' : ''; ?>><?php echo $kriteria['nama_kriteria']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="addQuestion()">Tambah Pertanyaan</button>
                    <button type="submit" class="btn btn-success">Simpan Survei</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        let questionCount = <?php echo count($questions); ?>;
        let deletedQuestions = [];

        function addQuestion() {
            const questionIndex = document.querySelectorAll('.question-card').length;
            const questionCard = document.createElement('div');
            questionCard.className = 'card question-card';
            questionCard.innerHTML = `
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pertanyaan ${questionIndex + 1}</span>
                        <button type="button" class="btn-close" aria-label="Close" onclick="removeQuestion(this)"></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <input type="text" class="form-control" name="questions[${questionIndex}][pertanyaan]" required>
                        <input type="hidden" name="questions[<?php echo $index; ?>][soal_id]" value="<?php echo $question['soal_id']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kriteria</label>
                        <select class="form-select" name="questions[${questionIndex}][survey_kriteria_id]">
                            <option value="0" disabled selected>Pilih Kriteria</option>
                            <?php foreach ($kriteria_list as $kriteria) : ?>
                                <option value="<?php echo $kriteria['survey_kriteria_id']; ?>"><?php echo $kriteria['nama_kriteria']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            `;
            document.getElementById('questionsContainer').appendChild(questionCard);
        }


        function removeQuestion(button, soal_id) {
            button.closest('.question-card').remove();
            if (soal_id) {
                deletedQuestions.push(soal_id); // Tambahkan ID pertanyaan ke array deletedQuestions
            }
            // Perbarui input hidden dengan ID pertanyaan yang dihapus
            document.getElementById('deletedQuestionsInput').value = deletedQuestions.join(',');
        }
       
    </script>
</body>
</html>
