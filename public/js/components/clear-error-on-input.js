    document.addEventListener('DOMContentLoaded', function() {
        // Cek semua inputan dan hapus error message ketika user mengetik
        document.querySelectorAll('input, select, textarea').forEach(function(el) {
            el.addEventListener('input', function() {
                // Hapus error class
                el.classList.remove('border-red-400');
                const errorMessage = el.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('text-red-500')) {
                    errorMessage.textContent = '';
                }
            });
        });

        // Untuk dropdown mapel
        const dropdownWrapper = document.getElementById('dropdownWrapper');
        const dropdownButton = document.getElementById('dropdownButton');

        dropdownButton.addEventListener('click', function() {
            // Hapus border merah dari dropdown
            dropdownButton.classList.remove('border-red-400');
            dropdownWrapper.classList.remove('border-red-400');

            // Cari dan hapus pesan error
            const errorMessage = document.querySelector('#error-mapel_id');
            if (errorMessage) {
                errorMessage.remove();
            }
        });
    });
