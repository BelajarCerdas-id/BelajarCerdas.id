function paginateManagementSession() {
    const container = document.getElementById('container-management-session');
    if (!container) return;

    const levelId = container.dataset.levelId;
    if (!levelId) return;

    fetchFilteredManagementSession(levelId);

    function fetchFilteredManagementSession() {
        $.ajax({
            url: `/english-zone/management-levels/${levelId}/management-session/paginate`,
            method: 'GET',
            success: function (response) {
                $('#table-list-management-session').empty(); // Clear previous entries
                $('.pagination-container-management-session').empty(); // Clear previous pagination links

                if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {
                        $('#table-list-management-session').append(`
                            <tr class="text-xs">
                                <td class="td-table !text-black !text-center">${index + 1}</td>
                                <td class="td-table !text-black !text-center">${item.english_zone_level?.level_name ?? '-'}</td>
                                <td class="td-table !text-black">${item.session_name ?? '-'}</td>
                                <td class="border text-center border-gray-300">
                                    <div class="dropdown dropdown-left">
                                        <div tabindex="0" role="button">
                                            <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                        </div>
                                        <ul tabindex="0"
                                            class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                            <li class="text-xs">
                                                <a href="#" class="btn-edit-session" data-session-id="${item.id}" data-session='${JSON.stringify(item)}'>
                                                    <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                    Edit Sesi
                                                </a>
                                                </li>
                                            <li class="text-xs">
                                                <a href="#" class="btn-delete-session" data-session-id="${item.id}">
                                                    <i class="fa-solid fa-trash text-red-600"></i>
                                                    Delete Sesi
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });

                    $('#empty-message-management-session').hide(); // sembunyikan pesan kosong
                    $('.thead-table-management-session').show(); // Tampilkan tabel thead
                } else {
                    $('#table-list-management-session').empty(); // Clear existing rows
                    $('#empty-message-management-session').show(); // Tampilkan pesan kosong
                    $('.thead-table-management-session').hide(); // sembunyikan tabel thead
                }
            }
        });
    }
}

$(document).ready(function () {
    paginateManagementSession();
});

// Form Action Insert session
let isProcessing = false;
$('#submit-button-insert').on('click', function (e) {
    e.preventDefault();

    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const form = $('#management-session-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    const btn = $(this);

    btn.prop('disabled', true); // Disable button UI

    $.ajax({
        url: '/english-zone/management-levels/management-session/store',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#alert-success-insert-session').html(`
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

            $('#management-session-form')[0].reset();

            paginateManagementSession();

            isProcessing = false;
            btn.prop('disabled', false);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#management-session-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#management-session-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                });
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }

            isProcessing = false;
            btn.prop('disabled', false);
        }
    });
});

// Event listener tombol "edit session" (open modal)
$(document).off('click', '.btn-edit-session').on('click', '.btn-edit-session', function (e) {
    e.preventDefault();

    const session = $(this).data('session'); // ← ambil object session lengkap
    const sessionId = session.id;

    // set id ke form
    $('#edit-session-form').data('session-id', sessionId);

    // Reset error
    $('#edit-session-form .text-red-500').text('');
    $('#edit-session-form input, #edit-session-form select').removeClass('border-red-400 border');

    // isi semua field otomatis
    $('#session_name_id').val(session.session_name);

    // buka modal
    const modal = document.getElementById('my_modal_1');
    if (modal) modal.showModal();
});


// edit session
$('#submit-button-update').on('click', function (e) {
    e.preventDefault();

    if (isProcessing) return; // Abaikan jika sedang proses

    isProcessing = true; // Tandai sedang diproses

    const form = $(this).closest('form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // otomatis ambil semua field input/select di form

    const sessionId = $(form).data('session-id');

    const btn = $(this);
    btn.prop('disabled', true);

    // kosongkan error
    $('#edit-session-form .text-red-500').text('');
    $('#edit-session-form input').removeClass('border-red-400 border');

    $.ajax({
        url: `/english-zone/management-levels/management-session/edit/${sessionId}`,
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
            $('#alert-success-update-session').html(`
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

            paginateManagementSession();

            isProcessing = false;
            btn.prop('disabled', false);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#edit-session-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#edit-session-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                })
            }

            isProcessing = false;
            btn.prop('disabled', false);
        }
    });
});

// function close modal delete level
function closeModal() {
    const closeModal = document.getElementById('my_modal_2');
    closeModal.close();
}

// Event listener tombol "delete session" (open modal)
$(document).off('click', '.btn-delete-session').on('click', '.btn-delete-session', function (e) {
    e.preventDefault();

    const sessionId = $(this).data('session-id');

    // (Optional) set id ke form untuk submit
    $('#delete-session-form').data('session-id', sessionId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_2');
    if (modal) {
        modal.showModal();
    }
});

// delete session
$('#delete-session-form').on('submit', function (e) {
    e.preventDefault();

    const sessionId = $(this).data('session-id');

    $.ajax({
        url: `/english-zone/management-levels/management-session/delete/${sessionId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_2');
            if (modal) {
                modal.close();

                $('#alert-success-delete-session').html(
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
                paginateManagementSession();
            }
        },
    });
});