<?php
require_once 'models/QuizModel.php';

function createQuiz($koneksi) {
    if ($_SESSION['role'] != 'teacher') { die("Akses Ditolak"); }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $chapter_id = $_POST['chapter_id'];
        $title = $_POST['title'];
        
        $quiz_id = buatKuisBaru($koneksi, $chapter_id, $title);

        header("Location: index.php?page=edit_quiz&id=" . $quiz_id);
    }
}

function editQuiz($koneksi) {
    if ($_SESSION['role'] != 'teacher') { die("Akses Ditolak"); }
    
    $quiz_id = $_GET['id'];

    $quiz = ambilDetailQuiz($koneksi, $quiz_id);
    $questions = ambilDaftarSoal($koneksi, $quiz_id);
    
    require 'views/teacher/quiz_builder.php';
}

function saveQuestion($koneksi) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $quiz_id = $_POST['quiz_id'];

        $weight = intval($_POST['weight']); 
        
        require_once 'models/QuizModel.php';

        $skor_sudah_ada = hitungTotalSkorSaatIni($koneksi, $quiz_id);

        $total_nanti = $skor_sudah_ada + $weight;

        if ($total_nanti > 100) {
            $sisa = 100 - $skor_sudah_ada;
            echo "<script>
                alert('GAGAL MENYIMPAN! ❌\\n\\nTotal bobot nilai tidak boleh melebihi 100.\\nTotal saat ini: $skor_sudah_ada\\nYang akan diinput: $weight\\nSisa kuota: $sisa');
                window.location='index.php?page=edit_quiz&id=$quiz_id';
            </script>";
            exit;
        }

        $media_filename = null;
        if (!empty($_POST['media_file'])) {
            $media_filename = trim($_POST['media_file']);
        }

        $question_text = $_POST['question_text'];
        $type = $_POST['question_type'];
        $options = isset($_POST['options']) ? $_POST['options'] : [];
        $correct = isset($_POST['correct_option']) ? $_POST['correct_option'] : null;

        simpanPertanyaan($koneksi, $quiz_id, $question_text, $type, $options, $correct, $weight, $media_filename);
        
        header("Location: index.php?page=edit_quiz&id=$quiz_id");
    }
}

function deleteQuestion($koneksi) {
    $question_id = intval($_GET['question_id']);
    $quiz_id = intval($_GET['quiz_id']);

    $query_answers = "DELETE FROM quiz_answers WHERE question_id = $question_id";
    mysqli_query($koneksi, $query_answers);

    $query_options = "DELETE FROM options WHERE question_id = $question_id";
    mysqli_query($koneksi, $query_options);

    $q_cek = mysqli_query($koneksi, "SELECT media_file FROM questions WHERE id = $question_id");
    $data = mysqli_fetch_assoc($q_cek);

    if (!empty($data['media_file'])) {
        $media = $data['media_file'];

        if (strpos($media, 'http') === false) {
            $file_path = "uploads/" . $media;

            if (file_exists($file_path)) {
                unlink($file_path); 
            }
        }
    }

    $query_delete = "DELETE FROM questions WHERE id = $question_id";
    mysqli_query($koneksi, $query_delete);

    header("Location: index.php?page=edit_quiz&id=$quiz_id");
}
?>