function paginateFeaturesList(page = 1) {
    $.ajax({
        url: '/paginate-features-list',
        method: 'GET',
        data: {
            page: page
        },
        success: function (response) {
            $('#tbody-features-list').empty();
            $('.pagination-container-feature-list').empty();

            if (response.data.length > 0) {

                $.each(response.data, function (index, item) {
                    $('#tbody-features-list').append(`
                        <tr>
                            <td class="td-table !text-black !text-center">${index + 1}</td>
                            <td class="td-table !text-black">${item.nama_fitur}</td>
                            <td class="td-table">
                                <div class="flex justify-center items-center">
                                    <div class="btn-edit-fitur bg-[#4189E0] py-2 px-3 w-max rounded-md text-white font-bold cursor-pointer" data-fitur-id="${item.id}" data-nama-fitur="${item.nama_fitur}">
                                        <i class="fa-solid fa-pen"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);
                })
                    $('.pagination-container-feature-list').html(response.links);
                    bindPaginationLinks();
                    $('#empty-message-features-list').hide(); // sembunyikan pesan kosong
                    $('.thead-table-features-list').show(); // Tampilkan tabel thead
            } else {
                    $('#tbody-features-list').empty(); // Clear existing rows
                    $('.thead-table-features-list').hide(); // Tampilkan tabel thead
                    $('#empty-message-features-list').removeClass('hidden');
            }
        }
    });
}

$(document).ready(function () {
    paginateFeaturesList();
})


function bindPaginationLinks() {
    $('.pagination-container-feature-list').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        paginateFeaturesList(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// FORM INSERT FEATURE
$(document).ready(function () {
    $('#form-features-list').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/features-list-management/store',
            method: 'POST',
            data: {
                nama_fitur: $('#nama_fitur-insert').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function (response) {
                $('#alert-success-insert-data-fitur').html(
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

                // Reset form
                $('#form-features-list')[0].reset();

                setTimeout(function() {
                    document.getElementById('alertSuccess').remove();
                }, 3000);

                document.getElementById('btnClose').addEventListener('click', function () {
                    document.getElementById('alertSuccess').remove();
                });

                // inisialisasi fungsi untuk update data terbaru
                paginateFeaturesList();
            },
            error: function (xhr) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $(`#error-${field}-insert`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $(`[name="${field}-insert"]`).addClass('border-red-400 border');
                });
            }
        });
    });
})

// DISPLAY MODAL EDIT FEATURE
$(document).off('click', '.btn-edit-fitur').on('click', '.btn-edit-fitur', function (e) {
    e.preventDefault();

    const featureId = $(this).data('fitur-id');
    const featureName = $(this).data('nama-fitur');

    $('#form-edit-feature-list').data('fitur-id', featureId);

    $('#error-nama_fitur-update').text('');

    const modal = document.getElementById('my_modal_1');
    if (modal) {
        $('#nama_fitur-update').val(featureName);
        modal.showModal();
    }
})


// FORM EDIT FEATURE
$('#form-edit-feature-list').on('submit', function (e) {
    e.preventDefault();

    const featureId = $(this).data('fitur-id');
    const featureName = $('#nama_fitur-update').val();

    // Kosongkan error sebelumnya
    $('#error-nama_fitur-update').text('');

    const formData = new FormData(this);

    $.ajax({
        url: `/features-list-management/update/${featureId}`,
        method: 'POST',
        data: {
            nama_fitur: featureName,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_1');
            if (modal) {
                modal.close();

                $('#alert-success-update-data-fitur').html(
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

                setTimeout(function() {
                    document.getElementById('alertSuccess').remove();
                }, 3000);

                document.getElementById('btnClose').addEventListener('click', function () {
                    document.getElementById('alertSuccess').remove();
                });

                // Memanggil fungsi untuk memuat ulang data
                paginateFeaturesList();
            }
        },
        error: function (xhr) {
            const errors = xhr.responseJSON.errors;

            $.each(errors, function (field, messages) {
                // Tampilkan pesan error
                $(`#error-${field}-update`).text(messages[0]);

                // Tambahkan style error ke input (jika ada)
                $(`[name="${field}-update"]`).addClass('border-red-400 border');
            });
        }
    });
});
