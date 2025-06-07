    // Script untuk mendengarkan event broadcast pada saat student bertanya ke mentor
    let currentStatusTanyaMentor = 'semua';
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.channel('tanya')
            .listen('.question.created', (e) => {
                // console.log('✅ Komentar diterima dari broadcast:', e);
                // Saat ada data baru, ambil ulang semua data dengan AJAX
                console.log('✅ Broadcast diterima:', e); // <- Harusnya muncul
                fetchFilteredDataTanyaMentor(currentStatusTanyaMentor);
            });
    });
