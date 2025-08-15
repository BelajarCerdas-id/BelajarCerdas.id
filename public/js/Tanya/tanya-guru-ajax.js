function fetchFilteredDataTanyaMentor(status, page = 1) {
    $.ajax({
    url: '/paginateTanyaTeacher',
    method: 'GET',
    data: {
        status: status,
        page: page // Include the page parameter
    },
        success: function (data) {
        $('#tableListTeacher').empty(); // Clear previous entries
        $('.pagination-container-tanya').empty(); // Clear previous pagination links

        if (data.data.length > 0) {

        $.each(data.data, function (index, application) {
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

    const nl2br = (text) => {
        if (!text) return '';
        return text.replace(/\n/g, '<br>');
    };

    const escapeHtml = (text) => {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    // ini untuk url yang js dengan html nya terpisah, route dilakukan di controller nya bukan di sini
    const restoreUrl = data.restoreUrl.replace(':id', application.id);
    const limitString = (str, limit) => str.length > limit ? str.substring(0, limit) + '...' : str;

    $('#tableListTeacher').append(`
        <tr class="text-xs">
            <td class="td-table !text-black !text-center">${index + 1}</td>
            <td class="td-table !text-black !text-center">${(application.student?.student_profiles?.nama_lengkap || '')}</td>
            <td class="td-table !text-black !text-center">${application.kelas?.kelas || ''}</td>{!! nl2br(e($tanya->pertanyaan)) !!}
            <td class="td-table !text-black">${nl2br(escapeHtml(application.pertanyaan))}</td>
            <td class="td-table !text-black !text-center">${application.mapel?.mata_pelajaran}</td>
            <td class="td-table !text-black !text-center">${application.bab?.nama_bab}</td>
            <td class="td-table !text-black !text-center">${createdAt}</td>
            <td class="td-table !text-black !text-center">
                ${
                    application.is_being_viewed
                        ? `<span class="text-gray-500 italic">Sedang Dilihat</span>`
                        : `<a href="${restoreUrl}" class="btn-lihat-soal" data-id="${application.id}">Lihat</a>`
                }
            </td>
        </tr>
    `);
});

    // Append pagination links
        $('.pagination-container-tanya').html(data.links);
        bindPaginationLinks();
        $('#emptyMessageTanyaTeacher').hide(); // sembunyikan pesan kosong
        $('.thead-table-tanya-teacher').show(); // Tampilkan tabel thead
    } else {
        $('#tableListTeacher').empty(); // Clear existing rows
        $('#emptyMessageTanyaTeacher').show(); // Tampilkan pesan kosong
        $('.thead-table-tanya-teacher').hide(); // sembunyikan tabel thead
    }
}
    });
}

    bindPaginationLinks();

    $(document).ready(function() {
        // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredDataTanyaMentor('semua');

        // Tangani klik tombol Lihat supaya update is_being_viewed dulu sebelum redirect
        $(document).on('click', '.btn-lihat-soal', function(e) {
            e.preventDefault();

            const soalId = $(this).data('id');
            const urlDetail = $(this).attr('href');
            const btn = $(this);

            btn.prop('disabled', true).text('Sedang Dilihat');

            $.ajax({
                url: `/tanya/${soalId}/mark-viewed`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    window.location.href = urlDetail;
                },

                error: function(xhr) {
                    // if (xhr.status === 409) {
                    //     alert('❌ Soal sedang dilihat oleh mentor lain.');
                    // } else {
                    //     alert('❌ Gagal menandai soal sebagai sedang dilihat.');
                    // }
                    btn.prop('disabled', false).text('Lihat');
                },
                // error: function() {
                //     alert('Gagal menandai soal sebagai sedang dilihat.');
                // }
            });
        });
    });


    $('#statusFilter').on('change', function() {
        const status = $(this).val();
        fetchFilteredDataTanyaMentor(status); // Call the function to fetch data based on status
    });

    function bindPaginationLinks() {
        $('.pagination-container-tanya').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            const status = $('#statusFilter').val(); // Dapatkan filter status saat ini
            fetchFilteredDataTanyaMentor(status, page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
    }



