let currentStatusOfficeAccounts = 'semua';
document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('officeAccounts')
        .listen('.office.accounts', (e) => {
            paginateListOfficeAccounts(currentStatusOfficeAccounts);
    });
});
