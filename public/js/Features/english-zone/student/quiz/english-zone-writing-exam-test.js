let currentQuestionIndex = 0; // Global, default ke soal pertama

function quizWritingExamTest(page = 1, selectedIndex = 0) {
    const container = document.getElementById('container-quiz-writing-exam-test');
    if (!container) return;

    const levelId = container.dataset.levelId;
    if (!levelId) return;

    fetchquizWritingExamTest(levelId, page, selectedIndex);

    function fetchquizWritingExamTest(levelId, page, selectedIndex) {
        $.ajax({
            url: `/english-zone/${levelId}/quiz/writing-exam-test/form`,
            method: 'GET',
            data: {
                page: page,
            },
            success: function (response) {
                const TODAY_DATE = response.today;
                localStorage.setItem('timer_exam_today', TODAY_DATE); // simpan untuk semua fungsi timer

                const activePassageId = response.passage_id;

                const EXPIRE_KEY = `timer_exam_expire_${TODAY_DATE}_${activePassageId}`;
                const STARTED_KEY = `timer_exam_started_${TODAY_DATE}_${activePassageId}`;
                const expireTime = localStorage.getItem(EXPIRE_KEY);

                // JIKA TIMER SUDAH HABIS SAAT PAGE LOAD
                let shouldAutoSubmit = false;

                if (expireTime && parseInt(expireTime) <= Date.now()) {
                    const AUTO_SUBMIT_KEY = `timer_exam_autosubmit_${TODAY_DATE}_${activePassageId}`;
                    localStorage.setItem(AUTO_SUBMIT_KEY, 'true');

                    shouldAutoSubmit = true;
                }

                const isTimerRunning = localStorage.getItem(STARTED_KEY) && expireTime && parseInt(expireTime) > Date.now();

                const questionsAnswer = response.questionsAnswer;

                const examAnswerDuration = response.examAnswerDuration; // mengambil durasi pengerjaan ujian

                const subscription = response.subscription;
                const subscriptionEntitlement = response.subscriptionEntitlement;

                let startDate = null;
                let endDate = null;

                if (subscription) {
                    startDate = new Date(subscription.start_date);
                    endDate = new Date(subscription.end_date);
                }

                // jika tidak ada passages yang aktif maka tampilkan pesan
                if (response.data.length === 0) {
                    container.innerHTML = `<div class="p-6 font-bold opacity-70 flex justify-center">Tidak ada passage yang aktif pada quiz ini.</div>`;
                    return;
                }

                // jika passage dan soal aktif, maka tampilkan data
                else if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {

                        let passageContent = item.passage_content;

                        passageContent = passageContent.replace(/<img /g, '<img class="max-w-[100%] lg:max-w-[550px] rounded my-2"');

                        // Mengecek apakah soal sudah dijawab oleh pengguna
                        const isAnswered = questionsAnswer?.[activePassageId]?.length > 0;

                        const passage = `
                            <div class="passage-content space-y-4 text-justify ${!isAnswered && (subscriptionEntitlement ? subscriptionEntitlement.subscription_status === 'aktif' : '') ? 'blur-[3px]' : ''}">
                                ${passageContent} 
                            </div>
                        `;

                        // BUTTON LOGIC
                        if (!subscription) {
                            $('#timer-duration').text('-'); // menampilkan durasi pengerjaan ujian
                        }

                        // Jika semua soal sudah dijawab, tampilkan konten
                        if (isAnswered) {
                            $('#timer-duration').text(examAnswerDuration); // menampilkan durasi pengerjaan ujian
                        } else {
                            $('#timer-duration').text('-');
                        }
                        
                        // show button submit answer
                        // variabel kosong
                        let buttonSubmitAnswerHTML = '';
                        let buttonStartExamHTML = '';

                        let submitContainerClass = 'hidden';
                        let startContainerClass = 'hidden';

                        if (subscription) {
                            if (isAnswered) {
                                // sudah dijawab → submit disabled, tampil
                                stopTimerExam();
                                buttonSubmitAnswerHTML = `
                                    <button class="border py-[6px] w-max px-12 text-xs lg:text-sm 
                                    bg-gray-200 opacity-70 rounded-md" disabled>
                                        Submit answer
                                    </button>
                                `;
                                submitContainerClass = '';
                            } else {
                                // belum dijawab → submit aktif + start exam
                                buttonSubmitAnswerHTML = `
                                    <button id="button-submit-exam-answer"
                                        class="border py-[6px] w-max px-12 text-xs lg:text-sm 
                                        bg-[--color-default] text-white font-bold rounded-md hover:brightness-90"
                                        data-level-id="${levelId}"
                                        data-passage-id="${activePassageId}">
                                        Submit answer
                                    </button>
                                `;

                                buttonStartExamHTML = `
                                    <button type="button"
                                        class="border py-[6px] w-max px-12 text-xs lg:text-sm 
                                        bg-[--color-default] text-white font-bold rounded-md hover:brightness-90"
                                        onclick="buttonStartTimerExam()">
                                        Start exam
                                    </button>
                                `;

                                submitContainerClass = 'hidden';
                                startContainerClass = '';
                            }
                        } else {
                            // tidak berlangganan
                            buttonSubmitAnswerHTML = `
                                <button class="border py-[6px] w-max px-12 text-xs lg:text-sm 
                                bg-gray-200 opacity-70 rounded-md" disabled>
                                    Submit answer
                                </button>
                            `;

                            buttonStartExamHTML = `
                                <button class="border py-[6px] w-max px-12 text-xs lg:text-sm 
                                bg-gray-200 opacity-70 rounded-md" disabled>
                                    Start exam
                                </button>
                            `;

                            submitContainerClass = '';
                            startContainerClass = 'hidden';
                        }

                        let textarea = '';

                        if (subscription) {
                            textarea = isAnswered
                                ? `
                                    <textarea type="text" id="user_answer_text_${activePassageId}" name="user_answer_text"
                                        class="w-full mt-4 p-4 text-sm resize-none min-h-52 max-h-96 border-gray-200 border outline-none rounded-md px-4 focus:border-[1px]"
                                        placeholder="Write your answer here..." readonly>${questionsAnswer?.[activePassageId] ?? ''}</textarea>
                                `
                                : `
                                    <textarea type="text" id="user_answer_text_${activePassageId}" name="user_answer_text"
                                        class="w-full mt-4 p-4 text-sm resize-none min-h-52 max-h-96 border-gray-200 border outline-none rounded-md px-4 focus:border-[1px]"
                                        placeholder="Write your answer here..." readonly>${questionsAnswer?.[activePassageId] ?? ''}</textarea>
                                `
                        } else if (!subscription) {
                            textarea = `
                                    <textarea type="text" id="user_answer_text_${activePassageId}" name="user_answer_text"
                                        class="w-full mt-4 p-4 text-sm resize-none min-h-52 max-h-96 border-gray-200 border outline-none rounded-md px-4 focus:border-[1px]"
                                        placeholder="Write your answer here..." readonly>${questionsAnswer?.[activePassageId] ?? ''}</textarea>
                            `
                        }

                        const content = `
                            <div class="grid grid-cols-1 xl:grid-cols-4 bg-white shadow rounded-xl border">
                                <!--- passage content  --->
                                <div class="col-span-2">
                                    <div class="flex flex-col sm:flex-row sm:justify-between pt-6 px-6 gap-4 sm:gap-2">

                                        <!-- Passage (kiri) -->
                                        <div class="flex justify-center font-semibold">
                                            <span class="text-lg"> Task ${page} </span>
                                        </div>

                                        <!-- writing + Level (mobile) -->
                                        <div class="flex flex-row items-center justify-between gap-4 text-xs sm:hidden">
                                            <span>Writing Exam Test</span>
                                            <span>${response.levelName}</span>
                                        </div>

                                        <!-- writing (tengah, muncul sm ke atas) -->
                                        <div class="hidden text-center items-center sm:flex text-xs">
                                            Writing Exam Test
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

                                    <div class="mx-6 border-y border-gray-300 my-8">
                                        <div
                                            class="toggleButton w-full flex items-center py-2 rounded-lg border p-0 text-sm !font-light">
                                                <span>Example Answer</span>
                                                <i class="fa-solid fa-chevron-up icon"></i>
                                        </div>

                                        <div class="content-accordion">
                                            <div class="example-answer space-y-2 overflow-y-auto max-h-[600px] text-sm pr-6 py-4 ${!isAnswered && (subscriptionEntitlement ? subscriptionEntitlement.subscription_status === 'aktif' : '') ? 'blur-[3px]' : ''}">
                                                ${item.example_answer ?? 'No example answer found.'}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-span-2 border px-4 mt-8 xl:mt-0">
                                    <form id="bank-soal-quiz-exam-writing-test-question-form" data-level-id="${levelId}" data-passage-id="${activePassageId}">
                                        <input type="hidden" name="level_id" value="${levelId}">
                                        <input type="hidden" name="passage_id" value="${activePassageId}">
                                        <input type="hidden" name="subscription_history_id" value="${subscription ? subscription.id : 0}">
                                        <input type="hidden" name="current_page" id="current_page" value="${response.page}">
                                        ${textarea}
                                            <span id="error-user_answer_text" class="text-red-500 font-bold text-xs pt-2"></span>

                                        <div id="button-submit-exam-answer" class="w-full flex justify-end my-4">
                                            <div id="container-button-submit-exam-answer" class="${submitContainerClass}">
                                                ${buttonSubmitAnswerHTML}
                                            </div>

                                            <div class="container-button-start-exam-answer ${startContainerClass}">
                                                ${buttonStartExamHTML}
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        `;

                        container.innerHTML = content;

                        const TOTAL_SECONDS = 60 * 30; // durasi ujian (UI)

                        const timerExam = document.getElementById('timer-exam');
                        if (!isAnswered) {
                            timerExam.textContent = formatTime(TOTAL_SECONDS);
                        } else if (isAnswered) {
                            timerExam.textContent = '-';
                        }

                        // JIKA UJIAN SUDAH SELESAI → BERSIHKAN TOTAL
                        setTimeout(() => {
                            const AUTO_SUBMIT_KEY = `timer_exam_autosubmit_${TODAY_DATE}_${activePassageId}`;

                            if (localStorage.getItem(AUTO_SUBMIT_KEY)) {
                                autoSubmitAnswer();
                                localStorage.removeItem(AUTO_SUBMIT_KEY);
                            }
                        }, 300);


                        // LANJUTKAN TIMER (REFRESH)
                        setTimeout(() => {
                            if (isTimerRunning) {
                                startTimerExam();
                                $('#container-button-submit-exam-answer').show();
                                $('.container-button-start-exam-answer').hide();

                                // buka textarea
                                const textarea = document.querySelector('[name="user_answer_text"]');
                                textarea?.removeAttribute('readonly');
                            }
                        }, 50); // beri delay kecil agar elemen sudah siap
                    });

                    // Append pagination links
                    $('.pagination-container-question-writing-exam-test').html(response.links);
                    bindPaginationLinks();
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
    const urlParams = new URLSearchParams(window.location.search);
    const page = parseInt(urlParams.get('page')) || 1;

    quizWritingExamTest(page, currentQuestionIndex);
});

function bindPaginationLinks() {
    $('.pagination-container-question-writing-exam-test').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault();

        const url = new URL(this.href);
        const page = new URL(this.href).searchParams.get('page');

        // UPDATE URL BROWSER
        window.history.pushState({}, '', `?page=${page}`);

        const TODAY_DATE = localStorage.getItem('timer_exam_today');
        const passageId = document.getElementById('bank-soal-quiz-exam-writing-test-question-form')?.dataset.passageId;

        const EXPIRE_KEY = `timer_exam_expire_${TODAY_DATE}_${passageId}`;
        const STARTED_KEY = `timer_exam_started_${TODAY_DATE}_${passageId}`;
        const expireTime = localStorage.getItem(EXPIRE_KEY);

        const isTimerRunning = localStorage.getItem(STARTED_KEY) && expireTime && parseInt(expireTime) > Date.now();

        if (isTimerRunning) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Maaf, kamu tidak dapat mengakses passage lain disaat ujian berlangsung.',
            });
            return;
        }

        pauseTimerExam();
        
        quizWritingExamTest(page, currentQuestionIndex);
    });
}

