function quizWritingPracticeTest(page = 1, selectedIndex = 0) {
    const container = document.getElementById('container-quiz-writing-practice-test');
    if (!container) return;

    const levelId = container.dataset.levelId;
    if (!levelId) return;

    fetchquizWritingPracticeTest(levelId, page, selectedIndex);

    function fetchquizWritingPracticeTest(levelId, page, selectedIndex) {
        $.ajax({
            url: `/english-zone/${levelId}/quiz/writing-practice-test/form`,
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

                        const passage = `
                            <div class="space-y-4 text-justify">
                                ${passageContent} 
                            </div>
                        `;

                        // BUTTON LOGIC

                        // Mengecek apakah soal sudah dijawab oleh pengguna
                        const isAnswered = questionsAnswer?.[activePassageId]?.length > 0; // `!!` akan mengubah nilai tersebut menjadi boolean `true` atau `false`.

                        // show button submit answer
                        // variabel kosong
                        let buttonSubmitAnswerHTML = '';

                        if (subscription) {
                            buttonSubmitAnswerHTML = isAnswered
                                ? `<button class="border py-[6px] w-max px-12 text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Submit answer</button>`
                                : `<button id="button-submit-exam-answer" class="border py-[6px] w-max px-12 text-xs lg:text-sm text-center bg-[--color-default] text-white font-bold rounded-md hover:brightness-90"
                                    data-level-id="${levelId}" data-passage-id="${activePassageId}">Submit answer</button>`;
                        } else if (!subscription) {
                            buttonSubmitAnswerHTML = `<button class="border py-[6px] w-max px-12 text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Submit answer</button>`
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
                                        placeholder="Write your answer here...">${questionsAnswer?.[activePassageId] ?? ''}</textarea>
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
                                            <span>Writing Practice Test</span>
                                            <span>${response.levelName}</span>
                                        </div>

                                        <!-- writing (tengah, muncul sm ke atas) -->
                                        <div class="hidden text-center items-center sm:flex text-xs">
                                            Writing Practice Test
                                        </div>

                                        <!-- Level (kanan, muncul sm ke atas) -->
                                        <div class="hidden sm:flex text-xs text-right items-center">
                                            ${response.levelName}
                                        </div>

                                    </div>

                                    <div class="border-t border-gray-300 mt-2 mb-4 mx-6"></div>

                                    <div
                                        class="overflow-y-auto max-h-[600px] text-sm px-6">
                                        ${passage}
                                    </div>

                                <div class="mx-6 border-y border-gray-300 my-8">
                                    <div
                                        class="toggleButton w-full flex items-center py-2 rounded-lg border p-0 text-sm !font-light">
                                            <span>Example Answer</span>
                                            <i class="fa-solid fa-chevron-up icon"></i>
                                    </div>
    
                                    <div class="content-accordion">
                                        <div class="space-y-2 overflow-y-auto max-h-[600px] text-sm pr-6 py-4">
                                            ${item.example_answer ?? 'No example answer found.'}
                                        </div>
                                    </div>
                                </div>


                                </div>

                                <div class="col-span-2 border-0 xl:border px-4">
                                    <form id="bank-soal-quiz-practice-writing-test-question-form" data-level-id="${levelId}" data-passage-id="${activePassageId}">
                                        <input type="hidden" name="level_id" value="${levelId}">
                                        <input type="hidden" name="passage_id" value="${activePassageId}">
                                        <input type="hidden" name="subscription_history_id" value="${subscription ? subscription.id : 0}">
                                        <input type="hidden" name="current_page" id="current_page" value="${response.page}">
                                        ${textarea}
                                            <span id="error-user_answer_text" class="text-red-500 font-bold text-xs pt-2"></span>

                                        <div id="button-submit-practice-answer" class="w-full flex justify-end my-4">${buttonSubmitAnswerHTML}</div>
                                    </form>
                                </div>
                            </div>

                        `;

                        container.innerHTML = content;
                    });

                    // Append pagination links
                    $('.pagination-container-question-writing-practice-test').html(response.links);
                    bindPaginationLinks(); // Bind click event ke link pagination yang baru
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
    quizWritingPracticeTest();
});

function bindPaginationLinks() {
    $('.pagination-container-question-writing-practice-test').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        quizWritingPracticeTest(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

let isProcessing = false;
// Submit form jawaban
$(document).on('submit', '#bank-soal-quiz-practice-writing-test-question-form', function (e) {
    e.preventDefault();
    if (isProcessing) return; // Abaikan jika sedang proses

    isProcessing = true; // Tandai sedang diproses

    const levelId = $(this).data('level-id');
    const passageId = $(this).data('passage-id');
    const formData = new FormData(this);

    const btn = $(this).find('button');

    btn.prop('disabled', true);

    $.ajax({
        url: `/english-zone-student/${levelId}/${passageId}/quiz/writing-practice-test/answers`,
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
            quizWritingPracticeTest(page);

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

const toggles = document.getElementsByClassName('toggleButton');
const contentDiv = document.getElementsByClassName('content-accordion');
const icons = document.getElementsByClassName('icon');

// ✅ Buka accordion pertama secara default
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
// }
