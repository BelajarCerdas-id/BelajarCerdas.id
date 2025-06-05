document.addEventListener("DOMContentLoaded", () => {
    const currentUrl = window.location.href;
    const allLinks = document.querySelectorAll(".list-menu a"); // Ambil semua link

    allLinks.forEach(link => {
        // Cek apakah href dari <a> cocok dengan URL saat ini
        if (link.href === currentUrl || currentUrl.startsWith(link.href + '/')) {
            link.classList.add("active"); // Tandai <a> sebagai aktif

            const parentLi = link.closest('.list-menu');
            if (parentLi) {
                parentLi.classList.add("active");
            }
        }
    });
});
