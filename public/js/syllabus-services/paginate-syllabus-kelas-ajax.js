function paginateSyllabusKelas() {
    const container = document.getElementById('container-syllabus-kelas');
    if (!container) return;

    const kurikulumName = container.dataset.namaKurikulum;
    const kurikulumId = container.dataset.kurikulumId;
    const faseId = container.dataset.faseId;

    if (!kurikulumName) return;
    if (!kurikulumId) return;
    if (!faseId) return;

    fetchFilteredDataSyllabusKelas(kurikulumName, kurikulumId, faseId, 1);
    function fetchFilteredDataSyllabusKelas(nama_kurikulum, kurikulum_id, fase_id, page = 1) {
        $.ajax({
            url: `/paginate-syllabus-service-kelas/${kurikulumName}/${kurikulumId}/${faseId}`,
            method: 'GET',
            data: { page: page },
            success: function (data) {
                $('#tableListSyllabusKelas').empty();
                $('.pagination-container-syllabus-kelas').empty();

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

                        let mapelDetail = data.mapelDetail.replace(':nama_kurikulum', application.kurikulum?.nama_kurikulum).replace(':kurikulum_id', application.kurikulum_id).replace(':fase_id', application.fase_id).replace(':kelas_id', application.id);
                        let kelasUpdate = data.kelasUpdate.replace(':id', application.id);

                        $('#tableListSyllabusKelas').append(`
                            <tr class="text-xs">
                                <td class="border border-gray-300">${application.kelas ?? '-'}</td>
                                <td class="border text-center border-gray-300">
                                    <a href="${mapelDetail}" class="btn-mapel-detail" data-nama-kurikulum="${application.kurikulum?.nama_kurikulum}" data-kurikulum-id="${application.kurikulum_id}"
                                    data-fase-id="${application.fase_id}" data-kelas-id="${application.id}">
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
                                                    <a href="#" class="btn-edit-kelas" data-kurikulum-id="${application.kurikulum_id}" data-id="${application.id}" data-kelas="${application.kelas}">
                                                        <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                        Edit Kelas
                                                    </a>
                                                </li>
                                                </li>
                                                <li class="text-xs" onclick="historyKelas(this)"
                                                    data-nama_lengkap="${application.user_account?.office_profiles?.nama_lengkap}"
                                                    data-status="${application.user_account?.role}"
                                                    data-updated_at="${updatedAt}">
                                                    <a>
                                                        <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                        View History
                                                    </a>
                                                </li>
                                                <li class="text-xs">
                                                    <a href="#" class="btn-delete-kelas" data-id="${application.id}">
                                                        <i class="fa-solid fa-trash text-red-500"></i>
                                                        Delete Kelas
                                                    </a>
                                                </li>
                                            </ul>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });

                    // Insert pagination HTML
                    $('.pagination-container-syllabus-kelas').html(data.links);
                    // Bind click event ke link pagination yang baru
                    bindPaginationLinks(kurikulumName, kurikulumId, faseId);

                    $('#emptyMessageSyllabusKelas').hide();
                    $('.thead-table-syllabus-kelas').show();

                } else {
                    $('#emptyMessageSyllabusKelas').show();
                    $('.thead-table-syllabus-kelas').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('ajax error:', status, error);
            }
        });
    }
    function bindPaginationLinks(kurikulumName, kurikulumId, faseId) {
        $('.pagination-container-syllabus-kelas').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            fetchFilteredDataSyllabusKelas(kurikulumName, kurikulumId, faseId, page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
    }
}

$(document).ready(function () {
    const kurikulumName = $(this).data('nama-kurikulum');
    const kurikulumId = $(this).data('kurikulum-id');
    const faseId = $(this).data('fase-id');
    const kelasId = $(this).data('id');

    currentKurikulumId = kurikulumId;
    currentKurikulumName = kurikulumName;
    currentFaseId = faseId;
    currentKelasId = kelasId;

    // Ambil data semua saat halaman dimuat
    paginateSyllabusKelas();
});


// Event listener tombol "edit kelas" (open modal)
$(document).off('click', '.btn-edit-kelas').on('click', '.btn-edit-kelas', function(e) {
    e.preventDefault();

    const curiculumId = $(this).data('kurikulum-id');
    const faseId = $(this).data('fase-id');
    const kelasId = $(this).data('id');
    const kelas = $(this).data('kelas');

    // (Optional) set id ke form untuk submit
    $('#kelasForm').data('kurikulum-id', curiculumId);
    $('#kelasForm').data('fase-id', faseId);
    $('#kelasForm').data('id', kelasId);

    // Reset input
    // $('#kelas').val('');

    // Reset text error
    $('#error-kelas').text('');

    // Tampilkan modal
    const modal = document.getElementById('my_modal_1');
    if (modal) {
        $('#kelas').val(kelas);
        modal.showModal();
    }
});

$('#kelasForm').on('submit', function (e) {
    e.preventDefault();

    const curiculumId = $(this).data('kurikulum-id');
    const faseId = $(this).data('fase-id');
    const kelasId = $(this).data('id');
    const kelas = $('#kelas').val();

    // Kosongkan error sebelumnya
    $('#error-kelas').text('');

    $.ajax({
        url: `/syllabus/curiculum/kelas/update/${curiculumId}/${faseId}/${kelasId}`,
        method: 'POST',
        data: {
            kelas: kelas,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_1');
            if (modal) {
                modal.close();
            }

            $('#alert-success-update-data-kelas').html(
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
            paginateSyllabusKelas();

        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors && errors.kelas) {
                    $('#error-kelas').text(errors.kelas[0]);
                    $('#kelas').addClass('border-2 border-red-400');
                }
            }
        }
    });
});

// open modal history kelas
function historyKelas(element) {
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

// Event listener tombol "delete kelas" (open modal)
$(document).off('click', '.btn-delete-kelas').on('click', '.btn-delete-kelas', function(e) {
    e.preventDefault();

    const kelasId = $(this).data('id');

    // (Optional) set id ke form untuk submit
    $('#deleteKelasForm').data('id', kelasId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_3');
    if (modal) {
        modal.showModal();
    }
});

$('#deleteKelasForm').on('submit', function (e) {
    e.preventDefault();

    const kelasId = $(this).data('id');

    $.ajax({
        url: `/syllabus/curiculum/kelas/delete/${kelasId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_3');
            if (modal) {
                modal.close();

                $('#alert-success-delete-data-kelas').html(
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
                paginateSyllabusKelas();
            }
        },
    });
});
