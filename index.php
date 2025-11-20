<?php

include 'conn.php';

?>
<!doctype html>
<html>
<head>
    <title>Daftar User</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<main class="layout">
    <h2>Daftar Admin</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>No</th><th>id</th><th>Nama</th><th>email</th><th>role</th><th>Di Buat tanggal</th>
        </tr>
        <?php
            $res = mysqli_query($koneksi, "SELECT * FROM users WHERE role='admin'");
            $no=1;
            while($row = mysqli_fetch_array($res)):
        ?>
        <tr>
            <td><?=$no++;?></td>
            <td><?=$row['id']?></td>
            <td><?=$row['full_name']?></td>
            <td><?=$row['email']?></td>
            <td><?=$row['role']?></td>
            <td><?=$row['created_at']?></td>
        </tr>
        <?php endwhile; ?>
</main>
</body>
</html>