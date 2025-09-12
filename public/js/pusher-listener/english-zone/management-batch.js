document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('managementBatch')
        .listen('.management.batch', (event) => {
            paginateManagementBatches();
        });
});