function formatTime(seconds) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m} Menit ${String(s).padStart(2, '0')} Detik`;
}

const toggles = document.getElementsByClassName('toggleButton');
const contentDiv = document.getElementsByClassName('content-accordion');
const icons = document.getElementsByClassName('icon');

// Buka accordion pertama secara default
if (contentDiv.length > 0) {
    contentDiv[0].style.height = contentDiv[0].scrollHeight + "px";
    icons[0].classList.remove('fa-chevron-up');
    icons[0].classList.add('fa-chevron-down');
}

document.addEventListener("click", function (e) {
    const toggle = e.target.closest('.toggleButton');
    if (!toggle) return;

    for (let i = 0; i < toggles.length; i++) {
        if (toggles[i] === toggle) {
            if (contentDiv[i].style.height && contentDiv[i].style.height !== "0px") {
                contentDiv[i].style.height = "0px";
                toggles[i].style.color = "#111130";
                icons[i].classList.remove('fa-chevron-down');
                icons[i].classList.add('fa-chevron-up');
            } else {
                contentDiv[i].style.height = contentDiv[i].scrollHeight + "px";
                toggles[i].style.color = "";
                icons[i].classList.remove('fa-chevron-up');
                icons[i].classList.add('fa-chevron-down');
            }

            for (let j = 0; j < contentDiv.length; j++) {
                if (j !== i) {
                    contentDiv[j].style.height = "0px";
                    toggles[j].style.color = "#111130";
                    icons[j].classList.remove('fa-chevron-down');
                    icons[j].classList.add('fa-chevron-up');
                }
            }
        }
    }
});

function buttonStartTimerExam() {
    const form = document.getElementById('bank-soal-quiz-exam-writing-test-question-form');

    const levelId = form?.dataset.levelId;

    $.ajax({
        url: `/english-zone/${levelId}/quiz/writing-exam-test/form`,
        method: 'GET',
        success: function (res) {
            if (!res.subscription) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Maaf, paket kamu sudah tidak aktif.',
                });

                resetTimerExam();
                return;
            }

            startTimerExam();
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memverifikasi paket.',
            });
        }
    });
}

let isProcessing = false;
// Submit form jawaban
$(document).on('submit', '#bank-soal-quiz-exam-writing-test-question-form', function (e) {
    e.preventDefault();
    if (isProcessing) return; // Abaikan jika sedang proses

    isProcessing = true; // Tandai sedang diproses

    const levelId = $(this).data('level-id');
    const passageId = $(this).data('passage-id');
    const formData = new FormData(this);

    const btn = $(this).find('button');

    btn.prop('disabled', true);

    $.ajax({
        url: `/english-zone/${levelId}/quiz/writing-exam-test/form`,
        method: 'GET',
        success: function (res) {
            if (!res.subscription) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Maaf, paket kamu sudah tidak aktif.',
                });

                const page = $('#current_page').val();
                quizWritingExamTest(page, currentQuestionIndex);

                resetTimerExam();

                $('#timer-exam').text('-');

                return;
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memverifikasi paket.',
            });
        }
    });

    // untuk mengirim data duration
    const TODAY_DATE = localStorage.getItem('timer_exam_today');
    const START_KEY = `timer_exam_start_${TODAY_DATE}_${passageId}`;
    const startTime = localStorage.getItem(START_KEY);

    let duration = '00 Menit 00 Detik';

    if (startTime) {
        const MAX_DURATION = 60 * 30; // sama dengan totalSeconds

        let used = Math.floor((Date.now() - parseInt(startTime)) / 1000);
        used = Math.min(used, MAX_DURATION); // BATASI

        duration = `${Math.floor(used / 60)} Menit ${String(used % 60).padStart(2, '0')} Detik`;
    }

    formData.append('exam_answer_duration', duration);

    $.ajax({
        url: `/english-zone-student/${levelId}/${passageId}/quiz/writing-exam-test/answers`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#alert-success-submit-answer').html(`
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
            // jika success, inisialisasi content untuk memunculkan soal yang terakhir dikerjakan
            const page = $('#current_page').val();
            quizWritingExamTest(page, currentQuestionIndex);

            isProcessing = false;
            btn.prop('disabled', false);

            stopTimerExam();
            $('#timer-exam').text('-');

            $('.container-button-start-exam-answer').hide();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const response = xhr.responseJSON.errors;
                $.each(response, function (field, messages) {
                    $(`#error-${field}`).text(messages[0]);
                });
            }
            isProcessing = false;
            btn.prop('disabled', false);
        }
    });
});

