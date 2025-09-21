function paginateManagementUnit() {
    const container = document.getElementById('container-management-unit');
    if (!container) return;

    const levelId = container.dataset.levelId;
    if (!levelId) return;

    fetcFilteredDataManagementUnit(levelId);

    function fetcFilteredDataManagementUnit(page = 1) {
        $.ajax({
            url: `/english-zone/management-levels/unit/paginate/${levelId}`,
            method: 'GET',
            data: {
                page: page
            },
            success: function (response) {
                $('#table-list-management-unit').empty(); // Clear previous entries
                $('.pagination-container-management-unit').empty(); // Clear previous pagination links

                if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {
                        $('#table-list-management-unit').append(`
                        <tr class="text-xs">
                            <td class="td-table !text-black !text-center">${index + 1}</td>
                            <td class="td-table !text-black">${item.unit_name}</td>
                                <td class="border text-center border-gray-300">
                                    <div class="dropdown dropdown-left">
                                        <div tabindex="0" role="button">
                                            <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                        </div>
                                        <ul tabindex="0"
                                            class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                            <li class="text-xs">
                                                <a href="#" class="btn-edit-unit" data-unit-id="${item.id}" data-unit='${JSON.stringify(item)}'>
                                                    <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                    Edit Unit
                                                </a>
                                            </li>
                                            <li class="text-xs">
                                                <a href="#" class="btn-delete-unit" data-unit-id="${item.id}">
                                                    <i class="fa-solid fa-trash text-red-600"></i>
                                                    Delete unit
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                        </tr>
                        `);
                    });

                    // Append pagination links
                    $('.pagination-container-management-unit').html(response.links);
                    bindPaginationLinks(); // Bind click event ke link pagination yang baru
                    $('#empty-message-management-unit').hide(); // sembunyikan pesan kosong
                    $('.thead-table-management-unit').show(); // Tampilkan tabel thead
                } else {
                    $('#table-list-management-unit').empty(); // Clear existing rows
                    $('#empty-message-management-unit').show(); // Tampilkan pesan kosong
                    $('.thead-table-management-unit').hide(); // sembunyikan tabel thead
                }
            }
        });
    }
}

$(document).ready(function () {
    paginateManagementUnit();
});

function bindPaginationLinks() {
    $('.pagination-container-management-unit').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        paginateManagementUnit(page); // Ambil response yang difilter untuk halaman yang ditentukan
    });
}

// Form Action Insert unit
let isProcessing = false;
$('#submit-button').on('click', function (e) {
    e.preventDefault();

    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const form = $(this).closest('form')[0]; // ambil DOM Form-nya
    const levelId = $(form).data('level-id');
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol
    const btn = $(this);
    btn.prop('disabled', true); // Disable button UI

    $.ajax({
        url: `/english-zone/management-levels/unit/store/${levelId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#alert-success-insert-unit').html(`
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

            $('#management-unit-form')[0].reset();

            paginateManagementUnit();

            isProcessing = false;
            btn.prop('disabled', false);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#management-unit-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                });
                isProcessing = false;
                btn.prop('disabled', false);
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
                isProcessing = false;
                btn.prop('disabled', false);
            }
        }
    });
});

// Event listener tombol "edit unit" (open modal)
$(document).off('click', '.btn-edit-unit').on('click', '.btn-edit-unit', function (e) {
    e.preventDefault();

    const unit = $(this).data('unit'); // ← ambil object level lengkap
    const unitId = unit.id;

    // set id ke form
    $('#edit-unit-form').data('unit-id', unitId);

    // Reset error
    $('#edit-unit-form .text-red-500').text('');
    $('#edit-unit-form input').removeClass('border-red-400 border');

    // isi semua field otomatis
    $('#unit_name_id').val(unit.unit_name);

    // buka modal
    const modal = document.getElementById('my_modal_2');
    if (modal) modal.showModal();
});


// edit unit
$('#edit-unit-form').on('submit', function (e) {
    e.preventDefault();

    const unitId = $(this).data('unit-id');
    const formData = $(this).serialize(); // otomatis ambil semua field input/select di form

    // kosongkan error
    $('#edit-unit-form .text-red-500').text('');
    $('#edit-unit-form input').removeClass('border-red-400 border');

    $.ajax({
        url: `/english-zone/management-levels/unit/edit/${unitId}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function (response) {
            document.getElementById('my_modal_2').close();

            // alert sukses
            $('#alert-success-update-unit').html(`
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

            paginateManagementUnit();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#edit-unit-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#edit-unit-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                })
            }
        }
    });
});

// function close modal delete unit
function closeModal() {
    const closeModal = document.getElementById('my_modal_3');
    closeModal.close();
}

// Event listener tombol "delete level" (open modal)
$(document).off('click', '.btn-delete-unit').on('click', '.btn-delete-unit', function (e) {
    e.preventDefault();

    const unitId = $(this).data('unit-id');

    // (Optional) set id ke form untuk submit
    $('#delete-unit-form').data('unit-id', unitId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_3');
    if (modal) {
        modal.showModal();
    }
});

// delete level
$('#delete-unit-form').on('submit', function (e) {
    e.preventDefault();

    const unitId = $(this).data('unit-id');

    $.ajax({
        url: `/english-zone/management-levels/unit/delete/${unitId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_3');
            if (modal) {
                modal.close();

                $('#alert-success-delete-unit').html(
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
                paginateManagementUnit();
            }
        },
    });
});