document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('managementZoom')
        .listen('.management.zoom', (event) => {
            paginateManagementZoom();
        });
});