// Fungsi untuk auto submit soal yang belum disimpan (saat waktu habis, ujian belum selesai)
function autoSubmitAnswer() {
    const form = document.getElementById('bank-soal-quiz-exam-writing-test-question-form');
    if (!form) {
        console.warn('Form not ready, skip autosubmit');
        return;
    }

    const levelId = form.dataset.levelId;
    const passageId = form.dataset.passageId;
    const subscriptionId = form.querySelector('[name="subscription_history_id"]')?.value;

    if (!levelId || !passageId || !subscriptionId) {
        console.warn('Autosubmit aborted: incomplete data');
        return;
    }

    const textarea = form.querySelector('[name="user_answer_text"]');

    const TODAY_DATE = localStorage.getItem('timer_exam_today');
    const START_KEY = `timer_exam_start_${TODAY_DATE}_${passageId}`;
    const startTime = localStorage.getItem(START_KEY);

    let duration = '00 Menit 00 Detik';
    if (startTime) {
        const MAX_DURATION = 60 * 30; // sama dengan totalSeconds

        let used = Math.floor((Date.now() - parseInt(startTime)) / 1000);
        used = Math.min(used, MAX_DURATION); // BATASI

        duration = `${Math.floor(used / 60)} Menit ${String(used % 60).padStart(2, '0')} Detik`;
    }

    const formData = new FormData();
    formData.append('subscription_history_id', subscriptionId);
    formData.append('level_id', levelId);
    formData.append('passage_id', passageId);
    formData.append('user_answer_text', textarea?.value?.trim() || '-');
    formData.append('exam_answer_duration', duration);

    $.ajax({
        url: `/english-zone-student/${levelId}/${passageId}/quiz/writing-exam-test/answers`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            resetTimerExam();

            // REFRESH UI SETELAH DATA MASUK DB
            const page = document.getElementById('current_page')?.value || 1;
            quizWritingExamTest(page, currentQuestionIndex);
        },
        error: function (xhr) {
            console.error('Auto submit failed', xhr.responseText);
        }
    });
}


