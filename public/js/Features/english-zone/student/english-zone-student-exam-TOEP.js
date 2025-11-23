let currentQuestionIndex = 0; // Global, default ke soal pertama

function fetchExamQuestionsForm(levelId, sessionId, selectedIndex = 0) {
    $.ajax({
        url: `/english-zone-student/${levelId}/${sessionId}/exam-TOEP/form`,
        method: 'GET',
        success: function (response) {
            const container = document.getElementById('exam-questions-form');
            const containerExamForm = $('#exam-questions-content');
            if (!container) return;

            containerExamForm.empty();

            // Mendapatkan data soal
            const groupedQuestions = response.data;
            // Mendapatkan data jawaban
            const questionsAnswer = response.questionsAnswer;

            // Cek apakah data soal kosong
            if (groupedQuestions.length === 0) return;

            // Mendapatkan soal berdasarkan nomor soal
            const soalGroup = groupedQuestions[selectedIndex];
            // Mendapatkan soal pertama beserta field nya (untuk explanation, question, skilltag, difficulty)
            const soal = soalGroup[0];

            // Hitung jumlah soal
            const totalSoal = groupedQuestions.length;

            // Hitung jumlah soal yang sudah dijawab
            let jumlahSoalTerjawab = 0;
            groupedQuestions.forEach((group) => {
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

            // Jika semua soal sudah dijawab, tampilkan konten
            if (isAllAnswered) {
                const scoreExam = response.scoreExam; // mengambil nilai ujian

                $('#score-exam').text(scoreExam); // menampilkan nilai ujian
            }

            const subscription = response.subscription;
            const now = new Date(response.now);

            let startDate = null;
            let endDate = null;

            if (subscription) {
                startDate = new Date(subscription.start_date);
                endDate = new Date(subscription.end_date);
            }

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

            // Helper untuk generate pilihan jawaban (option)
            const generateOptions = (group) => {
                const optionKeys = ['A', 'B', 'C', 'D', 'E'];

                const shuffleOptions = [...group];

                return shuffleOptions.map((item, index) => {
                    const newKey = optionKeys[index];
                    const containsImage = /<img\s+[^>]*src=/.test(item.options_value);

                    // Tambahkan class img jika ada gambar
                    if (containsImage) {
                        content = addClassToImgTags(item.options_value, 'max-w-[300px] rounded my-2');
                    }

                    let statusClass = '';

                    // Memeriksa apakah semua soal sudah dijawab
                    if (isAllAnswered) {
                        // jika jawaban user sudah disimpan (tanda '?' sebelum field pada questionsAnswer adalah untuk akses properti dari data yang belum pasti ada)
                        if (questionsAnswer[soal.id]) {
                            // Memeriksa apakah pilihan jawaban yang dipilih user sudah benar, jika benar maka tampilkan opsi dengan warna hijau
                            if (questionsAnswer[soal.id]?.user_answer_option === soal.answer_key && questionsAnswer[soal.id].user_answer_option === item.options_key) {
                                statusClass = 'bg-green-200 text-green-600 font-bold';
                                // Memeriksa apakah pilihan jawaban yang dipilih user salah, jika salah maka tampilkan opsi dengan warna merah
                            } else if (questionsAnswer[soal.id].user_answer_option !== soal.answer_key && questionsAnswer[soal.id].user_answer_option === item.options_key) {
                                statusClass = 'bg-red-200 text-red-600 font-bold';
                                // memeriksa apakah jawaban ada, dan jika jawaban user salah maka tampilkan opsi jawaban benar
                            } else if (questionsAnswer[soal.id].user_answer_option && item.options_key === soal.answer_key) {
                                statusClass = 'bg-green-200 text-green-600 font-bold';
                            }
                        }
                    } else {
                        if (questionsAnswer[soal.id]?.user_answer_option === item.options_key) {
                            statusClass = 'bg-gray-200 font-bold opacity-70';
                        }
                    }

                    let optionsValue = '';

                    // memeriksa apakah soal sudah dijawab oleh pengguna
                    if (questionsAnswer[soal.id]) {
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
                                <input type="radio" name="options_value_${soal.id}" id="soal${item.options_key}" value="${item.options_key}" class="hidden" data-soal-id="${soal.id}">
                                <label for="soal${item.options_key}" class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] cursor-pointer checked-option ${statusClass}">
                                    <div class="font-bold min-w-[30px]">${newKey}.</div>
                                    <div class="w-full flex flex-col gap-8">${item.options_value}</div>
                                </label>
                            `;
                        } else {
                            optionsValue = `
                                <input type="radio" name="options_value_${soal.id}" id="soal${item.options_key}" value="${item.options_key}" class="hidden" data-soal-id="${soal.id}">
                                <label for="soal${item.options_key}" class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] cursor-pointer checked-option ${statusClass}">
                                    ${newKey}. ${item.options_value}
                                </label>
                            `;
                        }
                    }

                    // Render opsi jawaban
                    return `
                        ${optionsValue}
                    `;
                }).join('');
            };

            // Render Nomor Soal
            const nomorSoalHTML = groupedQuestions.map((group, index) => {
                let statusClassNumberQuestions = '';

                // Memeriksa apakah semua soal sudah dijawab
                if (isAllAnswered) {
                    // Memeriksa apakah soal sudah disimpan oleh pengguna dan jawaban benar
                    if (questionsAnswer[group[0].id]?.user_answer_option === group[0].answer_key) {
                        statusClassNumberQuestions = '!bg-green-200 text-green-600 font-bold';

                        // Memeriksa apakah soal sudah disimpan oleh pengguna dan jawaban salah
                    } else if (questionsAnswer[group[0].id]?.user_answer_option !== group[0].answer_key) {
                        statusClassNumberQuestions = '!bg-red-200 text-red-600 font-bold';
                    }
                    // Jika soal belum dijawab semua
                } else {
                    // Memeriksa apakah soal sudah disimpan oleh pengguna dan question_id sesuai dengan soal yang dilihat
                    // menggunakan group[0] jika ingin membuat dan melihat semua aktif, jika menggunakan soal.id hanya akan aktif jika soal nya sedang dilihat
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
            // Mengecek apakah soal sudah dijawab oleh pengguna dan jawaban sudah disimpan
            const isAnswered = !!questionsAnswer[soal.id]; // `!!` akan mengubah nilai tersebut menjadi boolean `true` atau `false`.

            // ambil jawaban user untuk soal ini (tangani dua kemungkinan bentuk: string atau object)
            const userAnswer = questionsAnswer[soal.id]?.user_answer_option ?? questionsAnswer[soal.id];

            // cek benar/salah berdasar jawaban user yang sebenarnya
            const isCorrect = isAnswered && (userAnswer === soal.answer_key);

            // show button submit answer
            // memeriksa apakah soal sudah dijawab oleh pengguna, jika sudah maka button menjadi disabled
            let buttonSubmitAnswerHTML = '';
            if (subscription) {
                buttonSubmitAnswerHTML = isAnswered
                    ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Simpan Jawaban</button>`
                    : `<button id="button-submit-exam-answer" class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[--color-default] text-white font-bold rounded-md hover:brightness-90" data-level-id="${levelId}" data-session-id="${sessionId}">Simpan Jawaban</button>`;
            } else if (!subscription) {
                buttonSubmitAnswerHTML = `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Simpan Jawaban</button>`
            }

            const buttonCorrectOrWrongHTML = isAllAnswered
                ? (isCorrect
                    ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-green-200 text-green-600 font-bold rounded-md" disabled>Jawaban Benar</button>`
                    : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-red-200 text-red-600 font-bold opacity-70 rounded-md" disabled>Jawaban Salah</button>`)
                : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Jawaban Benar/Salah</button>`;

            // SHOW EXPLANATION VIDEO OR TEXT
            const videoId = response.videoIds[selectedIndex];

            // show button pembahasan
            // memeriksa apakah soal sudah dijawab oleh pengguna, jika sudah maka dapat melihat pembahasan
            const buttonPembahasanHTML = isAllAnswered
                ? `<button type="button" onclick="showExplanation(this)" data-video-id="${videoId}"
                    class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[#4189E0] text-white font-bold rounded-md hover:brightness-90">Pembahasan</button>`
                : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Pembahasan</button>`;

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
                <div class="flex flex-col items-start gap-4">${soal.explanation}</div>
            `;


            // QUESTION SPLIT IMAGE
            const splitQuestions = soal.questions.split('<img');
            const questionTextOnly = splitQuestions[0];

            let questionImage = '', textAfterImage = '';

            if (splitQuestions.length > 1) {
                const imgSplit = splitQuestions[1].split('>'); // pisahkan tag <img> dan sisa teks
                const imgTag = imgSplit[0]; // bagian src dan atribut gambar
                const restText = imgSplit.slice(1).join('>'); // gabungkan sisa setelah tag img

                questionImage = `<img class="max-w-[75%]" ${imgTag}>`; // Susun tag <img> lengkap dengan class tambahan
                textAfterImage = restText.trim(); // Hapus spasi berlebih pada teks setelah gambar
            }

            // Gabungkan menjadi HTML: bungkus gambar dan teks
            const questionImageAndTextAfter = `
                <div class="flex flex-col gap-4 items-start">
                    ${questionImage}
                    <div>${textAfterImage}</div>
                </div>
            `;

            // Final Render HTML
            const formHtml = `
                <form id="bank-soal-exam-question-form" data-level-id="${levelId}" data-session-id="${sessionId}">
                    <div class="h-max bg-white shadow-lg border pb-4 flex flex-col xl:flex-row gap-8 p-4">
                        <div class="w-full xl:w-[70%] h-max order-2 xl:order-none">

                            <div class="flex gap-4">
                                <div class="border border-gray-400 py-[4px] w-2/4 lg:w-[80%] flex items-center text-sm justify-center font-bold opacity-70">Soal Ujian</div>
                                <div id="difficulty" class="border border-gray-400 py-[4px] w-2/4 lg:w-[20%] text-sm text-center font-bold opacity-70">Level: ${soal.difficulty}</div>
                            </div>

                            <div id="soal-container" class="exam-question-form border border-gray-400 py-6 px-4 w-full my-6">
                                <div class="mb-4">${questionTextOnly}</div>
                                <div>${questionImageAndTextAfter}</div>

                                <input type="hidden" name="question_id" value="${soal.id}">
                                <input type="hidden" name="user_answer_option" id="userAnswer${soal.id}" value="${questionsAnswer[soal.id]?.user_answer_option ?? ''}">
                                <input type="hidden" name="status_answer" id="statusAnswer" value="">
                                <input type="hidden" name="question_score" id="question_score" value="${response.scoreEachQuestion}">
                                <input type="hidden" name="subscription_history_id" id="subscription_history_id" value="${subscription ? subscription.id : 0}">
                                <span id="error-user_answer_option" class="text-red-500 font-bold text-xs pt-2"></span>

                                <div>${generateOptions(soalGroup)}</div>
                            </div>

                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                <div id="button-submit-exam-answer">${buttonSubmitAnswerHTML}</div>
                                <div id="button-correct-or-wrong-answer">${buttonCorrectOrWrongHTML}</div>
                                <div id="button-pembahasan" class="col-span-2 lg:col-span-1">${buttonPembahasanHTML}</div>
                            </div>
                        </div>

                        <div class="w-full xl:w-[30%] order-1 xl:order-none">
                            <div class="text-center mb-6 border border-gray-400 py-[4px] text-sm font-bold opacity-70">Nomor Soal</div>
                            <div id="nomor-soal-container" class="grid grid-cols-6 gap-1 text-center text-xs border border-gray-400">${nomorSoalHTML}</div>
                        </div>
                    </div>
                </form>

                <dialog id="my_modal_1" class="modal">
                    <div class="modal-box bg-white max-w-7xl max-h-[600px]">
                        <div class="flex justify-center w-full mb-4">
                            <span class="text-2xl font-bold opacity-70">Pembahasan</span>
                        </div>
                        ${videoExplanation}
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button onclick="closePembahasanModal()">close</button>
                    </form>
                </dialog>
            `;

            // Render HTML
            container.innerHTML = formHtml;

            // Set nomor soal aktif
            $(`#nomor${selectedIndex}`).prop('checked', true);

            // Tampilkan soal berdasarkan nomor soal yang di klik user
            $(document).off('click', '.nomor-soal').on('click', '.nomor-soal', function () {
                const index = parseInt($(this).data('index'));
                currentQuestionIndex = index;
                fetchExamQuestionsForm(levelId, sessionId, index);
            });
        }
    });
}


// Fetch soal ujian
function examQuestions() {
    const container = document.getElementById('exam-questions-form');
    if (!container) return;

    const levelId = container.dataset.levelId;
    const sessionId = container.dataset.sessionId;

    if (!levelId) return;
    if (!sessionId) return;

    fetchExamQuestionsForm(levelId, sessionId, currentQuestionIndex);
}

// Inisialisasi saat halaman siap
$(document).ready(function () {
    examQuestions();
});

// Listener radio -> update input hidden
$(document).on('change', 'input[type="radio"][name^="options_value_"]', function () {
    const soalId = $(this).data('soal-id');
    const selectedOption = $(this).val();
    $(`#userAnswer${soalId}`).val(selectedOption);
    $('#error-user_answer_option').text('');
});

// Submit form jawaban
$(document).on('submit', '#bank-soal-exam-question-form', function (e) {
    e.preventDefault();
    const levelId = $(this).data('level-id');
    const sessionId = $(this).data('session-id');
    const formData = new FormData(this);

    $.ajax({
        url: `/english-zone-student/${levelId}/${sessionId}/exam-TOEP/answers`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            // jika success, inisialisasi content untuk memunculkan soal yang terakhir dikerjakan
            fetchExamQuestionsForm(levelId, sessionId, currentQuestionIndex);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const response = xhr.responseJSON.errors;
                $.each(response, function (field, messages) {
                    $(`#error-${field}`).text(messages[0]);
                });
            }
        }
    });
});

// Tampilkan pembahasan soal ujian melalaui modal
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
