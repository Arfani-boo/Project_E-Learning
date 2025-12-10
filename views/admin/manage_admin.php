<?php include 'views/layouts/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <div>
            <h2>👨‍💻 Manajemen Admin</h2>
            <p style="color: gray; margin: 0;">Daftarkan akun Admin di sini.</p>
        </div>
        <a href="index.php?page=dashboard" class="btn" 
        style="background-color: #6c757d; color: white; text-decoration: none; padding: 12px 15px; border-radius: 5px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-align: center;">⬅ Back To Dashboard
        </a>
    </div>
    <br>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <h3>➕ Tambah Admin Baru</h3>
                <hr style="margin: 10px 0; border: 0; border-top: 1px solid #eee;">

                <form action="index.php?page=manage_admin" method="POST">
                    <input type="hidden" name="aksi" value="tambah">

                    <div class="form-group">
                        <label>Nama Lengkap<a style="color:red; font-size:large;">*</a></label>
                        <input type="text" name="full_name" class="form-control" id="nama" placeholder="Misal: Budi">
                        <small id="fullNameError" class="err"></small>
                    </div>

                    <div class="form-group">
                        <label>Email Login<a style="color:red; font-size:large;">*</a></label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="admin@admin123.id">
                        <small id="emailError" class="err"></small>
                    </div>

                    <div class="form-group">
                        <label>Password Awal<a style="color:red; font-size:large;">*</a></label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="passInputGuru" class="form-control" placeholder="******">
                            <span style="position: absolute; right: 10px; top: 10px; cursor: pointer;" title="Lihat Password">
                                👁️
                            </span>
                        </div>
                        <small id="passwordError" class="err"></small>
                        <small style="color: gray;">Admin baru bisa menggantinya nanti.</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Simpan Data Admin</button>
                </form>
            </div>
        </div>

        <div style="flex: 2; min-width: 900px;">
            <div class="card">
                <h3>📋 Daftar Siswa Terdaftar</h3>
                
                <?php if(mysqli_num_rows($admin_list) == 0): ?>
                    <p style="text-align: center; margin-top: 20px;">Belum ada data guru.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Admin</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($admin_list)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <b><?= $row['full_name'] ?></b>
                                </td>
                                <td><?= $row['email'] ?></td>
                                <td>
                                    <a href="index.php?page=manage_admin&hapus_id=<?= $row['id'] ?>" onclick="return confirm('Yakin?')" class="btn btn-delete">🗑️ Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const email   = document.getElementById('email').value.trim();
        const password   = document.getElementById('passInputGuru').value.trim();
        const nama   = document.getElementById('nama').value.trim();
        let ok = true;
        if (!validateEmail(email)) {ok = false};
        if (!validatePassword(password)) {ok = false};
        if (!validateFullName(nama)) {ok = false};
        if (!ok){e.preventDefault()};
    });
});
</script>
<?php include 'views/layouts/footer.php'; ?>