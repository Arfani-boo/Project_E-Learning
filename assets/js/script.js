document.addEventListener("DOMContentLoaded", function() {
    
     const toggleBtns = document.querySelectorAll('span[id^="togglePass"]');
    
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {

            const input = this.previousElementSibling;
            
            if (input && input.tagName === 'INPUT') {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                this.textContent = type === 'password' ? '👁️' : '🙈';
            }
        });
    });

    const progressBars = document.querySelectorAll('div[style*="width:"]');
    
    progressBars.forEach(bar => {
        const originalWidth = bar.style.width;
        
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.width = originalWidth;
        }, 300);
    });

    const deleteLinks = document.querySelectorAll('a[href*="delete"], a[href*="hapus"]');
    
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.getAttribute('onclick')) {
                e.preventDefault(); 
                if (confirm("⚠️ PERINGATAN: Anda yakin ingin menghapus data ini? Data yang dihapus tidak bisa dikembalikan.")) {
                    window.location.href = this.href;
                }
            }
        });
    });

});