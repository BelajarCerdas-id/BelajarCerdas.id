let currentQuestionIndex = 0; // Global, default ke soal pertama

function quizReadingExamTest(page = 1, selectedIndex = 0) {
    const container = document.getElementById('container-quiz-reading-exam-test');
    if (!container) return;

    const levelId = container.dataset.levelId;
    if (!levelId) return;

    fetchquizReadingExamTest(levelId, page, selectedIndex);

    function fetchquizReadingExamTest(levelId, page, selectedIndex) {
        $.ajax({
            url: `/english-zone/${levelId}/quiz/reading-exam-test/form`,
            method: 'GET',
            data: {
                page: page,
            },
            success: function (response) {
                const activePassageId = response.passage_id;

                const filteredQuestions = response.questions.filter(q => q[0].passage_id === activePassageId);

                const questionsAnswer = response.questionsAnswer;

                // Hitung jumlah soal
                const totalSoal = filteredQuestions.length;

                // Hitung jumlah soal yang sudah dijawab
                let jumlahSoalTerjawab = 0;
                filteredQuestions.forEach((group) => {
                    // Mendapatkan id soal pada group pertama
                    const questionId = group[0].id;
                    // Mendapatkan jawaban sesuai id soal
                    const jawaban = questionsAnswer[questionId];

                    // Cek apakah jawaban sudah dijawab
                    if (jawaban) {
                        jumlahSoalTerjawab++;
                    }
                });

                // Cek apakah semua soal sudah dijawab
                const isAllAnswered = jumlahSoalTerjawab === totalSoal;
                const subscription = response.subscription;

                if (!subscription) {
                    $('#score-exam').text('-');
                }

                // Jika semua soal sudah dijawab, tampilkan konten
                if (isAllAnswered) {
                    const scoreExam = response.scoreExam; // mengambil nilai ujian

                    $('#score-exam').text(scoreExam); // menampilkan nilai ujian
                }

                let startDate = null;
                let endDate = null;

                if (subscription) {
                    startDate = new Date(subscription.start_date);
                    endDate = new Date(subscription.end_date);
                }

                // jika tidak ada passages yang aktif maka tampilkan pesan
                if (response.data.length === 0) {
                    container.innerHTML = `<div class="p-6 font-bold opacity-70 flex justify-center">Tidak ada passage yang aktif pada quiz ini.</div>`;
                    $('#container-score-exam').hide();
                    return;
                }

                // jika tidak ada soal yang aktif pada passage, maka tampilkan pesan
                else if (filteredQuestions.length === 0) {
                    container.innerHTML = `<div class="p-6 font-bold opacity-70 flex justify-center">Tidak ada soal untuk passage ini.</div>`;
                    $('#container-score-exam').hide();

                    // Append pagination links
                    $('.pagination-container-question-reading-exam-test').html(response.links);
                    bindPaginationLinks(); // Bind click event ke link pagination yang baru

                    return;
                }

                // jika passage dan soal aktif, maka tampilkan data
                else if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {

                        let passageContent = item.passage_content;

                        passageContent = passageContent.replace(/<img /g, '<img class="max-w-[100%] lg:max-w-[550px] rounded my-2"');

                        const passage = `
                            <div class="space-y-4 text-justify">
                                ${passageContent} 
                            </div>
                        `;

                        const selectedQuestionGroup = filteredQuestions[selectedIndex];

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

                        const generateOptions = (group) => {
                            const optionKeys = ['A', 'B', 'C', 'D', 'E'];

                            const shuffleOptions = [...group];

                            return shuffleOptions.map((item, index) => {
                                const newKey = optionKeys[index];

                                const containsImage = /<img\s+[^>]*src=/.test(item.options_value);

                                if (containsImage) {
                                    optionsHtml = addClassToImgTags(item.options_value, 'max-w-[300px] rounded my-2');
                                }

                                let statusClass = '';

                                // Memeriksa apakah semua soal sudah dijawab
                                if (isAllAnswered) {
                                    // jika jawaban user sudah disimpan (tanda '?' sebelum field pada questionsAnswer adalah untuk akses properti dari data yang belum pasti ada)
                                    if (questionsAnswer[selectedQuestionGroup[0].id]) {
                                        // Jika sudah menjawab dan benar
                                        if (questionsAnswer[selectedQuestionGroup[0].id]?.user_answer_option === selectedQuestionGroup[0].answer_key
                                            && questionsAnswer[selectedQuestionGroup[0].id].user_answer_option === item.options_key) {
                                            statusClass = 'bg-green-200 text-green-600 font-bold';

                                            // Jika jawab salah
                                        } else if (questionsAnswer[selectedQuestionGroup[0].id].user_answer_option !== selectedQuestionGroup[0].answer_key
                                            && questionsAnswer[selectedQuestionGroup[0].id].user_answer_option === item.options_key) {
                                            statusClass = 'bg-red-200 text-red-600 font-bold';

                                            // Jika sudah jawab tapi bukan ini pilihannya, maka highlight jawaban benar
                                        } else if (questionsAnswer[selectedQuestionGroup[0].id].user_answer_option && item.options_key === selectedQuestionGroup[0].answer_key) {
                                            statusClass = 'bg-green-200 text-green-600 font-bold';
                                        }
                                    }
                                } else {
                                    if (questionsAnswer[selectedQuestionGroup[0].id]?.user_answer_option === item.options_key) {
                                        statusClass = 'bg-gray-200 font-bold opacity-70';
                                    }
                                }

                                let optionsValue = '';

                                // memeriksa apakah soal sudah dijawab oleh pengguna
                                if (questionsAnswer[selectedQuestionGroup[0].id]) {
                                    // memeriksa apakah options_value terdapat image atau tidak
                                    if (containsImage) {
                                        optionsValue = `
                                            <div class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] checked-option ${statusClass}">
                                                <div class="font-bold min-w-[30px]">${newKey}.</div>
                                                <div class="w-full flex flex-col gap-8">${item.options_value}</div>
                                            </div>
                                        `;
                                    } else {
                                        optionsValue = `
                                            <div class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] checked-option ${statusClass}">
                                                ${newKey}. ${item.options_value}
                                            </div>
                                        `;
                                    }
                                } else {
                                    if (containsImage) {
                                        optionsValue = `
                                            <input type="radio" name="options_value_${selectedQuestionGroup[0].id}" id="soal${item.options_key}" value="${item.options_key}" class="hidden" data-soal-id="${selectedQuestionGroup[0].id}">
                                            <label for="soal${item.options_key}" class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] cursor-pointer checked-option ${statusClass}">
                                                <div class="font-bold min-w-[30px]">${newKey}.</div>
                                                <div class="w-full flex flex-col gap-8">${item.options_value}</div>
                                            </label>
                                        `;
                                    } else {
                                        optionsValue = `
                                            <input type="radio" name="options_value_${selectedQuestionGroup[0].id}" id="soal${item.options_key}" value="${item.options_key}" class="hidden" data-soal-id="${selectedQuestionGroup[0].id}">
                                            <label for="soal${item.options_key}" class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] cursor-pointer checked-option ${statusClass}">
                                                ${newKey}. ${item.options_value}
                                            </label>
                                        `;
                                    }
                                }

                                return `
                                    ${optionsValue}
                                `;
                            }).join('');
                        }

                        const nomorSoalHTML = filteredQuestions.map((group, index) => {
                            let statusClassNumberQuestions = '';

                            // Memeriksa apakah semua soal sudah dijawab
                            if (isAllAnswered) {
                                // menggunakan group[0] jika ingin membuat dan melihat semua nomor soal benar / salah, jika menggunakan selectedQuestionGroup[0].id hanya akan aktif jika soal nya sedang dilihat
                                // Memeriksa apakah soal sudah dijawab oleh pengguna dan apakah jawaban user benar
                                if (questionsAnswer[group[0].id]?.user_answer_option === group[0].answer_key) {
                                    statusClassNumberQuestions = '!bg-green-200 text-green-600 font-bold';
                                    // Memeriksa apakah soal sudah dijawab oleh pengguna dan apakah jawaban user salah
                                } else if (questionsAnswer[group[0].id]?.user_answer_option !== group[0].answer_key) {
                                    statusClassNumberQuestions = '!bg-red-200 text-red-600 font-bold';
                                }
                            } else {
                                // Memeriksa apakah soal sudah disimpan oleh pengguna dan question_id sesuai dengan soal yang dilihat
                                // menggunakan group[0] jika ingin membuat dan melihat semua aktif
                                if (questionsAnswer[group[0].id]?.question_id === group[0].id) {
                                    statusClassNumberQuestions = '!bg-[--color-default] text-white font-bold';

                                    // memeriksa jika soal belum dijawab oleh pengguna
                                } else {
                                    statusClassNumberQuestions = '';
                                }
                            }

                            // variabel kosong untuk menandakan soal
                            let premiumQuestions = '';

                            // memeriksa jika user berlangganan maka soal menjadi terbuka
                            if (subscription) {
                                premiumQuestions = '';
                                // jika user tidak berlangganan maka soal menjadi tertutup
                            } else if (!subscription) {
                                premiumQuestions = `<i class="fas fa-lock text-[--color-default]"></i>`;
                            }

                            return `
                                <input type="radio" id="nomor${index}" name="nomorSoal" class="hidden">
                                <label for="nomor${index}" class="nomor-soal border border-gray-400 py-1 hover:bg-gray-200 cursor-pointer text-xs ${statusClassNumberQuestions}" data-index="${index}">
                                    <span class="font-bold">${index + 1}</span>
                                    ${premiumQuestions}
                                </label>
                            `;
                        }).join('');

                        // BUTTON LOGIC

                        // Mengecek apakah soal sudah dijawab oleh pengguna
                        const isAnswered = !!questionsAnswer[selectedQuestionGroup[0].id]; // `!!` akan mengubah nilai tersebut menjadi boolean `true` atau `false`.

                        // ambil jawaban user untuk soal ini (tangani dua kemungkinan bentuk: string atau object)
                        const userAnswer = questionsAnswer[selectedQuestionGroup[0].id]?.user_answer_option ?? questionsAnswer[selectedQuestionGroup[0].id];

                        // cek benar/salah berdasar jawaban user yang sebenarnya
                        const isCorrect = isAnswered && (userAnswer === selectedQuestionGroup[0].answer_key);

                        // show button submit answer
                        // variabel kosong
                        let buttonSubmitAnswerHTML = '';

                        if (subscription) {
                            buttonSubmitAnswerHTML = isAnswered
                                ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Save</button>`
                                : `<button id="button-submit-exam-answer" class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[--color-default] text-white font-bold rounded-md hover:brightness-90" data-level-id="${levelId}" data-passage-id="${activePassageId}">Save</button>`;
                        } else if (!subscription) {
                            buttonSubmitAnswerHTML = `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Save</button>`
                        }

                        const buttonCorrectOrWrongHTML = isAllAnswered
                            ? (isCorrect
                                ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-green-200 text-green-600 font-bold rounded-md" disabled>Answer: Correct</button>`
                                : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-red-200 text-red-600 font-bold opacity-70 rounded-md" disabled>Answer: Wrong</button>`)
                            : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Answer: Correct / Wrong</button>`;

                        const videoId = response.videoIds[selectedIndex];

                        const videoExplanation = videoId ? `
                            <div class="border max-w-7xl h-[500px] flex justify-start">
                                <div class="w-full h-full">
                                    <iframe id="video-frame" class="w-full h-full"
                                        src="https://www.youtube.com/embed/${videoId}"
                                        frameborder="0"
                                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        ` : `
                            <div class="flex flex-col items-start gap-4">${selectedQuestionGroup[0].explanation}</div>
                        `;

                        // show button pembahasan
                        // memeriksa apakah soal sudah dijawab oleh pengguna, jika sudah maka dapat melihat pembahasan
                        const buttonPembahasanHTML = isAllAnswered
                            ? `<button type="button" onclick="showExplanation(this)" data-video-id="${videoId}"
                                class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[#4189E0] text-white font-bold rounded-md hover:brightness-90">Explanation</button>`
                            : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Explanation</button>`;

                        // QUESTION SPLIT IMAGE
                        const splitQuestions = selectedQuestionGroup[0].questions.split('<img');
                        const questionTextOnly = splitQuestions[0];

                        let questionImage = '', textAfterImage = '';

                        if (splitQuestions.length > 1) {
                            const imgSplit = splitQuestions[1].split('>'); // pisahkan tag <img> dan sisa teks
                            const imgTag = imgSplit[0]; // bagian src dan atribut gambar
                            const restText = imgSplit.slice(1).join('>'); // gabungkan sisa setelah tag img

                            questionImage = `<img class="max-w-[100%]" ${imgTag}>`; // Susun tag <img> lengkap dengan class tambahan
                            textAfterImage = restText.trim(); // Hapus spasi berlebih pada teks setelah gambar
                        }

                        // Gabungkan menjadi HTML: bungkus gambar dan teks
                        const questionImageAndTextAfter = `
                            <div class="flex flex-col gap-4 items-start">
                                ${questionImage}
                                <div>${textAfterImage}</div>
                            </div>
                        `;

                        const content = `
                            <div class="grid grid-cols-1 xl:grid-cols-4 bg-white shadow rounded-xl border">
                                <!--- passage content  --->
                                <div class="col-span-3">
                                    <div class="flex flex-col sm:flex-row sm:justify-between pt-6 px-6 gap-4 sm:gap-2">

                                        <!-- Passage (kiri) -->
                                        <div class="flex justify-center font-semibold">
                                            <span class="text-lg"> Passage ${page} </span>
                                        </div>

                                        <!-- Reading + Level (mobile) -->
                                        <div class="flex flex-row items-center justify-between gap-4 text-xs sm:hidden">
                                            <span>Reading Exam Test</span>
                                            <span>${response.levelName}</span>
                                        </div>

                                        <!-- Reading (tengah, muncul sm ke atas) -->
                                        <div class="hidden text-center items-center sm:flex text-xs">
                                            Reading Exam Test
                                        </div>

                                        <!-- Level (kanan, muncul sm ke atas) -->
                                        <div class="hidden sm:flex text-xs text-right items-center">
                                            ${response.levelName}
                                        </div>

                                    </div>

                                    <div class="border-t border-gray-300 mt-2 mb-4 mx-6"></div>

                                    <div
                                        class="overflow-y-auto xl:max-h-[600px] text-sm px-6">
                                        ${passage}
                                    </div>
                                </div>

                                <!-- Navigation Panel -->
                                <div class="col-span-1 p-6 space-y-4 bg-gray-50 border mt-8 xl:mt-0">
                                    <h3 class="font-semibold text-sm sm:text-lg border-b border-gray-300 pb-2">Questions Number</h3>
                                    <div id="container-questions-number" class="grid grid-cols-6 gap-1 text-center text-xs">
                                        ${nomorSoalHTML}
                                    </div>
                                </div>

                                <!-- Questions Below -->
                                <div class="col-span-3 mt-8">
                                    <form id="bank-soal-quiz-exam-reading-test-question-form" data-level-id="${levelId}" data-passage-id="${activePassageId}">
                                        <span class="text-sm sm:text-lg font-semibold px-6 pt-4">Answer The Question Below</span>
                                        <div class="m-4 space-y-4 border border-gray-300 p-3 rounded-md">
                                            <div class="flex gap-2">${selectedIndex + 1}. ${questionTextOnly}</div>
                                            <div>${questionImageAndTextAfter}</div>

                                            <input type="hidden" name="question_id" value="${selectedQuestionGroup[0].id}">
                                            <input type="hidden" name="level_id" value="${levelId}">
                                            <input type="hidden" name="passage_id" value="${activePassageId}">
                                            <input type="hidden" name="user_answer_option" id="userAnswer${selectedQuestionGroup[0].id}" value="">
                                            <input type="hidden" name="question_score" id="question_score" value="${response.scoreEachQuestion}">
                                            <input type="hidden" name="subscription_history_id" value="${subscription ? subscription.id : 0}">
                                            <input type="hidden" name="current_page" id="current_page" value="${response.page}">
                                            <span id="error-user_answer_option" class="text-red-500 font-bold text-xs pt-2"></span>

                                            ${generateOptions(selectedQuestionGroup)}
                                        </div>

                                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 m-4">
                                            <div id="button-submit-practice-answer">${buttonSubmitAnswerHTML}</div>
                                            <div id="button-correct-or-wrong-answer">${buttonCorrectOrWrongHTML}</div>
                                            <div id="button-pembahasan" class="col-span-2 lg:col-span-1">${buttonPembahasanHTML}</div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <dialog id="my_modal_1" class="modal">
                                <div class="modal-box bg-white max-w-7xl max-h-[600px]">
                                    <div class="flex justify-center w-full mb-4">
                                        <span class="text-2xl font-bold opacity-70">Explanation</span>
                                    </div>
                                    ${videoExplanation}
                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button onclick="closePembahasanModal()">close</button>
                                </form>
                            </dialog>
                        `;

                        container.innerHTML = content;
                    });

                    // Append pagination links
                    $('.pagination-container-question-reading-exam-test').html(response.links);
                    $('#container-score-exam').show();
                    bindPaginationLinks(); // Bind click event ke link pagination yang baru

                    // Set nomor soal aktif
                    $(`#nomor${selectedIndex}`).prop('checked', true);

                    // Tampilkan soal berdasarkan nomor soal yang di klik user
                    $(document).off('click', '.nomor-soal').on('click', '.nomor-soal', function () {
                        const index = parseInt($(this).data('index'));
                        currentQuestionIndex = index;
                        quizReadingExamTest(page, index);
                    });
                } else {

                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }
}

$(document).ready(function () {
    quizReadingExamTest();
});

function bindPaginationLinks() {
    $('.pagination-container-question-reading-exam-test').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        quizReadingExamTest(page, currentQuestionIndex); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// Listener radio -> update input hidden
$(document).on('change', 'input[type="radio"][name^="options_value_"]', function () {
    const soalId = $(this).data('soal-id');
    const selectedOption = $(this).val();
    $(`#userAnswer${soalId}`).val(selectedOption);
    $('#error-user_answer_option').text('');
});

let isProcessing = false;
// Submit form jawaban
$(document).on('submit', '#bank-soal-quiz-exam-reading-test-question-form', function (e) {
    e.preventDefault();

    if (isProcessing) return; // Abaikan jika sedang proses

    isProcessing = true; // Tandai sedang diproses

    const levelId = $(this).data('level-id');
    const passageId = $(this).data('passage-id');
    const formData = new FormData(this);

    const btn = $(this).find('button');

    btn.prop('disabled', true);

    $.ajax({
        url: `/english-zone-student/${levelId}/${passageId}/quiz/reading-exam-test/answers`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            // jika success, inisialisasi content untuk memunculkan soal yang terakhir dikerjakan
            const page = $('#current_page').val();
            quizReadingExamTest(page, currentQuestionIndex);

            isProcessing = false;
            btn.prop('disabled', false);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const response = xhr.responseJSON.errors;
                $.each(response, function (field, messages) {
                    $(`#error-${field}`).text(messages[0]);
                });

                isProcessing = false;
                btn.prop('disabled', false);
            }
        }
    });
});

// Tampilkan pembahasan melalaui modal
function showExplanation(element) {
    const modal = document.getElementById('my_modal_1');
    const iframe = document.getElementById('video-frame');

    const videoId = element.getAttribute('data-video-id');

    if (iframe && videoId) {
        iframe.src = `https://www.youtube.com/embed/${videoId}`;
    }

    modal.showModal();
}

function closePembahasanModal() {
    const iframe = document.getElementById('video-frame');
    if (iframe) {
        iframe.src = ''; // remove the video after close modal
    }
}