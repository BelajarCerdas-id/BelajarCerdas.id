$(document).ready(function () {
    var oldBatch = $('#batch_id').val();
    var oldBatchScheduleGroup = $('#batch_schedule_group_id').val();
    var oldDays = $('#days_id').val();
    var oldHours = $('#hours_id').val();
    var oldMentor = $('#mentor_id').val();

    selectBatchScheduleGroup = document.getElementById('batch_schedule_group_id');
    selectDays = document.getElementById('days_id');
    selectHours = document.getElementById('hours_id');
    selectMentors = document.getElementById('mentor_id');

    function enableBatchScheduleGroupDropdown() {
        selectBatchScheduleGroup.disabled = false;
        selectBatchScheduleGroup.classList.replace('opacity-50', 'opacity-100');
        selectBatchScheduleGroup.classList.replace('!cursor-default', 'cursor-pointer');
    }

    function enableDaysDropdown() {
        selectDays.disabled = false;
        selectDays.classList.replace('opacity-50', 'opacity-100');
        selectDays.classList.replace('!cursor-default', 'cursor-pointer');
    }

    function enableHoursDropdown() {
        selectHours.disabled = false;
        selectHours.classList.replace('opacity-50', 'opacity-100');
        selectHours.classList.replace('!cursor-default', 'cursor-pointer');
    }

    function enableMentorsDropdown() {
        selectMentors.disabled = false;
        selectMentors.classList.replace('opacity-50', 'opacity-100');
        selectMentors.classList.replace('!cursor-default', 'cursor-pointer');
    }

    // === Dropdown Batch -> Batch Schedule Group ===
    $('#batch_id').on('change', function () {
        var batch_id = $(this).val();
        if (batch_id) {
            $.ajax({
                url: '/batch-schedule-groups/' + batch_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    enableBatchScheduleGroupDropdown();

                    $('#batch_schedule_group_id').empty().append(
                        '<option value="" class="hidden">Select Group</option>'
                    );

                    $.each(data, function (i, scheduleGroup) {
                        const day = scheduleGroup.days.join(' & ');
                        $('#batch_schedule_group_id').append(`
                                <option value="${scheduleGroup.batch_schedule_group}">Group ${scheduleGroup.batch_schedule_group} - ${day}</option>
                            `);
                    });

                    // restore old value kalau ada
                    if (oldBatchScheduleGroup) {
                        $('#batch_schedule_group_id').val(oldBatchScheduleGroup)
                            .trigger('change');
                    }
                }
            });
        } else {
            $('#batch_schedule_group_id').empty();
        }
    });

    if (oldBatch) {
        $('#batch_id').val(oldBatch).trigger('change');
    }

    // === Batch Schedule Group -> Days ===
    $('#batch_schedule_group_id').on('change', function () {
        var batch_schedule_group_id = $(this).val();
        var batch_id = $('#batch_id').val();

        if (batch_schedule_group_id && batch_id) {
            $.ajax({
                url: '/days/' + batch_id + '/' + batch_schedule_group_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    enableDaysDropdown();

                    $('#days_id').empty().append(
                        '<option value="" class="hidden">Select Days</option>'
                    );

                    $.each(data, function (i, day) {
                        $('#days_id').append(`
                                <option value="${day.day}">${day.day}</option>
                            `);
                    });

                    // restore old value kalau ada
                    if (oldDays) {
                        $('#days_id').val(oldDays).trigger('change');
                    }
                }
            });
        } else {
            $('#days_id').empty();
        }
    });

    if (oldBatchScheduleGroup) {
        $('#batch_schedule_group_id').val(oldBatchScheduleGroup).trigger('change');
    }

    // === Dropdown Days -> Hours ===
    $('#days_id').on('change', function () {
        var day = $(this).val();
        var batch_id = $('#batch_id').val();
        var batch_schedule_group_id = $('#batch_schedule_group_id').val();

        if (day && batch_id && batch_schedule_group_id) {
            $.ajax({
                url: '/hours/' + batch_id + '/' + batch_schedule_group_id + '/' + day,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    enableHoursDropdown();

                    $('#hours_id').empty().append(
                        '<option value="" class="hidden">Select Hours</option>'
                    );

                    $.each(data, function (i, hour) {
                        $('#hours_id').append(`
                                <option value="${hour.schedule_time_group}" data-batch-schedule-id="${hour.ids}">
                                    ${hour.time}
                                </option>
                            `);
                    });

                    // restore old value kalau ada
                    if (oldHours) {
                        $('#hours_id').val(oldHours).trigger('change');
                    }
                }
            });
        }
    });

    // === Dropdown Hours -> Mentors ===
    $('#hours_id').on('change', function () {
        // ambil attribute dari data-batch-schedule-id pada dropdown hours, lalu set data ke value batch_schedule_id
        let selected = $(this).find(':selected');
        // ambil nilai dari attribute data-batch-schedule-id
        let batchScheduleId = selected.data('batch-schedule-id');
        // set nilai itu ke input #batch_schedule_id
        $('#batch_schedule_id').val(batchScheduleId);

        var schedule_time_group = $(this).val();
        var batch_id = $('#batch_id').val();
        var batch_schedule_group_id = $('#batch_schedule_group_id').val();
        var day = $('#days_id').val();

        if (schedule_time_group && batch_id && batch_schedule_group_id && day) {
            $.ajax({
                url: '/mentors/' + batch_id + '/' + batch_schedule_group_id + '/' + day +
                    '/' + schedule_time_group,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    enableMentorsDropdown();

                    $('#mentor_id').empty().append(
                        '<option value="" class="hidden">Select One Mentor</option>'
                    );

                    $.each(data, function (mentorId, schedules) {
                        let mentor = schedules[0]; // ambil satu schedule aja
                        $('#mentor_id').append(`
                                <option value="${mentor.mentor_id}">
                                    ${mentor.user_account?.mentor_profiles?.nama_lengkap}
                                </option>
                            `);
                    });

                    // restore old mentor kalau ada
                    if (oldMentor) {
                        $('#mentor_id').val(oldMentor);
                    }
                }
            });
        }
    });
});