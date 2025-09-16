document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('mentorSchedule')
        .listen('.mentor.schedule', (event) => {
            paginateManagementMentorSchedule();
        });
});
