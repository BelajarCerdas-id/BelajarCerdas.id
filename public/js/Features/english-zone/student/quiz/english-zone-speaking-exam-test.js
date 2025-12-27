let currentQuestionIndex = 0; // Global, default ke soal pertama

function quizSpeakingExamTest(page = 1, selectedIndex = 0) {
    const container = document.getElementById('container-quiz-speaking-exam-test');
    if (!container) return;

    const levelId = container.dataset.levelId;
    if (!levelId) return;

    fetchquizSpeakingExamTest(levelId, page, selectedIndex);

    function fetchquizSpeakingExamTest(levelId, page, selectedIndex) {
        $.ajax({
            url: `/english-zone/${levelId}/quiz/speaking-exam-test/form`,
            method: 'GET',
            data: {
                page: page,
            },
            success: function (response) {
                const activePassageId = response.passage_id;

                const questionsAnswer = response.questionsAnswer;

                const subscription = response.subscription;

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
                            <div class="passage-content space-y-4 text-justify">
                                ${passageContent} 
                            </div>
                        `;

                        // show button submit answer
                        // variabel kosong
                        let buttonSubmitAnswerHTML = '';

                        let submitContainerClass = 'hidden';

                        if (subscription) {
                            if (isAnswered) {
                                // sudah dijawab → submit disabled, tampil
                                buttonSubmitAnswerHTML = `
                                    <button id="btn-submit-speaking"
                                        class="w-max text-xs py-2 px-4 bg-[--color-default] text-white rounded font-bold disabled:opacity-50" disabled>
                                            Submit Answer
                                    </button>
                                `;

                                submitContainerClass = '';
                            } else {
                                // belum dijawab → submit aktif + start exam
                                buttonSubmitAnswerHTML = `
                                    <button id="btn-submit-speaking"
                                        class="w-max text-xs py-2 px-4 bg-[--color-default] text-white rounded font-bold disabled:opacity-50" disabled>
                                            Submit Answer
                                    </button>
                                `;

                                submitContainerClass = '';
                            }
                        } else {
                            // tidak berlangganan
                            buttonSubmitAnswerHTML = `
                                    <button id="btn-submit-speaking"
                                        class="w-max text-xs py-2 px-4 bg-[--color-default] text-white rounded font-bold disabled:opacity-50" disabled>
                                            Submit Answer
                                    </button>
                            `;

                            submitContainerClass = '';
                        }

                        let user_answer_audio = '';

                        if (questionsAnswer?.[activePassageId]?.length > 0) {
                            user_answer_audio = `
                                <audio controls controlsList="nodownload">
                                    <source src="/english-zone-audio/${questionsAnswer?.[activePassageId] ?? ''}" type="audio/webm">
                                    Browser kamu tidak mendukung audio tag.
                                </audio>
                            `;
                        } else {
                            user_answer_audio = '';
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

                                        <!-- speaking + Level (mobile) -->
                                        <div class="flex flex-row items-center justify-between gap-4 text-xs sm:hidden">
                                            <span>Speaking Exam Test</span>
                                            <span>${response.levelName}</span>
                                        </div>

                                        <!-- speaking (tengah, muncul sm ke atas) -->
                                        <div class="hidden text-center items-center sm:flex text-xs">
                                            Speaking Exam Test
                                        </div>

                                        <!-- Level (kanan, muncul sm ke atas) -->
                                        <div class="hidden sm:flex text-xs text-right items-center">
                                            ${response.levelName}
                                        </div>

                                    </div>

                                    <div class="border-t border-gray-300 mt-2 mb-4 mx-6"></div>

                                    <div
                                        class="overflow-y-auto xl:max-h-[600px] text-sm px-6 mb-4">
                                        ${passage}
                                    </div>
                                </div>

                                <div class="col-span-2 border px-4 mt-8 xl:mt-0">
                                    <form id="speaking-exam-test-form" data-level-id="${levelId}" data-passage-id="${activePassageId}">
                                        <input type="hidden" name="level_id" value="${levelId}">
                                        <input type="hidden" name="passage_id" value="${activePassageId}">
                                        <input type="hidden" name="subscription_history_id" value="${subscription ? subscription.id : 0}">
                                        <input type="hidden" name="current_page" id="current_page" value="${response.page}">
                                            <div class="bg-gray-50 border rounded-lg p-4 mt-4 space-y-4">

                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-solid fa-stopwatch text-[#4189E0] font-bold"></i>
                                                        <p class="text-xs opacity-70">Speaking Duration</p>
                                                    </div>
                                                    <p id="speak-timer" class="text-lg font-bold">00:00</p>
                                                </div>

                                                ${user_answer_audio}

                                                <button id="btn-start-record" type="button"
                                                    class="text-xs px-4 py-2 bg-[--color-default] text-white font-bold rounded ${questionsAnswer?.[activePassageId] ? 'opacity-50' : ''}" 
                                                    ${questionsAnswer?.[activePassageId] ? 'disabled' : ''}>
                                                    Start Speaking
                                                </button>

                                                <button id="btn-stop-record" type="button"
                                                    class="text-xs px-4 py-2 bg-red-500 text-white font-bold rounded hidden" disabled>
                                                    Stop Speaking
                                                </button>

                                                <audio id="audio-preview" controls class="hidden" name="user_answer_audio"></audio>

                                                <button id="btn-reset-record" type="button"
                                                    class="text-xs px-4 py-2 bg-[--color-default] text-white font-bold rounded hidden">
                                                    Reset Voice
                                                </button>
                                            </div>

                                            <!-- Submit -->
                                            <div class="w-full flex justify-end my-4" class="${submitContainerClass}">
                                                ${buttonSubmitAnswerHTML}
                                            </div>
                                    </form>
                                </div>
                            </div>

                        `;

                        container.innerHTML = content;

                        $(document).off('click', '#btn-start-record').on('click', '#btn-start-record', function () {
                            // sebelum start record, cek subscription dahulu
                            $.ajax({
                                url: `/english-zone/${levelId}/quiz/speaking-exam-test/form`,
                                method: 'GET',
                                success: function (response) {
                                    // jika tidak ada subscription aktif, maka tampilkan error
                                    if (!response.subscription) {
                                        subscriptionEmpty();
                                    } else {
                                        startSpeaking();
                                    }
                                },
                                error: function () {
                                    swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Gagal memverifikasi paket.',
                                    });
                                }
                            });
                        });

                        $(document).off('click', '#btn-stop-record').on('click', '#btn-stop-record', function () {
                            stopRecording();
                        });

                        $(document).off('click', '#btn-reset-record').on('click', '#btn-reset-record', function () {
                            resetRecording();
                        });
                    });

                    // Append pagination links
                    $('.pagination-container-question-speaking-exam-test').html(response.links);
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
    quizSpeakingExamTest();
});

