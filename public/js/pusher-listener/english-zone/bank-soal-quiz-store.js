document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('bankSoal')
        .listen('.bank.soal', (event) => {
            paginateManagementBankSoalQuiz();
            paginateBankSoalEditQuestionQuizEZ();
        });
});
