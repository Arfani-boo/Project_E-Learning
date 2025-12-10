<?php
require_once 'models/CourseModel.php';
require_once 'models/QuizModel.php';
require_once 'models/ActivityModel.php';
require_once 'models/UserModel.php';

function dashboardTeacher($koneksi) {
    if ($_SESSION['role'] != 'teacher') { header("Location: index.php?page=login"); exit; }
    
    $teacher_id = $_SESSION['user_id'];
    $myCourses = ambilCourseMilikGuru($koneksi, $teacher_id);
    
    require 'views/teacher/dashboard.php';
}

function manageCourse($koneksi) {
    if ($_SESSION['role'] != 'teacher') { header("Location: index.php?page=login"); exit; }

    if (isset($_GET['hapus_id'])) {
        $id = intval($_GET['hapus_id']);
        $cek = ambilCourseByIdUser($koneksi, $id, $_SESSION['user_id']);
        if ($cek) {
            hapusCourse($koneksi, $id);
            echo "<script>alert('Kelas berhasil dihapus!'); window.location='index.php?page=dashboard';</script>";
        } else {
            echo "<script>alert('Gagal! Kelas tidak ditemukan atau bukan milik Anda.'); window.location='index.php?page=dashboard';</script>";
        }
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $level = $_POST['level'];
        
        if (!empty($_POST['id'])) {
            updateCourse($koneksi, $_POST['id'], $title, $desc, $level);
        } else {
            buatKelasBaru($koneksi, $_SESSION['user_id'], $title, $desc, $level);
        }
        header("Location: index.php?page=dashboard");
        exit;
    }

    $data_edit = null;
    if (isset($_GET['edit_id'])) {
        $data_edit = ambilCourseByIdUser($koneksi, $_GET['edit_id'], $_SESSION['user_id']);
    }

    require 'views/teacher/course_form.php';
}

function manageMaterials($koneksi) {
    $chapter_id = $_GET['chapter_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        simpanMateri($koneksi, $_POST);
        header("Location: index.php?page=course_detail&id=" . $_POST['course_id']);
    } else {
        require 'views/teacher/material_form.php';
    }
}

function gradeEssay($koneksi) {
    if ($_SESSION['role'] != 'teacher') { die("Akses Ditolak"); }
    
    require_once 'models/QuizModel.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $answer_id = intval($_POST['answer_id']);
        $attempt_id = intval($_POST['attempt_id']);
        $nilai = intval($_POST['score']);

        updateNilaiManual($koneksi, $answer_id, $nilai);

        hitungUlangTotalSkor($koneksi, $attempt_id);

        echo "<script>
            alert('Nilai berhasil disimpan! ✅'); 
            window.location='index.php?page=grading_list'; 
        </script>";
        exit;
    } 

    else {
        $listJawaban = ambilJawabanEssayPending($koneksi, $_SESSION['user_id']);
        
        require 'views/teacher/grading_view.php';
    }
}

function courseDetailTeacher($koneksi) {
    if ($_SESSION['role'] != 'teacher') { die("Akses Ditolak"); }
    
    $course_id = $_GET['id'];

    if (isset($_GET['hapus_chapter'])) {
        $chap_id = intval($_GET['hapus_chapter']);
        hapusChapter($koneksi, $chap_id);
        header("Location: index.php?page=course_detail&id=$course_id");
        exit;
    }

    if (isset($_GET['hapus_quiz'])) {
        $quiz_id_del = intval($_GET['hapus_quiz']);

        $q_soal = mysqli_query($koneksi, "SELECT id, media_file FROM questions WHERE quiz_id = $quiz_id_del");

        while ($soal = mysqli_fetch_assoc($q_soal)) {
            $q_id = $soal['id'];

            mysqli_query($koneksi, "DELETE FROM quiz_answers WHERE question_id = $q_id");

            mysqli_query($koneksi, "DELETE FROM options WHERE question_id = $q_id");

            if (!empty($soal['media_file'])) {
                $path = "uploads/" . $soal['media_file'];
                if (file_exists($path)) { unlink($path); }
            }
        }

        mysqli_query($koneksi, "DELETE FROM questions WHERE quiz_id = $quiz_id_del");

        mysqli_query($koneksi, "DELETE FROM quiz_attempts WHERE quiz_id = $quiz_id_del");

        mysqli_query($koneksi, "DELETE FROM quizzes WHERE id = $quiz_id_del");

        header("Location: index.php?page=course_detail&id=$course_id");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_chapter'])) {
        $judul_bab = $_POST['chapter_title'];
        $urutan = $_POST['sequence_order'];
        
        $q = "INSERT INTO chapters (course_id, title, sequence_order) VALUES ($course_id, '$judul_bab', $urutan)";
        mysqli_query($koneksi, $q);
        
        header("Location: index.php?page=course_detail&id=$course_id");
        exit;
    }

    $course = ambilCourseById($koneksi, $course_id);
    $chapters = ambilFullCourseStructure($koneksi, $course_id); 
    
    require 'views/teacher/course_manage.php';
}

function daftarSiswaPerCourse($koneksi) {
    $course_id = intval($_GET['course_id']);
    $teacher_id = $_SESSION['user_id'];

    $course = ambilCourseByIdUser($koneksi, $course_id, $teacher_id);
    if (!$course) {
        die("Akses ditolak.");
    }

    $siswa_list = ambilSiswaByCourse($koneksi, $course_id);
    require 'views/teacher/student_list.php';
}

function progressSiswa($koneksi) {
    $course_id = intval($_GET['course_id']);
    $student_id = intval($_GET['student_id']);
    $teacher_id = $_SESSION['user_id'];

    $course = ambilCourseByIdUser($koneksi, $course_id, $teacher_id);
    if (!$course) die("Akses ditolak.");

    $student = ambilUserById($koneksi, $student_id);

    $chapters = ambilFullCourseStructure($koneksi, $course_id);

    $nilai_list = ambilRekapNilaiSiswa($koneksi, $student_id, $course_id);

    require 'views/teacher/student_progress.php';
}

?>