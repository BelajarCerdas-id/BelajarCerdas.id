document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('bankSoalStatusUpdate')
        .listen('.bank.soal.status.update', (event) => {
            paginateManagementBankSoalQuiz();
            paginateBankSoalEditQuestionQuizEZ();
        });
});
