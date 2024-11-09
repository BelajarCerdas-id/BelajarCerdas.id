function fetchFilteredDataCatatan(kelas_catatan, mapel, page = 1) {
        $.ajax({
            url: '/paginateCatatan',
            method: 'GET',
            data: {
                kelas_catatan: kelas_catatan,
                mapel: mapel,
                page: page // Include the page parameter
            },
            success: function(data) {
                $('#filterListNote').empty(); // Clear previous entries
                $('.pagination-container-catatan').empty(); // Clear previous pagination links

                if (data.data.length > 0) {
                    $.each(data.data, function(index, application) {
                        const formatDate = (dateString) => {
                            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',
                                'Sabtu'
                            ];
                            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei',
                                'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
                                'November', 'Desember'
                            ];

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

                        const createdAt = application.created_at ?
                            `${formatDate(application.created_at)}, ${timeFormatter.format(new Date(application.created_at))}` :
                            'Tanggal tidak tersedia';

                        const updatedAt = application.updated_at ?
                            `${formatDate(application.updated_at)}, ${timeFormatter.format(new Date(application.updated_at))}` :
                            'Tanggal tidak tersedia';

                        $('#filterListNote').append(`
                            <div class="bg-white border-4">
                            </div>
                        `);
                    });

                    // Append pagination links
                    $('.pagination-container-catatan').html(data.links);
                    $('.pagination-container-catatan')
                        .show(); // pake yang atas uda cukup, ini ditambahin karna dibawah di hide ketika tidak ada data pada saat filtering. kalo ga ditambahin pas filtering dari ga ada data ke yang ada, pagination ikutan hilang
                    $('.showMessage').hide(); // ini juga sama kaya pagination-container-siswa
                } else {
                    $('#filterListNote').empty();
                    // $('#filterList').append('Tidak ada riwayat pertanyaan');
                    $('.pagination-container-catatan').hide();
                    $('.showMessage').show();
                }
            }
        });
    }

    bindPaginationLinks();

    $(document).ready(function() {
        // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredDataCatatan('semua');
    });


    $('#kelasFilter').on('change', function() {
        const kelas_catatan = $(this).val();
        fetchFilteredDataCatatan(kelas_catatan); // Call the function to fetch data based on status
    });

    $('#mapelFilter').on('change', function() {
        const mapel = $(this).val();
        fetchFilteredDataCatatan(mapel); // Call the function to fetch data based on status
    });

function bindPaginationLinks() {
    $('.pagination-container-catatan').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        const kelas_catatan = $('#kelasFilter').val(); // Dapatkan filter status saat ini
        const mapel = $('#mapelFilter').$(this).val(); // Dapatkan filter status saat ini
        fetchFilteredDataCatatan(kelas_catatan, mapel, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// function bindPaginationLinks() {
//     $('.pagination-container-catatan').off('click', 'a').on('click', 'a', function (event) {
//         event.preventDefault(); // Cegah perilaku default link
//         const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
//         const mapel = $('#mapelFilter').val(); // Dapatkan filter status saat ini
//         fetchFilteredDataCatatan(mapel, page); // Ambil data yang difilter untuk halaman yang ditentukan
//     });
// }