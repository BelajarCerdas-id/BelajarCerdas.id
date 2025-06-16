    document.addEventListener("DOMContentLoaded", () => {
        const toggles = document.querySelectorAll(".toggle-menu"); // Dropdown utama

        // Fungsi untuk menutup semua dropdown
        function closeAllSubDropdowns(except = null) {
            document.querySelectorAll(".content-dropdown").forEach(dropdown => {
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
                const parent = toggle.closest('.list-item'); // Cari elemen utama

                // Toggle dropdown utama
                parent.classList.toggle("show");

                // Tutup dropdown lain yang tidak diklik
                document.querySelectorAll(".list-item").forEach(dropdown => {
                    if (dropdown !== parent) {
                        dropdown.classList.remove("show");
                    }
                });

                // Tutup semua sub-dropdown saat dropdown utama berubah
                closeAllSubDropdowns();
            });
        });

        // Event listener untuk sub-dropdown (toggle-menu2)
        // toggles2.forEach(toggle => {
        //     toggle.addEventListener("click", () => {
        //         const parent = toggle.closest('.content-dropdown'); // Cari elemen sub-dropdown

        //         if (parent.classList.contains("show")) {
        //             // Jika sudah terbuka, tutup
        //             parent.classList.remove("show");
        //         } else {
        //             // Jika belum terbuka, tutup yang lain lalu buka yang ini
        //             closeAllSubDropdowns();
        //             closeAllListDropdowns();
        //             parent.classList.add("show");
        //         }
        //     });
        // });
    });
