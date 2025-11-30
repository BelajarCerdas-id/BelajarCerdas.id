document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('managementPassage')
        .listen('.management.passage', (event) => {
            paginateManagementPassage();
            paginateManagementPassageDetail();
        });
});
