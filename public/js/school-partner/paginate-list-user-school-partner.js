function paginateUserSchoolPartner(search_student, page = 1) {
    const container = document.getElementById('container-user-school-partner-list');
    if (!container) return;

    const schoolId = container.dataset.schoolId;
    if (!schoolId) return;

    fetchFilteredUserSchoolPartner(search_student, schoolId, page);

    function fetchFilteredUserSchoolPartner(search_student, schoolId, page = 1) {
        $.ajax({
            url: `/list-user-school-subscription/paginate/${schoolId}`,
            method: 'GET',
            data: {
                search_student: search_student,
                page: page
            },
            success: function (response) {
                $('#tbody-user-school-partner-list').empty();
                $('.pagination-container-user-school-partner-list').empty();

                const schoolIdentity = response.schoolIdentity;

                // tampilkan identitas sekolah
                $('#school-identity').html([
                    `
                        <div class="bg-white shadow-lg rounded-md p-4 h-40 max-w-2xl border">
                            <div class="flex items-center gap-4">
                                <i class="fa-solid fa-school text-xl bg-[#4189E0] p-4 text-white rounded-full"></i>
                                <div class="flex flex-col" gap-4>
                                    <span class="font-bold opacity-70">${schoolIdentity.nama_sekolah}</span>
                                    <span class="font-bold opacity-70 text-sm">NPSN: ${schoolIdentity.npsn}</span>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-col gap-2">
                                <span class="font-bold opacity-70 text-sm">Kepala Sekolah: ${schoolIdentity.nama_kepsek}</span>
                                <span class="font-bold opacity-70 text-sm">NIK: ${schoolIdentity.nik_kepsek}</span>
                            </div>
                        </div>
                    `
                ]);

                if (response.data.length > 0) {
                    $.each(response.data, function (index, items) {
                        const group = items[0];
                        const formatDate = (dateString) => {
                            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September','Oktober', 'November', 'Desember'];

                            const date = new Date(dateString);
                            const day = date.getDate();
                            const monthName = months[date.getMonth()];
                            const year = date.getFullYear();

                            return `${day}-${monthName}-${year}`;
                        };

                        let featureCells = '';

                        items.forEach(item => {
                            const startDate = item.start_date ? formatDate(item.start_date) : 'Tanggal tidak tersedia';
                            const endDate = item.end_date ? formatDate(item.end_date) : 'Tanggal tidak tersedia';

                            featureCells += `
                                <td class="td-table !text-black !text-center">${startDate} - ${endDate}</td>
                                <td class="td-table text-center w-[15%]">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden peer toggle-active-features-user-school-partner"
                                            data-subscription-id="${item.id}"
                                            ${item.subscription_status === 'aktif' ? 'checked' : ''} />
                                        <div class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors duration-300 ease-in-out"></div>
                                        <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 ease-in-out peer-checked:translate-x-2.5"></div>
                                    </label>
                                </td>
                            `;
                        });

                        $('#dropdown-features-access-control').empty(); // kosongkan dulu

                        // tampilkan dropdown kontrol akses fitur
                        items.forEach(item => {
                            $('#dropdown-features-access-control').append([
                                `
                                    <li>
                                        <a class="hover:bg-transparent !cursor-default">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden peer toggle-active-features-all-user-school-partner"
                                                data-school-id="${schoolIdentity.nama_sekolah}" data-feature-id="${item.transactions?.features?.id}"
                                                ${item.subscription_status === 'aktif' ? 'checked' : ''} />
                                                <div class="w-9 h-4 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors duration-300 ease-in-out"></div>
                                                <div class="absolute left-0.4 top-0.1 w-4 h-4 bg-white rounded-full shadow-md transition-transform duration-300 ease-in-out peer-checked:translate-x-2.5"></div>
                                            </label>
                                            <span class="text-xs">${item.transactions?.features?.nama_fitur}</span>
                                        </a>
                                    </li>
                                `
                            ])
                        })

                        $('#tbody-user-school-partner-list').append(`
                            <tr>
                                <td class="td-table !text-black !text-center">${index + 1}</td>
                                <td class="td-table !text-black">${group.user_account?.student_profiles?.nama_lengkap}</td>
                                <td class="td-table !text-black !text-center">${group.user_account?.student_profiles?.fase?.nama_fase}</td>
                                <td class="td-table !text-black !text-center">${group.user_account?.student_profiles?.kelas?.kelas}</td>
                                ${featureCells}
                            </tr>
                        `);
                    });

                        $('.pagination-container-user-school-partner-list').html(response.links);
                        bindPaginationLinks();
                        $('#empty-message-user-school-partner-list').hide(); // sembunyikan pesan kosong
                        $('.thead-table-user-school-partner-list').show(); // Tampilkan tabel thead
                        $('#dropdown-features-access-control').show(); // tampikan list dropdown
                    } else {
                        $('#tbody-user-school-partner-list').empty(); // Clear existing rows
                        $('.thead-table-user-school-partner-list').hide(); // Sembunyikan tabel thead
                        $('#empty-message-user-school-partner-list').show(); // Tampilkan pesan kosong
                        $('#dropdown-features-access-control').hide(); // sembunyikan list dropdown
                }
            }
        });
    }
}


