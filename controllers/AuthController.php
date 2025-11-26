<?php
// controllers/AuthController.php
// Panggil model yang dibutuhkan
require_once 'models/UserModel.php';

// --- FUNGSI LOGIN ---
function login($koneksi) {
    // Jika tombol Login ditekan (Method POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Panggil fungsi di UserModel (nanti kita buat)
        $user = cekLoginUser($koneksi, $email, $password);

        if ($user) {
            // Login Berhasil -> Set Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['school_id'] = $user['school_id'];

            // Redirect ke dashboard
            header("Location: index.php?page=dashboard");
            exit;
        } else {
            // Login Gagal -> Balik ke halaman login bawa error
            $error = "Email atau Password salah!";
            require 'views/auth/login.php';
        }
    } else {
        // Jika cuma buka halaman (Method GET)
        require 'views/auth/login.php';
    }
}

// --- FUNGSI REGISTER SISWA (Self Service) ---
function registerStudent($koneksi) {
    // Kita butuh data sekolah untuk dropdown di form
    require_once 'models/SchoolModel.php';
    $sekolah = ambilSemuaSekolah($koneksi); 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = [
            'full_name' => $_POST['full_name'],
            'email'     => $_POST['email'],
            'password'  => $_POST['password'],
            'school_id' => $_POST['school_id'] // Bisa null jika tidak pilih
        ];

        // Panggil fungsi register di UserModel
        if (daftarUserBaru($koneksi, $data)) {
            header("Location: index.php?page=login&pesan=sukses");
            exit;
        } else {
            $error = "Gagal mendaftar (Email mungkin sudah ada).";
            require 'views/auth/register_student.php';
        }
    } else {
        require 'views/auth/register_student.php';
    }
}

// --- FUNGSI LOGOUT ---
function logout() {
    session_destroy();
    header("Location: index.php?page=login");
    exit;
}
?>