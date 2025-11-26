<?php
// controllers/TeacherController.php
require_once 'models/CourseModel.php';
require_once 'models/QuizModel.php';

// --- DASHBOARD GURU ---
function dashboardTeacher($koneksi) {
    if ($_SESSION['role'] != 'teacher') { header("Location: index.php?page=login"); exit; }
    
    $teacher_id = $_SESSION['user_id'];
    $myCourses = ambilCourseMilikGuru($koneksi, $teacher_id);
    
    require 'views/teacher/dashboard.php';
}

// --- MANAJEMEN COURSE ---
function manageCourse($koneksi) {
    if ($_SESSION['role'] != 'teacher') { header("Location: index.php?page=login"); exit; }

    // A. LOGIC HAPUS
    if (isset($_GET['hapus_id'])) {
        $id = intval($_GET['hapus_id']);
        // Cek kepemilikan dulu biar guru A ga hapus kelas guru B
        $cek = ambilCourseByIdUser($koneksi, $id, $_SESSION['user_id']);
        if ($cek) {
            hapusCourse($koneksi, $id);
            echo "<script>alert('Kelas berhasil dihapus!'); window.location='index.php?page=dashboard';</script>";
        } else {
            echo "<script>alert('Gagal! Kelas tidak ditemukan atau bukan milik Anda.'); window.location='index.php?page=dashboard';</script>";
        }
        exit;
    }

    // B. LOGIC SIMPAN (TAMBAH / UPDATE)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $level = $_POST['level'];
        
        if (!empty($_POST['id'])) {
            // Mode UPDATE
            updateCourse($koneksi, $_POST['id'], $title, $desc, $level);
        } else {
            // Mode TAMBAH BARU
            buatKelasBaru($koneksi, $_SESSION['user_id'], $title, $desc, $level);
        }
        header("Location: index.php?page=dashboard");
        exit;
    }

    // C. LOGIC TAMPILAN (FORM EDIT)
    $data_edit = null;
    if (isset($_GET['edit_id'])) {
        $data_edit = ambilCourseByIdUser($koneksi, $_GET['edit_id'], $_SESSION['user_id']);
    }

    require 'views/teacher/course_form.php';
}
// --- MANAJEMEN MATERI (Video/Text) ---
function manageMaterials($koneksi) {
    // Ambil ID Chapter dari URL
    $chapter_id = $_GET['chapter_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Simpan materi (Link Video atau Teks Bacaan)
        simpanMateri($koneksi, $_POST);
        header("Location: index.php?page=course_detail&id=" . $_POST['course_id']);
    } else {
        require 'views/teacher/material_form.php';
    }
}

// --- FITUR GRADING / KOREKSI ESAI ---
function gradeEssay($koneksi) {
    if ($_SESSION['role'] != 'teacher') { die("Akses Ditolak"); }
    
    require_once 'models/QuizModel.php'; // Pastikan Model terpanggil

    // A. JIKA TOMBOL SIMPAN DITEKAN (POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $answer_id = intval($_POST['answer_id']);
        $attempt_id = intval($_POST['attempt_id']);
        $nilai = intval($_POST['score']); // Pastikan name di form adalah 'score'
        
        // 1. Simpan Nilai Esai ke database
        updateNilaiManual($koneksi, $answer_id, $nilai);
        
        // 2. [PENTING] Hitung Ulang Total Skor Siswa (PG + Esai)
        hitungUlangTotalSkor($koneksi, $attempt_id);
        
        // 3. Refresh / Redirect
        // Kita arahkan ke 'grading_list' agar kembali ke daftar antrian koreksi
        echo "<script>
            alert('Nilai berhasil disimpan! ✅'); 
            window.location='index.php?page=grading_list'; 
        </script>";
        exit;
    } 
    
    // B. JIKA MEMBUKA HALAMAN (GET) - Mode Lihat Daftar
    else {
        // Ambil daftar jawaban esai yang belum dinilai milik guru ini
        $listJawaban = ambilJawabanEssayPending($koneksi, $_SESSION['user_id']);
        
        require 'views/teacher/grading_view.php';
    }
}

function courseDetailTeacher($koneksi) {
    if ($_SESSION['role'] != 'teacher') { die("Akses Ditolak"); }
    
    $course_id = $_GET['id'];

    // 1. Logic Hapus Chapter
    if (isset($_GET['hapus_chapter'])) {
        $chap_id = intval($_GET['hapus_chapter']);
        hapusChapter($koneksi, $chap_id);
        header("Location: index.php?page=course_detail&id=$course_id");
        exit;
    }

    // 2. [PERBAIKAN] LOGIC HAPUS KUIS (ANTI ERROR)
    // Kita harus menghapus data secara berurutan: Jawaban -> Opsi -> Soal -> Kuis
    if (isset($_GET['hapus_quiz'])) {
        $quiz_id_del = intval($_GET['hapus_quiz']);

        // A. Ambil semua soal di kuis ini
        $q_soal = mysqli_query($koneksi, "SELECT id, media_file FROM questions WHERE quiz_id = $quiz_id_del");

        while ($soal = mysqli_fetch_assoc($q_soal)) {
            $q_id = $soal['id'];

            // B. Hapus Jawaban Siswa pada soal ini (INI PENTING!)
            mysqli_query($koneksi, "DELETE FROM quiz_answers WHERE question_id = $q_id");

            // C. Hapus Opsi Pilihan Ganda
            mysqli_query($koneksi, "DELETE FROM options WHERE question_id = $q_id");

            // D. Hapus File Media (Gambar/Audio) jika ada
            if (!empty($soal['media_file'])) {
                $path = "uploads/" . $soal['media_file'];
                if (file_exists($path)) { unlink($path); }
            }
        }

        // E. Hapus Semua Soal
        mysqli_query($koneksi, "DELETE FROM questions WHERE quiz_id = $quiz_id_del");

        // F. Hapus Riwayat Mengerjakan (Attempts)
        mysqli_query($koneksi, "DELETE FROM quiz_attempts WHERE quiz_id = $quiz_id_del");

        // G. TERAKHIR: Baru Hapus Kuisnya
        mysqli_query($koneksi, "DELETE FROM quizzes WHERE id = $quiz_id_del");

        header("Location: index.php?page=course_detail&id=$course_id");
        exit;
    }
    
    // 3. Logic Tambah Chapter Baru (Quick Add)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_chapter'])) {
        $judul_bab = $_POST['chapter_title'];
        $urutan = $_POST['sequence_order'];
        
        $q = "INSERT INTO chapters (course_id, title, sequence_order) VALUES ($course_id, '$judul_bab', $urutan)";
        mysqli_query($koneksi, $q);
        
        header("Location: index.php?page=course_detail&id=$course_id");
        exit;
    }

    // Ambil Data untuk Tampilan
    $course = ambilCourseById($koneksi, $course_id);
    $chapters = ambilFullCourseStructure($koneksi, $course_id); 
    
    require 'views/teacher/course_manage.php';
}