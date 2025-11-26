<?php
// models/UserModel.php

function cekLoginUser($koneksi, $email, $password) {
    $email = mysqli_real_escape_string($koneksi, $email);
    
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    // Verifikasi Password Hash
    if ($user && password_verify($password, $user['password_hash'])) {
        return $user;
    }
    return false;
}

function daftarUserBaru($koneksi, $data) {
    $nama = mysqli_real_escape_string($koneksi, $data['full_name']);
    $email = mysqli_real_escape_string($koneksi, $data['email']);
    $pass = password_hash($data['password'], PASSWORD_DEFAULT);
    $school_id = !empty($data['school_id']) ? intval($data['school_id']) : "NULL";
    
    // Role default 'student' jika tidak diisi
    $role = isset($data['role']) ? $data['role'] : 'student';

    $query = "INSERT INTO users (full_name, email, password_hash, role, school_id) 
              VALUES ('$nama', '$email', '$pass', '$role', $school_id)";
    
    return mysqli_query($koneksi, $query);
}

function ambilSemuaGuru($koneksi) {
    // Join dengan tabel schools biar tahu guru dari sekolah mana
    $query = "SELECT users.*, schools.name as school_name 
              FROM users 
              LEFT JOIN schools ON users.school_id = schools.id 
              WHERE role = 'teacher'";
    return mysqli_query($koneksi, $query);
}

function tambahGuruOlehAdmin($koneksi, $data) {
    // Fungsi ini sama dengan daftarUserBaru, tapi khusus admin (bisa panggil fungsi diatas)
    return daftarUserBaru($koneksi, $data);
}

function hitungJumlahGuru($koneksi) {
    $query = "SELECT COUNT(*) as total FROM users WHERE role = 'teacher'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

function hitungJumlahSiswa($koneksi) {
    $query = "SELECT COUNT(*) as total FROM users WHERE role = 'student'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

function ambilUserById($koneksi, $id) {
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}

// 2. Update data profil
function updateDataUser($koneksi, $id, $data) {
    $email = mysqli_real_escape_string($koneksi, $data['email']);
    $school_id = isset($data['school_id']) ? intval($data['school_id']) : "NULL";
    
    // Query dasar: update email
    $query = "UPDATE users SET email = '$email'";
    
    // Jika role BUKAN admin, update juga sekolahnya
    // (Logika ini dijaga di Controller, tapi di query kita pastikan aman)
    if (isset($data['school_id'])) {
        $query .= ", school_id = $school_id";
    }
    
    // Jika password diisi (tidak kosong), maka update password
    if (!empty($data['password'])) {
        $passHash = password_hash($data['password'], PASSWORD_DEFAULT);
        $query .= ", password_hash = '$passHash'";
    }
    
    $query .= " WHERE id = $id";
    
    return mysqli_query($koneksi, $query);
}

?>

