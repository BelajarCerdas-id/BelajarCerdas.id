function paginateSyllabusFase() {
    const container = document.getElementById('container-syllabus-fase');
    if (!container) return;

    const kurikulumName = container.dataset.namaKurikulum;
    const kurikulumId = container.dataset.kurikulumId;

    if (!kurikulumName) return;
    if (!kurikulumId) return;

    fetchFilteredDataSyllabusFase(kurikulumName, kurikulumId, 1);

    function fetchFilteredDataSyllabusFase(kurikulumName, kurikulumId, page = 1) {
        $.ajax({
            url: `/paginate-syllabus-service-fase/${kurikulumName}/${kurikulumId}`,
            method: 'GET',
            data: { page: page },
            success: function (data) {
                $('#tableListSyllabusFase').empty();
                $('.pagination-container-syllabus-fase').empty();

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

                        let kelasDetail = data.kelasDetail.replace(':nama_kurikulum', application.kurikulum?.nama_kurikulum).replace(':kurikulum_id', application.kurikulum_id).replace(':fase_id', application.id);
                        let faseUpdate = data.faseUpdate.replace(':id', application.id);

                        $('#tableListSyllabusFase').append(`
                            <tr class="text-xs">
                                <td class="border border-gray-300">${application.nama_fase ?? '-'}</td>
                                <td class="border text-center border-gray-300">
                                    <a href="${kelasDetail}" class="btn-kelas-detail" data-id="${application.id}" data-nama-kurikulum="${application.kurikulum?.nama_kurikulum}"
                                    data-kurikulum-id="${application.kurikulum_id}">
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
                                                class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm  z-[9999]">
                                                <li class="text-xs">
                                                    <a href="#" class="btn-edit-fase" data-kurikulum-id="${application.kurikulum_id}" data-id="${application.id}" data-nama-fase="${application.nama_fase}">
                                                        <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                        Edit Fase
                                                    </a>
                                                </li>
                                                </li>
                                                <li class="text-xs" onclick="historyFase(this)"
                                                    data-nama_lengkap="${application.user_account?.office_profiles?.nama_lengkap}"
                                                    data-status="${application.user_account?.role}"
                                                    data-updated_at="${updatedAt}">
                                                    <a>
                                                        <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                        View History
                                                    </a>
                                                </li>
                                                <li class="text-xs">
                                                    <a href="#" class="btn-delete-fase" data-id="${application.id}">
                                                        <i class="fa-solid fa-trash text-red-500"></i>
                                                        Delete Fase
                                                    </a>
                                                </li>
                                            </ul>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });

                    // Insert pagination HTML
                    $('.pagination-container-syllabus-fase').html(data.links).show();


                    $('#emptyMessageSyllabusFase').hide();
                    $('.thead-table-syllabus-fase').show();

                    // Bind click event ke link pagination yang baru
                    bindPaginationLinks(kurikulumName, kurikulumId);
                } else {
                    $('#emptyMessageSyllabusFase').show();
                    $('.thead-table-syllabus-fase').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('ajax error:', status, error);
            }
        });
    }
    function bindPaginationLinks(kurikulumName, kurikulumId) {
        $('.pagination-container-syllabus-fase').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            fetchFilteredDataSyllabusFase(kurikulumName, kurikulumId, page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
    }
}

$(document).ready(function () {
    const kurikulumName = $(this).data('nama-kurikulum');
    const kurikulumId = $(this).data('kurikulum-id');
    const faseId = $(this).data('id');

    currentKurikulumName = kurikulumName;
    currentKurikulumId = kurikulumId;
    currentFaseId = faseId;

    // Ambil semua saat halaman dimuat
    paginateSyllabusFase();
});

// Event listener tombol "edit fase" (open modal)
$(document).off('click', '.btn-edit-fase').on('click', '.btn-edit-fase', function(e) {
    e.preventDefault();

    const curiculumId = $(this).data('kurikulum-id');
    const faseId = $(this).data('id');
    const faseName = $(this).data('nama-fase');

    // (Optional) set id ke form untuk submit
    $('#faseForm').data('kurikulum-id', curiculumId);
    $('#faseForm').data('id', faseId);

    // Reset input
    // $('#nama_fase').val('');

    // Reset text error
    $('#error-nama-fase').text('');

    // Tampilkan modal
    const modal = document.getElementById('my_modal_1');
    if (modal) {
        $('#nama_fase').val(faseName);
        modal.showModal();
    }
});

$('#faseForm').on('submit', function (e) {
    e.preventDefault();

    const curiculumId = $(this).data('kurikulum-id');
    const faseId = $(this).data('id');
    const faseName = $('#nama_fase').val();

    // Kosongkan error sebelumnya
    $('#error-nama-fase').text('');

    $.ajax({
        url: `/syllabus/curiculum/fase/update/${curiculumId}/${faseId}`,
        method: 'POST',
        data: {
            nama_fase: faseName,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_1');
            if (modal) {
                modal.close();

                $('#alert-success-update-data-fase').html(
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
                paginateSyllabusFase();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors && errors.nama_fase) {
                    $('#error-nama-fase').text(errors.nama_fase[0]);
                    $('#nama_fase').addClass('border-2 border-red-400');
                }
            }
        }
    });
});

// open modal history fase
function historyFase(element) {
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

// Event listener tombol "delete kurikulum" (open modal)
$(document).off('click', '.btn-delete-fase').on('click', '.btn-delete-fase', function(e) {
    e.preventDefault();

    const faseId = $(this).data('id');

    // (Optional) set id ke form untuk submit
    $('#deleteFaseForm').data('id', faseId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_3');
    if (modal) {
        modal.showModal();
    }
});

$('#deleteFaseForm').on('submit', function (e) {
    e.preventDefault();

    const faseId = $(this).data('id');

    $.ajax({
        url: `/syllabus/curiculum/fase/delete/${faseId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_3');
            if (modal) {
                modal.close();

                $('#alert-success-delete-data-fase').html(
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
                paginateSyllabusFase();
            }
        },
    });
});

