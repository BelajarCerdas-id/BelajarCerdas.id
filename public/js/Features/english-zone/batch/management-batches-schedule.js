function paginateManagementBatchesSchedule() {
    const container = document.getElementById('accordion-container');
    if (!container) return;

    const batchId = container.dataset.batchId;
    const batchName = container.dataset.batchName;

    fetchFilteredDataManagementBatchesSchedule(batchName, batchId);

    function fetchFilteredDataManagementBatchesSchedule(batch_name, batch_id) {
        $.ajax({
            url: `/english-zone/management-batches/schedule/paginate/${batch_name}/${batch_id}`,
            method: 'GET',
            success: function (response) {

                if (response.data.length > 0) {
                    response.data.forEach((group, index) => {

                        const containerAccordionGroups = $('#grid-list-accordion-groups');
                        containerAccordionGroups.empty();

                        Object.entries(response.data).forEach(([batchScheduleGroup, scheduleGroups]) => {
                            // Ambil satu item pertama dari scheduleGroups buat referensi
                            const first = Object.values(scheduleGroups)[0][0];

                            // Loop schedule_time_group
                            const scheduleTimeGroupHtml = Object.entries(scheduleGroups).map(([scheduleTimeGroup, schedules]) => {
                                const scheduleTime = schedules.map((item) => {
                                    return `
                                        <div class="flex flex-row justify-between py-4">
                                            <span>${item.day_of_week} | ${item.start_time} - ${item.end_time}</span>
                                            <div class="space-x-2">
                                                <a href="#" class="btn-edit-batch-schedule text-blue-600 text-sm"
                                                data-batch-id="${batchId}"
                                                data-batch-schedule-id="${item.id}"
                                                data-batch-schedule-group="${item.batch_schedule_group}"
                                                data-batch-schedule='${JSON.stringify(item)}'>
                                                    Edit
                                                </a>
                                                <a href="#" class="btn-delete-batch-schedule text-red-600 text-sm"
                                                data-batch-schedule-id="${item.id}">
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    `;
                                }).join('');

                                return `
                                        <div class="flex justify-between items-center my-4">
                                            <span class="font-semibold">Jadwal ${scheduleTimeGroup}</span>
                                            <a href="#" data-batch-id="${batchId}" data-batch-name="${batchName}" data-batch-schedule-group="${first.batch_schedule_group ?? ''}" 
                                                data-schedule-time-group="${scheduleTimeGroup}"
                                                class="btn-tambah-jadwal text-[#4189E0] text-sm font-bold">
                                                Tambah jadwal
                                            </a>
                                        </div>
                                        <div class="bg-gray-50 p-2 rounded mb-2">
                                            ${scheduleTime}
                                        </div>
                                    `;
                            }).join('');

                            // CONTENT BATCH SCHEDULE JIKA DATANYA ADA
                            const card = `
                                <div class="wrapper-content-accordion-questions p-0">
    
                                    <div
                                        class="toggleButton w-full flex items-center px-4 py-2 bg-gray-100 rounded-lg border p-0">
                                        <label class="">
                                            <span>Group ${first.batch_schedule_group}</span>
                                        </label>
                                        <i class="fa-solid fa-chevron-up icon"></i>
                                    </div>
    
                                    <div class="content-accordion">
                                        <div class="p-4 space-y-2">
                                            ${scheduleTimeGroupHtml}
    
                                            <!-- Form Tambah Jadwal -->
                                            <h2 class="text-md font-semibold">Schedule (New)</h2>
                                            <div id="tambah-jadwal-container"
                                                class="mt-3 px-3 pt-3 border rounded bg-white h-[200px] overflow-y-auto">
                                                <form id="management-batch-schedule-form" data-batch-id="${batchId}"
                                                    data-batch-name="${batchName}">
                                                    <input type="hidden" name="batch_schedule_group" value="${first.batch_schedule_group ?? ''}">
                                                    <input type="hidden" name="schedule_time_group" value="${(response.lastScheduleTimeGroup[first.batch_schedule_group] ?? 0) + 1}">

                                                    <div class="jadwal-wrapper grid grid-cols-3 gap-2">
                                                        <!-- Day Of Week -->
                                                        <div class="flex flex-col">
                                                            <h4 class="text-sm font-medium mb-2">Day Of Week</h4>   
                                                            <select id="day_of_week" name="day_of_week[]" class="select select-bordered">
                                                                <option value="" class="hidden">Pilih Hari</option>
                                                                <option value="Senin">Senin</option>
                                                                <option value="Selasa">Selasa</option>
                                                                <option value="Rabu">Rabu</option>
                                                                <option value="Kamis">Kamis</option>
                                                                <option value="Jumat">Jumat</option>
                                                                <option value="Sabtu">Sabtu</option>
                                                                <option value="Minggu">Minggu</option>
                                                            </select>
                                                            <span id=""
                                                                class="error-day_of_week text-red-500 font-bold text-xs pt-2"></span>
                                                        </div>
            
                                                        <!-- Start Time -->
                                                        <div class="flex flex-col">
                                                            <h4 class="text-sm font-medium mb-2">Start Time</h4>
                                                            <input type="time" id="start_time" name="start_time[]"
                                                                value="" class="input input-bordered" />
                                                            <span id=""
                                                                class="error-start_time text-red-500 font-bold text-xs pt-2"></span>
                                                        </div>
            
                                                        <!-- End Time -->
                                                        <div class="flex flex-col">
                                                            <h4 class="text-sm font-medium mb-2">End Time</h4>
                                                            <input type="time" id="end_time" name="end_time[]" value=""
                                                                class="input input-bordered" />
                                                            <span id=""
                                                                class="error-end_time text-red-500 font-bold text-xs pt-2"></span>
                                                        </div>
                                                    </div>
            
                                                    <div class="flex items-center justify-between">
                                                        <button type="button" id="tambah-jadwal" class="btn btn-primary btn-sm my-4">+ Tambah</button>
                                                        <button type="button"
                                                            class="submit-button bg-[#4189E0] btn-sm my-4 py-4 px-4 text-white flex items-center justify-center rounded-lg">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            containerAccordionGroups.append(card);
                        });
                    });

                }

                const containerAccordionNewGroup = $('#accordion-new-groups-container');
                containerAccordionNewGroup.empty();

                // CONTENT NEW BATCH SCHEDULE GROUP
                const card = `
                <!-- Accordion New Groups -->
                <div id="materi-container" class="${response.data.length > 0 ? 'hidden' : ''}">
                    <div class="materi wrapper-content-accordion-questions p-0">

                        <div
                            class="toggleButton w-full flex items-center px-4 py-2 bg-gray-100 rounded-lg border p-0">
                            <label class="">
                                <span>Group</span>
                                <span>(New)</span>
                            </label>
                            <i class="fa-solid fa-chevron-up icon"></i>
                        </div>

                        <div class="content-accordion">
                            <div class="p-4 space-y-2">

                                <!-- Form Tambah Jadwal -->
                                <div id="tambah-jadwal-container"
                                    class="mt-3 px-3 pt-3 border rounded bg-white h-[200px] overflow-y-auto">
                                    <form id="management-batch-schedule-form" data-batch-id="${batchId}"
                                        data-batch-name="${batchName}">
                                        <input type="hidden" name="batch_schedule_group" value="${response.data.length > 0 ? response.data[0].batch_schedule_group : ''}">
                                        <input type="hidden" name="schedule_time_group" value="${response.data.length > 0 ? response.data[0].schedule_time_group : ''}">
                                        <div class="jadwal-wrapper grid grid-cols-3 gap-2">
                                            <!-- Day Of Week -->
                                            <div class="flex flex-col">
                                                <h4 class="text-sm font-medium mb-2">Day Of Week</h4>
                                                <select id="day_of_week" name="day_of_week[]" class="select select-bordered">
                                                    <option value="" class="hidden">Pilih Hari</option>
                                                    <option value="Senin">Senin</option>
                                                    <option value="Selasa">Selasa</option>
                                                    <option value="Rabu">Rabu</option>
                                                    <option value="Kamis">Kamis</option>
                                                    <option value="Jumat">Jumat</option>
                                                    <option value="Sabtu">Sabtu</option>
                                                    <option value="Minggu">Minggu</option>
                                                </select>
                                                <span id=""
                                                    class="error-day_of_week text-red-500 font-bold text-xs pt-2"></span>
                                            </div>

                                            <!-- Start Time -->
                                            <div class="flex flex-col">
                                                <h4 class="text-sm font-medium mb-2">Start Time</h4>
                                                <input type="time" id="start_time" name="start_time[]"
                                                    value="" class="input input-bordered" />
                                                <span id=""
                                                    class="error-start_time text-red-500 font-bold text-xs pt-2"></span>
                                            </div>

                                            <!-- End Time -->
                                            <div class="flex flex-col">
                                                <h4 class="text-sm font-medium mb-2">End Time</h4>
                                                <input type="time" id="end_time" name="end_time[]" value=""
                                                    class="input input-bordered" />
                                                <span id=""
                                                    class="error-end_time text-red-500 font-bold text-xs pt-2"></span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <button type="button" id="tambah-jadwal" class="btn btn-primary btn-sm my-4">+ Tambah</button>
                                            <button type="button"
                                                class="submit-button bg-[#4189E0] btn-sm my-4 py-4 px-4 text-white flex items-center justify-center rounded-lg">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="tambah-materi"
                    class="bg-[#4189e0] hover:bg-blue-500 w-[200px] h-8 text-white font-bold rounded-lg my-8 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Group</span>
                </button>
                `;
                containerAccordionNewGroup.append(card);
            }
        })
    }
}

$(document).ready(function () {
    paginateManagementBatchesSchedule();
});


// INSERT BATCH SCHEDULE
let isProcessing = false;
$(document).on('click', '.submit-button', function (e) {
    e.preventDefault();

    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const form = $(this).closest('form')[0]; // cari form terdekat
    if (!form) return;

    const batchId = $(form).data('batch-id');
    const batchName = $(form).data('batch-name');

    const formData = new FormData(form);

    const btn = $(this);

    btn.prop('disabled', true); // Disable button UI

    $.ajax({
        url: `/english-zone/management-batches/schedule/store/${batchName}/${batchId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            isProcessing = false;
            btn.prop('disabled', false);
            $('#alert-success-insert-batch-schedule').html(`
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
            `);

            setTimeout(function () {
                $('#alertSuccess').remove();
            }, 3000);

            $('#btnClose').on('click', function () {
                $('#alertSuccess').remove();
            });

            form.reset();

            // Tutup modal
            const modal = document.getElementById('my_modal_3');
            if (modal) modal.close();

            // Panggil fungsi untuk memuat ulang data
            paginateManagementBatchesSchedule();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                // reset semua error dulu
                document.querySelectorAll('.error-day_of_week, .error-start_time, .error-end_time')
                    .forEach(el => el.textContent = '');

                // looping semua error Laravel
                Object.keys(errors).forEach(function (key) {
                    const messages = errors[key];
                    const parts = key.split('.'); // misalnya: ["day_of_week","0"]
                    const field = parts[0];
                    const index = parts[1]; // bisa undefined kalau error $unique dari controller

                    const inputs = form.querySelectorAll(`[name="${field}[]"]`);

                    if (index !== undefined && inputs[index]) {
                        // 🔹 Error validasi array dengan index (misal day_of_week.0)
                        const parent = inputs[index].closest('.flex.flex-col');
                        const span = parent.querySelector(`.error-${field}, .error-${field}-ins`);
                        if (span) span.textContent = messages[0];
                        inputs[index].classList.add('border', 'border-red-400');
                    } else {
                        // 🔹 Error tanpa index (misal $unique dari controller: day_of_week, start_time, end_time)
                        inputs.forEach(input => {
                            input.classList.add('border', 'border-red-400');
                            const parent = input.closest('.flex.flex-col');
                            const span = parent.querySelector(`.error-${field}, .error-${field}-ins`);
                            if (span) span.textContent = messages[0];
                        });
                    }
                });

                isProcessing = false;
                btn.prop('disabled', false);
            } else {
                isProcessing = false;
                btn.prop('disabled', false);
                alert('Terjadi kesalahan saat mengirim data.');
            }
        }
    });
});

// Event listener tombol "tambah jadwal" (open modal)
$(document).off('click', '.btn-tambah-jadwal').on('click', '.btn-tambah-jadwal', function (e) {
    e.preventDefault();

    const batchId = $(this).data('batch-id');
    const batchName = $(this).data('batch-name');
    const batchScheduleGroup = $(this).data('batch-schedule-group');
    const scheduleTimeGroup = $(this).data('schedule-time-group');

    // set id ke form
    $('#insert-schedule-form').data('batch-id', batchId);
    $('#insert-schedule-form').data('batch-name', batchName);

    $('#insert-schedule-form .text-red-500').text('');
    $('#insert-schedule-form input, #insert-schedule-form select').removeClass('border-red-400 border');

    // buka modal
    const modal = document.getElementById('my_modal_3');
    if (modal) {
        $('#batch_schedule_group').val(batchScheduleGroup);
        $('#schedule_time_group').val(scheduleTimeGroup);
        modal.showModal();
    }
});


// Event listener tombol "edit batch schedule" (open modal)
$(document).off('click', '.btn-edit-batch-schedule').on('click', '.btn-edit-batch-schedule', function (e) {
    e.preventDefault();

    const batchSchedule = $(this).data('batch-schedule'); // ← ambil object batch lengkap
    const batchScheduleId = batchSchedule.id;
    const batchScheduleGroup = batchSchedule.batch_schedule_group;

    const batchId = $(this).data('batch-id');

    // set id ke form
    $('#edit-batch-schedule-form').data('batch-schedule-id', batchScheduleId);
    $('#edit-batch-schedule-form').data('batch-id', batchId);

    // Reset error
    $('#edit-batch-schedule-form .text-red-500').text('');
    $('#edit-batch-schedule-form input, #edit-batch-schedule-form select').removeClass('border-red-400 border');

    // isi semua field otomatis
    $('#day_of_week_id').val(batchSchedule.day_of_week);
    $('#start_time_id').val(batchSchedule.start_time);
    $('#end_time_id').val(batchSchedule.end_time);
    $('#batch_schedule_group_id').val(batchSchedule.batch_schedule_group);

    // buka modal
    const modal = document.getElementById('my_modal_1');
    if (modal) modal.showModal();
});


// edit batch schedule
$('#edit-batch-schedule-form').on('submit', function (e) {
    e.preventDefault();

    const batchScheduleId = $(this).data('batch-schedule-id');
    const batchScheduleGroup = $(this).data('batch-schedule-group');
    const formData = $(this).serialize(); // otomatis ambil semua field input/select di form

    const batchId = $(this).data('batch-id');

    // kosongkan error
    $('#edit-batch-schedule-form .text-red-500').text('');
    $('#edit-batch-schedule-form input, #edit-batch-schedule-form select').removeClass('border-red-400 border');

    $.ajax({
        url: `/english-zone/management-batches/schedule/edit/${batchId}/${batchScheduleId}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function (response) {
            document.getElementById('my_modal_1').close();

            // alert sukses
            $('#alert-success-update-batch-schedule').html(`
                <div class="w-full flex justify-center">
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
            `);

            setTimeout(() => $('#alertSuccess').remove(), 3000);
            $('#btnClose').on('click', () => $('#alertSuccess').remove());

            paginateManagementBatchesSchedule();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#edit-batch-schedule-form').find(`.error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#edit-batch-schedule-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                })
            }
        }
    });
});

// function close modal delete batch schedule
function closeModal() {
    const closeModal = document.getElementById('my_modal_2');
    closeModal.close();
}

// Event listener tombol "delete kurikulum" (open modal)
$(document).off('click', '.btn-delete-batch-schedule').on('click', '.btn-delete-batch-schedule', function (e) {
    e.preventDefault();

    const batchScheduleId = $(this).data('batch-schedule-id');

    // (Optional) set id ke form untuk submit
    $('#delete-batch-schedule-form').data('batch-schedule-id', batchScheduleId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_2');
    if (modal) {
        modal.showModal();
    }
});

// delete batch schedule
$('#delete-batch-schedule-form').on('submit', function (e) {
    e.preventDefault();

    const batchScheduleId = $(this).data('batch-schedule-id');

    $.ajax({
        url: `/english-zone/management-batches/schedule/delete/${batchScheduleId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_2');
            if (modal) {
                modal.close();

                $('#alert-success-delete-batch-schedule').html(
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
                paginateManagementBatchesSchedule();
            }
        },
    });
});

// Delegasi event "+ Tambah" jadwal baru
document.addEventListener('click', function (event) {
    if (event.target && event.target.id === 'tambah-jadwal') {
        const form = event.target.closest('form');
        if (!form) return;
        const wrapper = form.querySelector('.jadwal-wrapper');

        // langsung append 3 kolom ke grid wrapper
        wrapper.insertAdjacentHTML('beforeend', `
            <!-- Day Of Week -->
            <div class="flex flex-col mt-2">
                <h4 class="text-sm font-medium mb-2">Day Of Week</h4>
                <select name="day_of_week[]" class="select select-bordered w-full">
                    <option value="" class="hidden">Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>
                <span id="" class="error-day_of_week text-red-500 font-bold text-xs pt-2"></span>
            </div>

            <!-- Start Time -->
            <div class="flex flex-col mt-2">
                <h4 class="text-sm font-medium mb-2">Start Time</h4>
                <input type="time" name="start_time[]" class="input input-bordered" value="" />
                <span id="" class="error-start_time text-red-500 font-bold text-xs pt-2"></span>
            </div>

            <!-- End Time -->
            <div class="flex flex-col mt-2">
                <h4 class="text-sm font-medium mb-2">End Time</h4>
                <input type="time" name="end_time[]" class="input input-bordered" value="" />
                <span id="" class="error-end_time text-red-500 font-bold text-xs pt-2"></span>
            </div>
        `);
    }
});


// Delegasi event "+ Tambah" group baru
document.addEventListener('click', function (event) {
    const containerAccordion = document.getElementById('accordion-container');
    if (!containerAccordion) return;

    const batchId = containerAccordion.dataset.batchId;
    const batchName = containerAccordion.dataset.batchName;

    const button = event.target.closest('button#tambah-materi');
    if (!button) return; // kalau bukan klik tombol tambah-materi, stop

    const container = document.getElementById('accordion-new-groups-container');
    if (!container) return;

    // default object, supaya nggak error
    let response = { data: [] };

    const newMateri = document.createElement('div'); // Elemen materi baru
    newMateri.classList.add(
        'materi',
        'flex',
        'lg:gap-12',
        'gap-4',
        'flex-col',
        'lg:flex-row',
        'my-4'
    );
    newMateri.innerHTML = `
            <div class="wrapper-content-accordion-questions p-0 w-full">
                <div
                    class="toggleButton w-full flex items-center px-4 py-2 bg-gray-100 rounded-lg border p-0">
                    <label class="">
                        <span>Group</span>
                        <span class="materi-label font-semibold"></span>
                        <span>(New)</span>
                    </label>
                    <i class="fa-solid fa-chevron-up icon"></i>
                </div>
                <div class="content-accordion">
                    <div class="p-4 space-y-2">
                        <div class="mt-3 px-3 pt-3 border rounded bg-white h-[200px] overflow-y-auto">
                            <form id="management-batch-schedule-form" data-batch-id="${batchId}" data-batch-name="${batchName}">
                            <input type="hidden" name="batch_schedule_group" value="${response.data.length > 0 ? response.data[0].batch_schedule_group : ''}">
                            <input type="hidden" name="schedule_time_group" value="${response.data.length > 0 ? response.data[0].schedule_time_group : ''}">
                                <div class="jadwal-wrapper grid grid-cols-3 gap-2">
                                    <div class="flex flex-col">
                                        <h4 class="text-sm font-medium mb-2">Day Of Week</h4>
                                        <select name="day_of_week[]" class="select select-bordered">
                                            <option value="" class="hidden">Pilih Hari</option>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jumat">Jumat</option>
                                            <option value="Sabtu">Sabtu</option>
                                            <option value="Minggu">Minggu</option>
                                        </select>
                                        <span class="error-day_of_week text-red-500 font-bold text-xs pt-2"></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <h4 class="text-sm font-medium mb-2">Start Time</h4>
                                        <input type="time" name="start_time[]" class="input input-bordered"/>
                                        <span class="error-start_time text-red-500 font-bold text-xs pt-2"></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <h4 class="text-sm font-medium mb-2">End Time</h4>
                                        <input type="time" name="end_time[]" class="input input-bordered"/>
                                        <span class="error-end_time text-red-500 font-bold text-xs pt-2"></span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <button type="button" id="tambah-jadwal" class="btn btn-primary btn-sm my-4">+ Tambah</button>
                                    <button type="button" class="submit-button bg-[#4189E0] btn-sm my-4 py-4 px-4 text-white flex items-center justify-center rounded-lg">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>`;
    container.insertBefore(newMateri, button);
});
