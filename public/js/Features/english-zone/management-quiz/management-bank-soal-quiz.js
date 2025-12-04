function paginateManagementBankSoalQuiz(search_question = '') {
    const container = document.getElementById('container-bank-soal-quiz');
    if (!container) return;
    
    const levelId = container.dataset.levelId;
    const passageId = container.dataset.passageId;
    const passageType = container.dataset.passageType;

    if (!levelId) return;
    if (!passageId) return;
    if (!passageType) return;

    fetchDataManagementBankSoalQuiz(search_question, levelId, passageId, passageType);

    function fetchDataManagementBankSoalQuiz(search_question, levelId, passageId, passageType) {
        $.ajax({
            url: `/english-zone/management-quiz/management-passage/${levelId}/${passageId}/${passageType}/bank-soal/paginate`,
            method: 'GET',
            data: {
                search_question // Include the page parameter
            },
            success: function (data) {
                const containerQuestion = $('#grid-list-soal');
                containerQuestion.empty();

                if (data.data.length > 0) {
                    data.data.forEach((group, index) => {
                        // Ambil item pertama buat pertanyaan
                        const first = group[0]; // Karena setiap group itu array dari soal yang sama

                        // Mengiterasi setiap opsi dari soal tersebut
                        function addClassToImgTags(html, className) {
                            return html
                                .replace(/<img\b(?![^>]*class=)[^>]*>/g, (imgTag) => {
                                    // Tambahkan class jika belum ada atribut class
                                    return imgTag.replace('<img', `<img class="${className}"`);
                                })
                                .replace(/<img\b([^>]*?)class="(.*?)"/g, (imgTag, before, existingClasses) => {
                                    // Tambahkan class ke img yang sudah punya class
                                    return `<img ${before}class="${existingClasses} ${className}"`;
                                });
                        }

                        const optionsHTML = group.map((item) => {
                            const containsImage = /<img\s+[^>]*src=/.test(item.options_value);
                            let content = item.options_value;
                            let optionsValue = '';

                            // Tambahkan class img jika ada gambar
                            if (containsImage) {
                                content = addClassToImgTags(item.options_value, 'max-w-[300px] rounded my-2');
                            }

                            // cek apakah optionsValue mengandung image
                            if (containsImage) {
                                optionsValue = `
                                    <div class="max-w-7xl border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px]
                                            ${item.options_key === item.answer_key ? 'border-green-400 bg-green-400 text-white font-bold' : ''}">
                                            <div class="font-bold min-w-[30px]">${item.options_key}.</div>
                                            <div class="w-full">${content}</div>
                                    </div>
                                `;
                            } else {
                                optionsValue = `
                                    <div class="max-w-7xl border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px]
                                        ${item.options_key === item.answer_key ? 'border-green-400 bg-green-400 text-white font-bold' : ''}">
                                        ${item.options_key}. ${content}
                                    </div>
                                `;
                            }

                            return `
                                ${optionsValue}
                            `;
                        }).join('');

                        // Ambil videoId yang sesuai dengan index pada masing" group soal
                        const videoId = data.videoIds[index];

                        const imageInExplanation = /<img\s+[^>]*src=/.test(first.explanation);

                        // Tambahkan class img jika ada gambar
                        if (imageInExplanation) {
                            first.explanation = addClassToImgTags(first.explanation, 'max-w-[350px] rounded my-2');
                        }

                        // Tampilkan video jika explanation itu adalah link video, jika tidak tampilkan explanation teks
                        const videoExplanation = videoId ? `
                            <div class="border max-w-sm !h-60 flex justify-start">
                                <div class="w-full h-full">
                                    <iframe class="w-full h-full"
                                        src="https://www.youtube.com/embed/${videoId}"
                                        frameborder="0"
                                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        ` : `<div class="max-w-7xl flex flex-col items-start gap-4">${imageInExplanation ? first.explanation : first.explanation}</div>`;

                        // untuk memisahkan teks sebelum dengan img dan text setelah img
                        const splitQuestions = first.questions.split('<img'); // split sebelum <img>
                        const questionTextOnly = splitQuestions[0]; // sebelum <img> ( [0] dan [1] digunakan untuk memisahkan 2 element berbeda )

                        // Inisialisasi variabel kosong untuk menampung elemen gambar dan teks setelah gambar
                        let questionImage = '', textAfterImage = '';

                        // Cek apakah hasil split punya bagian setelah <img (artinya ada gambar)
                        if (splitQuestions.length > 1) {
                            const imgSplit = splitQuestions[1].split('>'); // pisahkan tag <img> dan sisa teks
                            const imgTag = imgSplit[0]; // bagian src dan atribut gambar
                            const restText = imgSplit.slice(1).join('>'); // gabungkan sisa setelah tag img

                            questionImage = `<img class="max-w-[25%]" ${imgTag}>`; // Susun tag <img> lengkap dengan class tambahan
                            textAfterImage = restText.trim(); // Hapus spasi berlebih pada teks setelah gambar
                        }

                        // Gabungkan menjadi HTML: bungkus gambar dan teks
                        const questionHTML = `
                            <div class="flex flex-col gap-10 items-start">
                                ${questionImage}
                                <div>${textAfterImage}</div>
                            </div>
                        `;

                        let editQuestion = data.editQuestion.replace(':level_id', first.level_id).replace(':passage_id', first.passage_id)
                            .replace(':passage_type', first.english_zone_passage?.passage_type).replace(':question_id', first.id);

                        const card = `
                            <div class="flex justify-end">
                                <div class="dropdown dropdown-left w-max mx-4">
                                    <div tabindex="0" role="button">
                                        <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                    </div>

                                    <ul tabindex="0"
                                        class="dropdown-content menu bg-base-100 rounded-box w-max shadow-sm z-[9999]">

                                        <!-- Toggle Publish -->
                                        <li class="text-xs relative left-[-5px]">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden peer toggle-active-question"
                                                    data-question-ids="${group.map(question => question.id).join(', ')}"
                                                    ${first.status_bank_soal === 'Publish' ? 'checked' : ''} />

                                                <div class="w-8 h-4 bg-gray-300 peer-checked:bg-green-500 rounded-full relative transition-colors duration-300 ease-in-out">
                                                </div>

                                                <div class="absolute left-4 top-2.4 w-3 h-3 bg-white rounded-full shadow-md 
                                                    transition-transform duration-300 ease-in-out peer-checked:translate-x-[10px]"></div>

                                                <span>Action</span>
                                            </label>
                                        </li>

                                        <!-- Edit Question -->
                                        <li class="text-xs">
                                            <a href="${editQuestion}" class="text-[#4189e0] font-bold">
                                                <i class="fas fa-pen"></i>
                                                <span>Edit Question</span>
                                            </a>
                                        </li>
            
                                        <!-- Delete Questions -->
                                        <li class="text-xs">
                                            <a href="#" class="btn-delete-question text-red-600" data-question-ids="${group.map(question => question.id).join(', ')}">
                                                <i class="fa-solid fa-trash text-red-600"></i>
                                                Delete Question
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                                    
                            <div class="wrapper-content-accordion-questions !mt-2 mb-6">
                                <div class="toggleButton-questions">
                                    <div class="flex gap-1 max-w-[1450px]">
                                        <span>${index + 1}.</span>
                                        <span class="w-full">${questionTextOnly}</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-up icon"></i>
                                </div>

                                <div class="content-accordion">
                                    <div class="max-w-7xl text-sm mt-6">
                                        <div>${questionHTML}</div>
                                        <div>${optionsHTML}</div>
                                        <div class="flex flex-col gap-6 mb-8 mt-6">
                                            <div>
                                                <span class="font-bold opacity-70">Jawaban Benar:</span>
                                                <span class="font-bold text-green-400">${first.answer_key}</span>
                                            </div>
                                            <div>
                                                <p class="font-bold opacity-70 mb-4">Penjelasan:</p>
                                                ${videoExplanation}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        containerQuestion.append(card);
                    });

                    initAccordion();
                    $('#empty-message-bank-soal-quiz').hide(); // sembunyikan pesan kosong
                } else {
                    $('#empty-message-bank-soal-quiz').show(); // Tampilkan pesan kosong
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }
}

$(document).ready(function () {
    paginateManagementBankSoalQuiz();
})

// Fungsi untuk memfilter data berdasarkan search_question (pakai on input karena ketika data yang user cari akan munul tanpa di enter atau apapun by click)
$('#search_question').on('input', function () {
    const search_question = $(this).val();
    paginateManagementBankSoalQuiz(search_question); // Call the function to fetch data based on search_questions
});

// function submit form
$('#submit-button').on('click', function (e) {
    e.preventDefault();

    const form = $('#quiz-bank-soal-quiz-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    const container = document.getElementById('container-bank-soal-quiz');
    if (!container) return;

    const levelId = container.dataset.levelId;
    const passageId = container.dataset.passageId;
    const passageType = container.dataset.passageType;

    if (!levelId) return;
    if (!passageId) return;
    if (!passageType) return;

    $.ajax({
        url: `/english-zone/management-quiz/management-passage/${levelId}/${passageId}/${passageType}/bank-soal/store`,
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

                $('#alert-success-insert-bank-soal-quiz').html(`
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

            $('#quiz-bank-soal-quiz-form')[0].reset();
            $('#wordPreviewContainer-bulkUpload-word').addClass('hidden');
            $('#textPreview-bulkUpload-word').text('');
            $('#textSize-bulkUpload-word').text('');
            $('#textPages-bulkUpload-word').text('');
            $('#textCircle-bulkUpload-word').html('');
            $('#logo-bulkUpload-word img').attr('src', '').hide();

            paginateManagementBankSoalQuiz();
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

// action Unpublish dan Publish question
$(document).ready(function () {
    const questionId = $(this).data('question-ids');

    $(document).on('change', '.toggle-active-question', function () {
        let questionId = $(this).data('question-ids'); // Ambil passage id dari atribut data-id di checkbox
        let status = $(this).is(':checked') ? 'Publish' : 'Unpublish'; // Jika toggle ON maka publish, kalau OFF maka unpublish

        $.ajax({
            url: `/english-zone/management-quiz/management-passage/${questionId}/bank-soal/activate`, // Endpoint ke server
            method: 'PUT', // Method HTTP PUT untuk update data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status_bank_soal: status // Kirim status baru (publish/unpublish)
            },
            success: function (response) {
                // inisialisasi update data terbaru setelah berhasil update data
                paginateManagementBankSoalQuiz();
            },
            error: function (xhr) {
                alert('Gagal mengubah status.');
                checkbox.prop('checked', !checkbox.is(':checked')); // ← GUNAKAN INI
            }
        });
    });
});

// function close modal delete passage
function closeModal() {
    const closeModal = document.getElementById('my_modal_2');
    closeModal.close();
}

// Event listener tombol "delete passage" (open modal)
$(document).off('click', '.btn-delete-question').on('click', '.btn-delete-question', function (e) {
    e.preventDefault();

    let questionId = $(this).data('question-ids').toString().replace(/\s+/g, '');

    // (Optional) set id ke form untuk submit
    $('#delete-question-form').data('question-ids', questionId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_2');
    if (modal) {
        modal.showModal();
    }
});

// delete question
$('#delete-question-form').on('submit', function (e) {
    e.preventDefault();

    const questionId = $(this).data('question-ids');

    $.ajax({
        url: `/english-zone/management-quiz/management-passage/${questionId}/bank-soal/delete`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_2');
            if (modal) {
                modal.close();

                $('#alert-success-delete-bank-soal-quiz').html(
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

                setTimeout(function () {
                    document.getElementById('alertSuccess').remove();
                }, 3000);

                document.getElementById('btnClose').addEventListener('click', function () {
                    document.getElementById('alertSuccess').remove();
                });

                // Memanggil fungsi untuk memuat ulang data
                paginateManagementBankSoalQuiz();
            }
        },
    });
});