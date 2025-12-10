<?php

function ambilKelasSaya($koneksi, $student_id) {
    $query = "SELECT courses.*, enrollments.enrolled_at 
              FROM enrollments 
              JOIN courses ON enrollments.course_id = courses.id 
              WHERE enrollments.user_id = $student_id";
    return mysqli_query($koneksi, $query);
}

function gabungKelas($koneksi, $user_id, $course_id) {
    $query = "INSERT INTO enrollments (user_id, course_id) VALUES ($user_id, $course_id)";
    return mysqli_query($koneksi, $query);
}

function tandaiMateriSelesai($koneksi, $user_id, $material_id) {
    $cek = mysqli_query($koneksi, "SELECT * FROM material_completions WHERE user_id=$user_id AND material_id=$material_id");
    if (mysqli_num_rows($cek) == 0) {
        $query = "INSERT INTO material_completions (user_id, material_id) VALUES ($user_id, $material_id)";
        mysqli_query($koneksi, $query);
    }
}

function hitungPersentaseProgress($koneksi, $user_id, $course_id) {
    if (empty($course_id) || $course_id == 0) {
        return 0;
    }

    $query_total = "SELECT COUNT(*) as total 
                    FROM materials m 
                    JOIN chapters c ON m.chapter_id = c.id 
                    WHERE c.course_id = $course_id";
    $res_total = mysqli_query($koneksi, $query_total);

    if (!$res_total) return 0;
    
    $total_materi = mysqli_fetch_assoc($res_total)['total'];

    if ($total_materi == 0) return 0;

    $query_selesai = "SELECT COUNT(*) as selesai 
                      FROM material_completions mc
                      JOIN materials m ON mc.material_id = m.id
                      JOIN chapters c ON m.chapter_id = c.id
                      WHERE c.course_id = $course_id 
                      AND mc.user_id = $user_id";
    $res_selesai = mysqli_query($koneksi, $query_selesai);
    $sudah_selesai = mysqli_fetch_assoc($res_selesai)['selesai'];

    return round(($sudah_selesai / $total_materi) * 100);
}

function cekMateriSelesai($koneksi, $user_id, $material_id) {
    $q = "SELECT * FROM material_completions WHERE user_id = $user_id AND material_id = $material_id";
    $r = mysqli_query($koneksi, $q);
    return (mysqli_num_rows($r) > 0);
}
function keluarDariKelas($koneksi, $user_id, $course_id) {
    $query_materi = "DELETE mc FROM material_completions mc
                     INNER JOIN materials m ON mc.material_id = m.id
                     INNER JOIN chapters c ON m.chapter_id = c.id
                     WHERE c.course_id = $course_id 
                     AND mc.user_id = $user_id";
    mysqli_query($koneksi, $query_materi);

    $query_quiz = "DELETE qa FROM quiz_attempts qa
                   INNER JOIN quizzes q ON qa.quiz_id = q.id
                   INNER JOIN chapters c ON q.chapter_id = c.id
                   WHERE c.course_id = $course_id 
                   AND qa.user_id = $user_id";
    mysqli_query($koneksi, $query_quiz);

    $query_enroll = "DELETE FROM enrollments 
                     WHERE user_id = $user_id 
                     AND course_id = $course_id";
                     
    return mysqli_query($koneksi, $query_enroll);
}

function ambilStatistikSiswa($koneksi, $user_id) {
    $q1 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM enrollments WHERE user_id = $user_id");
    $total_kelas = mysqli_fetch_assoc($q1)['total'];

    $q2 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM material_completions WHERE user_id = $user_id");
    $total_materi = mysqli_fetch_assoc($q2)['total'];

    return [
        'total_joined' => $total_kelas,
        'materi_selesai' => $total_materi
    ];
}

function hapusTandaSelesai($koneksi, $user_id, $material_id) {
    $query = "DELETE FROM material_completions 
              WHERE user_id = $user_id 
              AND material_id = $material_id";
    return mysqli_query($koneksi, $query);
}

function ambilSiswaByCourse($koneksi, $course_id) {
    $query = "SELECT u.id, u.full_name, u.email, e.enrolled_at
              FROM enrollments e
              JOIN users u ON e.user_id = u.id
              WHERE e.course_id = $course_id
              ORDER BY u.full_name ASC";
    return mysqli_query($koneksi, $query);
}

function hitungProgressSiswa($koneksi, $student_id, $course_id) {
    $total_materi = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT COUNT(*) as total FROM materials m
         JOIN chapters c ON m.chapter_id = c.id
         WHERE c.course_id = $course_id"))['total'];

    $selesai = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT COUNT(*) as selesai FROM material_completions mc
         JOIN materials m ON mc.material_id = m.id
         JOIN chapters c ON m.chapter_id = c.id
         WHERE c.course_id = $course_id AND mc.user_id = $student_id"))['selesai'];

    return $total_materi ? round($selesai / $total_materi * 100) : 0;
}

?>