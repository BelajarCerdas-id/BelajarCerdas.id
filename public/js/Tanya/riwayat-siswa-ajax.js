function fetchFilteredDataRiwayatStudent(status_soal, page = 1) {
    $.ajax({
    url: '/filter',
    method: 'GET',
    data: {
        status_soal: status_soal,
        page: page // Include the page parameter
    },
    success: function(data) {
        $('#filterList').empty(); // Clear previous entries
        $('.pagination-container-siswa').empty(); // Clear previous pagination links

    if (data.data.length > 0) {
        $.each(data.data, function(index, application) {
        const formatDate = (dateString) => {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September','Oktober', 'November', 'Desember'];

        const date = new Date(dateString);
        const dayName = days[date.getDay()];
        const day = date.getDate();
        const monthName = months[date.getMonth()];
        const year = date.getFullYear();

        return `${dayName}, ${day}-${monthName}-${year}`;
    };

    const timeFormatter = new Intl.DateTimeFormat('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });

    const createdAt = application.created_at ? `${formatDate(application.created_at)}, ${timeFormatter.format(new Date(application.created_at))}` : 'Tanggal tidak tersedia';

    const updatedAt = application.updated_at ? `${formatDate(application.updated_at)}, ${timeFormatter.format(new Date(application.updated_at))}` : 'Tanggal tidak tersedia';

    // const restoreUrl = `{{ route('getRestore.edit', ':id') }}`.replace(':id',
    //     application.id);
    const restoreUrl = data.restoreUrl.replace(':id', application.id);
    const limitString = (str, limit) => (str ? (str.length > limit ? str.substring(0, limit) + '...' : str) : '-');

    $('#filterList').append(`
        <tr>
            <td class="td-table !text-black !text-center">${index + 1}</td>
            <td class="td-table !text-black">${limitString(application.pertanyaan, 45) || '-'}</td>
            <td class="td-table !text-black !text-center">${application.mapel?.mata_pelajaran || ''}</td>
            <td class="td-table !text-black !text-center">${application.bab?.nama_bab || ''}</td>
            <td class="td-table !text-black !text-center">${createdAt}</td>
            <td class="td-table !text-black !text-center">${updatedAt}</td>
            <td class="td-table !text-black !text-center">${application.status_soal || ''}</td>
            <td class="td-table !text-black">${limitString(application.jawaban, 45) || ''}</td>
            <td class="td-table !text-black">${limitString(application.alasan_ditolak) || '-'}</td>
            <td class="td-table !text-[#4189e0] font-bold !text-center"><a href="${restoreUrl}">Lihat</a></td>
        </tr>
    `);
});

    // Append pagination links
        $('.pagination-container-siswa').html(data.links);
        $('.pagination-container-siswa').show(); // pake yang atas uda cukup, ini ditambahin karna dibawah di hide ketika tidak ada data pada saat filtering. kalo ga ditambahin pas filtering dari ga ada data ke yang ada, pagination ikutan hilang
        $('#filterTable thead').show();
        $('.showMessage').hide(); // ini juga sama kaya pagination-container-siswa\
    } else {
        $('#filterTable thead').hide();
        $('#filterList').empty();
        $('.pagination-container-siswa').hide();
        $('.showMessage').show();
    }
}
    });
}

    bindPaginationLinks();

    $(document).ready(function() {
        // Ambil data yang berstatus_soal 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredDataRiwayatStudent('semua');
    });


    $('#statusFilterRiwayatStudent').on('change', function() {
        const status_soal = $(this).val();
        fetchFilteredDataRiwayatStudent(status_soal); // Call the function to fetch data based on status_soal
    });

    function bindPaginationLinks() {
        $('.pagination-container-siswa').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        const status_soal = $('#statusFilterRiwayatStudent').val(); // Dapatkan filter status_soal saat ini
        fetchFilteredDataRiwayatStudent(status_soal, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}
