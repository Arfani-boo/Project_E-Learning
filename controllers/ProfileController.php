<?php
// controllers/ProfileController.php
require_once 'models/UserModel.php';
require_once 'models/SchoolModel.php';

function editProfile($koneksi) {
    if (!isset($_SESSION['user_id'])) { header("Location: index.php?page=login"); exit; }
    
    $id_user = $_SESSION['user_id'];
    $role_user = $_SESSION['role'];

    // 1. TANGKAP ASAL HALAMAN (Default ke dashboard jika kosong)
    // Cek dari POST (saat submit) atau GET (saat baru buka)
    $kembali_ke = isset($_POST['from']) ? $_POST['from'] : (isset($_GET['from']) ? $_GET['from'] : 'dashboard');

    // PROSES SIMPAN PERUBAHAN
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = [
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ];

        if ($role_user != 'admin') {
            $data['school_id'] = $_POST['school_id'];
        }

        if (updateDataUser($koneksi, $id_user, $data)) {
            // UPDATE: Redirect dinamis sesuai asal halaman
            echo "<script>
                    alert('Profil berhasil diperbarui!'); 
                    window.location='index.php?page=$kembali_ke';
                  </script>";
            exit; // Penting di stop disini
        } else {
            $error = "Gagal mengupdate profil.";
        }
    }

    // TAMPILKAN FORM
    $user = ambilUserById($koneksi, $id_user);
    if ($role_user != 'admin') {
        $daftar_sekolah = ambilSemuaSekolah($koneksi);
    }

    // Kirim variabel $kembali_ke ke View
    require 'views/profile/edit.php';
}
?>