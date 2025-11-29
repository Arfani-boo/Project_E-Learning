<?php include 'views/layouts/header.php'; ?>

<div class="container">
    <h2>👨‍🏫 Manajemen Guru</h2>
    <p style="color: gray;">Daftarkan akun untuk tenaga pengajar di sini.</p>
    <br>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <h3>➕ Tambah Guru Baru</h3>
                <hr style="margin: 10px 0; border: 0; border-top: 1px solid #eee;">

                <form action="index.php?page=manage_teachers" method="POST">
                    <input type="hidden" name="aksi" value="tambah">

                    <div class="form-group">
                        <label>Nama Lengkap Guru<a style="color:red; font-size:large;">*</a></label>
                        <input type="text" name="full_name" class="form-control" id="nama" placeholder="Misal: Mr. Budi">
                        <small id="fullNameError" class="err"></small>
                    </div>

                    <div class="form-group">
                        <label>Email Login<a style="color:red; font-size:large;">*</a></label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="guru@sekolah.id">
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
                        <small style="color: gray;">Guru bisa menggantinya nanti.</small>
                    </div>

                    <div class="form-group">
                        <label>Asal Sekolah<a style="color:red; font-size:large;">*</a></label>
                        <select name="school_id" class="form-control" id="sekolah">
                            <option value="" id="sekolah">-- Pilih Sekolah --</option>
                            <?php 
                            // Kita reset pointer data sekolah jika perlu, atau loop ulang
                            // Pastikan $sekolah dikirim dari Controller
                            foreach($sekolah as $s): 
                            ?>
                                <option value="<?= $s['id'] ?>" id="sekolah"><?= $s['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small id="sekolahError" class="err"></small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Simpan Data Guru</button>
                </form>
            </div>
        </div>

        <div style="flex: 2; min-width: 400px;">
            <div class="card">
                <h3>📋 Daftar Guru Terdaftar</h3>
                
                <?php if(mysqli_num_rows($guru_list) == 0): ?>
                    <p style="text-align: center; margin-top: 20px;">Belum ada data guru.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Guru</th>
                                <th>Email</th>
                                <th>Asal Sekolah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($guru_list)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <b><?= $row['full_name'] ?></b>
                                </td>
                                <td><?= $row['email'] ?></td>
                                <td>
                                    <?php if($row['school_name']): ?>
                                        <span class="badge bg-blue"><?= $row['school_name'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-orange">Umum</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="#" style="color: red; text-decoration: none;">Hapus</a>
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
        const sekul   = document.getElementById('sekolah').value;
        let ok = true;
        if (!validateEmail(email)) {ok = false};
        if (!validatePassword(password)) {ok = false};
        if (!validateFullName(nama)) {ok = false};
        if (!validateSekolah(sekul)) {ok = false};
        if (!ok){e.preventDefault()};
    });
});
</script>
<?php include 'views/layouts/footer.php'; ?>