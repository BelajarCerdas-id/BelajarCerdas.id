function fetchFilteredDataMentorTL(page = 1) {
    $.ajax({
        url: '/paginateViewLaporan', // Endpoint untuk mengambil data
        method: 'GET',
        data: { page: page }, // Kirim nomor halaman jika menggunakan pagination
        success: function (data) {
            // Hapus data lama
            $('#filterListViewLaporanTL').empty();
            $('.pagination-container-viewLaporan-TL').empty();

            // Jika ada data, render tabel
            if (data.data.length > 0) {
                data.data.forEach(function (item) {
                    const status = data.statusStar[item.id];
                    let actionButtons = '';

                    if (status) {
                        // Jika status ada, tampilkan sesuai status
                        if (status.status === 'Diterima') {
                            actionButtons = `<button class="text-success bg-green-200 p-2 w-32 rounded-lg cursor-default">${status.status}</button>`;
                        } else if (status.status === 'Ditolak') {
                            actionButtons = `<button class="text-white bg-red-500 p-2 w-24 rounded-lg cursor-default">${status.status}</button>`;
                        }
                    } else {
                        // Jika status tidak ada, tampilkan tombol Terima dan Tolak
                        actionButtons = `
                            <button class="btn btn-success !text-white" onclick="updateStatus(${item.id}, 'Diterima')">Terima</button>
                            <button class="btn btn-error !text-white" onclick="updateStatus(${item.id}, 'Ditolak')">Tolak</button>
                        `;
                    }

                    // Tambahkan baris ke tabel
                    $('#filterListViewLaporanTL').append(`
                        <tr>
                            <td>${item.nama_lengkap}</td>
                            <td>${item.kelas}</td>
                            <td>${item.mapel}</td>
                            <td>${item.bab}</td>
                            <td>${item.pertanyaan.substring(0, 100)}...</td>
                            <td>${item.jawaban}</td>
                            <td>${item.status}</td>
                            <td>Lihat</td>
                            <td class="flex gap-4">${actionButtons}</td>
                        </tr>
                    `);
                });

                // Tampilkan pagination links
                $('.pagination-container-viewLaporan-TL').html(data.links);
            } else {
                $('#filterListViewLaporanTL').append('<tr><td colspan="9">Tidak ada data.</td></tr>');
            }
        },
        error: function () {
            alert('Gagal memuat data.');
        }
    });
}






