function fetchFilteredDataTanya(status, page = 1) {
    $.ajax({
    url: '/paginateTanyaTeacher',
    method: 'GET',
    data: {
        status: status,
        page: page // Include the page parameter
    },
    success: function(data) {
        $('#tableListTeacher').empty(); // Clear previous entries
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

    // ini untuk url jika js didalam file yang sama dengan html
    // const restoreUrl = `{{ route('getRestore.edit', ':id') }}`.replace(':id',
    //     application.id);

    // ini untuk url yang js dengan html nya terpisah, route dilakukan di controller nya bukan di sini
    const restoreUrl = data.restoreUrl.replace(':id', application.id);
    const limitString = (str, limit) => str.length > limit ? str.substring(0, limit) + '...' : str;

    $('#tableListTeacher').append(`
        <tr class="text-xs">
            <td>${index + 1}</td>
            <td>${(application.nama_lengkap || '')}</td>
            <td>${application.kelas || ''}</td>
            <td>${limitString(application.pertanyaan, 5) || ''}</td>
            <td>${application.mapel}</td>
            <td>${application.bab}</td>
            <td>${createdAt}</td>
            <td><a href="${restoreUrl}">Lihat</a></td>
        </tr>
    `);
});

    // Append pagination links
        $('.pagination-container-tanya').html(data.links);
                    
    } else {
        $('#tableTanyaTeacher thead').hide(); // hide thead table
        $('#tableListTeacher').empty(); // Clear existing rows
        $('#emptyMessage').show(); // Tampilkan pesan kosong
    }
}
    });
}
    
    bindPaginationLinks();

    $(document).ready(function() {
        // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredDataTanya('semua');
    });


    $('#statusFilter').on('change', function() {
        const status = $(this).val();
        fetchFilteredDataTanya(status); // Call the function to fetch data based on status
    });

    function bindPaginationLinks() {
        $('.pagination-container-tanya').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            const status = $('#statusFilter').val(); // Dapatkan filter status saat ini
            fetchFilteredDataTanya(status, page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
    }