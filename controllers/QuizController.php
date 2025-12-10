<?php
// controllers/QuizController.php
require_once 'models/QuizModel.php';

// --- 1. MEMBUAT JUDUL KUIS BARU ---
function createQuiz($koneksi) {
    // Cek apakah user adalah Guru
    if ($_SESSION['role'] != 'teacher') { die("Akses Ditolak"); }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $chapter_id = $_POST['chapter_id'];
        $title = $_POST['title'];
        
        // Panggil Model untuk buat header kuis
        $quiz_id = buatKuisBaru($koneksi, $chapter_id, $title);
        
        // Setelah kuis jadi, langsung arahkan ke halaman tambah soal
        header("Location: index.php?page=edit_quiz&id=" . $quiz_id);
    }
}

// --- 2. HALAMAN EDITOR SOAL (Form Builder) ---
function editQuiz($koneksi) {
    if ($_SESSION['role'] != 'teacher') { die("Akses Ditolak"); }
    
    $quiz_id = $_GET['id'];
    
    // Ambil data kuis dan daftar soal yang sudah ada
    $quiz = ambilDetailQuiz($koneksi, $quiz_id);
    $questions = ambilDaftarSoal($koneksi, $quiz_id);
    
    require 'views/teacher/quiz_builder.php';
}

// --- 3. MENYIMPAN PERTANYAAN BARU ---
function saveQuestion($koneksi) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $quiz_id = $_POST['quiz_id'];
        
        // [FIX 1] Ambil dari input name="weight" (Bukan points)
        $weight = intval($_POST['weight']); 
        
        require_once 'models/QuizModel.php';
        
        // [FIX 2] Hitung Total Skor Saat Ini (Pastikan Model pakai SUM weight)
        $skor_sudah_ada = hitungTotalSkorSaatIni($koneksi, $quiz_id);
        
        // Hitung Total Prediksi
        $total_nanti = $skor_sudah_ada + $weight;
        
        // [FIX 3] VALIDASI KETAT
        if ($total_nanti > 100) {
            $sisa = 100 - $skor_sudah_ada;
            // Tampilkan Alert JavaScript lalu kembali
            echo "<script>
                alert('GAGAL MENYIMPAN! ❌\\n\\nTotal bobot nilai tidak boleh melebihi 100.\\nTotal saat ini: $skor_sudah_ada\\nYang akan diinput: $weight\\nSisa kuota: $sisa');
                window.location='index.php?page=edit_quiz&id=$quiz_id';
            </script>";
            exit; // Stop script agar tidak lanjut menyimpan
        }

        // --- LOGIC SIMPAN LINK/NAMA MEDIA ---
        $media_filename = null;
        // Ambil data dari $_POST karena formnya sekarang Text Input
        if (!empty($_POST['media_file'])) {
            // Trim untuk menghapus spasi tidak sengaja di awal/akhir
            $media_filename = trim($_POST['media_file']);
        }

        // --- SIMPAN DATA ---
        $question_text = $_POST['question_text'];
        $type = $_POST['question_type'];
        $options = isset($_POST['options']) ? $_POST['options'] : [];
        $correct = isset($_POST['correct_option']) ? $_POST['correct_option'] : null;

        // [FIX 4] Kirim variabel $weight ke Model
        simpanPertanyaan($koneksi, $quiz_id, $question_text, $type, $options, $correct, $weight, $media_filename);
        
        header("Location: index.php?page=edit_quiz&id=$quiz_id");
    }
}

// --- 4. MENGHAPUS SOAL ---
function deleteQuestion($koneksi) {
    // Ambil ID dari URL
    $question_id = intval($_GET['question_id']);
    $quiz_id = intval($_GET['quiz_id']);
    
    // TAHAP 1: Hapus Jawaban Siswa yang terkait soal ini (PENTING!)
    // Ini yang menyebabkan error Fatal Error tadi
    $query_answers = "DELETE FROM quiz_answers WHERE question_id = $question_id";
    mysqli_query($koneksi, $query_answers);
    
    // TAHAP 2: Hapus Opsi Jawaban (A, B, C, D)
    $query_options = "DELETE FROM options WHERE question_id = $question_id";
    mysqli_query($koneksi, $query_options);

    // TAHAP 3: Hapus File Media (Hanya jika itu file LOKAL, bukan Link YouTube)
    $q_cek = mysqli_query($koneksi, "SELECT media_file FROM questions WHERE id = $question_id");
    $data = mysqli_fetch_assoc($q_cek);

    if (!empty($data['media_file'])) {
        $media = $data['media_file'];
        
        // Cek: Jangan hapus jika itu Link URL (http/https)
        if (strpos($media, 'http') === false) {
            $file_path = "uploads/" . $media;
            // Hapus file fisik hanya jika file tersebut ada di folder uploads
            if (file_exists($file_path)) {
                unlink($file_path); 
            }
        }
    }

    // TAHAP 4: Baru Hapus Soalnya (Aman)
    $query_delete = "DELETE FROM questions WHERE id = $question_id";
    mysqli_query($koneksi, $query_delete);
    
    // Kembali ke Quiz Builder
    header("Location: index.php?page=edit_quiz&id=$quiz_id");
}
?>