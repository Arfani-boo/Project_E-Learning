<?php
function buatKuisBaru($koneksi, $chapter_id, $title) {
    $title = mysqli_real_escape_string($koneksi, $title);
    $query = "INSERT INTO quizzes (chapter_id, title) VALUES ($chapter_id, '$title')";
    mysqli_query($koneksi, $query);
    return mysqli_insert_id($koneksi);
}

function ambilDetailQuiz($koneksi, $quiz_id) {
    
    $query = "SELECT q.*, c.course_id 
              FROM quizzes q
              JOIN chapters c ON q.chapter_id = c.id
              WHERE q.id = $quiz_id";
              
    $result = mysqli_query($koneksi, $query);

    if(mysqli_num_rows($result) == 0) {
        return null;
    }
    
    return mysqli_fetch_assoc($result);
}

function ambilDaftarSoal($koneksi, $quiz_id) {

    $soal = [];
    $query = "SELECT * FROM questions WHERE quiz_id = $quiz_id ORDER BY id ASC";
    $result = mysqli_query($koneksi, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['question_type'] == 'multiple_choice') {
            $q_id = $row['id'];
            $query_opsi = "SELECT * FROM options WHERE question_id = $q_id ORDER BY id ASC";
            $res_opsi = mysqli_query($koneksi, $query_opsi);
            
            $opsi = [];
            while($o = mysqli_fetch_assoc($res_opsi)) {
                $opsi[] = $o;
            }
            $row['options'] = $opsi;
        }
        $soal[] = $row;
    }
    return $soal;
}

function tambahPertanyaan($koneksi, $quiz_id, $text, $type, $weight) {
    $text = mysqli_real_escape_string($koneksi, $text);
    $query = "INSERT INTO questions (quiz_id, question_text, question_type, weight) 
              VALUES ($quiz_id, '$text', '$type', $weight)";
    mysqli_query($koneksi, $query);
    return mysqli_insert_id($koneksi);
}

function tambahOpsiJawaban($koneksi, $question_id, $text, $is_correct) {
    $text = mysqli_real_escape_string($koneksi, $text);
    $query = "INSERT INTO options (question_id, option_text, is_correct) 
              VALUES ($question_id, '$text', $is_correct)";
    mysqli_query($koneksi, $query);
}

function ambilSoalQuiz($koneksi, $quiz_id) {
    $soal = [];
    $query = "SELECT * FROM questions WHERE quiz_id = $quiz_id";
    $result = mysqli_query($koneksi, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['question_type'] == 'multiple_choice') {
            $q_id = $row['id'];
            $query_opsi = "SELECT id, option_text FROM options WHERE question_id = $q_id";
            $res_opsi = mysqli_query($koneksi, $query_opsi);
            $opsi = [];
            while($o = mysqli_fetch_assoc($res_opsi)) {
                $opsi[] = $o;
            }
            $row['options'] = $opsi;
        }
        $soal[] = $row;
    }
    return $soal;
}

function simpanJawabanSiswa($koneksi, $user_id, $quiz_id, $jawaban_list) {

    $query_attempt = "INSERT INTO quiz_attempts (user_id, quiz_id, score, attempted_at) VALUES ($user_id, $quiz_id, 0, NOW())";
    mysqli_query($koneksi, $query_attempt);
    $attempt_id = mysqli_insert_id($koneksi);
    
    $total_score_pg = 0; 

    foreach ($jawaban_list as $question_id => $jawaban_isi) {
        
        $points_dapat = 0;
        $answer_text_for_db = ""; 
        $selected_opt_id = "NULL"; 

        $q_sql = "SELECT * FROM questions WHERE id = $question_id";
        $q_res = mysqli_fetch_assoc(mysqli_query($koneksi, $q_sql));
        
        $tipe = $q_res['question_type'];
        $bobot_soal = $q_res['weight'];

        if ($tipe == 'multiple_choice') {
            $selected_opt_id = intval($jawaban_isi);

            $cek_sql = "SELECT * FROM options WHERE id = $selected_opt_id";
            $cek_res = mysqli_fetch_assoc(mysqli_query($koneksi, $cek_sql));
            
            if ($cek_res) {
                $answer_text_for_db = mysqli_real_escape_string($koneksi, $cek_res['option_text']);

                if ($cek_res['is_correct'] == 1) {
                    $points_dapat = $bobot_soal;
                }
            }
        } 

        else {
            $answer_text_for_db = mysqli_real_escape_string($koneksi, $jawaban_isi);
            $points_dapat = 0;
        }

        $total_score_pg += $points_dapat;

        $insert_ans = "INSERT INTO quiz_answers (quiz_attempt_id, question_id, selected_option_id, answer_text, points_awarded)
                       VALUES ($attempt_id, $question_id, $selected_opt_id, '$answer_text_for_db', $points_dapat)";
                       
        mysqli_query($koneksi, $insert_ans);
    }

    $update_score = "UPDATE quiz_attempts SET score = $total_score_pg WHERE id = $attempt_id";
    mysqli_query($koneksi, $update_score);
}

