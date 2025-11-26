<?php include 'views/layouts/header.php'; ?>

<div class="card auth-box" style="max-width: 600px;">
    <h2><?= isset($data_edit) ? '✏️ Edit Kelas' : '📝 Buat Kelas Baru' ?></h2>
    <hr>

    <form action="index.php?page=manage_course" method="POST">
        
        <?php if(isset($data_edit)): ?>
            <input type="hidden" name="id" value="<?= $data_edit['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Judul Kelas</label>
            <input type="text" name="title" class="form-control" required 
                   value="<?= isset($data_edit) ? $data_edit['title'] : '' ?>"
                   placeholder="Contoh: Basic English Grammar">
        </div>

        <div class="form-group">
            <label>Deskripsi Singkat</label>
            <textarea name="description" class="form-control" rows="3" required><?= isset($data_edit) ? $data_edit['description'] : '' ?></textarea>
        </div>

        <div class="form-group">
            <label>Level Kesulitan</label>
            <select name="level" class="form-control">
                <?php $lvl = isset($data_edit) ? $data_edit['level'] : ''; ?>
                <option value="beginner" <?= $lvl=='beginner'?'selected':'' ?>>Beginner</option>
                <option value="intermediate" <?= $lvl=='intermediate'?'selected':'' ?>>Intermediate</option>
                <option value="advanced" <?= $lvl=='advanced'?'selected':'' ?>>Advanced</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <?= isset($data_edit) ? 'Simpan Perubahan' : 'Buat Kelas' ?>
            </button>
            <a href="index.php?page=dashboard" class="btn" style="background: #ddd; color: #333;">Batal</a>
        </div>
    </form>
</div>

<?php include 'views/layouts/footer.php'; ?>