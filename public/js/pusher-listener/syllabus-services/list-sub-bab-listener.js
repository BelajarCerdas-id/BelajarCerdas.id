document.addEventListener('DOMContentLoaded', function() {
    window.Echo.channel('syllabus')
    .listen('.syllabus.crud', (event) => {
        paginateSyllabusSubBab();
    });
});
