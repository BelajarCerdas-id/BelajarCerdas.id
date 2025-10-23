function paginateStudentBatchDetail() {
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

    fetchDataStudentBatchDetail(featureVariantId, levelId, batchId, batchScheduleGroups, batchScheduleId, studentIds);

    function fetchDataStudentBatchDetail(page = 1) {
        $.ajax({
            url: `/english-zone/management-student-batch-detail/non-school-partner/${featureVariantId}/${levelId}/${batchId}/${batchScheduleGroups}/${batchScheduleId}/${studentIds}/paginate`,
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
                            <td class="td-table !text-black !text-center">${first.student?.no_hp}</td>
                            <td class="td-table !text-black !text-center">${first.student?.email}</td>
                            <td class="td-table !text-black !text-center">
                                <button class="btn-reschedule bg-blue-500 hover:bg-blue-600 text-white font-bold text-xs w-26 h-8 rounded-lg transition-colors featureVariantId-200"
                                data-feature-variant-id="${featureVariantId}" data-level-id="${levelId}" data-batch-id="${batchId}" data-batch-schedule-groups="${batchScheduleGroups}" data-days-of-week="${scheduleDays}" 
                                data-hours="${scheduleHours}" data-student-batch-ids="${formattedIds}" data-transaction-source="${first.feature_subscription_history?.transactions?.transaction_source}">
                                    Re-Schedule
                                </button>
                            </td>
                            <td class="td-table !text-black !text-center">
                                <button class="btn-refund bg-red-500 hover:bg-red-600 text-white font-bold text-xs w-26 h-8 rounded-lg transition-colors featureVariantId-200"
                                data-student-id="${first.student_id}" data-transaction-source="${first.feature_subscription_history?.transactions?.transaction_source}">
                                    Refund
                                </button>
                            </td>
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
    paginateStudentBatchDetail();
});

function bindPaginationLinks() {
    $('.pagination-container-management-student-batch').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        paginateStudentBatchDetail(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// Event listener tombol "reschedule" (open modal)
$(document).off('click', '.btn-reschedule').on('click', '.btn-reschedule', function (e) {
    e.preventDefault();

    const batchId = $(this).data('batch-id');
    const batchScheduleGroups = $(this).data('batch-schedule-groups');
    const daysOfWeek = $(this).data('days-of-week');
    const hours = $(this).data('hours');
    const studentBatchIds = $(this).data('student-batch-ids');
    const featureVariantId = $(this).data('feature-variant-id');
    const levelId = $(this).data('level-id');
    const transactionSource = $(this).data('transaction-source');

    // set id ke form
    $('#reschedule-student-batch-form').data('batch_id', batchId);
    $('#reschedule-student-batch-form').data('batch-schedule-groups', batchScheduleGroups);
    $('#reschedule-student-batch-form').data('day_of_week_id', daysOfWeek);
    $('#reschedule-student-batch-form').data('hours_id', hours);
    $('#reschedule-student-batch-form').data('student_batch_ids', studentBatchIds);
    $('#reschedule-student-batch-form').data('feature_variant_id', featureVariantId);
    $('#reschedule-student-batch-form').data('level_id', levelId);
    $('#reschedule-student-batch-form').data('transaction-source', transactionSource);

    // Reset error
    $('#reschedule-student-batch-form .text-red-500').text('');
    $('#reschedule-student-batch-form input, #reschedule-student-batch-form select').removeClass('border-red-400 border');

    // set value ke form
    $('#input-batch-schedule-group-ids').val(batchScheduleGroups);
    $('#input-student-batch-ids').val(studentBatchIds);
    $('#input-feature-variant-id').val(featureVariantId);
    $('#input-level-id').val(levelId);
    $('#input-transaction-source').val(transactionSource);

    // buka modal
    const modal = document.getElementById('my_modal_1');
    if (modal) modal.showModal();
});


// reschedule form
let isProcessing = false;
$('#submit-button').on('click', function (e) {
    e.preventDefault();

    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const form = $('#reschedule-student-batch-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    const btn = $(this);

    btn.prop('disabled', true); // Disable button UI

    // kosongkan error
    $('#reschedule-student-batch-form .text-red-500').text('');
    $('#reschedule-student-batch-form input, #reschedule-student-batch-form select').removeClass('border-red-400 border');

    $.ajax({
        url: `/english-zone/management-student-batch-detail/reschedule`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            document.getElementById('my_modal_1').close();

            // alert sukses
            $('#alert-success-reschedule-student-batch').html(`
                <div class="w-full flex justify-center">
                    <div class="fixed z-[9999]">
                        <div id="alertSuccess"
                            class="relative top-[-45px] opacity-100 scale-90 bg-green-200 w-max p-3 flex items-center space-x-2 rounded-lg shadow-lg transition-all featureVariantId-300 ease-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current text-green-600" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-green-600 text-sm">${response.message}</span>
                            <i class="fas fa-times cursor-pointer text-green-600" id="btnClose"></i>
                        </div>
                    </div>
                </div>
            `);

            setTimeout(() => $('#alertSuccess').remove(), 3000);
            $('#btnClose').on('click', () => $('#alertSuccess').remove());

            // Reset form (input, select)
            $('#reschedule-student-batch-form')[0].reset();

            paginateStudentBatchDetail();

            isProcessing = false;
            btn.prop('disabled', false);
        },
        error: function (xhr) {
            // jika error form (text validation)
            if (xhr.status === 422 && xhr.responseJSON.form === 'error-form-reschedule') {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#reschedule-student-batch-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#reschedule-student-batch-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                })
            // jika error dari sweet alert (melebihi kapasitas maksimal batch)
            } else {
                document.getElementById('my_modal_1').close();

                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message,
                });

                // Reset form (input, select)
                $('#reschedule-student-batch-form')[0].reset();
            }
            isProcessing = false;
            btn.prop('disabled', false);
        }
    });
});

// function close modal refund
function closeModal() {
    const closeModal = document.getElementById('my_modal_2');
    closeModal.close();
}

// Event listener tombol "refund" (open modal)
$(document).off('click', '.btn-refund').on('click', '.btn-refund', function (e) {
    e.preventDefault();

    const studentId = $(this).data('student-id');
    const transactionSource = $(this).data('transaction-source');

    // (Optional) set id ke form untuk submit
    $('#refund-batch-user-form').data('student-id', studentId);
    $('#refund-batch-user-form').data('transaction-source', transactionSource);

    // buka modal
    const modal = document.getElementById('my_modal_2');
    if (modal) modal.showModal();
});

// refund batch user form
$('#refund-batch-user-form').on('submit', function (e) {
    e.preventDefault();

    const studentId = $(this).data('student-id');
    const transactionSource = $(this).data('transaction-source');

    $.ajax({
        url: `/english-zone/management-student-batch-detail/${studentId}/${transactionSource}/refund`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_2');
            if (modal) {
                modal.close();

                $('#alert-success-refund-student-batch').html(
                    `
                    <div class=" w-full flex justify-center">
                        <div class="fixed z-[9999]">
                            <div id="alertSuccess"
                                class="relative top-[-45px] opacity-100 scale-90 bg-green-200 w-max p-3 flex items-center space-x-2 rounded-lg shadow-lg transition-all duration-300 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current text-green-600" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-green-600 text-sm">${response.message}</span>
                                <i class="fas fa-times cursor-pointer text-green-600" id="btnClose"></i>
                            </div>
                        </div>
                    </div>
                    `
                );

                setTimeout(function () {
                    document.getElementById('alertSuccess').remove();
                }, 3000);

                document.getElementById('btnClose').addEventListener('click', function () {
                    document.getElementById('alertSuccess').remove();
                });

                // Memanggil fungsi untuk memuat ulang data
                paginateStudentBatchDetail();
            }
        },
    });
});


// DROPDOWN BERTINGKAT BATCH, DAYS, HOURS
$(document).ready(function () {
    var oldBatch = $('#batch_id').val();
    var oldDays = $('#day_of_week_id').val();
    var oldHours = $('#hours_id').val();

    const selectDays = document.getElementById('day_of_week_id');
    const selectHours = document.getElementById('hours_id');

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

    const container = document.getElementById('container-management-student-batch-detail');
    if (!container) return;

    const featureVariantId = container.dataset.featureVariantId;
    const levelId = container.dataset.levelId;

    // === Dropdown Batch -> Days ===
    $('#batch_id').on('change', function () {

        var batch_id = $(this).val();
        if (batch_id) {
            $.ajax({
                url: `/english-zone/management-student-batch-detail/dropdown-days/${batch_id}`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    enableSelectDays(); // enabled select days

                    $('#day_of_week_id').empty().append(
                        '<option value="" class="hidden">Pilih Hari</option>'
                    );

                    $.each(data, function (i, group) {
                        let days = group.days.join(' & ');
                        $('#day_of_week_id').append(`
                                    <option value="${group.group_id}">${days}</option>
                                `
                        );
                    });
                }
            });
        } else {
            $('#day_of_week_id').empty();
        }
    });

    // Trigger jika ada oldBatch (misalnya reload form karena error validasi)
    if (oldBatch) {
        $('#batch_id').val(oldBatch).trigger('change');
    }

    // === Dropdown Days -> Hours ===
    $('#day_of_week_id').on('change', function () {

        var group_id = $(this).val();
        var batch_id = $('#batch_id').val();
        var level_id = levelId;
        var feature_variant_id = featureVariantId;
        var transactionSource = $('#reschedule-student-batch-form').data('transaction-source'); // ambil data transaction source pada saat klik btn reschedule

        if (group_id && batch_id) {
            $.ajax({
                url: `/english-zone/management-student-batch-detail/non-school-partner/dropdown-hours/${batch_id}/${group_id}/${level_id}/${feature_variant_id}/${transactionSource}`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    enableSelectHours(); // enabled select hours

                    $('#hours_id').empty().append(
                        '<option value="" class="hidden">Choose Hour</option>'
                    );

                    $.each(data.data, function (i, hour) {
                        let count = data.studentCounts[hour.schedule_time_group] ?? 0;
                        $('#hours_id').append(`
                                <option value="${hour.schedule_time_group}" data-batch-schedule-id="${hour.ids}">
                                    ${hour.time} ${count} / 10
                                </option>
                            `);
                    });
                }
            });
        } else {
            $('#hours_id').empty();
        }
    });

    // Trigger jika ada oldBatch (misalnya reload form karena error validasi)
    if (oldDays) {
        $('#days_id').val(oldDays).trigger('change');
    }

    $('#hours_id').on('change', function () {
        // ambil attribute dari data-batch-schedule-id pada dropdown hours, lalu set data ke value batch_schedule_id
        let selected = $(this).find(':selected');
        // ambil nilai dari attribute data-batch-schedule-id
        let batchScheduleId = selected.data('batch-schedule-id');
        // set nilai itu ke input #input-batch-schedule-id
        $('#input-batch-schedule-ids').val(batchScheduleId);
    })
});