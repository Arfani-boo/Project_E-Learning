<?php
function cekLoginUser($koneksi, $email, $password) {
    $email = mysqli_real_escape_string($koneksi, $email);
    
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

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
    
    $role = isset($data['role']) ? $data['role'] : 'student';

    $query = "INSERT INTO users (full_name, email, password_hash, role, school_id) 
              VALUES ('$nama', '$email', '$pass', '$role', $school_id)";
    
    return mysqli_query($koneksi, $query);
}

function ambilSemuaSiswa($koneksi) {
    $query = "SELECT users.*, schools.name as school_name 
              FROM users 
              LEFT JOIN schools ON users.school_id = schools.id 
              WHERE role = 'student'";
    return mysqli_query($koneksi, $query);
}

function ambilSemuaAdmin($koneksi) {
    $query = "SELECT users.* FROM users 
              WHERE role = 'admin'";
    return mysqli_query($koneksi, $query);
}

function ambilSemuaGuru($koneksi) {
    $query = "SELECT users.*, schools.name as school_name 
              FROM users 
              LEFT JOIN schools ON users.school_id = schools.id 
              WHERE role = 'teacher'";
    return mysqli_query($koneksi, $query);
}

function tambahGuruOlehAdmin($koneksi, $data) {
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

function hitungJumlahAdmin($koneksi) {
    $query = "SELECT COUNT(*) as total FROM users WHERE role = 'admin'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

function hitungJumlahSekolah($koneksi) {
    $query = "SELECT COUNT(*) as total FROM schools";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

function ambilUserById($koneksi, $id) {
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}

function updateDataUser($koneksi, $id, $data) {
    $email = mysqli_real_escape_string($koneksi, $data['email']);
    $school_id = isset($data['school_id']) ? intval($data['school_id']) : "NULL";

    $query = "UPDATE users SET email = '$email'";
    
    if (isset($data['school_id'])) {
        $query .= ", school_id = $school_id";
    }

    if (isset($data['full_name'])) {
        $nama = mysqli_real_escape_string($koneksi, $data['full_name']);
        $query .= ", full_name = '$nama'";
    }

    if (!empty($data['password'])) {
        $passHash = password_hash($data['password'], PASSWORD_DEFAULT);
        $query .= ", password_hash = '$passHash'";
    }
    
    $query .= " WHERE id = $id";
    
    return mysqli_query($koneksi, $query);
}

function updateGuruManual($koneksi, $id, $full_name, $email, $school_id, $password = null) {
    $email = mysqli_real_escape_string($koneksi, $email);
    $cek   = mysqli_query($koneksi, "SELECT id FROM users WHERE email='$email' AND id<>$id");
    if (mysqli_num_rows($cek)) {
        return "Email sudah digunakan oleh user lain.";
    }

    $full_name = mysqli_real_escape_string($koneksi, $full_name);
    $school_id = intval($school_id);

    $set = "full_name = '$full_name',
            email     = '$email',
            school_id = $school_id";

    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $set .= ", password_hash = '" . mysqli_real_escape_string($koneksi, $hash) . "'";
    }
    $created="SELECT create_at FROM users WHERE id=$id";
    $sql = "UPDATE users SET $set WHERE id = $id";
    echo "SQL: $sql<br>Error: " . mysqli_error($koneksi);
    $ok  = mysqli_query($koneksi, $sql);

    return $ok ? true : mysqli_error($koneksi);
}
?>

