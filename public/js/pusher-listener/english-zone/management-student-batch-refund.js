document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('studentBatchRefund')
        .listen('.student.batch.refund', (event) => {
            paginateStudentBatchNonSchoolPartner();
            paginateStudentBatchSchoolPartner();
        });
});