function ambilJawabanEssayPending($koneksi, $teacher_id) {

    $query = "SELECT qa.id as answer_id, qa.answer_text, qa.points_awarded, 
                     q.question_text, 
                     q.weight as max_points,
                     u.full_name as student_name,
                     atm.id as attempt_id,
                     kz.title as quiz_title
              FROM quiz_answers qa
              JOIN questions q ON qa.question_id = q.id
              JOIN quiz_attempts atm ON qa.quiz_attempt_id = atm.id
              JOIN users u ON atm.user_id = u.id
              JOIN quizzes kz ON q.quiz_id = kz.id
              JOIN chapters ch ON kz.chapter_id = ch.id
              JOIN courses c ON ch.course_id = c.id
              WHERE q.question_type = 'essay' 
              AND qa.points_awarded = 0
              AND c.teacher_id = $teacher_id
              ORDER BY atm.id ASC";
              
    return mysqli_query($koneksi, $query);
}

function updateNilaiManual($koneksi, $answer_id, $points) {
    $query = "UPDATE quiz_answers SET points_awarded = $points WHERE id = $answer_id";
    return mysqli_query($koneksi, $query);
}

function hitungUlangTotalSkor($koneksi, $attempt_id) {

    $query = "SELECT SUM(points_awarded) as total_skor 
              FROM quiz_answers 
              WHERE quiz_attempt_id = $attempt_id";
              
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    $total_baru = intval($data['total_skor']);

    $q_update = "UPDATE quiz_attempts SET score = $total_baru WHERE id = $attempt_id";
    mysqli_query($koneksi, $q_update);
    
    return $total_baru;
}

function hapusSoal($koneksi, $question_id) {
    $query = "DELETE FROM questions WHERE id = $question_id";
    return mysqli_query($koneksi, $query);
}

function ambilRekapNilaiSiswa($koneksi, $user_id, $course_id) {
    $query = "SELECT qa.*, q.title as quiz_title 
              FROM quiz_attempts qa
              JOIN quizzes q ON qa.quiz_id = q.id
              JOIN chapters c ON q.chapter_id = c.id
              WHERE qa.user_id = $user_id 
              AND c.course_id = $course_id
              ORDER BY qa.attempted_at DESC";
              
    return mysqli_query($koneksi, $query);
}


function cekStatusKuis($koneksi, $user_id, $quiz_id) {
    $query = "SELECT * FROM quiz_attempts WHERE user_id = $user_id AND quiz_id = $quiz_id";
    $result = mysqli_query($koneksi, $query);
    return (mysqli_num_rows($result) > 0);
}

function resetRiwayatKuis($koneksi, $user_id, $quiz_id) {
    $query = "DELETE FROM quiz_attempts WHERE user_id = $user_id AND quiz_id = $quiz_id";
    return mysqli_query($koneksi, $query);
}
function ambilCourseIdDariQuiz($koneksi, $quiz_id) {
    $query = "SELECT c.id FROM courses c
              JOIN chapters ch ON c.id = ch.course_id
              JOIN quizzes q ON ch.id = q.chapter_id
              WHERE q.id = $quiz_id";
              
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    return isset($data['id']) ? $data['id'] : 0;
}

function cekAdaSoalEssay($koneksi, $quiz_id) {
    $query = "SELECT COUNT(*) as total FROM questions WHERE quiz_id = $quiz_id AND question_type = 'essay'";
    $res = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($res);
    return ($data['total'] > 0);
}

function cekStatusKoreksi($koneksi, $attempt_id) {
    $query = "SELECT COUNT(*) as pending 
              FROM quiz_answers qa
              JOIN questions q ON qa.question_id = q.id
              WHERE qa.quiz_attempt_id = $attempt_id 
              AND q.question_type = 'essay' 
              AND qa.points_awarded = 0";
              
    $res = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($res);

    return ($data['pending'] > 0) ? 'PENDING' : 'SELESAI';
}


function hitungTotalSkorSaatIni($koneksi, $quiz_id) {
    $query = "SELECT SUM(weight) as total FROM questions WHERE quiz_id = $quiz_id";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    
    return intval($data['total']);
}

function simpanPertanyaan($koneksi, $quiz_id, $question_text, $type, $options, $correct, $weight, $media_file = null) {
    $q_text = mysqli_real_escape_string($koneksi, $question_text);
    $q_media = $media_file ? "'$media_file'" : "NULL";
    
    $query = "INSERT INTO questions (quiz_id, question_text, question_type, weight, media_file) 
              VALUES ($quiz_id, '$q_text', '$type', $weight, $q_media)";
    
    if (mysqli_query($koneksi, $query)) {
        $question_id = mysqli_insert_id($koneksi);
        
        if ($type == 'multiple_choice') {
            foreach ($options as $key => $opt_text) {
                $is_correct = ($key == $correct) ? 1 : 0;
                $opt_clean = mysqli_real_escape_string($koneksi, $opt_text);
                mysqli_query($koneksi, "INSERT INTO options (question_id, option_text, is_correct) VALUES ($question_id, '$opt_clean', $is_correct)");
            }
        }
        return true;
    }
    return false;
}



?>