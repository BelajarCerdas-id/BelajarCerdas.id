function paginateSyllabusSubBab() {
    const container = document.getElementById('container-syllabus-sub-bab');
    if (!container) return;

    const kurikulumName = container.dataset.namaKurikulum;
    const kurikulumId = container.dataset.kurikulumId;
    const faseId = container.dataset.faseId;
    const kelasId = container.dataset.kelasId;
    const mapelId = container.dataset.mapelId;
    const babId = container.dataset.babId;

    if (!kurikulumName) return;
    if (!kurikulumId) return;
    if (!faseId) return;
    if (!kelasId) return;
    if (!mapelId) return;
    if (!babId) return;

    fetchFilteredDataSyllabusSubBab(kurikulumName, kurikulumId, faseId, kelasId, mapelId, babId, 1);

    function fetchFilteredDataSyllabusSubBab(nama_kurikulum, kurikulum_id, fase_id, kelas_id, mapel_id, bab_id, page = 1) {
        $.ajax({
            url: `/paginate-syllabus-service-sub-bab/${kurikulumName}/${kurikulumId}/${faseId}/${kelasId}/${mapelId}/${babId}`,
            method: 'GET',
            data: {page: page,},
            success: function (data) {
                $('#tableListSyllabusSubBab').empty();
                $('.pagination-container-syllabus-sub-bab').empty();

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

                        let subBabUpdate = data.subBabUpdate.replace(':kurikulum_id', application.kurikulum_id).replace(':kelas_id', application.kelas_id).replace(':mapel_id', application.mapel_id)
                                            .replace(':bab_id', application.bab_id).replace(':id', application.id);

                        let featureCheckboxesSubBab = '';

                        $.each(data.dataFeaturesRoles, function (fIndex, featureItem) {
                            const subBabId = application.id;
                            const featureId = featureItem.feature_id;

                            const status = data.statusSubBabFeature?.[subBabId]?.[featureId] ?? null;
                            const isChecked = status === 'publish' ? 'checked' : '';

                            featureCheckboxesSubBab += `
                            <td class="border text-center border-gray-300">
                                <input type="checkbox"
                                    class="w-4 h-4 cursor-pointer accent-green-600 checkbox-sub-bab"
                                    data-id-sub-bab="${subBabId}"
                                    data-feature="${featureId}"
                                    ${isChecked}>
                            </td>
                            `;
                        });

                        $('#tableListSyllabusSubBab').append(`
                            <tr class="text-xs">
                                <td class="border border-gray-300">${application.sub_bab}</td>
                                ${featureCheckboxesSubBab}
                                <td class="border text-center border-gray-300">
                                    <div class="dropdown dropdown-left">
                                        <div tabindex="0" role="button">
                                            <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                        </div>
                                            <ul tabindex="0"
                                                class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                                <li class="text-xs">
                                                    <a href="#" class="btn-edit-sub-bab" data-kurikulum-id="${application.kurikulum_id}" data-kelas-id="${application.kelas_id}"
                                                        data-mapel-id="${application.mapel_id}" data-bab-id="${application.bab_id}" data-id="${application.id}" data-sub-bab="${application.sub_bab}">
                                                        <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                        Edit Sub Bab
                                                    </a>
                                                </li>
                                                </li>
                                                <li class="text-xs" onclick="historySubBab(this)"
                                                    data-nama_lengkap="${application.user_account?.office_profiles?.nama_lengkap}"
                                                    data-status="${application.user_account?.role}"
                                                    data-updated_at="${updatedAt}">
                                                    <a>
                                                        <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                        View History
                                                    </a>
                                                </li>
                                                <li class="text-xs">
                                                    <a href="#" class="btn-delete-sub-bab" data-id="${application.id}">
                                                        <i class="fa-solid fa-trash text-red-500"></i>
                                                        Delete Sub Bab
                                                    </a>
                                                </li>
                                            </ul>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });

                    // Insert pagination HTML
                    $('.pagination-container-syllabus-sub-bab').html(data.links);

                    // Bind click event ke link pagination yang baru
                    bindPaginationLinks(kurikulumName, kurikulumId, faseId, kelasId, mapelId, babId);

                    $('#emptyMessageSyllabusSubBab').hide();
                    $('.thead-table-syllabus-sub-bab').show();
                } else {
                    $('#emptyMessageSyllabusSubBab').show();
                    $('.thead-table-syllabus-sub-bab').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('ajax error:', status, error);
            }
        });
    }

}

$(document).ready(function () {
    // Ambil data semua saat halaman dimuat
    paginateSyllabusSubBab();

        $(document).on('change', '.checkbox-sub-bab', function() {
            let id = $(this).data('id-sub-bab'); // bab_id
            let feature_id = $(this).data('feature'); // feature_id
            let status_sub_bab = $(this).is(':checked') ? 'publish' : 'unpublish';
            let csrf = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/syllabus/curiculum/sub-bab/activate/' + id,
                type: 'PUT',
                data: {
                    _token: csrf,
                    status_sub_bab: status_sub_bab,
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


function bindPaginationLinks(kurikulumName, kurikulumId, faseId, kelasId, mapelId, babId) {
    $('.pagination-container-syllabus-sub-bab').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        fetchFilteredDataSyllabusSubBab(kurikulumName, kurikulumId, faseId, kelasId, mapelId, babId, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}


// Event listener tombol "edit mapel" (open modal)
$(document).off('click', '.btn-edit-sub-bab').on('click', '.btn-edit-sub-bab', function(e) {
    e.preventDefault();

    const curiculumId = $(this).data('kurikulum-id');
    const kelasId = $(this).data('kelas-id');
    const mapelId = $(this).data('mapel-id');
    const babId = $(this).data('bab-id');
    const subBabId = $(this).data('id');
    const subBabName = $(this).data('sub-bab');


    // set id ke form untuk submit
    $('#subBabForm').data('kurikulum-id', curiculumId);
    $('#subBabForm').data('kelas-id', kelasId);
    $('#subBabForm').data('mapel-id', mapelId);
    $('#subBabForm').data('bab-id', babId);
    $('#subBabForm').data('id', subBabId);

    // Reset input
    // $('#mata_pelajaran').val('');

    // Reset text error
    $('#error-sub-bab').text('');

    // Tampilkan modal
    const modal = document.getElementById('my_modal_1');
    if (modal) {
        $('#sub_bab').val(subBabName);
        modal.showModal();
    }
});

$('#subBabForm').on('submit', function (e) {
    e.preventDefault();

    const curiculumId = $(this).data('kurikulum-id');
    const kelasId = $(this).data('kelas-id');
    const mapelId = $(this).data('mapel-id');
    const babId = $(this).data('bab-id');
    const subBabId = $(this).data('id');
    const subBabName = $('#sub_bab').val();

    // Kosongkan error sebelumnya
    $('#error-sub-bab').text('');

    $.ajax({
        url: `/syllabus/curiculum/sub-bab/update/${curiculumId}/${kelasId}/${mapelId}/${babId}/${subBabId}`,
        method: 'POST',
        data: {
            sub_bab: subBabName,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_1');
            if (modal) {
                modal.close();

                $('#alert-success-update-data-sub-bab').html(
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
                paginateSyllabusSubBab();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors && errors.sub_bab) {
                    $('#error-sub-bab').text(errors.sub_bab[0]);
                    $('#sub_bab').addClass('border-2 border-red-400');
                }
            }
        }
    });
});

// Event listener tombol "delete mapel" (open modal)
$(document).off('click', '.btn-delete-sub-bab').on('click', '.btn-delete-sub-bab', function(e) {
    e.preventDefault();

    const subBabId = $(this).data('id');

    // (Optional) set id ke form untuk submit
    $('#deleteSubBabForm').data('id', subBabId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_3');
    if (modal) {
        modal.showModal();
    }
});

$('#deleteSubBabForm').on('submit', function (e) {
    e.preventDefault();

    const subBabId = $(this).data('id');

    $.ajax({
        url: `/syllabus/curiculum/sub-bab/delete/${subBabId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_3');
            if (modal) {
                modal.close();

                $('#alert-success-update-data-sub-bab').html(
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
                paginateSyllabusSubBab();
            }
        },
    });
});
