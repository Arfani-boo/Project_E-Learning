function clearError(elementId) {
    const errorElement = document.getElementById(elementId);
    errorElement.textContent = '';
}

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    errorElement.textContent = message;
}

//Fungsi Validasi ni wok

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        showError('emailError', 'Email wajib di-isi');
        return false;
    }
    if (!emailRegex.test(email)) {
        showError('emailError', 'Format email tidak valid');
        return false;
    }
    clearError('emailError');
    return true;
}

function validateFullName(fullName) {
    const nameRegex = /^[a-zA-Z\s,.']{3,50}$/;
    if (!fullName) {
        showError('fullNameError', 'Nama lengkap wajib diisi');
        return false;
    }
    if (!nameRegex.test(fullName)) {
        showError('fullNameError', 'Nama hanya boleh berisi huruf dan spasi (3-50 karakter)');
        return false;
    }
    clearError('fullNameError');
    return true;
}

function validateSekolah(sekolah) {
    if (!sekolah) {
        showError('sekolahError', 'Asal Sekolah Wajib di Pilih');
        return false;
    }
    clearError('sekolahError');
    return true;
}

function validateNewClass(kelas) {
    if (!kelas) {
        showError('KelasError', 'Judul Kelas Wajib di isi');
        return false;
    }
    clearError('KelasError');
    return true;
}

function validateDescription(des){
    if (!des) {
        showError('deskripsiError', 'Deskripsi Wajib Di Isi');
        return false;
    }
    if (des.length < 3){
        showError('deskripsiError', 'Minimal memiliki 3 karakter');
        return false;
    }
    clearError('deskripsiError');
    return true;
}

function validatePassword(password){
    if(!password){
        showError('passwordError','Password Harus Di Isi');
        return false;
    }
    clearError('passwordError');
    return true;
}

function validateNamaSekolah(sekolah) {
    if (!sekolah) {
        showError('namaSekolahError', 'Asal Sekolah Wajib di Isi');
        return false;
    }
    if (sekolah.length < 3){
        showError('namaSekolahError', 'Minimal memiliki 3 karakter');
        return false;
    }
    clearError('namaSekolahError');
    return true;
}

function validateClassTitle(kelas){
    if(!kelas){
        showError("judulError","Judul Kelas Wajib Di Isi");
        return false;
    }
    clearError("judulError");
    return true;
}