<?php include 'views/layouts/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0;">👨‍🎓 Manajemen Murid</h2>
            <p style="margin: 5px 0 0 0; color: gray;">Daftarkan akun untuk tenaga pengajar di sini.</p>
        </div>
        <a href="index.php?page=dashboard" class="btn" 
           style="background-color: #6c757d; color: white; text-decoration: none; padding: 10px 18px; border-radius: 5px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-weight: 500;">⬅ Back To Dashboard
        </a>
    </div>
    <br>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <h3>➕ Tambah Siswa Baru</h3>
                <hr style="margin: 10px 0; border: 0; border-top: 1px solid #eee;">

                <form action="index.php?page=manage_students" method="POST">
                    <input type="hidden" name="aksi" value="tambah">

                    <div class="form-group">
                        <label>Nama Lengkap Siswa<a style="color:red; font-size:large;">*</a></label>
                        <input type="text" name="full_name" class="form-control" id="nama" placeholder="Misal: Budi">
                        <small id="fullNameError" class="err"></small>
                    </div>

                    <div class="form-group">
                        <label>Email Login<a style="color:red; font-size:large;">*</a></label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="siswa@student.id">
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
                        <small style="color: gray;">Siswa bisa menggantinya nanti.</small>
                    </div>

                    <div class="form-group">
                        <label>Asal Sekolah<a style="color:red; font-size:large;">*</a></label>
                        <select name="school_id" class="form-control" id="sekolah">
                            <option value="" id="sekolah">-- Pilih Sekolah --</option>
                            <?php 
                            foreach($sekolah as $s): 
                            ?>
                                <option value="<?= $s['id'] ?>" id="sekolah"><?= $s['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small id="sekolahError" class="err"></small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Simpan Data Siswa</button>
                </form>
            </div>
        </div>

        <div style="flex: 2; min-width: 900px;">
            <div class="card">
                <h3>📋 Daftar Siswa Terdaftar</h3>
                
                <?php if(mysqli_num_rows($student_list) == 0): ?>
                    <p style="text-align: center; margin-top: 20px;">Belum ada data guru.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Email</th>
                                <th>Asal Sekolah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($student_list)): ?>
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
                                    <a href="index.php?page=profile&edit_student=<?= $row['id'] ?>&back=manage_students" class="btn btn-edit">✏️Edit</a>
                                    <a href="index.php?page=manage_students&hapus_id=<?= $row['id'] ?>" onclick="return confirm('Yakin?')" class="btn btn-delete">🗑️ Hapus</a>
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