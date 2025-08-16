let currentStatusOfficeAccounts = 'semua';
document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('officeAccounts')
        .listen('.office.accounts', (e) => {
            console.log('Broadcast diterima:', e);
            paginateListOfficeAccounts(currentStatusOfficeAccounts);
    });
});