function bindPaginationLinks() {
    $('.pagination-container-question-speaking-exam-test').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        quizSpeakingExamTest(page, currentQuestionIndex); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

function checkSubscription() {
    const levelId = $('#speaking-exam-test-form').data('level-id');
    $.ajax({
        url: `/english-zone/${levelId}/quiz/speaking-exam-test/form`,
        method: 'GET',
        success: function (response) {
            if (!response.subscription) {
                subscriptionEmpty();
                return;
            }
        },
        error: function () {
            swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memverifikasi paket.',
            });
        }
    });
}

function subscriptionEmpty() {
    swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Maaf, kamu tidak dapat mengakses ujian ini, karena kamu tidak memiliki paket aktif pada fitur ini. Silahkan aktifkan paket terlebih dahulu.',
    })
}


let isProcessing = false;
// Submit form jawaban
$(document).on('submit', '#speaking-exam-test-form', function (e) {
    e.preventDefault();
    if (isProcessing) return; // Abaikan jika sedang proses

    isProcessing = true; // Tandai sedang diproses

    const levelId = $(this).data('level-id');
    const passageId = $(this).data('passage-id');
    const formData = new FormData(this);

    // sebelum submit cek subscription dahulu
    checkSubscription();

    if (recordedAudioBlob) {
        formData.append('user_answer_audio', recordedAudioBlob, 'speaking.webm');
    }

    const btn = $(this).find('button');

    btn.prop('disabled', true);

    $.ajax({
        url: `/english-zone-student/${levelId}/${passageId}/quiz/speaking-exam-test/answers`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const subscription = response.subscription;

            if (subscription) {
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
            }
            // jika success, inisialisasi content untuk memunculkan soal yang terakhir dikerjakan
            const page = $('#current_page').val();
            quizSpeakingExamTest(page);

            isProcessing = false;
            btn.prop('disabled', false);
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

let mediaRecorder = null;
let audioChunks = [];
let recordedAudioBlob = null;

let speakingInterval = null;
let speakingStartTime = null;
let speakingSeconds = 0;
let stopEnabled = false;

async function startRecording() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true
            }
        });

        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];

        mediaRecorder.ondataavailable = (e) => {
            if (e.data.size > 0) {
                audioChunks.push(e.data);
            }
        };

        mediaRecorder.onstart = () => {
            speakingStartTime = Date.now();
            startSpeakingTimer();

            // pastikan stop bisa aktif
            setTimeout(() => {
                const btnStop = document.getElementById('btn-stop-record');
                btnStop.disabled = false;
                stopEnabled = true;
            }, 1100); // > 1 detik real
        };


        mediaRecorder.onstop = () => {
            recordedAudioBlob = new Blob(audioChunks, { type: 'audio/webm;codecs=opus' });

            const audioUrl = URL.createObjectURL(recordedAudioBlob);
            const audio = document.getElementById('audio-preview');
            const btnReset = document.getElementById('btn-reset-record');

            audio.src = audioUrl;
            audio.volume = 1.0;
            audio.classList.remove('hidden');

            audio.onloadedmetadata = () => {
                // Durasi REAL audio
                const realSeconds = Math.floor(audio.duration);
                updateTimerUI(realSeconds);
            };

            btnReset.classList.remove('hidden');
            document.getElementById('btn-submit-speaking').disabled = false;
        };

        mediaRecorder.start();
    } catch (err) {
        document.getElementById('btn-start-record').classList.remove('hidden');
        document.getElementById('btn-stop-record').classList.add('hidden');

        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Microphone permission is required.',
        });

        return;
    }
}

