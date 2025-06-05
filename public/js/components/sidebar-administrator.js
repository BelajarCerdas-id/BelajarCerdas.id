document.addEventListener("DOMContentLoaded", () => {
    // Pastikan script berjalan hanya setelah seluruh halaman dimuat

    const mainToggles = document.querySelectorAll(".toggle-menu-sidebar"); // Ambil semua tombol toggle dropdown utama
    const subToggles = document.querySelectorAll(".toggle-menu-sidebar2"); // Ambil semua toggle untuk sub-dropdown (jika ada)

    // Fungsi untuk menutup semua dropdown utama, kecuali elemen yang sedang aktif (optional)
    function closeAllMainDropdowns(except = null) {
        document.querySelectorAll(".list-item").forEach(item => {
            if (item !== except)
                item.classList.remove("show"); // Hanya hapus class 'show', biarkan 'active' tetap
        });
    }

    // Fungsi untuk menutup semua sub-dropdown (jika ada)
    function closeAllSubDropdowns(except = null) {
        document.querySelectorAll(".list-content-dropdown").forEach(dropdown => {
            if (dropdown !== except)
                dropdown.classList.remove("show");
        });
    }

    // Event listener untuk toggle dropdown utama
    mainToggles.forEach(toggle => {
        toggle.addEventListener("click", (e) => {
            e.preventDefault(); // Cegah perilaku default <a href>
            const parent = toggle.closest('.list-item'); // Ambil parent list-item
            const isOpen = parent.classList.contains("show"); // Cek apakah sudah terbuka

            closeAllMainDropdowns(); // Tutup semua dropdown lainnya (kecuali yang diklik)

            if (!isOpen) {
                parent.classList.add("show"); // Buka dropdown yang diklik
            }

            // Catatan: tidak menambah/menghapus class 'active' di sini.
            // Class 'active' hanya diatur berdasarkan URL agar tetap stabil.
        });
    });

    // Event listener untuk toggle sub-dropdown (jika ada nested dropdown)
    subToggles.forEach(toggle => {
        toggle.addEventListener("click", (e) => {
            e.preventDefault();
            const subDropdown = toggle.nextElementSibling;

            // Pastikan elemen setelah toggle adalah dropdown yang valid
            if (!subDropdown || !subDropdown.classList.contains("list-content-dropdown"))
                return;

            const isOpen = subDropdown.classList.contains("show");

            closeAllSubDropdowns(); // Tutup semua sub-dropdown lain

            if (!isOpen) {
                subDropdown.classList.add("show"); // Tampilkan sub-dropdown yang diklik
            }
        });
    });

    // Auto aktifkan menu berdasarkan URL yang sedang dibuka
    const currentUrl = window.location.href;
    const allLinks = document.querySelectorAll(".link-href"); // Ambil semua link

    allLinks.forEach(link => {
        // Cek apakah href dari <a> cocok dengan URL saat ini
        if (link.href === currentUrl || currentUrl.startsWith(link.href + '/')) {
            link.classList.add("active"); // Tandai <a> sebagai aktif

            const contentDropdown = link.closest(".content-dropdown");
            if (contentDropdown) {
                contentDropdown.classList.add("show"); // Pastikan dropdown terbuka

                const listItem = contentDropdown.closest(".list-item");
                if (listItem) {
                    listItem.classList.add("show", "active"); // Tandai list-item parent sebagai aktif
                }
            }

            const directListItem = link.closest(".list-item");
            if (directListItem && !link.closest(".content-dropdown")) {
                directListItem.classList.add("active"); // Kalau <a> langsung di list-item tanpa dropdown
            }
        }
    });
});
