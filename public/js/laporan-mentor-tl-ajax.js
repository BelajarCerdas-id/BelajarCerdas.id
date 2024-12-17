function fetchFilteredListMentor(page = 1) {
        $.ajax({
            url: '/paginateListMentor',
            method: 'GET',
            data: {
                page: page // Include the page parameter
            },
            success: function(data) {
                $('#filterListMentor').empty(); // Clear previous entries
                $('.pagination-container-listMentor').empty(); // Clear previous pagination links

                if (data.data.length > 0) {
                    $.each(data.data, function(index, application) {
                        
                        const url = data.url.replace(':id', application.id);
                        const totalTanya = data.countData[application.email] || 0;

                        $('#filterListMentor').append(`
                            <a href="${url}" data-id="${application.id}">
                            <div class="bg-white flex items-center rounded-xl shadow-lg h-24 cursor-pointer">
                                <div class="ml-4 flex items-center gap-3">
                                    <div>
                                        <i class="fas fa-circle-user text-4xl"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="flex items-center mb-1">
                                            <p class="pr-1 text-sm">Nama :</p>
                                            <p class="text-xs"> ${application.nama_lengkap} </p>
                                        </span>
                                        <span class="flex items-center mb-1">
                                            <p class="pr-1 text-sm">Sekolah :</p>
                                            <p class="text-xs"> ${application.sekolah} </p>
                                        </span>
                                        <div class="text-sm flex gap-2">
                                            <span class="flex items-center mb-1">
                                                <p class="pr-1 text-sm">Total Tanya :</p>
                                                <p class="text-xs"> ${totalTanya} </p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        `);
                    });

                    // Append pagination links
                    $('.pagination-container-listMentor').html(data.links);
                    $('.pagination-container-listMentor')
                        .show(); // pake yang atas uda cukup, ini ditambahin karna dibawah di hide ketika tidak ada data pada saat filtering. kalo ga ditambahin pas filtering dari ga ada data ke yang ada, pagination ikutan hilang
                    $('.showMessage').hide(); // ini juga sama kaya pagination-container-siswa
                } else {
                    $('#filterListMentor').empty();
                    // $('#filterList').append('Tidak ada riwayat pertanyaan');
                    $('.pagination-container-listMentor').hide();
                    $('.showMessage').show();
                    $('#filterListMentor').hide();
                }
            }
        });
    }

    bindPaginationLinks();

    $(document).ready(function() {
        // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
        fetchFilteredListMentor('semua');
    });

    function bindPaginationLinks() {
        $('.pagination-container-listMentor').off('click', 'a').on('click', 'a', function (event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            fetchFilteredListMentor(page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
}