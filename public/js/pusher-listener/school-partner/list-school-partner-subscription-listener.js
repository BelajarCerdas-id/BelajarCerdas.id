document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('schoolPartnerSubscription')
        .listen('.school.partner.subscription', (e) => {
            paginateSchoolPartner();
    });
});