// function untuk menampilkan modal jika waktu habis
function emptyTime() {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Maaf, waktu ujian kamu sudah habis.',
    });
}

let countdown = null;
function startTimerExam() {
    if (countdown !== null) {
        clearInterval(countdown);
        countdown = null;
    }

    const TODAY_DATE = localStorage.getItem('timer_exam_today');
    if (!TODAY_DATE) return;

    const passageId = document.getElementById('bank-soal-quiz-exam-writing-test-question-form')?.dataset.passageId;

    const START_KEY = `timer_exam_start_${TODAY_DATE}_${passageId}`;
    const EXPIRE_KEY = `timer_exam_expire_${TODAY_DATE}_${passageId}`;
    const STARTED_KEY = `timer_exam_started_${TODAY_DATE}_${passageId}`;

    const timerExam = document.getElementById('timer-exam');

    const expireTime = localStorage.getItem(EXPIRE_KEY);

    // JIKA ADA EXPIRE TAPI SUDAH HABIS → BERSIHKAN TOTAL
    if (expireTime && parseInt(expireTime) <= Date.now()) {
        emptyTime();
        autoSubmitAnswer();
        resetTimerExam();
        return;
    }

    // LANJUTKAN TIMER (REFRESH)
    if (localStorage.getItem(STARTED_KEY) && expireTime && parseInt(expireTime) > Date.now() ) {
        const remaining = Math.ceil((parseInt(expireTime) - Date.now()) / 1000);
        runCountdown(remaining);
        return;
    }

    // START BARU
    startNewCountdown();

    function startNewCountdown() {
        const totalSeconds = 60 * 30; // GANTI SESUAI DURASI
        const startTime = Date.now();
        const expireTime = startTime + totalSeconds * 1000;

        localStorage.setItem(START_KEY, startTime);
        localStorage.setItem(EXPIRE_KEY, expireTime);
        localStorage.setItem(STARTED_KEY, 'true');

        runCountdown(totalSeconds);
    }

    function runCountdown(seconds) {
        const textarea = document.querySelector('[name="user_answer_text"]');
        const passageContent = document.querySelector('.passage-content');
        const exampleAnswer = document.querySelector('.example-answer');

        textarea.removeAttribute('readonly'); // hapus readonly ketika timer berjalan
        passageContent.classList.replace('blur-[3px]', 'blur-0');
        exampleAnswer.classList.replace('blur-[3px]', 'blur-0');

        update(seconds);

        countdown = setInterval(() => {
            seconds--;
            update(seconds);

            if (seconds <= 0) {
                clearInterval(countdown);
                countdown = null;

                timerExam.textContent = 'Waktu habis';
                emptyTime();
                autoSubmitAnswer();
            }
        }, 1000);
    }

    function update(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        timerExam.textContent = `${m} Menit ${s.toString().padStart(2, '0')} Detik`;

        $('#container-button-submit-exam-answer').show();
        $('.container-button-start-exam-answer').hide();
    }
}

