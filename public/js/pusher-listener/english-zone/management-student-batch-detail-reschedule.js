document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('studentBatchReschedule')
        .listen('.student.batch.reschedule', (event) => {
            paginateStudentBatchDetail();
        });
});
