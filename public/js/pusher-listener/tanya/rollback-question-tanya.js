// untuk mendengarkan event broadcast ketika user mengirim pertanyaan dan mentor sedang melihat pertanyaan
document.addEventListener('DOMContentLoaded', function () {
    // Dengar event broadcast soal update status viewed
    window.Echo.channel('tanya')
        .listen('.question.created', (e) => {
            console.log('⚡️ Broadcast diterima di admin rollback:', e);

            // Refresh data rollback otomatis
            fetchFilteredDataTanyaRollback();
    });
});

document.addEventListener('DOMContentLoaded', function () {
    window.Echo.channel('tanya')
        .listen('.question.rollback', (e) => {
            // Mendengarkan event broadcast untuk menerima riwayat soal (diterima & ditolak)
            fetchFilteredDataTanyaRollback();
    });
});
