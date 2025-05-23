function paginateQuestionVerificationMentor() {
    const container = document.getElementById('question-verification-container');
    if (!container) return;

    const mentorId = container.dataset.mentorId;
    if (!mentorId) return;

    fetchTanyaVerifications(mentorId, 1);

    function fetchTanyaVerifications(mentorId, page = 1) {
        $.ajax({
            url: `/paginate/verifikasi-pertanyaan-mentor/${mentorId}`,
            data: { page: page },
            method: 'GET',
            success: function (data) {
                $('#tableQuestionVerification').empty();
                $('.pagination-container-question-verification-mentor').empty();

                if (data.data.length > 0) {
                    $.each(data.data, function (index, item) {

                        const updateDataTanyaVerificationAccepted = data.updateDataTanyaVerificationAccepted.replace(':id', item.id);
                        const restoreUrl = data.restoreUrl.replace(':id', item.tanya_id);

                        let verificationAction = '';

                        if(item.status_verifikasi === 'Ditolak') {
                            verificationAction = `
                                <button class="verify-btn-reject text-red-500 font-bold" data-id="${item.id}">Soal Ditolak</button>
                            `;
                        }else if(item.status_verifikasi === 'Menunggu') {
                            verificationAction = `
                                <div class="dropdown dropdown-left">
                                    <div tabindex="0" role="button">
                                        <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                    </div>
                                    <ul tabindex="0"
                                        class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm  z-[9999]">
                                        <li class="text-xs">
                                            <a href="${updateDataTanyaVerificationAccepted}" class="btn-verify" data-id="${item.id}">
                                                <i
                                                class="fa-solid fa-circle-check text-green-500 text-md"></i>
                                                Terima Soal
                                            </a>
                                        </li>

                                        <li class="text-xs">
                                            <a href="#" class="btn-tolak-soal" data-id="${item.id}">
                                                <i class="fa-solid fa-circle-xmark text-red-500 text-md"></i>
                                                Tolak Soal
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            `;
                        } else {
                            verificationAction = `
                                <a href="" class="verify-btn-success text-green-500 font-bold" data-id="${item.id}">Soal Diterima</a>
                            `;
                        }

                        $('#tableQuestionVerification').append(`
                            <tr>
                                <td class="border text-center">${index + 1}</td>
                                <td class="border">${item.tanya?.pertanyaan || '-'}</td>
                                <td class="text-center border">${item.tanya?.fase?.nama_fase || '-'}</td>
                                <td class="text-center border">${item.tanya?.kelas?.kelas || '-'}</td>
                                <td class="text-center border">${item.tanya?.mapel?.mata_pelajaran || '-'}</td>
                                <td class="text-center border">${item.tanya?.bab?.nama_bab || '-'}</td>
                                <td class="text-center border">
                                    <a href="${restoreUrl}" class="text-blue-500 underline">Lihat</a>
                                </td>
                                <td class="text-center border">${item.status_verifikasi}</td>
                                <td class="text-center border">
                                    <button>${verificationAction}</button>
                                </td>
                            </tr>
                        `);
                    });

                    $('.pagination-container-question-verification-mentor').html(data.links).show();
                    $('#thead-table-tanya-verification').show();
                    $('#emptyMessage-riwayat-question-verification').hide();

                    bindPaginationLinks(mentorId);
                } else {
                    $('#thead-table-tanya-verification').hide();
                    $('.pagination-container-question-verification-mentor').hide();
                    $('#emptyMessage-riwayat-question-verification').show();
                }
            }
        });
    }

    function bindPaginationLinks(mentorId) {
        $('.pagination-container-question-verification-mentor').off('click', 'a').on('click', 'a', function (e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchTanyaVerifications(mentorId, page);
        });
    }
}

// Inisialisasi saat DOM siap
$(document).ready(function () {
    paginateQuestionVerificationMentor();

        $(document).on('click', '.btn-verify', function(e) {
            e.preventDefault();

            const tanyaVerificationId = $(this).data('id');
            const urlDetail = $(this).attr('href');
            const btn = $(this);

            $.ajax({
                url: `/question-mentor-tanya/accepted/${tanyaVerificationId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    paginateQuestionVerificationMentor();
                },
                error: function (xhr) {
                    '';
                }
            });
        });
});


// Event listener tombol "Tolak Soal" (open modal)
$(document).off('click', '.btn-tolak-soal').on('click', '.btn-tolak-soal', function(e) {
    e.preventDefault();

    const soalId = $(this).data('id');

    // (Optional) set id ke form untuk submit
    $('#form-tolak-soal').data('soal-id', soalId);

    // Reset input
    $('#alasan_verifikasi_ditolak').val('');

    // Reset text error
    $('#error-alasan-ditolak').text('');

    // Tampilkan modal
    const modal = document.getElementById('my_modal_1');
    if (modal) {
        modal.showModal();
    }
});

$('#form-tolak-soal').on('submit', function (e) {
    e.preventDefault();

    const soalId = $(this).data('soal-id');
    const alasan = $('#alasan_verifikasi_ditolak').val();

    // Kosongkan error sebelumnya
    $('#error-alasan-ditolak').text('');

    $.ajax({
        url: `/question-mentor-tanya/rejected/${soalId}`,
        method: 'POST',
        data: {
            alasan_verifikasi_ditolak: alasan,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_1');
            if (modal) {
            modal.close();

            // Memanggil fungsi untuk memuat ulang data
            paginateQuestionVerificationMentor();
            }
            // $('#my_modal_1')[0].close();
            // paginateQuestionVerificationMentor(); // refresh table
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors && errors.alasan_verifikasi_ditolak) {
                    $('#error-alasan-ditolak').text(errors.alasan_verifikasi_ditolak[0]);
                }
            }
        }
    });
});
