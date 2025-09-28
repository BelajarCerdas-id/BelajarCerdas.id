
$(document).ready(function () {
    var oldBatch = $('#batch_id').val();
    var oldDays = $('#days_id').val(); // ambil value, bukan object
    var oldHours = $('#hours_id').val();
    var oldMentor = $('#mentors_id').val();

    const selectDays = document.getElementById('days_id');
    const selectHours = document.getElementById('hours_id');
    const selectMentors = document.getElementById('mentors_id');

    function enableSelectDays() {
        selectDays.disabled = false;
        selectDays.classList.replace('opacity-50', 'opacity-100');
        selectDays.classList.replace('!cursor-default', 'cursor-pointer');
    }

    function enableSelectHours() {
        selectHours.disabled = false;
        selectHours.classList.replace('opacity-50', 'opacity-100');
        selectHours.classList.replace('!cursor-default', 'cursor-pointer');
    }

    function enableSelectMentors() {
        selectMentors.disabled = false;
        selectMentors.classList.replace('opacity-50', 'opacity-100');
        selectMentors.classList.replace('!cursor-default', 'cursor-pointer');
    }

    // === Dropdown level, ambil id level ===
    $('.level-checkbox').on('change', function () {
        let selected = $('.level-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        $('#input-level-id').val(selected.join(','));
        validatePurchase(dataFeatureId); // cek ulang tombol
    })

    // === Dropdown Batch -> Days ===
    $('#batch_id').on('change', function () {

        $('#input-batch-id').val(this.value); // set batch value ke input hidden

        var batch_id = $(this).val();
        if (batch_id) {
            $.ajax({
                url: '/english-zone/purchase/dropdown-days/' + batch_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    enableSelectDays(); // enabled select days

                    $('#days_id').empty().append(
                        '<option value="" class="hidden">Choose Day</option>'
                    );

                    $.each(data, function (i, group) {
                        let days = group.days.join(' & ');
                        $('#days_id').append(`
                                <option value="${group.group_id}">${days}</option>
                            `
                        );
                    });
                }
            });
        } else {
            $('#days_id').empty();
        }
    });

    // Trigger jika ada oldBatch (misalnya reload form karena error validasi)
    if (oldBatch) {
        $('#batch_id').val(oldBatch).trigger('change');
    }

    // === Dropdown Days -> Hours ===
    $('#days_id').on('change', function () {

        $('#input-batch-schedule-group').val(this.value); // set batch schedule group value ke input hidden

        var group_id = $(this).val();
        var batch_id = $('#batch_id').val();

        if (group_id && batch_id) {
            $.ajax({
                url: '/english-zone/purchase/dropdown-hours/' + batch_id + '/' + group_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    enableSelectHours(); // enabled select hours

                    $('#hours_id').empty().append(
                        '<option value="" class="hidden">Choose Hour</option>'
                    );

                    $.each(data, function (i, hour) {
                        $('#hours_id').append(`
                                <option value="${hour.schedule_time_group}" data-batch-schedule-id="${hour.ids}">${hour.time}</option>
                            `
                        );
                    });
                }
            });
        }
    });

    // Trigger jika ada oldBatch (misalnya reload form karena error validasi)
    if (oldDays) {
        $('#days_id').val(oldDays).trigger('change');
    }

    // === Dropdown Hours -> Mentors ===
    $('#hours_id').on('change', function () {

        // ambil attribute dari data-batch-schedule-id pada dropdown hours, lalu set data ke value batch_schedule_id
        let selected = $(this).find(':selected');
        // ambil nilai dari attribute data-batch-schedule-id
        let batchScheduleId = selected.data('batch-schedule-id');
        // set nilai itu ke input #input-batch-schedule-id
        $('#input-batch-schedule-id').val(batchScheduleId);

        var schedule_time_group = $(this).val(); // ambil schedule_time_group
        var batch_id = $('#batch_id').val();
        var group_id = $('#days_id').val(); // ini batch_schedule_group

        if (schedule_time_group && batch_id && group_id) {
            $.ajax({
                url: '/english-zone/purchase/dropdown-mentors/' + batch_id + '/' + group_id + '/' + schedule_time_group,
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    enableSelectMentors(); // enabled select mentors

                    $('#mentors_id').empty().append(
                        '<option value="" class="hidden">Choose Mentor</option>'
                    );

                    $.each(data.data, function (i, mentor) {
                        const first = mentor[0];
                        $('#mentors_id').append(`
                                <option value="${first.user_account?.id}" data-mentor-id="${first.user_account?.id}">
                                    ${first.user_account?.mentor_profiles?.nama_lengkap} -
                                    <i class="fa-solid fa-user"></i> ${data.getStudentBatch[first.user_account?.id] ?? 0} / 10
                                </option>
                            `
                        );
                    });
                }
            });
        }
    });
    // Trigger jika ada oldBatch (misalnya reload form karena error validasi)
    if (oldHours) {
        $('#hours_id').val(oldHours).trigger('change');
    }

    // === Dropdown Mentors -> ambil mentor id ===
    $('#mentors_id').on('change', function () {
        // ambil attribute dari data-mentor-id pada dropdown hours, lalu set data ke value mentor_id
        let selected = $(this).find(':selected');
        // ambil nilai dari attribute data-mentor-id
        let mentorId = selected.data('mentor-id');
        // set nilai itu ke input #input-mentor-id
        $('#input-mentor-id').val(mentorId);
    })
});