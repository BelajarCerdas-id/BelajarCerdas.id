function fetchFilteredDataTanyaRollback(page = 1) {
    $.ajax({
    url: '/paginateTanyaRollback',
    method: 'GET',
    data: {
        page: page // Include the page parameter
    },
        success: function (data) {
        $('#tableListTanyaRollback').empty(); // Clear previous entries
        $('.pagination-container-tanya-rollback').empty(); // Clear previous pagination links

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
            const limitString = (str, limit) => str.length > limit ? str.substring(0, limit) + '...' : str;
            const updateIsBeingViewed = data.updateIsBeingViewed.replace(':id', application.id);

        $('#tableListTanyaRollback').append(`
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
                        : `-`
                    }
                </td>
                <td class="td-table !text-black !text-center">
                    ${
                        application.is_being_viewed
                        ? `${application.viewed_by?.mentor_profiles?.nama_lengkap} <br>
                            ${application.viewed_by?.no_hp}
                        `
                        : `-`
                    }
                </td>
                <td class="td-table !text-black !text-center">
                    ${
                        application.is_being_viewed
                        ? `<a href="${updateIsBeingViewed}" class="btn-rollback-question text-blue-500" data-id="${application.id}">Kembalikan ke antrean</a>`
                        : `-`
                    }
                </td>
            </tr>
        `);
    });

    // Append pagination links
        $('.pagination-container-tanya-rollback').html(data.links);
        bindPaginationLinks();
        $('#emptyMessageTanyaRollback').hide(); // sembunyikan pesan kosong
        $('.thead-table-tanya-rollback').show(); // Tampilkan tabel thead
    } else {
        $('#tableListTanyaRollback').empty(); // Clear existing rows
        $('#emptyMessageTanyaRollback').show(); // Tampilkan pesan kosong
        $('.thead-table-tanya-rollback').hide(); // sembunyikan tabel thead
    }
}
    });
}

    bindPaginationLinks();

    $(document).ready(function() {
        // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredDataTanyaRollback();

        $(document).on('click', '.btn-rollback-question', function(e) {
            e.preventDefault();

            const soalId = $(this).data('id');
            const urlDetail = $(this).attr('href');
            const btn = $(this);

            $.ajax({
                url: `/paginateTanyaRollback/update/${soalId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    fetchFilteredDataTanyaRollback();
                },
                error: function (xhr) {
                    '';
                }
            });
        });
    });


    function bindPaginationLinks() {
        $('.pagination-container-tanya-rollback').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            fetchFilteredDataTanyaRollback(page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
    }



