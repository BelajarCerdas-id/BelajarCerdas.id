function fetchFilteredDataMapel(mapel, page = 1) {
        $.ajax({
            url: '/paginateMapelCatatan',
            method: 'GET',
            data: {
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
                            <div class="bg-white border-[1px] h-28 w-[355px] relative flex flex-col">
                                <div class="ml-4 flex">
                                    <div class="flex items-center gap-4"
                                        onclick="showImage('{{ asset('images_catatan/' . $item->image_catatan) }}')">
                                        <img src="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                                            class="h-20 w-[100px] bg-cover bg-fit cursor-pointer" onclick="showData(this)"
                                            data-image="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                                            data-catatan ="${application.catatan}" 
                                    </div>
                                    <div class="flex flex-col leading-6 text-xs">
                                        <span>${application.kelas_catatan}</span>
                                        <span>${application.mapel}</span>
                                        <span>${application.bab}</span>
                                        <div class="flex items-center gap-2 mt-2">
                                            <i class="fas fa-user text-[--color-default]"></i>
                                            <span class="text-sm">${application.nama_lengkap}</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right absolute right-4 text-[--color-default] font-bold cursor-pointer"
                                                onclick="showData(this)"
                                                data-image ="${application.image_catatan ? '/images_catatan/' + application.image_catatan : ''}"
                                                data-catatan ="${application.catatan}"></i>
                                </div>
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
                    $('#filterListNote').hide();
                }
            }
        });
    }

    bindPaginationLinks();

    $(document).ready(function() {
        // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredDataMapel('semua');
    });


    $('#mapelFilter').on('change', function() {
        const mapel = $(this).val();
        fetchFilteredDataMapel(mapel); // Call the function to fetch data based on status
    });

    function bindPaginationLinks() {
        $('.pagination-container-catatan').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            const mapel = $('#mapelFilter').val(); // Dapatkan filter status saat ini
            fetchFilteredDataMapel(mapel, page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
}