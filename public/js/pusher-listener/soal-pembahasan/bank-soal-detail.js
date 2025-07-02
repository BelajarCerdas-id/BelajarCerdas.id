// untuk mendengarkan event broadcast ketika user mengedit soal dan terjadi perubahan di detail soal
document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('bankSoalEditQuestion')
        .listen('.bank.soal.edit.question', (e) => {
            paginateBankSoalDetail();
    });
});

// untuk mendengarkan event broadcast ketika user insert soal dan update soal terbaru di detail soal
document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('bankSoal')
        .listen('.bank.soal', (e) => {
            paginateBankSoalDetail();
    });
});
