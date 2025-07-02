document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('bankSoalEditQuestion')
        .listen('.bank.soal.edit.question', (event) => {
            paginateBankSoal();
    });
});
