function fetchFilteredData(status, page = 1) {
    $.ajax({
    url: '/filter',
    method: 'GET',
    data: {
        status: status,
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
    const limitString = (str, limit) => str.length > limit ? str.substring(0, limit) + '...' : str;

    $('#filterList').append(`
        <tr>
            <td>${index + 1}</td>
            <td>${limitString(application.pertanyaan, 5) || ''}</td>
            <td>${application.mapel || ''}</td>
            <td>${application.bab || ''}</td>
            <td>${createdAt}</td>
            <td>${updatedAt}</td>
            <td>${application.jawaban || ''}</td>
            <td>${application.status || ''}</td>
            <td><a href="${restoreUrl}">Lihat</a></td>
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
        // $('#filterList').append('Tidak ada riwayat pertanyaan');
        $('.pagination-container-siswa').hide();
        $('.showMessage').show();
    }
}
    });
}

    bindPaginationLinks();

    $(document).ready(function() {
        // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredData('semua');
    });


    $('#statusFilter').on('change', function() {
        const status = $(this).val();
        fetchFilteredData(status); // Call the function to fetch data based on status
    });

    function bindPaginationLinks() {
        $('.pagination-container-siswa').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        const status = $('#statusFilter').val(); // Dapatkan filter status saat ini
        fetchFilteredData(status, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}