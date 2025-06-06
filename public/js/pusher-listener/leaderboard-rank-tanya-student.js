document.addEventListener("DOMContentLoaded", () => {
    window.Echo.channel('tanya')
        .listen('.question.created', (e) => {
            // mendengarkan event broadcast untuk menghitung jumlah soal (menunggu & ditolak)
            leaderboardRankTanyaStudent();
        });
});
