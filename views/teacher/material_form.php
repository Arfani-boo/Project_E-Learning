<?php include 'views/layouts/header.php'; ?>

<div class="card auth-box" style="max-width: 700px;">
    <h2>📄 Tambah Materi Baru</h2>
    <p>Menambahkan konten ke dalam Bab.</p>
    <hr>

    <form action="index.php?page=manage_materials&chapter_id=<?= $_GET['chapter_id'] ?>" method="POST">
        
        <input type="hidden" name="course_id" value="<?= $_GET['course_id'] ?>">
        <input type="hidden" name="chapter_id" value="<?= $_GET['chapter_id'] ?>">

        <div class="form-group">
            <label>Judul Materi<a style="color:red; font-size:large;">*</a></label>
            <input type="text" name="title" class="form-control" id="materi" placeholder="Misal: Penjelasan Tenses (Video)">
            <small id="materiError" class="err"></small>
        </div>

        <div class="form-group">
            <label>Tipe Konten<a style="color:red; font-size:large;">*</a></label>
            <select name="type" id="inputType" class="form-control" onchange="toggleInputMateri()">
                <option value="">--- Klik Untuk Memilih Tipe Konten Materi Anda ---</option>
                <option value="video">📺 Video (YouTube / Link)</option>
                <option value="audio">🎧 Audio (MP3 Link)</option>
                <option value="text">📝 Reading / Teks Bacaan</option>
            </select>
            <small id="kontenError" class="err"></small>
        </div>

        <div class="form-group" id="groupUrl">
            <label>Link URL Media</label>
            <input type="url" name="content_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
            <small style="color: gray;">Paste link YouTube atau link file MP3 di sini.</small>
        </div>

        <div class="form-group" id="groupText" style="display: none;">
            <label>Isi Bacaan</label>
            <textarea name="text_content" class="form-control" rows="10" placeholder="Tulis materi pelajaran di sini..."></textarea>
            <small style="color: gray;">Anda bisa menulis artikel panjang di sini.</small>
        </div>

        <div class="form-group">
            <label>Urutan Materi</label>
            <input type="number" name="sequence_order" class="form-control" value="1" style="width: 100px;">
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Simpan Materi</button>
            <a href="index.php?page=course_detail&id=<?= $_GET['course_id'] ?>" class="btn" style="background: #ddd;">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const judul   = document.getElementById('materi').value.trim();
        const inp   = document.getElementById('inputType').value.trim();
        let ok = true;
        if (!validateCourseTitle(judul)) {ok = false};
        if (!validateTypeKonten(inp)) {ok = false};
        if (!ok){e.preventDefault()};
    });
});
function toggleInputMateri() {
    var tipe = document.getElementById('inputType').value;
    var groupUrl = document.getElementById('groupUrl');
    var groupText = document.getElementById('groupText');

    if (tipe === 'text') {
        groupUrl.style.display = 'none';
        groupText.style.display = 'block';
    } else {
        groupUrl.style.display = 'block';
        groupText.style.display = 'none';
    }
}

</script>

<?php include 'views/layouts/footer.php'; ?>