document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('bankSoal')
        .listen('.bank.soal', (event) => {
            paginateBankSoal();
        });
});
