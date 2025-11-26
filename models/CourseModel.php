<?php
// models/CourseModel.php

// --- COURSE ---

// [PERBAIKAN UTAMA] Fungsi ini sekarang pakai Query Canggih (Hitung Materi & Siswa)
// Namanya kita samakan dengan Controller: 'ambilCourseMilikGuru'
function ambilCourseMilikGuru($koneksi, $teacher_id) {
    $query = "SELECT c.*, 
              -- Hitung jumlah siswa di tabel enrollments
              (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) AS student_count,
              
              -- Hitung jumlah materi di tabel materials (lewat tabel chapters)
              (SELECT COUNT(*) FROM materials m 
               JOIN chapters ch ON m.chapter_id = ch.id 
               WHERE ch.course_id = c.id) AS total_materi
               
              FROM courses c 
              WHERE c.teacher_id = $teacher_id 
              ORDER BY c.id DESC";
              
    return mysqli_query($koneksi, $query);
}

function ambilSemuaCoursePublik($koneksi) {
    // Ambil info nama guru juga
    $query = "SELECT courses.*, users.full_name as teacher_name 
              FROM courses 
              JOIN users ON courses.teacher_id = users.id";
    return mysqli_query($koneksi, $query);
}

// [FUNGSI BUAT KELAS YANG SEMPAT HILANG]
function buatKelasBaru($koneksi, $teacher_id, $title, $desc, $level) {
    // Bersihkan input biar aman
    $title = mysqli_real_escape_string($koneksi, $title);
    $desc = mysqli_real_escape_string($koneksi, $desc);
    $level = mysqli_real_escape_string($koneksi, $level);
    
    // Query Insert
    $query = "INSERT INTO courses (teacher_id, title, description, level) 
              VALUES ($teacher_id, '$title', '$desc', '$level')";
              
    return mysqli_query($koneksi, $query);
}

// (Fungsi ini sepertinya duplikat dengan buatKelasBaru, kita pakai buatKelasBaru saja di Controller)
// Tapi dibiarkan juga tidak apa-apa asal tidak dipanggil.

function ambilCourseById($koneksi, $course_id) {
    $query = "SELECT * FROM courses WHERE id = $course_id";
    return mysqli_fetch_assoc(mysqli_query($koneksi, $query));
}

function ambilCourseByIdUser($koneksi, $course_id, $teacher_id) {
    // Ambil data hanya jika milik guru tersebut (Validasi keamanan)
    $query = "SELECT * FROM courses WHERE id = $course_id AND teacher_id = $teacher_id";
    return mysqli_fetch_assoc(mysqli_query($koneksi, $query));
}

function updateCourse($koneksi, $id, $title, $desc, $level) {
    $title = mysqli_real_escape_string($koneksi, $title);
    $desc = mysqli_real_escape_string($koneksi, $desc);
    $query = "UPDATE courses SET title='$title', description='$desc', level='$level' WHERE id=$id";
    return mysqli_query($koneksi, $query);
}

function hapusCourse($koneksi, $id) {
    // Karena di database sudah ON DELETE CASCADE, 
    // menghapus course otomatis menghapus chapters, materials, dan quiz di dalamnya.
    $query = "DELETE FROM courses WHERE id=$id";
    return mysqli_query($koneksi, $query);
}

// --- CHAPTER ---

function buatChapterBaru($koneksi, $course_id, $title, $urutan) {
    $title = mysqli_real_escape_string($koneksi, $title);
    $query = "INSERT INTO chapters (course_id, title, sequence_order) 
              VALUES ($course_id, '$title', $urutan)";
    return mysqli_query($koneksi, $query);
}

function hapusChapter($koneksi, $chapter_id) {
    return mysqli_query($koneksi, "DELETE FROM chapters WHERE id=$chapter_id");
}

function ambilFullCourseStructure($koneksi, $course_id) {
    $query = "SELECT * FROM chapters WHERE course_id = $course_id ORDER BY sequence_order ASC";
    $result = mysqli_query($koneksi, $query);
    
    $chapters = [];
    while($row = mysqli_fetch_assoc($result)) {
        $ch_id = $row['id'];
        $mat_query = "SELECT * FROM materials WHERE chapter_id = $ch_id ORDER BY sequence_order ASC";
        $mat_res = mysqli_query($koneksi, $mat_query);
        
        $materials = [];
        while($m = mysqli_fetch_assoc($mat_res)) {
            $materials[] = $m;
        }
        
        $row['materials'] = $materials; 
        $chapters[] = $row;
    }
    return $chapters;
}

// --- MATERIAL ---

function simpanMateri($koneksi, $data) {
    $chapter_id = intval($data['chapter_id']);
    $title = mysqli_real_escape_string($koneksi, $data['title']);
    $type = $data['type'];
    $urutan = intval($data['sequence_order']);
    
    $content_url = isset($data['content_url']) ? mysqli_real_escape_string($koneksi, $data['content_url']) : NULL;
    $text_content = isset($data['text_content']) ? mysqli_real_escape_string($koneksi, $data['text_content']) : NULL;

    $query = "INSERT INTO materials (chapter_id, title, type, content_url, text_content, sequence_order) 
              VALUES ($chapter_id, '$title', '$type', '$content_url', '$text_content', $urutan)";
    return mysqli_query($koneksi, $query);
}

function ambilDetailMateri($koneksi, $material_id) {
    $query = "SELECT * FROM materials WHERE id = $material_id";
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}
function ambilCourseInfoDariMaterial($koneksi, $material_id) {
    $query = "SELECT c.* FROM courses c
              JOIN chapters ch ON c.id = ch.course_id
              JOIN materials m ON ch.id = m.chapter_id
              WHERE m.id = $material_id";
              
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}




?>