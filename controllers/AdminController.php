<?php
require_once 'models/UserModel.php';
require_once 'models/SchoolModel.php';

function dashboardAdmin($koneksi) {
    if ($_SESSION['role'] != 'admin') { header("Location: index.php?page=login"); exit; }
    
    $totalGuru = hitungJumlahGuru($koneksi);
    $totalSiswa = hitungJumlahSiswa($koneksi);
    $totalSekolah = hitungJumlahSekolah($koneksi);
    $totalAdmin = hitungJumlahAdmin($koneksi);
    
    require 'views/admin/dashboard.php';
}

function manageTeachers($koneksi) {
    if ($_SESSION['role'] != 'admin') { header("Location: index.php?page=login"); exit; }

    $sekolah = ambilSemuaSekolah($koneksi);
    $guru_list = ambilSemuaGuru($koneksi);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['aksi'] == 'tambah') {
        $data = [
            'full_name' => $_POST['full_name'],
            'email'     => $_POST['email'],
            'password'  => $_POST['password'],
            'school_id' => $_POST['school_id'],
            'role'      => 'teacher'
        ];
        
        tambahGuruOlehAdmin($koneksi, $data);
        header("Location: index.php?page=manage_teachers");
        exit;
    }
    
    if (isset($_GET['hapus_id'])) {
        $id = intval($_GET['hapus_id']);
        mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");
        header("Location: index.php?page=manage_teachers");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_teacher_manual'])) {
        $id    = intval($_POST['id']);
        $nama  = $_POST['nama'];
        $email = $_POST['email'];
        $telp  = $_POST['telp'];
        $alamat= $_POST['alamat'];

        $err = updateGuruManual($koneksi, $id, $nama, $email, $telp, $alamat);
        if ($err !== true) {
            $_SESSION['error'] = $err;
            header("Location: index.php?page=manage_teachers&edit_id=$id");
            exit;
        }

        header("Location: index.php?page=manage_teachers&pesan=updated");
        exit;
    }

    require 'views/admin/manage_teachers.php';
}

function manageSchools($koneksi) {
    if ($_SESSION['role'] != 'admin') { header("Location: index.php?page=login"); exit; }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['aksi'] == 'tambah') {
        $nama_sekolah = mysqli_real_escape_string($koneksi, $_POST['name']);
        $alamat = mysqli_real_escape_string($koneksi, $_POST['address']); // Opsional
        
        $query = "INSERT INTO schools (name, address) VALUES ('$nama_sekolah', '$alamat')";
        mysqli_query($koneksi, $query);
        
        header("Location: index.php?page=manage_schools");
        exit;
    }

    if (isset($_GET['hapus_id'])) {
        $id = intval($_GET['hapus_id']);
        mysqli_query($koneksi, "DELETE FROM schools WHERE id=$id");
        header("Location: index.php?page=manage_schools");
        exit;
    }

    $sekolah_list = ambilSemuaSekolah($koneksi);
    
    require 'views/admin/manage_schools.php';
}

function manageStudents($koneksi) {
    if ($_SESSION['role'] != 'admin') { header("Location: index.php?page=login"); exit; }
    $sekolah = ambilSemuaSekolah($koneksi);
    $student_list = ambilSemuaSiswa($koneksi);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['aksi'] == 'tambah') {
        $data = [
            'full_name' => $_POST['full_name'],
            'email'     => $_POST['email'],
            'password'  => $_POST['password'],
            'school_id' => $_POST['school_id'],
            'role'      => 'student'
        ];
        
        daftarUserBaru($koneksi, $data);
        header("Location: index.php?page=manage_students");
        exit;
    }

    if (isset($_GET['hapus_id'])) {
        $id = intval($_GET['hapus_id']);
        mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");
        header("Location: index.php?page=manage_students");
        exit;
    }
    require 'views/admin/manage_students.php';
}

function manageAdmin($koneksi) {
    if ($_SESSION['role'] != 'admin') { header("Location: index.php?page=login"); exit; }

    $admin_list = ambilSemuaAdmin($koneksi);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['aksi'] == 'tambah') {
        $data = [
            'full_name' => $_POST['full_name'],
            'email'     => $_POST['email'],
            'password'  => $_POST['password'],
            'role'      => 'admin'
        ];
        
        daftarUserBaru($koneksi, $data);
        header("Location: index.php?page=manage_admin");
        exit;
    }

    if (isset($_GET['hapus_id'])) {
        $id = intval($_GET['hapus_id']);
        mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");
        header("Location: index.php?page=manage_admin");
        exit;
    }
    require 'views/admin/manage_admin.php';
}

?>