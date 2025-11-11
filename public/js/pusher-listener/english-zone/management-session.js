document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('managementSession')
        .listen('.management.session', (event) => {
            paginateManagementSession();
        });
});
