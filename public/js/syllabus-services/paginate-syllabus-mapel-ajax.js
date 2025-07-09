function paginateSyllabusMapel() {
    const container = document.getElementById('container-syllabus-mapel');
    if (!container) return;

    const kurikulumName = container.dataset.namaKurikulum;
    const kurikulumId = container.dataset.kurikulumId;
    const faseId = container.dataset.faseId;
    const kelasId = container.dataset.kelasId;

    if (!kurikulumName) return;
    if (!kurikulumId) return;
    if (!faseId) return;
    if (!kelasId) return;

    fetchFilteredDataSyllabusMapel(kurikulumName, kurikulumId, faseId, kelasId, 1);

    function fetchFilteredDataSyllabusMapel(nama_kurikulum, kurikulum_id, fase_id, kelas_id, page = 1) {
        $.ajax({
            url: `/paginate-syllabus-service-mapel/${kurikulumName}/${kurikulumId}/${faseId}/${kelasId}`,
            method: 'GET',
            data: {page: page,},
            success: function (data) {
                $('#tableListSyllabusMapel').empty();
                $('.pagination-container-syllabus-mapel').empty();

                if (data.data.length > 0) {
                    // Render rows
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

                    const updatedAt = application.updated_at ? `${formatDate(application.updated_at)}, ${timeFormatter.format(new Date(application.updated_at))}` : 'Tanggal tidak tersedia';

                        let babDetail = data.babDetail.replace(':nama_kurikulum', application.kurikulum?.nama_kurikulum).replace(':kurikulum_id', application.kurikulum_id)
                                                        .replace(':fase_id', application.fase_id).replace(':kelas_id', application.kelas_id).replace(':mapel_id', application.id);
                        let mapelUpdate = data.mapelUpdate.replace(':id', application.id).replace(':kelas_id', application.kelas_id);

                        $('#tableListSyllabusMapel').append(`
                            <tr class="text-xs">
                                <td class="border border-gray-300">${application.mata_pelajaran ?? '-'}</td>
                                <td class="border text-center border-gray-300">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden peer toggle-mapel"
                                            data-id="${application.id}"
                                            ${application.status_mata_pelajaran === 'publish' ? 'checked' : ''} />
                                        <div
                                            class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors duration-300 ease-in-out">
                                        </div>
                                            <div
                                            class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 ease-in-out peer-checked:translate-x-2.5">
                                        </div>
                                    </label>
                                </td>
                                <td class="border text-center border-gray-300">
                                    <a href="${babDetail}" class="btn-bab-detail" data-nama-kurikulum="${application.kurikulum?.nama_kurikulum}" data-kurikulum-id="${application.kurikulum_id}"
                                    data-fase-id="${application.fase_id}" data-kelas-id="${application.kelas_id}" data-mapel-id="${application.id}">
                                        <div class="text-[#4189e0]">
                                            <span>Detail</span>
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </div>
                                    </a>
                                </td>
                                <td class="border text-center border-gray-300">
                                    <div class="dropdown dropdown-left">
                                        <div tabindex="0" role="button">
                                            <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                        </div>
                                            <ul tabindex="0"
                                                class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                                <li class="text-xs">
                                                    <a href="#" class="btn-edit-mapel" data-id="${application.id}" data-kelas-id="${application.kelas_id}" data-mapel="${application.mata_pelajaran}">
                                                        <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                        Edit Mata Pelajaran
                                                    </a>
                                                </li>
                                                <li class="text-xs" onclick="historyMapel(this)"
                                                    data-nama_lengkap="${application.user_account?.office_profiles?.nama_lengkap}"
                                                    data-status="${application.user_account?.role}"
                                                    data-updated_at="${updatedAt}">
                                                    <a>
                                                        <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                        View History
                                                    </a>
                                                </li>
                                                <li class="text-xs">
                                                    <a href="#" class="btn-delete-mapel" data-id="${application.id}">
                                                        <i class="fa-solid fa-trash text-red-500"></i>
                                                        Delete Mata Pelajaran
                                                    </a>
                                                </li>
                                            </ul>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });

                    // Insert pagination HTML
                    $('.pagination-container-syllabus-mapel').html(data.links);

                    // Bind click event ke link pagination yang baru
                    bindPaginationLinks(kurikulumName, kurikulumId, faseId, kelasId);

                    $('#emptyMessageSyllabusMapel').hide();
                    $('.thead-table-syllabus-mapel').show();
                } else {
                    $('#emptyMessageSyllabusMapel').show();
                    $('.thead-table-syllabus-mapel').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('ajax error:', status, error);
            }
        });
    }
    function bindPaginationLinks(kurikulumName, kurikulumId, faseId, kelasId) {
        $('.pagination-container-syllabus-mapel').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            fetchFilteredDataSyllabusMapel(kurikulumName, kurikulumId, faseId, kelasId, page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
    }
}

$(document).ready(function () {
    const kurikulumName = $(this).data('nama-kurikulum');
    const kurikulumId = $(this).data('kurikulum-id');
    const faseId = $(this).data('fase-id');
    const kelasId = $(this).data('kelas-id');
    const mapelId = $(this).data('id');

    currentKurikulumId = kurikulumId;
    currentKurikulumName = kurikulumName;
    currentFaseId = faseId;
    currentKelasId = kelasId;
    currentMapelId = mapelId;

    // Ambil data semua saat halaman dimuat
    paginateSyllabusMapel();

    $(document).on('change', '.toggle-mapel', function() {
        let id = $(this).data('id'); // Ambil ID mapel dari atribut data-id di checkbox
        let status = $(this).is(':checked') ? 'publish' : 'unpublish'; // Jika toggle ON maka publish, kalau OFF maka unpublish

        $.ajax({
            url: '/syllabus/curiculum/mapel/activate/' + id, // Endpoint ke server
            method: 'PUT', // Method HTTP PUT untuk update data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status_mata_pelajaran: status // Kirim status baru (publish/unpublish)
            },
            success: function(response) {
                    console.log(response
                        .message); // Kalau berhasil, tampilkan pesan ke console
                    // Bisa juga tambahkan notifikasi atau toast di sini
            },
            // error: function(xhr) {
            //     alert('Gagal mengubah status.');
            //     // Kalau gagal, toggle dikembalikan ke kondisi sebelumnya
            //     $(this).prop('checked', !$(this).is(':checked'));
            // }
            error: function(xhr) {
                alert('Gagal mengubah status.');
                checkbox.prop('checked', !checkbox.is(':checked')); // ← GUNAKAN INI
            }
            });
    });
});


// Event listener tombol "edit mapel" (open modal)
$(document).off('click', '.btn-edit-mapel').on('click', '.btn-edit-mapel', function(e) {
    e.preventDefault();

    const mapelId = $(this).data('id');
    const kelasId = $(this).data('kelas-id');
    const mapel = $(this).data('mapel');

    // set id ke form untuk submit
    $('#mapelForm').data('id', mapelId);
    $('#mapelForm').data('kelas-id', kelasId);

    // Reset input
    // $('#mata_pelajaran').val('');

    // Reset text error
    $('#error-mapel').text('');

    // Tampilkan modal
    const modal = document.getElementById('my_modal_1');
    if (modal) {
        $('#mata_pelajaran').val(mapel);
        modal.showModal();
    }
});

$('#mapelForm').on('submit', function (e) {
    e.preventDefault();

    const mapelId = $(this).data('id');
    const kelasId = $(this).data('kelas-id');
    const mapel = $('#mata_pelajaran').val();

    // Kosongkan error sebelumnya
    $('#error-mapel').text('');

    $.ajax({
        url: `/syllabus/curiculum/mapel/update/${mapelId}/${kelasId}`,
        method: 'POST',
        data: {
            mata_pelajaran: mapel,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_1');
            if (modal) {
                modal.close();

                $('#alert-success-update-data-mapel').html(
                    `
                    <div class=" w-full flex justify-center">
                        <div class="fixed z-[9999]">
                            <div id="alertSuccess"
                                class="relative top-[-45px] opacity-100 scale-90 bg-green-200 w-max p-3 flex items-center space-x-2 rounded-lg shadow-lg transition-all duration-300 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current text-green-600" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-green-600 text-sm">${response.message}</span>
                                <i class="fas fa-times cursor-pointer text-green-600" id="btnClose"></i>
                            </div>
                        </div>
                    </div>
                    `
                );

                setTimeout(function() {
                    document.getElementById('alertSuccess').remove();
                }, 3000);

                document.getElementById('btnClose').addEventListener('click', function () {
                    document.getElementById('alertSuccess').remove();
                });

                // Memanggil fungsi untuk memuat ulang data
                paginateSyllabusMapel();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors && errors.mata_pelajaran) {
                    $('#error-mapel').text(errors.mata_pelajaran[0]);
                    $('#mata_pelajaran').addClass('border-2 border-red-400');
                }
            }
        }
    });
});

// open modal history mapel
function historyMapel(element) {
    const modal = document.getElementById('my_modal_2');
    const namaLengkap = element.getAttribute('data-nama_lengkap');
    const status = element.getAttribute('data-status');
    const updatedAt = element.getAttribute('data-updated_at');

    document.getElementById('text-nama_lengkap').innerText = namaLengkap;
    document.getElementById('text-status').innerText = status;
    document.getElementById('text-updated_at').innerText = updatedAt;

    modal.showModal();
}

function closeModal() {
    const closeModal = document.getElementById('my_modal_3');
    closeModal.close();
}

// Event listener tombol "delete mapel" (open modal)
$(document).off('click', '.btn-delete-mapel').on('click', '.btn-delete-mapel', function(e) {
    e.preventDefault();

    const mapelId = $(this).data('id');

    // (Optional) set id ke form untuk submit
    $('#deleteMapelForm').data('id', mapelId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_3');
    if (modal) {
        modal.showModal();
    }
});

$('#deleteMapelForm').on('submit', function (e) {
    e.preventDefault();

    const mapelId = $(this).data('id');

    $.ajax({
        url: `/syllabus/curiculum/mapel/delete/${mapelId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_3');
            if (modal) {
                modal.close();

                $('#alert-success-delete-data-mapel').html(
                    `
                    <div class=" w-full flex justify-center">
                        <div class="fixed z-[9999]">
                            <div id="alertSuccess"
                                class="relative top-[-45px] opacity-100 scale-90 bg-green-200 w-max p-3 flex items-center space-x-2 rounded-lg shadow-lg transition-all duration-300 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current text-green-600" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-green-600 text-sm">${response.message}</span>
                                <i class="fas fa-times cursor-pointer text-green-600" id="btnClose"></i>
                            </div>
                        </div>
                    </div>
                    `
                );

                setTimeout(function() {
                    document.getElementById('alertSuccess').remove();
                }, 3000);

                document.getElementById('btnClose').addEventListener('click', function () {
                    document.getElementById('alertSuccess').remove();
                });

            // Memanggil fungsi untuk memuat ulang data
                paginateSyllabusMapel();
            }
        },
    });
});
