const editorInstances = []; // 1. DEKLARASI GLOBAL untuk semua instance CKEditor
function paginateBankSoalEditQuestionEZ() {
    const container = document.getElementById('editor-container');
    if (!container) return;

    const levelId = container.dataset.levelId;
    const questionId = container.dataset.questionId;

    if (!levelId || !questionId) return;

    // fetchFilteredDataBankSoalEditQuestion(levelId, questionId);

    $.ajax({
        url: `/english-zone/bank-soal/form/${levelId}/${questionId}`,
        method: 'GET',
        success: function (response) {
            const grouped = response.data;
            const question = response.editQuestion;

            // options value select
            const optionsValue = Object.values(grouped).flat().map(item => `
                <div class="flex flex-col gap-4">
                    <span>
                        <label class="mb-2 text-sm">
                            Option ${item.options_key}
                            <sup class="text-red-500 pl-1">*</sup>
                        </label>
                    </span>
                        <textarea name="options_value[${item.id}]" class="editor">${item.options_value}</textarea>
                        <span id="error-options_value-${item.id}" class="text-red-500 font-bold text-xs"></span>
                </div>
            `).join('');

            // ambil answer_key options sesuai dengan banyaknya options_key pada soal
            const answerKeyOptions = Object.values(grouped).flat()
                .map(item => item.options_key) // ambil semua opsi: a, b, c, d, e
                .filter((value, index, self) => self.indexOf(value) === index) // hapus duplikat
                .map(opt => `<option value="${opt}">${opt}</option>`) // buat options sesuai banyaknya options_key
                .join('');
            
            const levelOptions = response.getLevels.map(level => `
                <option value="${level.id}" ${level.id === question.level_id ? 'selected' : ''}>
                    ${level.level_name}
                </option>
            `).join('');

            const sessionOptions = []; for (let i = 1; i <= 2; i++) {
                const option = `
                    <option value="${i}" ${i === question.session ? 'selected' : ''}>
                        Sesi ${i}
                    </option>
                `;
                sessionOptions.push(option);
            }

            const formHtml = `
                <form id="bank-soal-edit-question-form" data-level-id="${levelId}" data-question-id="${questionId}"
                    enctype="multipart/form-data">

                        <!-- Question -->
                        <div class="leading-10 mb-6 w-full">
                            <span>Question<sup class="text-red-500 pl-1">*</sup></span>
                            <textarea name="questions" id="questions" class="editor">${question.questions}</textarea>
                            <span id="error-questions" class="text-red-500 font-bold text-xs"></span>
                        </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        ${optionsValue}
                    </div>

                    <div class="grid grid-cols-2 gap-6 mt-6">
                        <div class="flex flex-col">
                            <label class="mb-2 text-sm">Answer Key<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="answer_key" id="answer_key" value="{{ old('answer_key') }}"
                                    class="bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                                        <option value="${question.answer_key}" class="hidden">
                                            ${question.answer_key}
                                        </option>

                                        ${answerKeyOptions}
                                </select>
                            <span id="error-answer_key" class="text-red-500 font-bold text-xs pt-2"></span>
                        </div>
                        <div class="flex flex-col">
                            <label class="mb-2 text-sm">Difficulty</label>
                            <select name="difficulty" id="difficulty" value="{{ old('difficulty') }}"
                                class="bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                                    <option value="${question.difficulty}" class="hidden">
                                        ${question.difficulty}
                                    <option value="Mudah">Mudah</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Sukar">Sukar</option>
                            </select>
                            <span id="error-difficulty" class="text-red-500 font-bold text-xs pt-2"></span>
                        </div>

                        <div class="flex flex-col">
                            <label class="mb-2 text-sm">Level</label>
                            <select name="level_id" id="level_id" value="{{ old('level_id') }}"
                                class="bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                                    <option value="${question.level_id}" class="hidden">
                                        ${question.english_zone_level?.level_name}
                                    ${levelOptions}
                            </select>
                            <span id="error-level_id" class="text-red-500 font-bold text-xs pt-2"></span>
                        </div>

                        <div class="flex flex-col">
                            <label class="mb-2 text-sm">Sesi</label>
                            <select name="session" id="session" value="{{ old('session') }}"
                                class="bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                                    <option value="${question.session}" class="hidden">
                                        Sesi ${question.session}
                                    ${sessionOptions}
                            </select>
                            <span id="error-session" class="text-red-500 font-bold text-xs pt-2"></span>
                        </div>

                        <div class="flex flex-col">
                            <label class="mb-2 text-sm">Status Soal</label>
                            <select name="status_soal" id="status_soal" value="{{ old('status_soal') }}"
                                class="bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer">
                                    <option value="${question.status_soal}" class="hidden">
                                        ${question.status_soal}
                                    <option value="Free">Free</option>
                                    <option value="Premium">Premium</option>
                            </select>
                            <span id="error-status_soal" class="text-red-500 font-bold text-xs pt-2"></span>
                        </div>
                    </div>

                    <div class="leading-10 w-full my-6">
                        <span>
                            Explanation
                            <sup class="text-red-500 pl-1">&#42;</sup>
                        </span>
                        <textarea name="explanation" id="explanation" class="editor">${question.explanation}</textarea>
                        <span id="error-explanation" class="text-red-500 font-bold text-xs"></span>
                    </div>

                    <div class="flex justify-end mt-20 lg:mt-8">
                        <button id="submit-button"
                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            `;
            container.innerHTML = formHtml;

            // Inisialisasi CKEditor jika ada
            const editorContainer = document.getElementById('editor-container');
            const uploadUrl = editorContainer.getAttribute('data-upload-url');
            const deleteUrl = editorContainer.getAttribute('data-delete-url');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let previousImageUrlsMap = {};

            const editors = document.querySelectorAll('.editor');

            editors.forEach((textarea, index) => {
                ClassicEditor
                    .create(textarea, {
                        ckfinder: {
                            uploadUrl: uploadUrl
                        },
                        toolbar: {
                            items: [
                                'undo', 'redo',
                                '|',
                                'heading',
                                '|',
                                'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor',
                                '|',
                                'bold', 'italic', 'strikethrough', 'highlight', 'subscript', 'superscript',
                                '|',
                                'link', 'uploadImage', 'blockQuote', 'codeBlock',
                                '|',
                                'mathType', 'chemType',
                                '|',
                                'alignment',
                                '|',
                                'bulletedList', 'numberedList', 'outdent', 'indent'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                    })
                    .then(editor => {
                        previousImageUrlsMap[index] = [];

                        editor.model.document.on('change:data', () => {
                            const currentContent = editor.getData();

                            const imageUrls = Array.from(currentContent.matchAll(/<img[^>]+src="([^">]+)"/g))
                                .map(match => match[1]);

                            const removedImages = previousImageUrlsMap[index].filter(url => !imageUrls.includes(url));

                            removedImages.forEach(url => {
                                fetch(deleteUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: JSON.stringify({ imageUrl: url })
                                })
                                .then(response => response.json())
                                .then(data => console.log('Gambar berhasil dihapus:', data))
                                .catch(error => console.error('Error saat menghapus gambar:', error));
                            });

                            previousImageUrlsMap[index] = imageUrls;
                        });
                            editorInstances.push({ element: textarea, instance: editor }); // ⬅ SIMPAN INSTANCE
                    })
                    .catch(error => console.error('Error CKEditor:', error));
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    paginateBankSoalEditQuestionEZ();
});

$(document).ready(function () {
    // form edit question
    $(document).on('submit', '#bank-soal-edit-question-form', function (e) {
        e.preventDefault();

        // 2. Bersihkan konten CKEditor dari <p>&nbsp;</p>
        editorInstances.forEach(({ element, instance }) => {
            let content = instance.getData();
            content = content.replace(/<p>(&nbsp;|\s)*<\/p>/gi, ''); // Hapus paragraf kosong
            element.value = content; // Set ulang ke textarea
        });

        const formData = new FormData(this);
        const questionId = $(this).data('question-id');

        $.ajax({
            url: `/english-zone/bank-soal/update/${questionId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#alert-success-bank-soal-edit-question').html(
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

                // inisialiasi paginateBankSoalEditQuestionEZ() setelah berhasil edit question
                paginateBankSoalEditQuestionEZ();

                // inisialiasi paginateBankSoalEditQuestionEZ() setelah berhasil edit question
                paginateBankSoalDetailEZ();
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    // Bersihkan semua error sebelumnya
                    // $('.text-error').text('');
                    $('.input-error').removeClass('border-red-400 border-2');

                    // Tampilkan pesan error (jika input nya ada array, seperti options_value ini)
                    $.each(errors, function (field, messages) {
                        let inputName = field;
                        let errorId = `error-${field}`;

                        // Tangani format field dengan titik seperti 'options_value.639'
                        if (field.includes('.')) {
                            const [name, index] = field.split('.');
                            inputName = `${name}[${index}]`; // options_value[639]
                            errorId = `error-${name}-${index}`; // error-options_value-639
                        }

                        // Tambahkan border ke field yang error
                        $(`[name="${inputName}"]`).addClass('border-red-400 border-2');

                        // Tampilkan pesan error
                        $(`#${errorId}`).text(messages[0]);
                    });

                } else if (xhr.status === 419) {
                    alert('CSRF token mismatch. Coba refresh halaman.');
                } else {
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            }
        });
    });
});
