$('#btn-submit-bank-soal').on('click', function (e) {
    e.preventDefault();

    const form = $('#bank-soal-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    $.ajax({
        url: '/soal-pembahasan/bank-soal-store',
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

                $('#alert-success-insert-bank-soal').html(`
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

            setTimeout(function() {
                $('#alertSuccess').remove();
            }, 3000);

            $('#btnClose').on('click', function () {
                $('#alertSuccess').remove();
            });

            $('#id_kelas').html('<option disabled selected>Pilih Kelas</option>').prop('disabled', true).removeClass('opacity-100 cursor-pointer').addClass('opacity-50 cursor-default');
            $('#id_mapel').html('<option disabled selected>Pilih Mata Pelajaran</option>').prop('disabled', true).removeClass('opacity-100 cursor-pointer').addClass('opacity-50 cursor-default');
            $('#id_bab').html('<option disabled selected>Pilih Bab</option>').prop('disabled', true).removeClass('opacity-100 cursor-pointer').addClass('opacity-50 cursor-default');
            $('#id_sub_bab').html('<option disabled selected>Pilih Bab</option>').prop('disabled', true).removeClass('opacity-100 cursor-pointer').addClass('opacity-50 cursor-default');

            $('#bank-soal-form')[0].reset();
            $('#wordPreviewContainer-bulkUpload-word').addClass('hidden');
            $('#textPreview-bulkUpload-word').text('');
            $('#textSize-bulkUpload-word').text('');
            $('#textPages-bulkUpload-word').text('');
            $('#textCircle-bulkUpload-word').html('');
            $('#logo-bulkUpload-word img').attr('src', '').hide();

            paginateBankSoal();
            paginateBankSoalDetail();
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
