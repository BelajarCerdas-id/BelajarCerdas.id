document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('managementBatchSchedule')
        .listen('.management.batch.schedule', (event) => {
            paginateManagementBatchesSchedule();
        });
});
