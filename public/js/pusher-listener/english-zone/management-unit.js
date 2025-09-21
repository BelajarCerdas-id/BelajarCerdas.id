document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('managementUnit')
        .listen('.management.unit', (event) => {
            paginateManagementUnit();
        });
});
