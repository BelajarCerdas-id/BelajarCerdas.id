function paginateManagementZoom(search_mentor = '', page = 1) {
    $.ajax({
        url: '/english-zone/management-zoom/paginate',
        method: 'GET',
        data: {
            page: page,
            search_mentor: search_mentor
        },
        success: function (response) {
            $('#tbody-table-management-zoom').empty(); // Clear previous entries
            $('.pagination-container-management-zoom').empty(); // Clear previous pagination links

            if (response.data.length > 0) {
                $.each(response.data, function (index, item) {
                    $('#tbody-table-management-zoom').append(`
                        <tr class="text-xs">
                            <td class="td-table !text-black !text-center">${index + 1}</td>
                            <td class="td-table !text-black !text-center">${item.mentor?.mentor_profiles?.nama_lengkap ?? '-'}</td>
                            <td class="td-table !text-black !text-center">${item.english_zone_level?.level_name ?? '-'}</td>
                            <td class="td-table !text-black !text-center">Sesi ${item.session ?? '-'}</td>
                            <td class="td-table !text-black !text-center">${item.english_zone_batch_schedule?.english_zone_batch?.batch_name ?? '-'}</td>
                            <td class="td-table !text-black !text-center">Group ${item.english_zone_batch_schedule?.batch_schedule_group ?? '-'}</td>
                            <td class="td-table !text-black !text-center">
                                ${item.english_zone_batch_schedule?.day_of_week ?? '-'}
                                <div> 
                                    ${item.english_zone_batch_schedule?.start_time ?? '-'} - ${item.english_zone_batch_schedule?.end_time ?? '-'}
                                </div>
                            </td>
                            <td class="td-table !text-black !text-center">
                                <a href="${item.link_zoom ?? '-'}" target="_blank" class="text-blue-600 font-bold text-xs underline underline-offset-1"> 
                                    Link Zoom
                                </a>
                            </td>
                            <td class="td-table !text-black !text-center">${item.meeting_id ?? '-'}</td>
                            <td class="td-table !text-black !text-center">${item.zoom_passcode ?? '-'}</td>
                            <td class="border text-center border-gray-300">
                                <div class="dropdown dropdown-left">
                                    <div tabindex="0" role="button">
                                        <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                    </div>
                                    <ul tabindex="0"
                                            class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                        <li class="text-xs">
                                            <a href="#" class="btn-edit-zoom" data-zoom-id="${item.id}" data-zoom='${JSON.stringify(item)}'>
                                                <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                Edit Zoom
                                            </a>
                                        </li>
                                        <li class="text-xs">
                                            <a href="#" class="btn-delete-zoom" data-zoom-id="${item.id}">
                                                <i class="fa-solid fa-trash text-red-600"></i>
                                                Delete Zoom
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `);
                });

                // Append pagination links
                $('.pagination-container-management-zoom').html(response.links);
                bindPaginationLinks(); // Bind click event ke link pagination yang baru
                $('#empty-message-management-zoom').hide(); // sembunyikan pesan kosong
                $('.thead-table-management-zoom').show(); // Tampilkan tabel thead
            } else {
                $('#tbody-table-management-zoom').empty(); // Clear existing rows
                $('#empty-message-management-zoom').show(); // Tampilkan pesan kosong
                $('.thead-table-management-zoom').hide(); // sembunyikan tabel thead
            }
        }
    });
}

$(document).ready(function () {
    paginateManagementZoom();
});

function bindPaginationLinks() {
    $('.pagination-container-management-zoom').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        const search_mentor = $('#search_mentor').val();
        paginateManagementZoom(search_mentor, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// Fungsi untuk memfilter data berdasarkan search_mentor (pakai on input karena ketika data yang user cari akan munul tanpa di enter atau apapun by click)
$('#search_mentor').on('input', function () {
    const search_mentor = $(this).val();
    paginateManagementZoom(search_mentor);
});

// Form Action Insert materi
let isProcessing = false;
$('#submit-button').on('click', function (e) {
    e.preventDefault();

    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const form = $('#management-zoom-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    const btn = $(this);

    btn.prop('disabled', true); // Disable button UI

    $.ajax({
        url: '/english-zone/management-zoom/store',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#alert-success-insert-zoom').html(`
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

            // Reset form (input, select)
            $('#management-zoom-form')[0].reset();

            paginateManagementZoom();

            isProcessing = false;
            btn.prop('disabled', false);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#management-zoom-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#management-zoom-form').find(`[name="${field}"]`).addClass('border-red-400 border');

                    if (errors.batch_schedule_id) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errors.batch_schedule_id[0],
                        });
                    }
                });
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }

            isProcessing = false;
            btn.prop('disabled', false);
        }
    });
});

// Event listener tombol "edit zoom" (open modal)
$(document).off('click', '.btn-edit-zoom').on('click', '.btn-edit-zoom', function (e) {
    e.preventDefault();

    const zoom = $(this).data('zoom'); // ← ambil object level lengkap
    const zoomId = zoom.id;

    // set id ke form
    $('#edit-zoom-form').data('zoom-id', zoomId);

    // Reset error
    $('#edit-zoom-form .text-red-500').text('');
    $('#edit-zoom-form input').removeClass('border-red-400 border');

    // isi semua field otomatis
    $('#link_zoom_id').val(zoom.link_zoom);
    $('#meeting_id_id').val(zoom.meeting_id);
    $('#zoom_passcode_id').val(zoom.zoom_passcode);

    // buka modal
    const modal = document.getElementById('my_modal_1');
    if (modal) modal.showModal();
});


// edit zoom
$('#edit-zoom-form').on('submit', function (e) {
    e.preventDefault();

    const zoomId = $(this).data('zoom-id');
    const formData = $(this).serialize(); // otomatis ambil semua field input/select di form

    // kosongkan error
    $('#edit-zoom-form .text-red-500').text('');
    $('#edit-zoom-form input').removeClass('border-red-400 border');

    $.ajax({
        url: `/english-zone/management-zoom/edit/${zoomId}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function (response) {
            document.getElementById('my_modal_1').close();

            // alert sukses
            $('#alert-success-update-zoom').html(`
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

            paginateManagementZoom();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#edit-zoom-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#edit-zoom-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                })
            }
        }
    });
});

// function close modal delete zoom
function closeModal() {
    const closeModal = document.getElementById('my_modal_2');
    closeModal.close();
}

// Event listener tombol "delete zoom" (open modal)
$(document).off('click', '.btn-delete-zoom').on('click', '.btn-delete-zoom', function (e) {
    e.preventDefault();

    const zoomId = $(this).data('zoom-id');

    // (Optional) set id ke form untuk submit
    $('#delete-zoom-form').data('zoom-id', zoomId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_2');
    if (modal) {
        modal.showModal();
    }
});

// delete zoom
$('#delete-zoom-form').on('submit', function (e) {
    e.preventDefault();

    const zoomId = $(this).data('zoom-id');

    $.ajax({
        url: `/english-zone/management-zoom/delete/${zoomId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_2');
            if (modal) {
                modal.close();

                $('#alert-success-delete-zoom').html(
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
                paginateManagementZoom();
            }
        },
    });
});