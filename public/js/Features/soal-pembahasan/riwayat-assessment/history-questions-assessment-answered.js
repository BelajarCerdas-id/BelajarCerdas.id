let currentQuestionIndex = 0; // Global, default ke soal pertama

function historyQuestionsAssessmentAnswered(materiId, tipeSoal, date, kelasId, mapelId, selectedIndex = 0) {
    $.ajax({
        url: `/soal-pembahasan/riwayat-assessment/${materiId}/${tipeSoal}/${date}/${kelasId}/${mapelId}/questions`,
        method: 'GET',
        success: function (response) {
            const container = document.getElementById('history-questions-assessment-answered');
            if (!container) return;

            // groupedQuestions adalah array yang berisi grup soal
            const groupedQuestions = response.data;

            // questionsAnswer adalah objek yang menyimpan jawaban user
            const questionsAnswer = response.questionsAnswer;

            // Jika tidak ada soal (data kosong), maka hentikan proses
            if (groupedQuestions.length === 0) return;

            // Ambil grup soal berdasarkan indeks soal yang sedang dipilih user (selectedIndex)
            const soalGroup = groupedQuestions[selectedIndex];

            // Cari soal pertama dalam grup ini yang sudah dijawab oleh user
            // `questionsAnswer[q.id]` akan bernilai truthy kalau soal itu sudah dijawab
            const answeredItem = soalGroup.find(q => questionsAnswer[q.id]);

            // Jika ditemukan soal yang sudah dijawab (answeredItem), maka gunakan itu
            // Jika tidak, pakai soal pertama dalam grup (soalGroup[0])
            const soal = answeredItem || soalGroup[0];

            // Helper untuk menambahkan class ke tag img
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
                // Inisialisasi array untuk menyimpan opsi jawaban A - E
                const optionKeys = ['A', 'B', 'C', 'D', 'E'];

                // Membuat salinan dari array group agar tidak merubah data aslinya
                // Dalam kasus ini, tidak dilakukan shuffle lagi untuk mempertahankan urutan opsi dari server
                const shuffleOptions = [...group];

                return shuffleOptions.map((item, index) => {
                    // Menentukan huruf label untuk opsi saat ini, berdasarkan urutan index (0 => A, 1 => B, dst)
                    const newKey = optionKeys[index]; // newKey ini hanya untuk memanipulasi visualisasi options agar tetap A - E, padahal aslinya option sudah di acak

                    const containsImage = /<img\s+[^>]*src=/.test(item.options_value);

                    // Tambahkan class img jika ada gambar
                    if (containsImage) {
                        content = addClassToImgTags(item.options_value, 'max-w-[300px] rounded my-2');
                    }

                    let statusClass = '';
                    // Memeriksa apakah pilihan jawaban yang dipilih user sudah benar, jika benar maka tampilkan opsi dengan warna hijau
                    if (questionsAnswer[soal.id] === soal.answer_key && questionsAnswer[soal.id] === item.options_key) {
                        statusClass = 'bg-green-200 text-green-600 font-bold';
                    // Memeriksa apakah pilihan jawaban yang dipilih user salah, jika salah maka tampilkan opsi dengan warna merah
                    } else if (questionsAnswer[soal.id] !== soal.answer_key && questionsAnswer[soal.id] === item.options_key) {
                        statusClass = 'bg-red-200 text-red-600 font-bold';
                        // memeriksa apakah jawaban ada, dan jika jawaban user salah maka tampilkan opsi jawaban benar
                    } else if (questionsAnswer[soal.id] && item.options_key === soal.answer_key) {
                        statusClass = 'bg-green-200 text-green-600 font-bold';
                    }

                    let optionsValue = '';

                    // memeriksa apakah soal sudah dijawab oleh pengguna
                    // if (questionsAnswer[soal.id]) {
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
                    // }

                    // Render opsi jawaban
                    return `
                        ${optionsValue}
                    `;
                }).join('');
            };

            // Render Nomor Soal
            // Mapping setiap grup soal untuk membuat elemen HTML tombol nomor soal
            const nomorSoalHTML = groupedQuestions.map((group, index) => {

                let statusClassNumberQuestions = '';

                // Mencari soal dalam grup ini yang sudah dijawab oleh user
                // `questionsAnswer` adalah objek yang menyimpan jawaban user, dengan key = question.id
                // Jadi kita mencari salah satu soal dalam group[] yang sudah dijawab user
                const answeredItem = group.find(q => questionsAnswer[q.id]);

                // Jika ada soal dalam grup ini yang sudah dijawab
                if (answeredItem) {
                    // Jika jawaban user sama dengan kunci jawaban soal tersebut
                    if (questionsAnswer[answeredItem.id] === answeredItem.answer_key) {
                        statusClassNumberQuestions = '!bg-green-200 text-green-600 font-bold';
                    } else {
                        statusClassNumberQuestions = '!bg-red-200 text-red-600 font-bold';
                    }
                }

                return `
                    <input type="radio" id="nomor${index}" name="nomorSoal" class="hidden">
                    <label for="nomor${index}" class="nomor-soal border border-gray-400 py-1 hover:bg-gray-200 cursor-pointer text-xs ${statusClassNumberQuestions}" data-index="${index}">
                        <span class="font-bold">${index + 1}</span>
                        ${group[0].status_soal === 'Premium' ? '<i class="fas fa-lock text-[--color-default]"></i>' : ''}
                    </label>
                `;
            }).join('');

            // BUTTON LOGIC

            // Mengecek apakah soal sudah dijawab oleh pengguna
            const isAnswered = !!questionsAnswer[soal.id]; // `!!` akan mengubah nilai tersebut menjadi boolean `true` atau `false`.

            // Mengecek apakah jawaban pengguna sama dengan jawaban yang benar
            const isCorrect = questionsAnswer[soal.id] === soal.answer_key;

            // show button submit answer
            // memeriksa apakah soal sudah dijawab oleh pengguna, jika sudah maka button menjadi disabled
            const buttonSubmitAnswerHTML = isAnswered
                ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Simpan Jawaban</button>`
                : `<button id="button-submit-practice-answer" class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[--color-default] text-white font-bold rounded-md hover:brightness-90">Simpan Jawaban</button>`;

            // show button correct or wrong answer
            // memeriksa apakah soal sudah dijawab oleh pengguna dan apakah jawaban user benar / salah
            const buttonCorrectOrWrongHTML = isAnswered
                ? (isCorrect
                    ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-green-200 text-green-600 font-bold rounded-md" disabled>Jawaban Benar</button>`
                    : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-red-200 text-red-600 font-bold opacity-70 rounded-md" disabled>Jawaban Salah</button>`)
                    : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Jawaban Benar/Salah</button>`;

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
                        <div class="flex flex-col items-start gap-4">${soal.explanation}</div>
                    `;

            // show button pembahasan
            // memeriksa apakah soal sudah dijawab oleh pengguna, jika sudah maka dapat melihat pembahasan
            const buttonPembahasanHTML = isAnswered
                ? `<button type="button" onclick="showExplanation(this)" data-video-id="${videoId}"
                    class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[#4189E0] text-white font-bold rounded-md hover:brightness-90">Pembahasan</button>`
                : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Pembahasan</button>`;

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
                    <div class="h-max bg-white shadow-lg border pb-4 flex flex-col xl:flex-row gap-8 p-4">
                        <div class="w-full xl:w-[70%] h-max order-2 xl:order-none">

                            <div class="flex gap-4">
                                <div class="border border-gray-400 py-[4px] w-2/4 lg:w-[80%] flex items-center text-sm justify-center font-bold opacity-70">Soal Latihan</div>
                                <div id="difficulty" class="border border-gray-400 py-[4px] w-2/4 lg:w-[20%] text-sm text-center font-bold opacity-70">Level: ${soal.difficulty}</div>
                            </div>

                            <div id="soal-container" class="border border-gray-400 py-6 px-4 w-full my-6">
                                <div class="mb-4">${questionTextOnly}</div>
                                <div>${questionImageAndTextAfter}</div>

                                <input type="hidden" name="question_id" value="${soal.id}">
                                <input type="hidden" name="user_answer_option" id="userAnswer${soal.id}" value="">
                                <span id="error-user_answer_option" class="text-red-500 font-bold text-xs pt-2"></span>

                                <div>${generateOptions(soalGroup)}</div>
                            </div>

                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                <div id="button-submit-practice-answer">${buttonSubmitAnswerHTML}</div>
                                <div id="button-correct-or-wrong-answer">${buttonCorrectOrWrongHTML}</div>
                                <div id="button-pembahasan" class="col-span-2 lg:col-span-1">${buttonPembahasanHTML}</div>
                            </div>
                        </div>

                        <div class="w-full xl:w-[30%] order-1 xl:order-none">
                            <div class="text-center mb-6 border border-gray-400 py-[4px] text-sm font-bold opacity-70">Nomor Soal</div>
                            <div id="nomor-soal-container" class="grid grid-cols-6 gap-1 text-center text-xs border border-gray-400">${nomorSoalHTML}</div>
                        </div>
                    </div>

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
                historyQuestionsAssessmentAnswered(materiId, tipeSoal, date, kelasId, mapelId, index);
            });
        }
    });
}

$(document).ready(function () {
    const container = $('#history-questions-assessment-answered');

    const materiId = container.data('materiId');
    const tipeSoal = container.data('tipeSoalId');
    const date = container.data('date');
    const kelas = container.data('kelasId');
    const mapel = container.data('mapelId');

    historyQuestionsAssessmentAnswered(materiId, tipeSoal, date, kelas, mapel, currentQuestionIndex);
});
