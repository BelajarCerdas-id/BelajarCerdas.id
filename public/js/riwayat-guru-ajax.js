function fetchFilteredDataRiwayat(status, page = 1) {
    $.ajax({
    url: '/filterTeacher',
    method: 'GET',
    data: {
        status: status,
        page: page // Include the page parameter
    },
    success: function(data) {
        $('#filterListTeacher').empty(); // Clear previous entries
        $('.pagination-container').empty(); // Clear previous pagination links

    if (data.data.length > 0) {
        $.each(data.data, function(index, application) {
        const formatDate = (dateString) => {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

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

    $('#filterListTeacher').append(`
        <tr class="text-xs">
            <td>${index + 1}</td>
            <td>${(application.nama_lengkap || '')}</td>
            <td>${application.kelas || ''}</td>
            <td>${limitString(application.pertanyaan, 5) || ''}</td>
            <td>${application.mapel}</td>
            <td>${application.bab}</td>
            <td>${createdAt}</td>
            <td>${updatedAt }</td>
            <td>${application.jawaban || ''}</td>
            <td>${application.status || ''}</td>
            <td><a href="${restoreUrl}">Lihat</a></td>
        </tr>
    `);
});

    // Append pagination links
        $('.pagination-container-riwayat').html(data.links);
        $('.pagination-container-riwayat').show();
        $('#filterTableTeacher thead').show();
        $('.emptyMessage').hide();
    } else {
        $('#filterTableTeacher thead').hide(); // hide thead table
        $('#filterListTeacher').empty(); // Clear existing rows
        $('.emptyMessage').show(); // Tampilkan pesan kosong
        $('.pagination-container-riwayat').hide();
    }
}
    });
}

    bindPaginationLinks();

    $(document).ready(function() {
        // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredDataRiwayat('semua');
    });


    $('#statusFilter').on('change', function() {
        const status = $(this).val();
        fetchFilteredDataRiwayat(status); // Call the function to fetch data based on status
    });

    function bindPaginationLinks() {
        $('.pagination-container-riwayat').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            const status = $('#statusFilter').val(); // Dapatkan filter status saat ini
            fetchFilteredDataRiwayat(status, page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
    }