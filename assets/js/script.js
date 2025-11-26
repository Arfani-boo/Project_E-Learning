// assets/js/script.js

document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. GLOBAL PASSWORD TOGGLE (Fitur Mata) ---
    // Cari semua elemen yang punya class atau id toggle password
    const toggleBtns = document.querySelectorAll('span[id^="togglePass"]');
    
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Cari input password di sebelahnya (sibling)
            const input = this.previousElementSibling;
            
            if (input && input.tagName === 'INPUT') {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                // Ubah ikon
                this.textContent = type === 'password' ? '👁️' : '🙈';
            }
        });
    });

    // --- 2. ANIMASI PROGRESS BAR (Dashboard Siswa) ---
    // Cari elemen bar yang punya style width
    const progressBars = document.querySelectorAll('div[style*="width:"]');
    
    progressBars.forEach(bar => {
        // Ambil lebar aslinya dari style inline PHP (misal: 50%)
        const originalWidth = bar.style.width;
        
        // Set jadi 0 dulu biar bisa animasi
        bar.style.width = '0%';
        
        // Kembalikan ke lebar asli setelah jeda dikit (biar animasi jalan)
        setTimeout(() => {
            bar.style.width = originalWidth;
        }, 300);
    });

    // --- 3. KONFIRMASI SAFETY GLOBAL (Optional Backup) ---
    // Cari semua link yang mengandung kata 'delete' atau 'hapus' di URL-nya
    const deleteLinks = document.querySelectorAll('a[href*="delete"], a[href*="hapus"]');
    
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Jika di HTML belum ada onclick, kita tambahkan di sini
            if (!this.getAttribute('onclick')) {
                e.preventDefault(); // Stop aksi asli
                if (confirm("⚠️ PERINGATAN: Anda yakin ingin menghapus data ini? Data yang dihapus tidak bisa dikembalikan.")) {
                    window.location.href = this.href; // Lanjut kalau Yes
                }
            }
        });
    });

});