function pauseTimerExam() {
    // hentikan interval, tapi JANGAN hapus localStorage
    clearInterval(countdown);
    countdown = null;

    const timerExam = document.getElementById('timer-exam');
    if (timerExam) timerExam.textContent = '-';
}

function resetTimerExam() {
    const TODAY_DATE = localStorage.getItem('timer_exam_today');
    if (!TODAY_DATE) return;

    clearInterval(countdown);
    countdown = null;

    const passageId = document.getElementById('bank-soal-quiz-exam-writing-test-question-form')?.dataset.passageId;

    localStorage.removeItem(`timer_exam_started_${TODAY_DATE}_${passageId}`);
    localStorage.removeItem(`timer_exam_start_${TODAY_DATE}_${passageId}`);
    localStorage.removeItem(`timer_exam_expire_${TODAY_DATE}_${passageId}`);
    localStorage.removeItem(`timer_exam_autosubmit_${TODAY_DATE}_${passageId}`);

    $('.container-button-start-exam-answer').show();
    $('#container-button-submit-exam-answer').hide();
}

function stopTimerExam() {
    clearInterval(countdown);
    countdown = null;

    const TODAY_DATE = localStorage.getItem('timer_exam_today');
    if (!TODAY_DATE) return;

    const passageId = document
        .getElementById('bank-soal-quiz-exam-writing-test-question-form')
        ?.dataset.passageId;

    const START_KEY = `timer_exam_start_${TODAY_DATE}_${passageId}`;
    const EXPIRE_KEY = `timer_exam_expire_${TODAY_DATE}_${passageId}`;

    const startTime = parseInt(localStorage.getItem(START_KEY));
    const expireTime = parseInt(localStorage.getItem(EXPIRE_KEY));
    if (!startTime || !expireTime) return;

    const STARTED_KEY = `timer_exam_started_${TODAY_DATE}_${passageId}`;
    localStorage.removeItem(STARTED_KEY);

    // Hapus data dari localStorage setelah ujian selesai
    localStorage.removeItem(START_KEY);
    localStorage.removeItem(EXPIRE_KEY);
}
