function paginateSyllabusBab() {
    const container = document.getElementById('container-syllabus-bab');
    if (!container) return;

    const kurikulumName = container.dataset.namaKurikulum;
    const kurikulumId = container.dataset.kurikulumId;
    const faseId = container.dataset.faseId;
    const kelasId = container.dataset.kelasId;
    const mapelId = container.dataset.mapelId;

    if (!kurikulumName) return;
    if (!kurikulumId) return;
    if (!faseId) return;
    if (!kelasId) return;
    if (!mapelId) return;

    fetchFilteredDataSyllabusBab(kurikulumName, kurikulumId, faseId, kelasId, mapelId, 1);

    function fetchFilteredDataSyllabusBab(nama_kurikulum, kurikulum_id, fase_id, kelas_id, mapel_id, page = 1) {
        $.ajax({
            url: `/paginate-syllabus-service-bab/${kurikulumName}/${kurikulumId}/${faseId}/${kelasId}/${mapelId}`,
            method: 'GET',
            data: {page: page,},
            success: function (data) {
                $('#tableListSyllabusBab').empty();
                $('.pagination-container-syllabus-bab').empty();

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

                        let subBabDetail = data.subBabDetail.replace(':nama_kurikulum', application.kurikulum?.nama_kurikulum).replace(':kurikulum_id', application.kurikulum_id)
                                            .replace(':fase_id', application.fase_id).replace(':kelas_id', application.kelas_id).replace(':mapel_id', application.mapel_id).replace(':bab_id', application.id);
                        let babUpdate = data.babUpdate.replace(':kurikulum_id', application.kurikulum_id).replace(':kelas_id', application.kelas_id).replace(':mapel_id', application.mapel_id)
                                            .replace(':id', application.id);

                        let featureCheckboxesBab = '';

                        $.each(data.dataFeaturesRoles, function (fIndex, featureItem) {
                            const babId = application.id;
                            const featureId = featureItem.feature_id;

                            const status = data.statusBabFeature?.[babId]?.[featureId] ?? null;
                            const isChecked = status === 'publish' ? 'checked' : '';

                            featureCheckboxesBab += `
                            <td class="border text-center border-gray-300">
                                <input type="checkbox"
                                    class="w-4 h-4 cursor-pointer accent-green-600 checkbox-bab"
                                    data-id-bab="${babId}"
                                    data-feature="${featureId}"
                                    ${isChecked}>
                            </td>
                            `;
                        });

                        $('#tableListSyllabusBab').append(`
                            <tr class="text-xs">
                                <td class="border border-gray-300">${application.nama_bab}</td>
                                <td class="border text-center border-gray-300">
                                    <a href="${subBabDetail}" class="btn-subBab-detail" data-nama-kurikulum="${application.kurikulum?.nama_kurikulum}" data-kurikulum-id="${application.kurikulum_id}"
                                    data-fase-id="${application.fase_id}" data-kelas-id="${application.kelas_id}" data-mapel-id="${application.mapel_id}" data-bab-id="${application.id}">
                                        <div class="text-[#4189e0]">
                                            <span>Detail</span>
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </div>
                                    </a>
                                </td>
                                ${featureCheckboxesBab}
                                <td class="border text-center border-gray-300">
                                    <div class="dropdown dropdown-left">
                                        <div tabindex="0" role="button">
                                            <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                        </div>
                                            <ul tabindex="0"
                                                class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                                <li class="text-xs">
                                                    <a href="#" class="btn-edit-bab" data-kurikulum-id="${application.kurikulum_id}" data-kelas-id="${application.kelas_id}"
                                                        data-mapel-id="${application.mapel_id}" data-id="${application.id}" data-nama-bab="${application.nama_bab}">
                                                        <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                        Edit Bab
                                                    </a>
                                                </li>
                                                </li>
                                                <li class="text-xs" onclick="historyBab(this)"
                                                    data-nama_lengkap="${application.user_account?.office_profiles?.nama_lengkap}"
                                                    data-status="${application.user_account?.role}"
                                                    data-updated_at="${updatedAt}">
                                                    <a>
                                                        <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                        View History
                                                    </a>
                                                </li>
                                                <li class="text-xs">
                                                    <a href="#" class="btn-delete-bab" data-id="${application.id}">
                                                        <i class="fa-solid fa-trash text-red-500"></i>
                                                        Delete Bab
                                                    </a>
                                                </li>
                                            </ul>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });

                    // Insert pagination HTML
                    $('.pagination-container-syllabus-bab').html(data.links);

                    // Bind click event ke link pagination yang baru
                    bindPaginationLinks(kurikulumName, kurikulumId, faseId, kelasId, mapelId);

                    $('#emptyMessageSyllabusBab').hide();
                    $('.thead-table-syllabus-bab').show();
                } else {
                    $('#emptyMessageSyllabusBab').show();
                    $('.thead-table-syllabus-bab').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('ajax error:', status, error);
            }
        });
    }
    function bindPaginationLinks(kurikulumName, kurikulumId, faseId, kelasId, mapelId) {
        $('.pagination-container-syllabus-bab').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault(); // Cegah perilaku default link
            const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
            fetchFilteredDataSyllabusBab(kurikulumName, kurikulumId, faseId, kelasId, mapelId, page); // Ambil data yang difilter untuk halaman yang ditentukan
        });
    }
}

$(document).ready(function () {
    const kurikulumName = $(this).data('nama-kurikulum');
    const kurikulumId = $(this).data('kurikulum-id');
    const faseId = $(this).data('fase-id');
    const kelasId = $(this).data('kelas-id');
    const mapelId = $(this).data('mapel-id');
    const babId = $(this).data('bab-id');

    currentKurikulumId = kurikulumId;
    currentKurikulumName = kurikulumName;
    currentFaseId = faseId;
    currentKelasId = kelasId;
    currentMapelId = mapelId;
    currentBabId = babId;

    // Ambil data semua saat halaman dimuat
    paginateSyllabusBab();

        $(document).on('change', '.checkbox-bab', function() {
            let id = $(this).data('id-bab'); // bab_id
            let feature_id = $(this).data('feature'); // feature_id
            let status_bab = $(this).is(':checked') ? 'publish' : 'unpublish';
            let csrf = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/syllabus/curiculum/bab/activate/' + id,
                type: 'PUT',
                data: {
                    _token: csrf,
                    status_bab: status_bab,
                    feature_id: feature_id // Kirim feature_id ke server
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(xhr) {
                    alert('Gagal mengubah status.');
                    $(this).prop('checked', !$(this).is(':checked'));
                }
            });
        });
});

// Event listener tombol "edit mapel" (open modal)
$(document).off('click', '.btn-edit-bab').on('click', '.btn-edit-bab', function(e) {
    e.preventDefault();

    const curiculumId = $(this).data('kurikulum-id');
    const kelasId = $(this).data('kelas-id');
    const mapelId = $(this).data('mapel-id');
    const babId = $(this).data('id');
    const babName = $(this).data('nama-bab');


    // set id ke form untuk submit
    $('#babForm').data('kurikulum-id', curiculumId);
    $('#babForm').data('kelas-id', kelasId);
    $('#babForm').data('mapel-id', mapelId);
    $('#babForm').data('id', babId);

    // Reset input
    // $('#mata_pelajaran').val('');

    // Reset text error
    $('#error-bab').text('');

    // Tampilkan modal
    const modal = document.getElementById('my_modal_1');
    if (modal) {
        $('#nama_bab').val(babName);
        modal.showModal();
    }
});

$('#babForm').on('submit', function (e) {
    e.preventDefault();

    const curiculumId = $(this).data('kurikulum-id');
    const kelasId = $(this).data('kelas-id');
    const mapelId = $(this).data('mapel-id');
    const babId = $(this).data('id');
    const babName = $('#nama_bab').val();

    // Kosongkan error sebelumnya
    $('#error-bab').text('');

    $.ajax({
        url: `/syllabus/curiculum/bab/update/${curiculumId}/${kelasId}/${mapelId}/${babId}`,
        method: 'POST',
        data: {
            nama_bab: babName,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_1');
            if (modal) {
                modal.close();

                $('#alert-success-update-data-bab').html(
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
                paginateSyllabusBab();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors && errors.nama_bab) {
                    $('#error-bab').text(errors.nama_bab[0]);
                    $('#nama_bab').addClass('border-2 border-red-400');
                }
            }
        }
    });
});

// Event listener tombol "delete mapel" (open modal)
$(document).off('click', '.btn-delete-bab').on('click', '.btn-delete-bab', function(e) {
    e.preventDefault();

    const babId = $(this).data('id');

    // (Optional) set id ke form untuk submit
    $('#deleteBabForm').data('id', babId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_3');
    if (modal) {
        modal.showModal();
    }
});

$('#deleteBabForm').on('submit', function (e) {
    e.preventDefault();

    const babId = $(this).data('id');

    $.ajax({
        url: `/syllabus/curiculum/bab/delete/${babId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_3');
            if (modal) {
                modal.close();

                $('#alert-success-delete-data-bab').html(
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
                paginateSyllabusBab();
            }
        },
    });
});
