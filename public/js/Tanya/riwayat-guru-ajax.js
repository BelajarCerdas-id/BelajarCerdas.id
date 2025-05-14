function fetchFilteredDataRiwayatMentor(status_soal, page = 1) {
    $.ajax({
    url: '/filterTeacher',
    method: 'GET',
    data: {
        status_soal: status_soal,
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
    const limitString = (str, limit) => (str ?(str.length > limit ? str.substring(0, limit) + '...' : str) : '-');

    $('#filterListTeacher').append(`
        <tr class="text-xs">
            <td class="td-table !text-black !text-center">${index + 1}</td>
            <td class="td-table !text-black !text-center">${(application.student?.student_profiles?.nama_lengkap || '')}</td>
            <td class="td-table !text-black !text-center">${application.kelas?.kelas || ''}</td>
            <td class="td-table !text-black">${limitString(application.pertanyaan, 45) || ''}</td>
            <td class="td-table !text-black !text-center">${application.mapel?.mata_pelajaran || ''}</td>
            <td class="td-table !text-black !text-center">${application.bab?.nama_bab || ''}</td>
            <td class="td-table !text-black !text-center">${createdAt}</td>
            <td class="td-table !text-black !text-center">${updatedAt }</td>
            <td class="td-table !text-black !text-center">${application.status_soal || ''}</td>
            <td class="td-table !text-black">${limitString(application.jawaban, 45) || ''}</td>
            <td class="td-table !text-black">${limitString(application.alasan_ditolak) || ''}</td>
            <td class="td-table !text-black !text-center"><a href="${restoreUrl}">Lihat</a></td>
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
        // Ambil data yang berstatus_soal 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredDataRiwayatMentor('semua');
    });


    $('#statusFilterRiwayatMentor').on('change', function() {
        const status_soal = $(this).val();
        fetchFilteredDataRiwayatMentor(status_soal); // Call the function to fetch data based on status_soal
    });

    function bindPaginationLinks() {
        $('.pagination-container-riwayat').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            const status_soal = $('#statusFilterRiwayatMentor').val(); // Dapatkan filter status_soal saat ini
            fetchFilteredDataRiwayatMentor(status_soal, page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
    }
