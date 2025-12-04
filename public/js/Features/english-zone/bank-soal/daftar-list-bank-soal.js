function paginateBankSoalEZ() {
    $.ajax({
        url: '/english-zone/paginate/bank-soal',
        method: 'GET',
        success: function (data) {
            $('#table-list-bank-soal').empty(); // Clear previous entries
            $('.pagination-container-bank-soal').empty(); // Clear previous pagination links

            if (data.data.length > 0) {

                const rows = [];

                data.data.forEach(group => {
                    Object.values(group).forEach(items => {
                        rows.push(items[0]);
                    });
                });

                rows.forEach((item, index) => {

                    let bankSoalDetail = data.bankSoalDetail.replace(':levelId', item.level_id).replace(':sessionId', item.session_id);

                    $('#table-list-bank-soal').append(`
                        <tr class="text-xs">
                            <td class="td-table !text-black !text-center">${index + 1}</td>
                            <td class="td-table !text-black !text-center">${item.english_zone_level?.level_name}</td>
                            <td class="td-table !text-black !text-center">${item.english_zone_session?.session_name}</td>
                            <td class="td-table !text-black !text-center">${item.status_bank_soal === 'Publish' ? 'Publish' : 'Unpublish'}</td>
                            <td class="border text-center border-gray-300">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="hidden peer toggle-active-bank-soal"
                                        data-level-id="${item.level_id}" data-session-id="${item.session_id}"
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
                                <a href="${bankSoalDetail}" class="btn-bank-soal-detail" data-level-id="${item.level_id}">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    `);
                });

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

// action Unpublish dan Publish bank soal
$(document).ready(function () {
    const levelId = $(this).data('level-id');
    // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
    paginateBankSoalEZ();

    $(document).on('change', '.toggle-active-bank-soal', function () {
        let levelId = $(this).data('level-id'); // Ambil sub bab id dari atribut data-id di checkbox
        let sessionId = $(this).data('session-id');
        let status = $(this).is(':checked') ? 'Publish' : 'Unpublish'; // Jika toggle ON maka publish, kalau OFF maka unpublish

        $.ajax({
            url: '/english-zone/bank-soal/activate/' + levelId + '/' + sessionId, // Endpoint ke server
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