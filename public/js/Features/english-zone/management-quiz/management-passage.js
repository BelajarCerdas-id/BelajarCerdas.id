function paginateManagementPassage() {
    $.ajax({
        url: '/english-zone/management-quiz/management-passage/paginate',
        method: 'GET',
        success: function (data) {
            $('#table-list-management-passage').empty(); // Clear previous entries
            $('.pagination-container-management-passage').empty(); // Clear previous pagination links

            if (data.data.length > 0) {
                const rows = [];

                data.data.forEach(group => {
                    Object.values(group).forEach(items => {
                        rows.push(items[0]);
                    });
                });

                $('#table-list-management-passage').empty();

                rows.forEach((item, index) => {
                    const passageDetail = data.passageDetail
                        .replace(':level_id', item.level_id).replace(':passage_type', item.passage_type);

                    $('#table-list-management-passage').append(`
                        <tr class="text-xs">
                            <td class="td-table !text-black !text-center">${index + 1}</td>
                            <td class="td-table !text-black !text-center">${item.english_zone_level?.level_name}</td>
                            <td class="td-table !text-black !text-center">${item.passage_type}</td>
                            <td class="td-table !text-center font-bold text-[#4189e0] text-xs">
                                <a href="${passageDetail}">
                                    Lihat Passage
                                </a>
                            </td>
                        </tr>
                    `);
                });

                $('#empty-message-management-passage').hide(); // sembunyikan pesan kosong
                $('.thead-table-management-passage').show(); // Tampilkan tabel thead
            } else {
                $('#table-list-management-passage').empty(); // Clear existing rows
                $('#empty-message-management-passage').show(); // Tampilkan pesan kosong
                $('.thead-table-management-passage').hide(); // sembunyikan tabel thead
            }
        }
    });
}

$(document).ready(function () {
    paginateManagementPassage();
});

$('#submit-button').on('click', function (e) {
    e.preventDefault();

    const form = $('#management-passage-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    $.ajax({
        url: '/english-zone/management-quiz/management-passage/store',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const modal = document.getElementById('my_modal_1');

            if (modal) {
                modal.close();

                $('#alert-success-insert-passage').html(`
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
            }

            setTimeout(function () {
                $('#alertSuccess').remove();
            }, 3000);

            $('#btnClose').on('click', function () {
                $('#alertSuccess').remove();
            });

            $('#management-passage-form')[0].reset();

            // reset audio passage
            $('#textPreview-audio-passage').text('');
            $('#textSize-audio-passage').text('');
            $('#textPages-audio-passage').text('');
            $('#textCircle-audio-passage').html('');

            // reset bulk upload word
            $('#wordPreviewContainer-bulkUpload-word').addClass('hidden');
            $('#textPreview-bulkUpload-word').text('');
            $('#textSize-bulkUpload-word').text('');
            $('#textPages-bulkUpload-word').text('');
            $('#textCircle-bulkUpload-word').html('');
            $('#logo-bulkUpload-word img').attr('src', '').hide();

            paginateManagementPassage();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const response = xhr.responseJSON;
                // error validation form dan bulkUpload
                const formErrors = response.errors.form_errors ?? {};
                const wordErrors = response.errors.word_validation_errors ?? [];

                let errorList = '';

                $.each(formErrors, function (field, messages) {
                    $(`#error-${field}`).text(messages[0]);
                    $(`[name="${field}"]`).addClass('border-red-400 border-2');
                });

                if (wordErrors.length > 0) {
                    wordErrors.forEach(err => {
                        errorList += `<li class="text-sm">${err}</li>`;
                    });

                    const html = `
                        <ul class="text-red-500 text-sm list-disc pl-5">
                            ${errorList}
                        </ul>
                    `;

                    const showError = `
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-2 h-max rounded">
                            <span class="font-bold text-sm">Terjadi Kesalahan :</span>
                            ${html}
                        </div>
                    `;

                    $('#error-bulkUpload').html(showError);
                    my_modal_1.showModal();
                }
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }
        }
    });
});
