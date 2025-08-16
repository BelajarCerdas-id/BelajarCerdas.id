function paginateBankSoal(page = 1) {
    $.ajax({
    url: '/soal-pembahasan/paginate/bank-soal',
    method: 'GET',
    data: {
        page: page // Include the page parameter
    },
        success: function (data) {
        $('#tableListBankSoal').empty(); // Clear previous entries
        $('.pagination-container-bank-soal').empty(); // Clear previous pagination links

            if (data.data.length > 0) {
                $.each(data.data, function (index, application) {

                let bankSoalDetail = data.bankSoalDetail.replace(':subBabId', application.sub_bab_id);

                $('#tableListBankSoal').append(`
                <tr class="text-xs">
                    <td class="td-table !text-black !text-center">${index + 1}</td>
                    <td class="td-table !text-black !text-center">${application.kurikulum?.nama_kurikulum}</td>
                    <td class="td-table !text-black !text-center">${application.kelas?.kelas}</td>
                    <td class="td-table !text-black !text-center">${application.mapel?.mata_pelajaran}</td>
                    <td class="td-table !text-black !text-center">${application.bab?.nama_bab}</td>
                    <td class="td-table !text-black !text-center">${application.sub_bab?.sub_bab}</td>
                    <td class="td-table !text-black !text-center">${application.status_bank_soal === 'Publish' ? 'Publish' : 'Unpublish'}</td>
                    <td class="border text-center border-gray-300">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="hidden peer toggle-active-bank-soal"
                                data-sub_bab_id="${application.sub_bab_id}"
                                ${application.status_bank_soal === 'Publish' ? 'checked' : ''} />
                            <div
                                class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors duration-300 ease-in-out">
                            </div>
                                <div
                                class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 ease-in-out peer-checked:translate-x-2.5">
                            </div>
                        </label>
                    </td>
                    <td class="td-table !text-center font-bold text-[#4189e0] text-xs">
                        <a href="${bankSoalDetail}" class="btn-bank-soal-detail" data-sub_bab_id="${application.sub_bab_id}">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
            `);
        });

        // Append pagination links
        $('.pagination-container-bank-soal').html(data.links);
        bindPaginationLinks();
        $('#emptyMessageBankSoal').hide(); // sembunyikan pesan kosong
        $('.thead-table-bank-soal').show(); // Tampilkan tabel thead
    } else {
        $('#tableListBankSoal').empty(); // Clear existing rows
        $('#emptyMessageBankSoal').show(); // Tampilkan pesan kosong
        $('.thead-table-bank-soal').hide(); // sembunyikan tabel thead
    }
}
    });
}

$(document).ready(function () {
    const subBabId = $(this).data('sub_bab_id');
    // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
    paginateBankSoal();

    $(document).on('change', '.toggle-active-bank-soal', function () {
        let subBabId = $(this).data('sub_bab_id'); // Ambil sub bab id dari atribut data-id di checkbox
        let status = $(this).is(':checked') ? 'Publish' : 'Unpublish'; // Jika toggle ON maka publish, kalau OFF maka unpublish

        $.ajax({
            url: '/soal-pembahasan/bank-soal/activate/' + subBabId, // Endpoint ke server
            method: 'PUT', // Method HTTP PUT untuk update data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status_bank_soal: status // Kirim status baru (publish/unpublish)
            },
            success: function (response) {
                // inisialisasi update data terbaru setelah berhasil insert data
                paginateBankSoal();
            },
            // error: function(xhr) {
                //     alert('Gagal mengubah status.');
                //     // Kalau gagal, toggle dikembalikan ke kondisi sebelumnya
                //     $(this).prop('checked', !$(this).is(':checked'));
            // }
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
        paginateBankSoal(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}