$(document).ready(function () {
    paginateUserSchoolPartner();
})

// Fungsi untuk memfilter data berdasarkan search_student (pakai on input karena ketika data yang user cari akan munul tanpa di enter atau apapun by click)
$('#search_student').on('input', function() {
    const search_student = $(this).val();
    paginateUserSchoolPartner(search_student); // Call the function to fetch data based on search_student
});


function bindPaginationLinks() {
    $('.pagination-container-user-school-partner-list').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        const search_student = $('#search_student').val();
        paginateUserSchoolPartner(search_student, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// features action (aktifkan, non-aktifkan fitur by student)
$(document).ready(function () {
    // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
    paginateUserSchoolPartner();

    $(document).on('change', '.toggle-active-features-user-school-partner', function () {
        let subscriptionId = $(this).data('subscription-id'); // Ambil subscription id dari atribut data-id di checkbox
        let status = $(this).is(':checked') ? 'aktif' : 'tidak_aktif'; // Jika toggle ON maka aktif, kalau OFF maka tidak_aktif

        $.ajax({
            url: `/school-subscription/activate/${subscriptionId}/user`, // Endpoint ke server
            method: 'PUT', // Method HTTP PUT untuk update data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                subscription_status: status // Kirim status baru (aktif / non aktif)
            },
            success: function (response) {
                // inisialisasi update data terbaru setelah berhasil insert data
                paginateUserSchoolPartner();
            },
            error: function (xhr) {
                alert('Gagal mengubah status.');
                checkbox.prop('checked', !checkbox.is(':checked')); // ← GUNAKAN INI
            }
        });
    });
});


// features action (aktifkan, non-aktifkan fitur all students)
$(document).ready(function () {
    // Ambil data yang berstatus 'semua' saat halaman dimuat (jadi ini menampilkan semua data tanpa filter)
    paginateUserSchoolPartner();

    $(document).on('change', '.toggle-active-features-all-user-school-partner', function () {
        let schoolId = $(this).data('school-id'); // Ambil school id dari atribut data-id di checkbox
        let featureId = $(this).data('feature-id'); // Ambil feature id dari atribut data-id di checkbox
        let status = $(this).is(':checked') ? 'aktif' : 'tidak_aktif'; // Jika toggle ON maka aktif, kalau OFF maka tidak_aktif

        $.ajax({
            url: `/school-subscription/activate/${schoolId}/${featureId}`, // Endpoint ke server
            method: 'PUT', // Method HTTP PUT untuk update data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                subscription_status: status // Kirim status baru (aktif / non aktif)
            },
            success: function (response) {
                // inisialisasi update data terbaru setelah berhasil insert data
                paginateUserSchoolPartner();
            },
            error: function (xhr) {
                alert('Gagal mengubah status.');
                checkbox.prop('checked', !checkbox.is(':checked')); // ← GUNAKAN INI
            }
        });
    });
});
