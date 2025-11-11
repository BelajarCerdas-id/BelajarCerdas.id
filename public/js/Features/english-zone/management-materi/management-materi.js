function paginateManagementMateri(page = 1) {
    $.ajax({
        url: '/english-zone/management-materi/paginate',
        method: 'GET',
        data: {
            page: page
        },
        success: function (response) {
            $('#table-list-management-materi').empty(); // Clear previous entries
            $('.pagination-container-management-materi').empty(); // Clear previous pagination links

            if (response.data.length > 0) {
                $.each(response.data, function (index, item) {

                    const first = item[0];

                    const materiDetail = response.materiDetail.replace(':id', first.level_id);

                    $('#table-list-management-materi').append(`
                    <tr class="text-xs">
                        <td class="td-table !text-black !text-center">${index + 1}</td>
                        <td class="td-table !text-black !text-center">${first.english_zone_level?.level_name}</td>
                        <td class="border text-center border-gray-300">
                            <a href="${materiDetail}" class="font-bold text-[#4189e0] text-xs">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                `);
                });

                // Append pagination links
                $('.pagination-container-management-materi').html(response.links);
                bindPaginationLinks(); // Bind click event ke link pagination yang baru
                $('#empty-message-management-materi').hide(); // sembunyikan pesan kosong
                $('.thead-table-management-materi').show(); // Tampilkan tabel thead
            } else {
                $('#table-list-management-materi').empty(); // Clear existing rows
                $('#empty-message-management-materi').show(); // Tampilkan pesan kosong
                $('.thead-table-management-materi').hide(); // sembunyikan tabel thead
            }
        }
    });
}

$(document).ready(function () {
    paginateManagementMateri();
});

function bindPaginationLinks() {
    $('.pagination-container-management-materi').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        paginateManagementMateri(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// Form Action Insert materi
let isProcessing = false;
$('#submit-button').on('click', function (e) {
    e.preventDefault();

    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const form = $('#management-materi-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    const btn = $(this);

    btn.prop('disabled', true); // Disable button UI

    $.ajax({
        url: '/english-zone/management-materi/store',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#alert-success-insert-materi').html(`
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
            `);

            setTimeout(function () {
                $('#alertSuccess').remove();
            }, 3000);

            $('#btnClose').on('click', function () {
                $('#alertSuccess').remove();
            });

            // Reset form (input, select)
            $('#management-materi-form')[0].reset();

            // reset form (upload file)
            $('.file-wrapper').each(function () {
                let prefix = $(this).data('prefix');
                $('#textPreview-' + prefix).text('');
                $('#textSize-' + prefix).text('');
                $('#textPages-' + prefix).text('');
                $('#textCircle-' + prefix).html('');
                $('#pdfLogo-' + prefix).attr('src', '').css('display', '');
                $('#fileArrowUp-' + prefix).show();
            })

            paginateManagementMateri();

            isProcessing = false;
            btn.prop('disabled', false);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#management-materi-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#management-materi-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                });
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }

            isProcessing = false;
            btn.prop('disabled', false);
        }
    });
});

// drodpwon bertingkat level -> sesi
$(document).ready(function () {
    var oldLevel = $('#level_id').attr('data-old-level');
    var oldSession = $('#session_id').attr('data-old-session'); // Ambil kelas yang dipilih jika ada

    var selectSession = document.getElementById('session_id');

    $('#level_id').on('change', function () {
        var level_id = $(this).val();
        if (level_id) {
            $.ajax({
                url: '/english-zone/session-dropdown/' + level_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    selectSession.disabled =
                        false; // untuk menonaktifkan disabled pada select kelas ketika fase sudah dipilih
                    selectSession.classList.replace('!cursor-default', '!cursor-pointer');
                    selectSession.classList.replace('opacity-50', 'opacity-100');
                    $('#session_id').empty();
                    $('#session_id').append(
                        '<option value="" class="hidden">Pilih Sesi</option>'
                    );
                    $.each(data, function (key, session) {
                        $('#session_id').append(
                            '<option value="' + session.id + '"' +
                            (oldSession == session.id ? ' selected' : '') +
                            '>' +
                            session.session_name + '</option>'
                        );
                    });

                    if (oldSession) {
                        $('#session_id').val(oldSession).trigger('change');
                    }
                }
            });
        } else {
            $('#session_id').empty();
        }
    });

    if (oldLevel) {
        $('#level_id').val(oldLevel).trigger('change');
    }
});