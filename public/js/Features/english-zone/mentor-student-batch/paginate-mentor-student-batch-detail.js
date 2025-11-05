function paginateMentorStudentBatchDetail() {
    const container = document.getElementById('container-management-student-batch-detail');
    if (!container) return;

    const featureVariantId = container.dataset.featureVariantId;
    const levelId = container.dataset.levelId;
    const batchScheduleGroups = container.dataset.batchScheduleGroups;
    const batchId = container.dataset.batchId;
    const batchScheduleId = container.dataset.batchScheduleIds;
    const studentIds = container.dataset.studentId;
    if (!featureVariantId) return;
    if (!levelId) return;
    if (!batchId) return;
    if (!batchScheduleGroups) return;
    if (!batchScheduleId) return;
    if (!studentIds) return;

    fetchDataMentorStudentBatchDetail(featureVariantId, levelId, batchId, batchScheduleGroups, batchScheduleId, studentIds);

    function fetchDataMentorStudentBatchDetail(page = 1) {
        $.ajax({
            url: `/english-zone-mentor/student-batch-detail/${featureVariantId}/${levelId}/${batchId}/${batchScheduleGroups}/${batchScheduleId}/${studentIds}/paginate`,
            method: 'GET',
            data: {
                page: page,
            },
            success: function (response) {
                $('#tbody-table-management-student-batch').empty();

                if (response.data.length > 0) {

                    const identityFirst = response.data[0][0];

                    let scheduleDays = '';
                    let scheduleHours = '';

                    scheduleDays = [...new Set(response.batchSchedules.map(s => s.day_of_week))].join(' & ');
                    scheduleHours = [...new Set(response.batchSchedules.map(s => `${s.start_time} - ${s.end_time}`))].join(', ');

                    let getLevels = '';
                    getLevels = [...new Set(response.getLevels.map(s => s.level_name))].join(', ');

                    $('#student-batch-detail-identity').html(`
                        <div class="bg-white shadow-lg rounded-md p-4 h-full max-w-2xl border">
                            <div class="flex items-center gap-4">
                                <i class="fa-solid fa-chalkboard-user text-xl bg-[#4189E0] p-4 text-white rounded-full"></i>
                                <div class="flex flex-col gap-2">
                                    <span class="font-bold opacity-70">STUDENT BATCH DETAIL</span>
                                    <span class="font-bold opacity-70 text-sm">
                                        Mentor Pengajar: ${identityFirst.mentor?.mentor_profiles?.nama_lengkap ?? '-'}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-col gap-2">
                                <span class="font-bold opacity-70 text-sm">
                                    Durasi: ${identityFirst.feature_subscription_history?.transactions?.feature_prices?.variant_name ?? '-'}
                                </span>
                                <span class="font-bold opacity-70 text-sm">Level: ${getLevels}</span>
                                <span class="font-bold opacity-70 text-sm">Batch: ${response.getBatch.batch_name}</span>
                                <span class="font-bold opacity-70 text-sm flex flex-wrap gap-2">
                                    Hari: ${scheduleDays}
                                </span>
                                <span class="font-bold opacity-70 text-sm flex flex-wrap gap-2">
                                    Jam: ${scheduleHours}
                                </span>
                            </div>
                        </div>
                    `);

                    $.each(response.data, function (index, items) {
                        const first = items[0];

                        let studentBatchIds = '';

                        items.forEach(function (item) {
                            studentBatchIds += `
                                ${item.id}
                            `;
                        })

                        const formattedIds = studentBatchIds.split(' ')
                            .filter(id => id.trim() !== '') // buang yang kosong
                            .join(',');

                        $('#tbody-table-management-student-batch').append(`
                        <tr class="text-xs">
                            <td class="td-table !text-black !text-center">${index + 1}</td>
                            <td class="td-table !text-black !text-center">${first.student?.student_profiles?.nama_lengkap}</td>
                        </tr>
                    `);
                    });

                    // Append pagination links
                    $('.pagination-container-management-student-batch').html(response.links);
                    bindPaginationLinks(); // Bind click event ke link pagination yang baru
                    $('#empty-message-management-student-batch').hide(); // sembunyikan pesan kosong
                    $('.thead-table-management-student-batch').show(); // Tampilkan tabel thead
                } else {
                    $('#tbody-table-management-student-batch').empty(); // Clear existing rows
                    $('#empty-message-management-student-batch').show(); // Tampilkan pesan kosong
                    $('.thead-table-management-student-batch').hide(); // sembunyikan tabel thead
                    $('#student-batch-detail-identity').hide(); // sembunyikan student batch detail identity
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }
}


$(document).ready(function () {
    paginateMentorStudentBatchDetail();
});

function bindPaginationLinks() {
    $('.pagination-container-management-student-batch').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        paginateMentorStudentBatchDetail(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}