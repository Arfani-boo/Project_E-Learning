<?php
require_once 'models/UserModel.php';
require_once 'models/SchoolModel.php';

function editProfile($koneksi) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_teacher'])) {
        $id   = intval($_POST['id']);
        $nama = $_POST['full_name'] ?? '';
        $email= $_POST['email']      ?? '';
        $sekolah = intval($_POST['school_id'] ?? 0);
        $pass  = $_POST['password']  ?? '';

        $err = updateGuruManual($koneksi, $id, $nama, $email, $sekolah, $pass);
        
        if ($err !== true) {
            $_SESSION['error'] = $err;
            header("Location: index.php?page=profile&edit_teacher=$id&back=" . $_POST['back']);
            exit;
        }
        
        $back = $_POST['back'] ?? 'manage_teachers';
        echo "<script>alert('Data guru berhasil diperbarui!'); window.location='index.php?page=$back';</script>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_school'])) {
        $id   = intval($_POST['id']);
        $name = mysqli_real_escape_string($koneksi, $_POST['name']);
        $addr = mysqli_real_escape_string($koneksi, $_POST['address'] ?? '');
        
        $result = mysqli_query($koneksi, "UPDATE schools SET name='$name', address='$addr' WHERE id=$id");
        
        if ($result) {
            $back = $_POST['back'] ?? 'manage_schools';
            echo "<script>alert('Data sekolah berhasil diperbarui!'); window.location='index.php?page=$back';</script>";
            exit;
        } else {
            $_SESSION['error'] = "Gagal update sekolah: " . mysqli_error($koneksi);
            header("Location: index.php?page=profile&edit_school=$id&back=" . $_POST['back']);
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_student'])) {
        $id   = intval($_POST['id']);
        $nama = $_POST['full_name'] ?? '';
        $email= $_POST['email']      ?? '';
        $sekolah = intval($_POST['school_id'] ?? 0);
        $pass  = $_POST['password']  ?? '';
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }
        updateGuruManual($koneksi, $id, $nama, $email, $sekolah, $pass);
        
        $back = $_POST['back'] ?? 'manage_schools';
        echo "<script>alert('Data siswa berhasil diperbarui!'); window.location='index.php?page=manage_students';</script>";
        exit;
    }

    if (isset($_GET['edit_teacher'])) {
        $id     = intval($_GET['edit_teacher']);
        $user   = ambilUserById($koneksi, $id);
        $daftar_sekolah = ambilSemuaSekolah($koneksi);
        $kembali_ke = $_GET['back'] ?? 'manage_teachers';
        require 'views/profile/edit.php';
        exit;
    }

    if (isset($_GET['edit_school'])) {
        $id     = intval($_GET['edit_school']);
        $school = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM schools WHERE id=$id")); // data sekolah
        $kembali_ke = $_GET['back'] ?? 'manage_schools';
        require 'views/profile/edit.php';
        exit;
    }
    
    if (isset($_GET['edit_student'])) {
        $id = intval($_GET['edit_student']);
        $user   = ambilUserById($koneksi, $id);
        $student = ambilUserById($koneksi, $id);
        $daftar_sekolah = ambilSemuaSekolah($koneksi);
        require 'views/profile/edit.php';
        exit;
    }

    if (!isset($_SESSION['user_id'])) { header("Location: index.php?page=login"); exit; }

    $id_user = $_SESSION['user_id'];
    $role_user = $_SESSION['role'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = [
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ];

        if ($role_user != 'admin') {
            $data['school_id'] = $_POST['school_id'];
        }

        if ($role_user === 'admin') {
            $_SESSION['full_name']=$_POST['full_name'];
            $data['full_name'] = $_POST['full_name'];
        }

        if (updateDataUser($koneksi, $id_user, $data)) {
            $from = $_POST['from'] ?? 'dashboard';
    echo "<script>alert('Profil berhasil diperbarui!'); window.location='index.php?page=$from';</script>";
    exit;
        } else {
            $error = "Gagal mengupdate profil.";
        }
    }

    $user = ambilUserById($koneksi, $id_user);
    if ($role_user != 'admin') {
        $daftar_sekolah = ambilSemuaSekolah($koneksi);
    }

    require 'views/profile/edit.php';
}
?>