// untuk mendengarkan event broadcast ketika admin memverifikasi diterima atau ditolak soal mentor
document.addEventListener("DOMContentLoaded", () => {
    window.Echo.channel('TanyaMentorVerifications')
        .listen('.tanya.mentor.verifications', (e) => {
            paginateQuestionVerificationMentor();
        });
});
