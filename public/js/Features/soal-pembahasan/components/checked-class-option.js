document.addEventListener("DOMContentLoaded", () => {
    // Auto aktifkan menu berdasarkan URL yang sedang dibuka
    const currentUrl = window.location.href;
    const allLinks = document.querySelectorAll(".link-href-class"); // Ambil semua link

    allLinks.forEach(link => {
        // Cek apakah href dari <a> cocok dengan URL saat ini
        if (link.href === currentUrl || currentUrl.startsWith(link.href + '/' )) {
            link.classList.add("active"); // Tandai <a> sebagai aktif

            const classOptions = link.closest('.list-class-item');

            if (classOptions) {
                classOptions.classList.add("active"); // Kalau <a> langsung di list-item tanpa dropdown
            }
        }
    });
});
