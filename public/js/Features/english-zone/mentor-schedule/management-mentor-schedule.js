function paginateManagementMentorSchedule(search_mentor = '', batch = '') {
    $.ajax({
        url: '/english-zone/management-mentor/schedule/paginate',
        method: 'GET',
        data: {
            search_mentor: search_mentor,
            batch: batch
        },
        success: function (response) {
            $('#table-list-management-mentor-schedule').empty();
            $('.pagination-container-management-mentor-schedule').empty();

            // Cek apakah ada data mentor
            if (response.data.length === 0) {
                $('#empty-message-management-mentor-schedule').show();
                $('.thead-table-management-mentor-schedule').hide();
                return;
            }

            let hasAnySchedule = false; // flag untuk cek ada schedule

            $.each(response.data, function (index, item) {
                let schedulesHtml = '';
                let theadHtml = '';
                let batchHeader = '';

                // Ambil batch yang dipilih atau default
                let scheduleGroups = {};
                if (batch) {
                    if (response.scheduleTimeGroup[batch]) {
                        scheduleGroups[batch] = response.scheduleTimeGroup[batch];
                    }
                } else {
                    scheduleGroups = response.scheduleTimeGroup;
                }

                // Skip mentor jika batch tidak punya schedule
                if (Object.keys(scheduleGroups).length === 0) return;

                hasAnySchedule = true;

                // Loop per batch
                $.each(scheduleGroups, function (batchId, scheduleTimeGroups) {
                    const batchNameMap = {
                        "Batch 1": "Januari",
                        "Batch 2": "Februari",
                        "Batch 3": "Maret",
                        "Batch 4": "April",
                        "Batch 5": "Mei",
                        "Batch 6": "Juni",
                        "Batch 7": "Juli",
                        "Batch 8": "Agustus",
                        "Batch 9": "September",
                        "Batch 10": "Oktober",
                        "Batch 11": "November",
                        "Batch 12": "Desember"
                    }

                    const batchNameLabel = batchNameMap[batchId]

                    batchHeader += `
                        <div class="font-semibold">
                            ${batchId} - ${batchNameLabel}
                        </div>
                    `;

                    // Loop per schedule_time_group
                    $.each(scheduleTimeGroups, function (scheduleTime, batchGroups) {
                        // Loop per batch_schedule_group
                        $.each(batchGroups, function (batchScheduleGroup, schedules) {
                            let ids = schedules.map(s => s.id).join(',');

                            // ambil semua jadwal aktif dari mentor
                            let activeScheduleIds = item.english_zone_mentor_schedule
                                ? item.english_zone_mentor_schedule
                                    .filter(ms => ms.status_schedule === 'aktif')
                                    .map(ms => ms.batch_schedule_id)
                                : [];

                            let isActive = schedules.some(s => activeScheduleIds.includes(s.id));
                            let checked = isActive ? 'checked' : '';

                            // Build td
                            schedulesHtml += `
                                <td class="td-table !text-black !text-center">
                                    <label class="flex items-center justify-center space-x-2 mb-1">
                                        <input type="checkbox" name="batch_schedule_id[]" data-mentor-id="${item.id}" data-id="${ids}"
                                        ${checked}
                                        class="form-checkbox text-blue-600 cursor-pointer toggle-activate-mentor-schedule">
                                    </label>
                                </td>
                            `;

                            // Build thead
                            let days = schedules.map(s => s.day_of_week).join(' & ');
                            let startTime = schedules[0].start_time;
                            let endTime = schedules[0].end_time;

                            theadHtml += `
                                <th class="th-table !text-black !text-center opacity-70 font-bold">
                                    <div class="flex flex-col">
                                        ${days}<br>
                                        ${startTime} - ${endTime}
                                    </div>
                                </th>
                            `;
                        });
                    });
                });

                // Append header tabel
                $('.thead-table-management-mentor-schedule').html(`
                    <tr>
                        <th class="th-table !text-black !text-center font-bold opacity-70" colspan="${theadHtml.split('th').length}">${batchHeader}</th>
                    </tr>
                    <tr>
                        <th class="th-table !text-black !text-center font-bold opacity-70">Nama Mentor</th>
                        ${theadHtml}
                    </tr>
                `);

                // Append row mentor
                $('#table-list-management-mentor-schedule').append(`
                    <tr class="text-xs">
                        <td class="td-table !text-black !text-center">${item.mentor_profiles?.nama_lengkap}</td>
                        ${schedulesHtml}
                    </tr>
                `);
            });

            if (hasAnySchedule) {
                $('#empty-message-schedule').hide();
                $('.thead-table-management-mentor-schedule').show();
                $('#empty-message-management-mentor-schedule').hide();
            } else {
                $('#table-list-management-mentor-schedule').empty();
                $('#empty-message-schedule').show();
                $('.thead-table-management-mentor-schedule').hide();
            }
        }
    });
}

$(document).ready(function () {
    paginateManagementMentorSchedule();
});

// Fungsi untuk memfilter data berdasarkan search_mentor (pakai on input karena ketika data yang user cari akan munul tanpa di enter atau apapun by click)
$('#search_mentor').on('input', function () {
    const searchValue = $(this).val();
    const selectedBatch = $('#dropdown-filter-batch').val() || '';
    paginateManagementMentorSchedule(searchValue, selectedBatch);
});

$('#dropdown-filter-batch').on('change', function () {
    const selectedBatch = $(this).val();
    const searchValue = $('#search_mentor').val() || '';
    paginateManagementMentorSchedule(searchValue, selectedBatch);
});


$(document).on('change', '.toggle-activate-mentor-schedule', function () {
    let ids = $(this).data('id').toString().split(','); // Ambil ID batch schedule dari atribut data-id di checkbox hasilnya array. contoh: ["34","35"]
    let mentorId = $(this).data('mentor-id'); // Ambil ID mentor dari atribut data-id di checkbox
    let status = $(this).is(':checked') ? 'aktif' : 'tidak_aktif'; // Jika toggle ON maka aktif, kalau OFF maka tidak_aktif

    $.ajax({
        url: '/english-zone/management-mentor/schedule/activate', // Endpoint ke server
        method: 'POST', // Method HTTP PUT untuk update data
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            batch_schedule_ids: ids,
            mentor_id: mentorId,
            status_schedule: status // Kirim status baru (aktif/tidak_aktif)
        },
        success: function (response) {
            //
        },
        error: function (xhr) {
            alert('Gagal mengubah status.');
            checkbox.prop('checked', !checkbox.is(':checked')); // ← GUNAKAN INI
        }
    });
});