async function startSpeaking() {
    speakingRemaining = 0;

    // Update UI awal
    document.getElementById('btn-start-record').classList.add('hidden');
    document.getElementById('btn-stop-record').classList.remove('hidden');

    // mulai recording
    await startRecording();
}

function updateTimerUI(seconds) {
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    document.getElementById('speak-timer').textContent = `${m}:${s}`;
}

function startSpeakingTimer() {
    stopSpeakingTimer();

    speakingSeconds = 0;
    stopEnabled = false;

    const btnStop = document.getElementById('btn-stop-record');
    btnStop.disabled = true;

    speakingInterval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - speakingStartTime) / 1000);
        speakingSeconds = elapsed;

        updateTimerUI(elapsed);

        if (!stopEnabled && elapsed >= 1) {
            btnStop.disabled = false;
            stopEnabled = true; // kunci sekali saja
        }
    }, 200);
}

function stopRecording() {
    stopSpeakingTimer();

    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }
    document.getElementById('btn-stop-record').classList.add('hidden');
}

function stopSpeaking() {
    // Stop timer
    if (speakingInterval) {
        clearInterval(speakingInterval);
        speakingInterval = null;
    }

    // Stop recording
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }

    // UI state
    document.getElementById('btn-stop-record').classList.add('hidden');
}

function stopSpeakingTimer() {
    if (speakingInterval) {
        clearInterval(speakingInterval);
        speakingInterval = null;
    }
}

function resetRecording() {
    stopSpeakingTimer();

    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }

    document.getElementById('speak-timer').textContent = '00:00';

    // add btn hidden
    document.getElementById('btn-stop-record').classList.add('hidden');
    document.getElementById('audio-preview').classList.add('hidden');
    document.getElementById('btn-reset-record').classList.add('hidden');

    // remove btn hidden
    document.getElementById('btn-start-record').classList.remove('hidden');

    // disable btn
    document.getElementById('btn-submit-speaking').disabled = true;
    document.getElementById('btn-stop-record').disabled = true;
}
