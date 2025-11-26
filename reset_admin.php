<?php
require 'koneksi.php';

// Password yang diinginkan: 123456
// Kita biarkan PHP yang membuat hash-nya agar 100% cocok dengan sistem Anda
$pass_baru = password_hash("123456", PASSWORD_DEFAULT);

// Update user admin
$query = "UPDATE users SET password_hash = '$pass_baru' WHERE email = 'guru@sekolah.id'";

if(mysqli_query($koneksi, $query)) {
    echo "<h1>Sukses!</h1>";
    echo "Password untuk <b>admin@sekolah.id</b> berhasil direset menjadi: <b>123456</b><br>";
    echo "Silakan <a href='index.php'>Login Sekarang</a>";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>