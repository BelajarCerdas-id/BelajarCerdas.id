function paginateManagementLevel(page = 1) {
    $.ajax({
        url: '/english-zone/management-levels/paginate',
        method: 'GET',
        data: {
            page: page
        },
        success: function (data) {
            $('#table-list-management-level').empty(); // Clear previous entries
            $('.pagination-container-management-level').empty(); // Clear previous pagination links

            if (data.data.length > 0) {
                $.each(data.data, function (index, item) {

                    $('#table-list-management-level').append(`
                    <tr class="text-xs">
                        <td class="td-table !text-black !text-center">${index + 1}</td>
                        <td class="td-table !text-black !text-center">${item.level_name}</td>
                        <td class="border text-center border-gray-300">
                            <div class="dropdown dropdown-left">
                                <div tabindex="0" role="button">
                                    <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                </div>
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                    <li class="text-xs">
                                        <a href="#" class="btn-edit-level" data-level-id="${item.id}" data-level='${JSON.stringify(item)}'>
                                            <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                            Edit Level
                                        </a>
                                    </li>
                                    <li class="text-xs">
                                        <a href="#" class="btn-delete-level text-red-600" data-level-id="${item.id}">
                                            <i class="fa-solid fa-trash text-red-600"></i>
                                            Delete Level
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `);
                });

                // Append pagination links
                $('.pagination-container-management-level').html(data.links);
                bindPaginationLinks(); // Bind click event ke link pagination yang baru
                $('#empty-message-management-level').hide(); // sembunyikan pesan kosong
                $('.thead-table-management-level').show(); // Tampilkan tabel thead
            } else {
                $('#table-list-management-level').empty(); // Clear existing rows
                $('#empty-message-management-level').show(); // Tampilkan pesan kosong
                $('.thead-table-management-level').hide(); // sembunyikan tabel thead
            }
        }
    });
}

$(document).ready(function () {
    paginateManagementLevel();
});

function bindPaginationLinks() {
    $('.pagination-container-management-level').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        paginateManagementLevel(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// Form Action Insert level
$('#submit-button').on('click', function (e) {
    e.preventDefault();

    const form = $('#management-level-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    $.ajax({
        url: '/english-zone/management-levels/store',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#alert-success-insert-level').html(`
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

            $('#management-level-form')[0].reset();

            paginateManagementLevel();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#management-level-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#management-level-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                });
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }
        }
    });
});

// Event listener tombol "edit level" (open modal)
$(document).off('click', '.btn-edit-level').on('click', '.btn-edit-level', function (e) {
    e.preventDefault();

    const level = $(this).data('level'); // ← ambil object level lengkap
    const levelId = level.id;

    // set id ke form
    $('#edit-level-form').data('level-id', levelId);

    // Reset error
    $('#edit-level-form .text-red-500').text('');
    $('#edit-level-form input, #edit-level-form select').removeClass('border-red-400 border');

    // isi semua field otomatis
    $('#level_name_id').val(level.level_name);

    // buka modal
    const modal = document.getElementById('my_modal_1');
    if (modal) modal.showModal();
});


// edit level
$('#edit-level-form').on('submit', function (e) {
    e.preventDefault();

    const levelId = $(this).data('level-id');
    const formData = $(this).serialize(); // otomatis ambil semua field input/select di form

    // kosongkan error
    $('#edit-level-form .text-red-500').text('');
    $('#edit-level-form input').removeClass('border-red-400 border');

    $.ajax({
        url: `/english-zone/management-levels/edit/${levelId}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function (response) {
            document.getElementById('my_modal_1').close();

            // alert sukses
            $('#alert-success-update-level').html(`
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

            paginateManagementLevel();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#edit-level-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#edit-level-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                })
            }
        }
    });
});

// function close modal delete level
function closeModal() {
    const closeModal = document.getElementById('my_modal_2');
    closeModal.close();
}

// Event listener tombol "delete level" (open modal)
$(document).off('click', '.btn-delete-level').on('click', '.btn-delete-level', function (e) {
    e.preventDefault();

    const levelId = $(this).data('level-id');

    // (Optional) set id ke form untuk submit
    $('#delete-level-form').data('level-id', levelId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_2');
    if (modal) {
        modal.showModal();
    }
});

// delete level
$('#delete-level-form').on('submit', function (e) {
    e.preventDefault();

    const levelId = $(this).data('level-id');

    $.ajax({
        url: `/english-zone/management-levels/delete/${levelId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_2');
            if (modal) {
                modal.close();

                $('#alert-success-delete-level').html(
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
                paginateManagementLevel();
            }
        },
    });
});