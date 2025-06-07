// untuk mendengarkan event broadcast ketika admin membayar soal mentor
document.addEventListener("DOMContentLoaded", function () {
    window.Echo.channel('paymentTanyaMentor')
        .listen('.payment.tanya.mentor', (e) => {
        paginatePaymentTanyaMentor();
    });
});
