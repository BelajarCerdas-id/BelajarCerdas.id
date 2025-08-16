function paginateListOfficeAccounts(status_akun, page = 1) {
    $.ajax({
        url: '/paginate-list-office-accounts',
        method: 'GET',
        data: {
            status_akun: status_akun,
            page: page
        },
        success: function (response) {
            $('#table-list-office-accounts').empty();
            $('.pagination-container-office-accounts').empty();

            if (response.data.length > 0) {

                // Simpan dulu value yang sedang dipilih
                let selected = $('#status_akun').val();

                $('#status_akun').empty().append('<option value="semua">Lihat Semua</option>');

                response.role.forEach((group) => {
                    const first = group[0]; // Ambil role pertama dari setiap group
                    $('#status_akun').append(`
                        <option value="${first.role}">${first.role}</option>
                    `)
                })

                // Setelah option di-refresh, set lagi value yg sebelumnya dipilih
                if (selected && $('#status_akun option[value="'+selected+'"]').length > 0) {
                    $('#status_akun').val(selected);
                } else {
                    $('#status_akun').val('semua'); // fallback
                }

                $.each(response.data, function (index, item) {
                    $('#table-list-office-accounts').append(`
                        <tr>
                            <td class="td-table !text-black !text-center">${index + 1}</td>
                            <td class="td-table !text-black">${item.office_profiles?.nama_lengkap}</td>
                            <td class="td-table !text-black">${item.email || 'Email tidak tersedia'}</td>
                            <td class="td-table !text-black !text-center">${item.role}</td>
                            <td class="td-table !text-black !text-center">${item.status_akun}</td>
                            <td class="border text-center border-gray-300">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="hidden peer toggle-active-office-accounts"
                                        data-account-id="${item.id}"
                                        ${item.status_akun === 'aktif' ? 'checked' : ''} />
                                    <div
                                        class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors duration-300 ease-in-out">
                                    </div>
                                        <div
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 ease-in-out peer-checked:translate-x-2.5">
                                    </div>
                                </label>
                            </td>
                        </tr>
                    `);
                })
                    $('.pagination-container-office-accounts').html(response.links);
                    bindPaginationLinks();
                    $('#empty-message-office-accounts').hide(); // sembunyikan pesan kosong
                    $('.thead-table-office-accounts').show(); // Tampilkan tabel thead
            } else {
                    $('#table-list-office-accounts').empty(); // Clear existing rows
                    $('.thead-table-office-accounts').hide(); // Tampilkan tabel thead
                    $('#empty-message-office-accounts').removeClass('hidden');
            }
        }
    });
}

    $(document).ready(function () {
        paginateListOfficeAccounts('semua');
    })

    $('#status_akun').on('change', function() {
        const status_akun = $(this).val();
        paginateListOfficeAccounts(status_akun); // Call the function to fetch data based on status_akun
    });

function bindPaginationLinks() {
    $('.pagination-container-office-accounts').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        const status_akun = $('#status_akun').val();
        paginateListOfficeAccounts(status_akun, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

$(document).ready(function () {
    // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
    paginateListOfficeAccounts();

    $(document).on('change', '.toggle-active-office-accounts', function () {
        let accountId = $(this).data('account-id'); // Ambil sub bab id dari atribut data-id di checkbox
        let status = $(this).is(':checked') ? 'aktif' : 'non-aktif'; // Jika toggle ON maka publish, kalau OFF maka unpublish

        $.ajax({
            url: '/office-accounts-management/activate/' + accountId, // Endpoint ke server
            method: 'PUT', // Method HTTP PUT untuk update data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status_akun: status // Kirim status baru (aktif / non aktif)
            },
            success: function (response) {
                // inisialisasi update data terbaru setelah berhasil insert data
                paginateListOfficeAccounts();
            },
            error: function (xhr) {
                alert('Gagal mengubah status.');
                checkbox.prop('checked', !checkbox.is(':checked')); // ← GUNAKAN INI
            }
        });
    });
});


$(document).ready(function () {
    $('#form-office-accounts').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '/office-accounts-management/store',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {
                $('#alert-success-insert-office-accounts').html(
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
                $('#form-office-accounts')[0].reset();

                setTimeout(function() {
                    document.getElementById('alertSuccess').remove();
                }, 3000);

                document.getElementById('btnClose').addEventListener('click', function () {
                    document.getElementById('alertSuccess').remove();
                });

                // inisialisasi fungsi untuk update data terbaru
                paginateListOfficeAccounts('semua');
            },
            error: function (xhr) {
                const errors = xhr.responseJSON.errors;

                // Bersihkan semua error sebelumnya
                $('.text-error').text('');
                $('.input-error').removeClass('border-red-400 border');

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $(`[name="${field}"]`).addClass('border-red-400 border');
                });
            }
        });
    });
})
