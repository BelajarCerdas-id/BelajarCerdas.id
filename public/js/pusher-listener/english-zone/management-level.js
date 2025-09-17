document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('managementLevels')
        .listen('.management.levels', (event) => {
            paginateManagementLevel();
        });
});
