function paginateManagementBatches() {
    $.ajax({
        url: '/english-zone/management-batches/paginate',
        method: 'GET',
        success: function (data) {
            $('#table-list-management-batch').empty(); // Clear previous entries
            $('.pagination-container-management-batch').empty(); // Clear previous pagination links

            if (data.data.length > 0) {
                $.each(data.data, function (index, item) {

                    const mapMonth = {
                        '01': 'Januari',
                        '02': 'Februari',
                        '03': 'Maret',
                        '04': 'April',
                        '05': 'Mei',
                        '06': 'Juni',
                        '07': 'Juli',
                        '08': 'Agustus',
                        '09': 'September',
                        '10': 'Oktober',
                        '11': 'November',
                        '12': 'Desember'
                    }

                    const montLabel = mapMonth[item.start_month];

                    const batchSchedule = data.batchSchedule.replace(':batch_name', item.batch_name).replace(':batch_id', item.id);

                    $('#table-list-management-batch').append(`
                    <tr class="text-xs">
                        <td class="td-table !text-black !text-center">${index + 1}</td>
                        <td class="td-table !text-black !text-center">${item.batch_name}</td>
                        <td class="td-table !text-black !text-center">${item.start_day} - ${montLabel}</td>
                        <td class="td-table !text-black !text-center">${item.max_capacity}</td>
                        <td class="td-table !text-black !text-center">
                            <a href="${batchSchedule}" class="text-[#4189e0] font-bold text-xs">Lihat Detail</a>
                        </td>
                        <td class="border text-center border-gray-300">
                            <div class="dropdown dropdown-left">
                                <div tabindex="0" role="button">
                                    <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                </div>
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                    <li class="text-xs">
                                        <a href="#" class="btn-edit-batch" data-batch-id="${item.id}" data-batch='${JSON.stringify(item)}'>
                                            <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                            Edit Batch
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `);
                });

                // Append pagination links
                $('.pagination-container-management-batch').html();
                $('#empty-message-management-batch').hide(); // sembunyikan pesan kosong
                $('.thead-table-management-batch').show(); // Tampilkan tabel thead
            } else {
                $('#table-list-management-batch').empty(); // Clear existing rows
                $('#empty-message-management-batch').show(); // Tampilkan pesan kosong
                $('.thead-table-management-batch').hide(); // sembunyikan tabel thead
            }
        }
    });
}

$(document).ready(function () {
    paginateManagementBatches();
});

// Form Action Insert Batch
$('#submit-button').on('click', function (e) {
    e.preventDefault();

    const form = $('#management-batch-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    $.ajax({
        url: '/english-zone/management-batches/store',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#alert-success-insert-batch').html(`
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

            $('#management-batch-form')[0].reset();

            paginateManagementBatches();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#management-batch-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#management-batch-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                });
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }
        }
    });
});


// Event listener tombol "edit level" (open modal)
$(document).off('click', '.btn-edit-batch').on('click', '.btn-edit-batch', function (e) {
    e.preventDefault();

    const batch = $(this).data('batch'); // ← ambil object batch lengkap
    const batchId = batch.id;

    // set id ke form
    $('#edit-batch-form').data('batch-id', batchId);

    // Reset error
    $('#edit-batch-form .text-red-500').text('');
    $('#edit-batch-form input, #edit-batch-form select').removeClass('border-red-400 border');

    // isi semua field otomatis
    $('#batch_name_id').val(batch.batch_name);
    $('#start_day_id').val(batch.start_day);
    $('#start_month_id').val(batch.start_month);
    $('#max_capacity_id').val(batch.max_capacity);

    // buka modal
    const modal = document.getElementById('my_modal_1');
    if (modal) modal.showModal();
});


// edit level
$('#edit-batch-form').on('submit', function (e) {
    e.preventDefault();

    const batchId = $(this).data('batch-id');
    const formData = $(this).serialize(); // otomatis ambil semua field input/select di form

    // kosongkan error
    $('#edit-batch-form .text-red-500').text('');
    $('#edit-batch-form input, #edit-batch-form select').removeClass('border-red-400 border');

    $.ajax({
        url: `/english-zone/management-batches/${batchId}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function (response) {
            document.getElementById('my_modal_1').close();

            // alert sukses
            $('#alert-success-update-batch').html(`
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

            paginateManagementBatches();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#edit-batch-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#edit-batch-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                })
            }
        }
    });
});
