<?php
// models/SchoolModel.php

function ambilSemuaSekolah($koneksi) {
    $query = "SELECT * FROM schools ORDER BY name ASC";
    return mysqli_query($koneksi, $query);
}
?>