function paginateBankSoalEZ(page = 1) {
    $.ajax({
    url: '/english-zone/paginate/bank-soal',
    method: 'GET',
    data: {
        page: page // Include the page parameter
    },
        success: function (data) {
        $('#table-list-bank-soal').empty(); // Clear previous entries
        $('.pagination-container-bank-soal').empty(); // Clear previous pagination links

            if (data.data.length > 0) {
                $.each(data.data, function (index, item) {

                    let bankSoalDetail = data.bankSoalDetail.replace(':levelId', item.level);

                    $('#table-list-bank-soal').append(`
                <tr class="text-xs">
                    <td class="td-table !text-black !text-center">${index + 1}</td>
                    <td class="td-table !text-black !text-center">${item.level}</td>
                    <td class="td-table !text-black !text-center">${item.status_bank_soal === 'Publish' ? 'Publish' : 'Unpublish'}</td>
                    <td class="border text-center border-gray-300">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="hidden peer toggle-active-bank-soal"
                                data-level-id="${item.level}"
                                ${item.status_bank_soal === 'Publish' ? 'checked' : ''} />
                            <div
                                class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors duration-300 ease-in-out">
                            </div>
                                <div
                                class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 ease-in-out peer-checked:translate-x-2.5">
                            </div>
                        </label>
                    </td>
                    <td class="td-table !text-center font-bold text-[#4189e0] text-xs">
                        <a href="${bankSoalDetail}" class="btn-bank-soal-detail" data-level-id="${item.level}">
                            Lihat Detail
                        </a>
                    </td>
                    <td class="border text-center border-gray-300">
                        <div class="dropdown dropdown-left">
                            <div tabindex="0" role="button">
                                <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                            </div>
                            <ul tabindex="0"
                                class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                <li class="text-xs">
                                    <a href="#" class="btn-edit-level" data-level-id="${item.level}">
                                        <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                        Edit Level
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `);
        });

        // Append pagination links
        $('.pagination-container-bank-soal').html(data.links);
        bindPaginationLinks();
        $('#empty-message-bank-soal').hide(); // sembunyikan pesan kosong
        $('.thead-table-bank-soal').show(); // Tampilkan tabel thead
    } else {
        $('#table-list-bank-soal').empty(); // Clear existing rows
        $('#empty-message-bank-soal').show(); // Tampilkan pesan kosong
        $('.thead-table-bank-soal').hide(); // sembunyikan tabel thead
    }
}
    });
}

// Event listener tombol "edit level" (open modal)
$(document).off('click', '.btn-edit-level').on('click', '.btn-edit-level', function(e) {
    e.preventDefault();

    const levelId = $(this).data('level-id');

    // (Optional) set id ke form untuk submit
    $('#edit-level-form').data('level-id', levelId);

    // Reset text error
    $('#error-level').text('');

    // Tampilkan modal
    const modal = document.getElementById('my_modal_2');
    if (modal) {
        $('#level').val(levelId);
        modal.showModal();
    }
});

// edit level
$('#edit-level-form').on('submit', function (e) {
    e.preventDefault();

    const levelId = $(this).data('level-id');
    const levelName = $('#level').val();

    // Kosongkan error sebelumnya
    $('#error-level').text('');

    $.ajax({
        url: `/english-zone/bank-soal/edit-level/${levelId}`,
        method: 'PUT',
        data: {
            level: levelName,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_2');
            if (modal) {
                modal.close();

                $('#alert-success-update-level').html(
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
                paginateBankSoalEZ();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors && errors.level) {
                    $('#error-level').text(errors.level[0]);
                    $('#level').addClass('border border-red-400');
                }
            }
        }
    });
});


// action Unpublish dan Publish bank soal
$(document).ready(function () {
    const levelId = $(this).data('level-id');
    // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
    paginateBankSoalEZ();

    $(document).on('change', '.toggle-active-bank-soal', function () {
        let levelId = $(this).data('level-id'); // Ambil sub bab id dari atribut data-id di checkbox
        let status = $(this).is(':checked') ? 'Publish' : 'Unpublish'; // Jika toggle ON maka publish, kalau OFF maka unpublish

        $.ajax({
            url: '/english-zone/bank-soal/activate/' + levelId, // Endpoint ke server
            method: 'PUT', // Method HTTP PUT untuk update data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status_bank_soal: status // Kirim status baru (publish/unpublish)
            },
            success: function (response) {
                // inisialisasi update data terbaru setelah berhasil insert data
                paginateBankSoalEZ();
            },
            error: function (xhr) {
                alert('Gagal mengubah status.');
                checkbox.prop('checked', !checkbox.is(':checked')); // ← GUNAKAN INI
            }
        });
    });
});


function bindPaginationLinks() {
    $('.pagination-container-bank-soal').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        paginateBankSoalEZ(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}



