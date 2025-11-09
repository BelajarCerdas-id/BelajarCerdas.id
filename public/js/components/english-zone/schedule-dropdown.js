$(document).ready(function () {
    var oldMentor = $('#mentor_id').val();

    selectMentors = document.getElementById('mentor_id');

    function enableMentorsDropdown() {
        selectMentors.disabled = false;
        selectMentors.classList.replace('opacity-50', 'opacity-100');
        selectMentors.classList.replace('!cursor-default', 'cursor-pointer');
    }

    $.ajax({
        url: '/schedule-dropdown-mentors',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            enableMentorsDropdown();

            $('#mentor_id').empty().append(
                '<option value="" class="hidden">Pilih Mentor</option>'
            );

            $.each(data, function (mentorId, schedules) {
                $('#mentor_id').append(`
                    <option value="${schedules.mentor_id}">
                        ${schedules.user_account?.mentor_profiles?.nama_lengkap}
                    </option>
                `);
            });

            // restore old mentor kalau ada
            if (oldMentor) {
                $('#mentor_id').val(oldMentor);
            }
        }
    });
});