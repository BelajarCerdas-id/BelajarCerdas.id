document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('schoolPartnerUserSubscription')
        .listen('.school.partner.user.subscription', (e) => {
            paginateUserSchoolPartner();
    });
});
