$('#bulkUpload-syllabus-form').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
        url: '/syllabus/bulkupload/syllabus',
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            const modal = document.getElementById('my_modal_4');

            if (modal) {
                modal.close();

                $('#alert-success-import-syllabus').html(`
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

            // inisialisasi paginate curiculum setelah import BulkUpload syllabus
            fetchFilteredDataSyllabusCuriculum();
            // inisialisasi paginate fase setelah import BulkUpload syllabus
            paginateSyllabusFase();
            // inisialisasi paginate kelas setelah import BulkUpload syllabus
            paginateSyllabusKelas();
            // inisialisasi paginate mapel setelah import BulkUpload syllabus
            paginateSyllabusMapel();
            // inisialisasi paginate bab setelah import BulkUpload syllabus
            paginateSyllabusBab();
            // inisialisasi paginate sub bab setelah import BulkUpload syllabus
            paginateSyllabusSubBab();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const response = xhr.responseJSON;
                // error validation form dan bulkUpload
                const formErrors = response.errors.form_errors ?? {};
                const excelErrors = response.errors.excel_validation_errors ?? [];

                let errorList = '';

                if (Object.keys(formErrors).length > 0) {
                    $.each(formErrors, function (field, messages) {
                        const showError = `
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-2 h-max rounded flex flex-col gap-2">
                                <span class="font-bold text-sm">Terjadi Kesalahan :</span>
                                ${messages[0]}
                            </div>
                        `;
                        $('#error-bulkUpload-excel').html(showError);
                    });
                }


                if (excelErrors.length > 0) {
                    excelErrors.forEach(err => {
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

                    $('#error-bulkUpload-excel').html(showError);
                    my_modal_4.showModal();

                }
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }
        }

    });
});
