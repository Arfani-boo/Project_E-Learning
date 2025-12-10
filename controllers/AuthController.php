<?php
require_once 'models/UserModel.php';

function login($koneksi) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = cekLoginUser($koneksi, $email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['school_id'] = $user['school_id'];

            header("Location: index.php?page=dashboard");
            exit;
        } else {
            $error = "Email atau Password salah!";
            require 'views/auth/login.php';
        }
    } else {
        require 'views/auth/login.php';
    }
}

function registerStudent($koneksi) {
    require_once 'models/SchoolModel.php';
    $sekolah = ambilSemuaSekolah($koneksi); 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = [
            'full_name' => $_POST['full_name'],
            'email'     => $_POST['email'],
            'password'  => $_POST['password'],
            'school_id' => $_POST['school_id']
        ];

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

function logout() {
    session_destroy();
    header("Location: index.php?page=login");
    exit;
}
?>