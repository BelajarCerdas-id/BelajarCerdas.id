// mendengarkan event broadcast menghitung pertanyaan baru yang belum di verifikasi admin
document.addEventListener("DOMContentLoaded", () => {
    window.Echo.channel('CountMentorQuestionsAwaitVerification')
        .listen('.count.mentor.questions.await.verification', (e) => {
        paginateListMentorTanya();
    });
});
