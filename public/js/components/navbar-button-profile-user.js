document.addEventListener("DOMContentLoaded", () => {
    const toggles = document.querySelectorAll(".toggle-menu-button-profile"); // Dropdown utama

    // Fungsi untuk menutup semua dropdown
    function closeAllSubDropdowns(except = null) {
        document.querySelectorAll(".content-dropdown-button-profile").forEach(dropdown => {
            if (dropdown !== except) {
                dropdown.classList.remove("show");
            }
        });
    }

    function closeAllListDropdowns(except = null) {
        document.querySelectorAll(".list-content-dropdown").forEach(dropdown => {
            if (dropdown !== except) {
                dropdown.classList.remove("show");
            }
        });
    }

    // Event listener untuk dropdown utama
    toggles.forEach(toggle => {
        toggle.addEventListener("click", () => {
            const parent = toggle.closest('.list-item-button-profile'); // Cari elemen utama

            // Toggle dropdown utama
            parent.classList.toggle("show");

            // Tutup dropdown lain yang tidak diklik
            document.querySelectorAll(".list-item-button-profile").forEach(dropdown => {
                if (dropdown !== parent) {
                    dropdown.classList.remove("show");
                }
            });

            // Tutup semua sub-dropdown saat dropdown utama berubah
            closeAllSubDropdowns();
        });
    });
});
