<?php
require_once 'models/CourseModel.php';
require_once 'models/QuizModel.php';
require_once 'models/ActivityModel.php';

function dashboard($koneksi) {
    $student_id = $_SESSION['user_id'];

    if (isset($_GET['unenroll_id'])) {
        $course_id = intval($_GET['unenroll_id']);
        keluarDariKelas($koneksi, $student_id, $course_id);
        header("Location: index.php?page=dashboard&pesan=keluar_sukses");
        exit;
    }

    $stats = ambilStatistikSiswa($koneksi, $student_id);

    $myClasses = ambilKelasSaya($koneksi, $student_id);
    
    require 'views/student/dashboard.php';
}

function catalog($koneksi) {
    $student_id = $_SESSION['user_id'];

    $allCourses = ambilSemuaCoursePublik($koneksi);
    $myClasses = ambilKelasSaya($koneksi, $student_id);
    
    $sudah_diambil = [];
    while($kelas = mysqli_fetch_assoc($myClasses)) {
        $sudah_diambil[] = $kelas['id']; 
    }
    
    if (isset($_GET['join_id'])) {
        if (!in_array($_GET['join_id'], $sudah_diambil)) {
            gabungKelas($koneksi, $student_id, $_GET['join_id']);
            header("Location: index.php?page=dashboard");
        }
    }
    
    require 'views/student/catalog.php';
}

function learningRoom($koneksi) {
    $material_id = intval($_GET['material_id']);
    $materi = ambilDetailMateri($koneksi, $material_id);

    $courseInfo = ambilCourseInfoDariMaterial($koneksi, $material_id);
    $course_id = $courseInfo['id']; 

    if (isset($_POST['mark_complete'])) {
        tandaiMateriSelesai($koneksi, $_SESSION['user_id'], $material_id);
        header("Location: index.php?page=learning&material_id=$material_id");
        exit;
    }

    if (isset($_POST['mark_incomplete'])) {

        hapusTandaSelesai($koneksi, $_SESSION['user_id'], $material_id);

        header("Location: index.php?page=learning&material_id=$material_id");
        exit;
    }

    require 'views/student/classroom.php';
}


function takeQuiz($koneksi) {
    $quiz_id = $_GET['quiz_id'];
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $jawabanSiswa = $_POST['jawaban']; 
        $user_id = $_SESSION['user_id'];

        resetRiwayatKuis($koneksi, $user_id, $quiz_id);
        
        simpanJawabanSiswa($koneksi, $user_id, $quiz_id, $jawabanSiswa);

        header("Location: index.php?page=quiz_result&quiz_id=$quiz_id");
    } else {
        $soalList = ambilSoalQuiz($koneksi, $quiz_id);
        require 'views/student/quiz_take.php';
    }
}

function courseDetailStudent($koneksi) {
    require_once 'models/CourseModel.php';
    require_once 'models/ActivityModel.php'; 
    
    $course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $course = ambilCourseById($koneksi, $course_id);

    if (!$course) {
        echo "<script>
                alert('Maaf, Kursus ini tidak ditemukan atau sudah dihapus!');
                window.location='index.php?page=dashboard';
              </script>";
        exit;
    }

    $chapters = ambilFullCourseStructure($koneksi, $course_id);
    
    require 'views/student/course_detail.php';
}

function studentTranscript($koneksi) {
    require_once 'models/QuizModel.php';
    require_once 'models/CourseModel.php'; 
    
    $course_id = $_GET['course_id'];
    $student_id = $_SESSION['user_id'];
    
    $course = ambilCourseById($koneksi, $course_id);
    $nilaiList = ambilRekapNilaiSiswa($koneksi, $student_id, $course_id);
    
    require 'views/student/transcript.php';
}



?>