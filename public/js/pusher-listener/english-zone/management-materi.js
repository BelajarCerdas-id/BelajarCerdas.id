document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('managementMateri')
        .listen('.management.materi', (event) => {
            paginateManagementMateri();
            paginateManagementMateriDetail();
        });
});
