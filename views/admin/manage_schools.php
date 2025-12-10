<?php include 'views/layouts/header.php'; ?>

<div class="container">
    <h2>🏫 Manajemen Sekolah Mitra</h2>
    <p>Tambah data sekolah agar siswa bisa memilihnya saat mendaftar.</p>
    <br>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <h3>➕ Tambah Sekolah</h3>
                <hr>
                <form action="index.php?page=manage_schools" method="POST">
                    <input type="hidden" name="aksi" value="tambah">
                    
                    <div class="form-group">
                        <label>Nama Sekolah <a style="color:red; font-size:large;">*</a></label>
                        <input type="text" name="name" class="form-control" id="NamaSekolah" placeholder="Example: SMAN 1 Jakarta">
                        <small id="namaSekolahError" class="err"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Alamat (Opsional)</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Simpan Data</button>
                </form>
            </div>
        </div>

        <div style="flex: 2; min-width: 900px;">
            <div class="card">
                <h3>📋 Daftar Sekolah</h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sekolah</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($row = mysqli_fetch_assoc($sekolah_list)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><b><?= $row['name'] ?></b></td>
                            <td><?= $row['address'] ? $row['address'] : '-' ?></td>
                            <td>
                                <a href="index.php?page=profile&edit_school=<?= $row['id'] ?>&back=manage_schools" class="btn btn-edit">✏️ Edit</a>
                                <a href="index.php?page=manage_schools&hapus_id=<?= $row['id'] ?>" 
                                   onclick="return confirm('Yakin hapus? User yang terhubung akan kehilangan data sekolahnya.')"
                                   class="btn btn-delete">🗑️ Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const sekulName = document.getElementById('NamaSekolah').value.trim();

        let ok = true;
        if (!validateNamaSekolah(sekulName)) {ok = false};
        if (!ok){e.preventDefault()};
    });
});
</script>
<?php include 'views/layouts/footer.php'; ?>