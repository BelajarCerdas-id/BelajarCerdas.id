// let currentQuestionIndex = 0; // Global, default ke soal pertama

// function fetchExamQuestionsForm(babId, selectedIndex = 0) {
//     $.ajax({
//         url: `/soal-pembahasan/kelas/${babId}/assessment/ujian`,
//         method: 'GET',
//         success: function (response) {
//             const container = document.getElementById('exam-questions-form');
//             const containerExamForm = $('#exam-questions-content');
//             if (!container) return;

//             containerExamForm.empty();

//             // Mendapatkan data soal
//             const groupedQuestions = response.data;
//             // Mendapatkan data jawaban
//             const questionsAnswer = response.questionsAnswer;

//             // Cek apakah data soal kosong
//             if (groupedQuestions.length === 0) return;

//             // Mendapatkan soal berdasarkan nomor soal
//             const soalGroup = groupedQuestions[selectedIndex];
//             // Mendapatkan soal pertama beserta field nya (untuk explanation, question, skilltag, difficulty)
//             const soal = soalGroup[0];

//             // Hitung jumlah soal
//             const totalSoal = groupedQuestions.length;

//             // Hitung jumlah soal yang sudah dijawab (Saved)
//             let jumlahSoalTerjawab = 0;
//             groupedQuestions.forEach((group) => {
//                 // Mendapatkan id soal pada group pertama
//                 const questionId = group[0].id;
//                 // Mendapatkan jawaban sesuai id soal
//                 const jawaban = questionsAnswer[questionId];

//                 // Cek apakah jawaban sudah dijawab dan status jawaban adalah 'Saved'
//                 if (jawaban && (jawaban.status_answer === 'Saved')) {
//                     jumlahSoalTerjawab++;
//                 }
//             });

//             // Cek apakah semua soal sudah dijawab
//             const isAllAnswered = jumlahSoalTerjawab === totalSoal;

//             // Jika semua soal sudah dijawab, tampilkan konten
//             if (isAllAnswered) {
//                 const scoreExam = response.scoreExam; // mengambil nilai ujian
//                 const examAnswerDuration = response.examAnswerDuration; // mengambil durasi pengerjaan ujian

//                 $('#score-exam').text(scoreExam); // menampilkan nilai ujian
//                 $('#timer-duration').text(examAnswerDuration); // menampilkan durasi pengerjaan ujian
//             }

//             function addClassToImgTags(html, className) {
//                 return html
//                     .replace(/<img\b(?![^>]*class=)[^>]*>/g, (imgTag) => {
//                         // Tambahkan class jika belum ada atribut class
//                         return imgTag.replace('<img', `<img class="${className}"`);
//                     })
//                     .replace(/<img\b([^>]*?)class="(.*?)"/g, (imgTag, before, existingClasses) => {
//                         // Tambahkan class ke img yang sudah punya class
//                         return `<img ${before}class="${existingClasses} ${className}"`;
//                     });
//             }

//             // Helper untuk generate pilihan jawaban (option)
//             const generateOptions = (group) => {
//                 const optionKeys = ['A', 'B', 'C', 'D', 'E'];

//                 const shuffleOptions = [...group];

//                 return shuffleOptions.map((item, index) => {
//                     const newKey = optionKeys[index];
//                     const containsImage = /<img\s+[^>]*src=/.test(item.options_value);

//                     // Tambahkan class img jika ada gambar
//                     if (containsImage) {
//                         content = addClassToImgTags(item.options_value, 'max-w-[300px] rounded my-2');
//                     }

//                     let statusClass = '';

//                     // Memeriksa apakah semua soal sudah dijawab
//                     if (isAllAnswered) {
//                         // jika jawaban user sudah disimpan (tanda '?' sebelum field pada questionsAnswer adalah untuk akses properti dari data yang belum pasti ada)
//                         if (questionsAnswer[soal.id]?.status_answer === 'Saved') {
//                             // Memeriksa apakah pilihan jawaban yang dipilih user sudah benar, jika benar maka tampilkan opsi dengan warna hijau
//                             if (questionsAnswer[soal.id]?.user_answer_option === soal.answer_key && questionsAnswer[soal.id].user_answer_option === item.options_key) {
//                                 statusClass = 'bg-green-200 text-green-600 font-bold';
//                             // Memeriksa apakah pilihan jawaban yang dipilih user salah, jika salah maka tampilkan opsi dengan warna merah
//                             } else if (questionsAnswer[soal.id].user_answer_option !== soal.answer_key && questionsAnswer[soal.id].user_answer_option === item.options_key) {
//                                 statusClass = 'bg-red-200 text-red-600 font-bold';
//                                 // memeriksa apakah jawaban ada, dan jika jawaban user salah maka tampilkan opsi jawaban benar
//                             } else if (questionsAnswer[soal.id].user_answer_option && item.options_key === soal.answer_key) {
//                                 statusClass = 'bg-green-200 text-green-600 font-bold';
//                             }
//                         // jika jawaban user masih ditandai
//                         }
//                     } else {
//                         if (questionsAnswer[soal.id]?.user_answer_option === item.options_key && (questionsAnswer[soal.id]?.status_answer === 'Saved' || questionsAnswer[soal.id]?.status_answer === 'Draft')) {
//                             statusClass = 'bg-gray-200 font-bold opacity-70';
//                         }
//                     }

//                     let optionsValue = '';

//                     // memeriksa apakah soal sudah dijawab oleh pengguna atau jawaban masih ditandai
//                     if (!questionsAnswer[soal.id] || questionsAnswer[soal.id]?.status_answer === 'Draft') {
//                         // memeriksa apakah options_value terdapat image atau tidak
//                         if (containsImage) {
//                             optionsValue = `
//                                 <input type="radio" name="options_value_${soal.id}" id="soal${item.options_key}" value="${item.options_key}" class="hidden" data-soal-id="${soal.id}">
//                                 <label for="soal${item.options_key}" class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] cursor-pointer checked-option ${statusClass}">
//                                     <div class="font-bold min-w-[30px]">${newKey}.</div>
//                                     <div class="w-full flex flex-col gap-8">${item.options_value}</div>
//                                 </label>
//                             `;
//                         } else {
//                             optionsValue = `
//                                 <input type="radio" name="options_value_${soal.id}" id="soal${item.options_key}" value="${item.options_key}" class="hidden" data-soal-id="${soal.id}">
//                                 <label for="soal${item.options_key}" class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] cursor-pointer checked-option ${statusClass}">
//                                     ${newKey}. ${item.options_value}
//                                 </label>
//                             `;
//                         }
//                     // memeriksa apakah soal sudah dijawab oleh pengguna dan jawaban sudah disimpan
//                     } else if (questionsAnswer[soal.id] && questionsAnswer[soal.id]?.status_answer === 'Saved') {
//                         // memeriksa apakah options_value terdapat image atau tidak
//                         if (containsImage) {
//                             optionsValue = `
//                                 <div class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] checked-option ${statusClass}">
//                                     <div class="font-bold min-w-[30px]">${newKey}.</div>
//                                     <div class="w-full flex flex-col gap-8">${item.options_value}</div>
//                                 </div>
//                             `;
//                         } else {
//                             optionsValue = `
//                                 <div class="border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px] checked-option ${statusClass}">
//                                     ${newKey}. ${item.options_value}
//                                 </div>
//                             `;
//                         }
//                     }

//                     // Render opsi jawaban
//                     return `
//                         ${optionsValue}
//                     `;
//                 }).join('');
//             };

//             // Render Nomor Soal
//             const nomorSoalHTML = groupedQuestions.map((group, index) => {
//                 let statusClassNumberQuestions = '';

//                 // Memeriksa apakah semua soal sudah dijawab
//                 if (isAllAnswered) {
//                     // Memeriksa apakah soal sudah disimpan oleh pengguna dan jawaban benar
//                     if (questionsAnswer[group[0].id]?.status_answer === 'Saved' && questionsAnswer[group[0].id]?.user_answer_option === group[0].answer_key) {
//                         statusClassNumberQuestions = '!bg-green-200 text-green-600 font-bold';

//                     // Memeriksa apakah soal sudah disimpan oleh pengguna dan jawaban salah
//                     } else if (questionsAnswer[group[0].id]?.status_answer === 'Saved' && questionsAnswer[group[0].id]?.user_answer_option !== group[0].answer_key) {
//                         statusClassNumberQuestions = '!bg-red-200 text-red-600 font-bold';
//                     }
//                 // Jika soal belum dijawab semua
//                 } else {
//                     // Memeriksa apakah soal sudah disimpan oleh pengguna dan question_id sesuai dengan soal yang dilihat
//                     // menggunakan group[0] jika ingin membuat dan melihat semua status Saved aktif, jika menggunakan soal.id hanya akan aktif jika soal nya sedang dilihat
//                     if (questionsAnswer[group[0].id]?.status_answer === 'Saved' && questionsAnswer[group[0].id]?.question_id === group[0].id) {
//                         statusClassNumberQuestions = '!bg-[--color-default] text-white font-bold';

//                     // Memeriksa apakah soal masih ditandai oleh pengguna dan question_id sesuai dengan soal yang dilihat
//                     } else if (questionsAnswer[group[0].id]?.status_answer === 'Draft' && questionsAnswer[group[0].id]?.question_id === group[0].id) {
//                         statusClassNumberQuestions = '!bg-[#F79D65] text-white font-bold';

//                     // memeriksa jika soal belum dijawab oleh pengguna
//                     } else {
//                         statusClassNumberQuestions = '';
//                     }
//                 }

//                 return `
//                     <input type="radio" id="nomor${index}" name="nomorSoal" class="hidden">
//                     <label for="nomor${index}" class="nomor-soal border border-gray-400 py-1 hover:bg-gray-200 cursor-pointer text-xs ${statusClassNumberQuestions}" data-index="${index}">
//                         <span class="font-bold">${index + 1}</span>
//                         ${group[0].status_soal === 'Premium' ? '<i class="fas fa-lock text-[--color-default]"></i>' : ''}
//                     </label>
//                 `;
//             }).join('');

//             // BUTTON LOGIC
//             // Mengecek apakah soal sudah dijawab oleh pengguna dan jawaban sudah disimpan
//             const isAnswered = !!questionsAnswer[soal.id] && questionsAnswer[soal.id]?.status_answer === 'Saved'; // `!!` akan mengubah nilai tersebut menjadi boolean `true` atau `false`.

//             // show button submit answer
//             // memeriksa apakah soal sudah dijawab oleh pengguna, jika sudah maka button menjadi disabled
//             // kalau mau buat sistem waktu pengerjaan user setiap soal tambahkan onclick="stopTimerExam()"
//             const buttonSubmitAnswerHTML = isAnswered
//                 ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Simpan Jawaban</button>`
//                 : `<button id="button-submit-exam-answer" class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[--color-default] text-white font-bold rounded-md hover:brightness-90" data-bab-id="${babId}">Simpan Jawaban</button>`;

//             // show button correct or wrong answer
//             // memeriksa apakah soal sudah dijawab oleh pengguna dan apakah jawaban user benar / salah
//             // kalau mau buat sistem waktu pengerjaan user setiap soal tambahkan onclick="stopTimerExam()"
//             const buttonCorrectOrWrongHTML = isAnswered
//                 ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Tandai jawaban</button>`
//                 : `<button id="button-mark-exam-answer" class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[#F79D65] text-white font-bold hover:brightness-90 rounded-md" data-bab-id="${babId}">Tandai jawaban</button>`;

//                     const videoId = response.videoIds[selectedIndex];

//                     const videoExplanation = videoId ? `
//                         <div class="border max-w-7xl h-[500px] flex justify-start">
//                             <div class="w-full h-full">
//                                 <iframe id="video-frame" class="w-full h-full"
//                                     src="https://www.youtube.com/embed/${videoId}"
//                                     frameborder="0"
//                                     allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
//                                     allowfullscreen>
//                                 </iframe>
//                             </div>
//                         </div>
//                     ` : `
//                         <div class="flex flex-col items-start gap-4">${soal.explanation}</div>
//                     `;

//             // show button pembahasan
//             // memeriksa apakah soal sudah dijawab oleh pengguna, jika sudah maka dapat melihat pembahasan
//             const buttonPembahasanHTML = isAllAnswered
//                 ? `<button type="button" onclick="showExplanation(this)" data-video-id="${videoId}"
//                     class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[#4189E0] text-white font-bold rounded-md hover:brightness-90">Pembahasan</button>`
//                 : `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Pembahasan</button>`;

//             // QUESTION SPLIT IMAGE
//             const splitQuestions = soal.questions.split('<img');
//             const questionTextOnly = splitQuestions[0];

//             let questionImage = '', textAfterImage = '';

//             if (splitQuestions.length > 1) {
//                 const imgSplit = splitQuestions[1].split('>'); // pisahkan tag <img> dan sisa teks
//                 const imgTag = imgSplit[0]; // bagian src dan atribut gambar
//                 const restText = imgSplit.slice(1).join('>'); // gabungkan sisa setelah tag img

//                 questionImage = `<img class="max-w-[75%]" ${imgTag}>`; // Susun tag <img> lengkap dengan class tambahan
//                 textAfterImage = restText.trim(); // Hapus spasi berlebih pada teks setelah gambar
//             }

//             // Gabungkan menjadi HTML: bungkus gambar dan teks
//             const questionImageAndTextAfter = `
//                 <div class="flex flex-col gap-4 items-start">
//                     ${questionImage}
//                     <div>${textAfterImage}</div>
//                 </div>
//             `;

//             // waktu ujian akan berjalan jika ada salah satu soal yang telah dijawab / ditandai
//             if (questionsAnswer[soal.id] && !isAllAnswered) {
//                 startTimerExam();
//             } else if (isAllAnswered) {
//                 stopTimerExam();
//             }

//             // Final Render HTML
//             const formHtml = `
//                 <form id="bank-soal-exam-question-form" data-bab-id="${babId}">
//                     <div class="h-max bg-white shadow-lg border pb-4 flex flex-col xl:flex-row gap-8 p-4">
//                         <div class="w-full xl:w-[70%] h-max order-2 xl:order-none">

//                             <div class="flex gap-4">
//                                 <div class="border border-gray-400 py-[4px] w-2/4 lg:w-[80%] flex items-center text-sm justify-center font-bold opacity-70">Soal Ujian</div>
//                                 <div id="difficulty" class="border border-gray-400 py-[4px] w-2/4 lg:w-[20%] text-sm text-center font-bold opacity-70">Level: ${soal.difficulty}</div>
//                             </div>

//                             <div id="soal-container" class="exam-question-form border border-gray-400 py-6 px-4 w-full my-6">
//                                 <div class="mb-4">${questionTextOnly}</div>
//                                 <div>${questionImageAndTextAfter}</div>

//                                 <input type="hidden" name="question_id" value="${soal.id}">
//                                 <input type="hidden" name="user_answer_option" id="userAnswer${soal.id}" value="${questionsAnswer[soal.id]?.user_answer_option ?? ''}">
//                                 <input type="hidden" name="status_answer" id="statusAnswer"  value="">
//                                 <input type="hidden" name="question_score" id="question_score"  value="${response.scoreEachQuestion}">
//                                 <span id="error-user_answer_option" class="text-red-500 font-bold text-xs pt-2"></span>

//                                 <div>${generateOptions(soalGroup)}</div>
//                             </div>

//                             <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
//                                 <div id="button-submit-exam-answer">${buttonSubmitAnswerHTML}</div>
//                                 <div id="button-correct-or-wrong-answer">${buttonCorrectOrWrongHTML}</div>
//                                 <div id="button-pembahasan" class="col-span-2 lg:col-span-1">${buttonPembahasanHTML}</div>
//                             </div>
//                         </div>

//                         <div class="w-full xl:w-[30%] order-1 xl:order-none">
//                             <div class="text-center mb-6 border border-gray-400 py-[4px] text-sm font-bold opacity-70">Nomor Soal</div>
//                             <div id="nomor-soal-container" class="grid grid-cols-6 gap-1 text-center text-xs border border-gray-400">${nomorSoalHTML}</div>
//                         </div>
//                     </div>
//                 </form>

//                 <dialog id="my_modal_1" class="modal">
//                     <div class="modal-box bg-white max-w-7xl max-h-[600px]">
//                         <div class="flex justify-center w-full mb-4">
//                             <span class="text-2xl font-bold opacity-70">Pembahasan</span>
//                         </div>
//                         ${videoExplanation}
//                     </div>
//                     <form method="dialog" class="modal-backdrop">
//                         <button onclick="closePembahasanModal()">close</button>
//                     </form>
//                 </dialog>
//             `;

//             // Render HTML
//             container.innerHTML = formHtml;

//             // Set nomor soal aktif
//             $(`#nomor${selectedIndex}`).prop('checked', true);

//             // Tampilkan soal berdasarkan nomor soal yang di klik user
//             $(document).off('click', '.nomor-soal').on('click', '.nomor-soal', function () {
//                 const index = parseInt($(this).data('index'));
//                 currentQuestionIndex = index;
//                 fetchExamQuestionsForm(babId, index);
//             });
//         }
//     });
// }

// // Fetch soal latihan
// function examQuestions() {
//     const container = document.getElementById('exam-questions-form');
//     if (!container) return;

//     const babId = container.dataset.babId;
//     if (!babId) return;

//     fetchExamQuestionsForm(babId, currentQuestionIndex);
// }

// // Inisialisasi saat halaman siap
// $(document).ready(function () {
//     examQuestions();
// });

// // Listener untuk memilih jawaban pilihan ganda
// $(document).on('change', 'input[type="radio"][name^="options_value_"]', function () {
//     const soalId = $(this).data('soal-id'); // Ambil ID soal dari atribut data
//     const selectedOption = $(this).val(); // Ambil nilai pilihan user
//     $(`#userAnswer${soalId}`).val(selectedOption); // Update input hidden untuk jawaban user
//     $('#error-user_answer_option').text(''); // Hapus pesan error jika ada
// });

// // Listener tombol untuk menyimpan jawaban
// $(document).on('click', '#button-submit-exam-answer', function (e) {
//     e.preventDefault(); // Cegah submit form default
//     const form = $(this).closest('form')[0]; // Ambil form terdekat
//     const babId = $(form).data('bab-id'); // Ambil babId dari atribut data
//     submitExamAnswer('Saved', form, babId); // Kirim jawaban sebagai 'Saved'
// });

// // Listener tombol untuk menandai jawaban
// $(document).on('click', '#button-mark-exam-answer', function (e) {
//     e.preventDefault();
//     const form = $(this).closest('form')[0];
//     const babId = $(form).data('bab-id');
//     submitExamAnswer('Draft', form, babId); // Kirim jawaban sebagai 'Draft'
// });


// // Submit form answers
// function submitExamAnswer(status_answer, form, babId) {
//     const formData = new FormData(form); // Ambil seluruh data dari form
//     formData.append('status_answer', status_answer); // Tambahkan status jawaban

//     $.ajax({
//         url: `/soal-pembahasan/kelas/${babId}/assessment/ujian/answer`, // Endpoint penyimpanan jawaban
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Kirim token CSRF
//         },
//         data: formData,
//         processData: false,
//         contentType: false,
//         success: function () {
//             // Cek apakah semua soal sudah dijawab
//             $.get(`/soal-pembahasan/kelas/${babId}/assessment/ujian`, function (res) {
//                 const allAnswered = res.data.every(group => {
//                     const qid = group[0].id;
//                     return res.questionsAnswer[qid]?.status_answer === 'Saved';
//                 });

//                 if (allAnswered) {
//                     stopTimerExam(); // Hentikan timer jika semua soal sudah disimpan
//                 } else {
//                     fetchExamQuestionsForm(babId, currentQuestionIndex); // Refresh soal
//                 }
//             });
//         },
//         error: function (xhr) {
//             // Tampilkan error validasi
//             if (xhr.status === 422) {
//                 const response = xhr.responseJSON.errors;
//                 $.each(response, function (field, messages) {
//                     $(`#error-${field}`).text(messages[0]);
//                 });
//             }
//         }
//     });
// }

// // Fungsi untuk auto submit soal yang belum disimpan (saat waktu habis, ujian belum selesai)
// function autoSubmitUnSavedQuestions() {
//     const babId = document.getElementById('exam-questions-form')?.dataset.babId;
//     if (!babId) return;

//     // Ambil waktu mulai ujian dari localStorage (dalam bentuk timestamp milidetik saat ujian dimulai)
//     const startTime = localStorage.getItem('timer_exam_start');
//     // Inisialisasi default durasi waktu pengerjaan ujian sebagai 0 menit 0 detik (format string)
//     let duration = '00 Menit 00 Detik';

//     if (startTime) {
//         // menghitung durasi waktu ujian yang sudah digunakan peserta dalam satuan detik.
//         const timeUsed = Math.floor((Date.now() - parseInt(startTime)) / 1000);
//         // Hitung jumlah menit dari total detik
//         const minutes = Math.floor(timeUsed / 60);
//         // Hitung sisa detik setelah dikonversi ke menit
//         const seconds = timeUsed % 60;

//         // Format durasi sebagai string dengan 2 digit: "00 Menit 00 Detik" (padStart(2, '0') = tambahkan angka 0 di depan jika waktu kurang dari 2 digit)
//         duration = `${String(minutes).padStart(2, '0')} Menit ${String(seconds).padStart(2, '0')} Detik`;
//     }

//     $.ajax({
//         url: `/soal-pembahasan/kelas/${babId}/assessment/ujian`,
//         method: 'GET',
//         success: function (response) {
//             const groupedQuestions = response.data;
//             const questionsAnswer = response.questionsAnswer;
//             const formData = new FormData();
//             const scoreEachQuestion = response.scoreEachQuestion;

//             // Iterasi setiap soal
//             groupedQuestions.forEach(group => {
//                 const soal = group[0];

//                 // Soal belum dijawab
//                 if (!questionsAnswer[soal.id]) {
//                     formData.append('question_id', soal.id);
//                     formData.append('user_answer_option', '-');
//                     formData.append('status_answer', 'Saved');
//                     formData.append('question_score', 0);
//                     formData.append('exam_answer_duration', duration);
//                 }
//                 // jika soal sudah dijawab tetapi waktu habis duluan sebelum selesai ngerjain semua soal
//                 else if (questionsAnswer[soal.id]?.status_answer === 'Saved') {
//                     formData.append('question_id', soal.id);
//                     formData.append('user_answer_option', questionsAnswer[soal.id]?.user_answer_option);
//                     formData.append('status_answer', 'Saved');
//                     formData.append('question_score', scoreEachQuestion);
//                     formData.append('exam_answer_duration', duration);
//                 }
//                 // Soal ditandai (Draft)
//                 else if (questionsAnswer[soal.id]?.status_answer === 'Draft') {
//                     formData.append('question_id', soal.id);
//                     formData.append('user_answer_option', questionsAnswer[soal.id]?.user_answer_option);
//                     formData.append('status_answer', 'Saved');
//                     formData.append('question_score', scoreEachQuestion);
//                     formData.append('exam_answer_duration', duration);
//                 }

//                 // Submit setiap soal
//                 $.ajax({
//                     url: `/soal-pembahasan/kelas/${babId}/assessment/ujian/answer`,
//                     method: 'POST',
//                     headers: {
//                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                     },
//                     data: formData,
//                     processData: false,
//                     contentType: false,
//                 });
//             });

//             // Refresh tampilan setelah submit
//             fetchExamQuestionsForm(babId, currentQuestionIndex);
//             stopTimerExam();
//         }
//     });
// }


// // Tampilkan pembahasan soal ujian melalaui modal
// function showExplanation(element) {
//     const modal = document.getElementById('my_modal_1');
//     const iframe = document.getElementById('video-frame');

//     const videoId = element.getAttribute('data-video-id');

//     if (iframe && videoId) {
//         iframe.src = `https://www.youtube.com/embed/${videoId}`; // Set iframe ke video
//     }

//     modal.showModal(); // Tampilkan modal
// }

// function closePembahasanModal() {
//     const iframe = document.getElementById('video-frame');
//     if (iframe) {
//         iframe.src = ''; // Reset iframe agar video berhenti setelah tutup modal
//     }
// }


// // function untuk menampilkan modal jika waktu habis
// function emptyTime() {
//     Swal.fire({
//         icon: 'error',
//         title: 'Oops...',
//         text: 'Maaf, waktu ujian kamu sudah habis.',
//     });
// }


// let countdown = null; // Menyimpan interval ID untuk countdown timer agar bisa dihentikan nanti

// function startTimerExam() {
//     // Cegah timer ganda jika sudah berjalan
//     if (countdown !== null) return;

//     const timerExam = document.getElementById('timer-exam'); // Ambil elemen untuk menampilkan timer
//     const COUNTDOWN_KEY = 'timer_exam_expire'; // Key untuk menyimpan waktu ujian berakhir di localStorage

//     const expireTime = localStorage.getItem(COUNTDOWN_KEY); // Ambil waktu ujian berakhir dari localStorage

//     if (expireTime) {
//         const remaining = Math.floor((parseInt(expireTime) - Date.now()) / 1000); // Hitung sisa waktu dalam detik
//         if (remaining > 0) {
//             runCountdown(remaining); // Lanjutkan countdown jika waktu masih tersedia
//         } else {
//             localStorage.removeItem(COUNTDOWN_KEY); // Hapus waktu kadaluarsa lama
//             startResendCountdown(); // Mulai ulang timer jika waktu sudah habis
//         }
//     } else {
//         startResendCountdown(); // Jika belum pernah diset, mulai timer dari awal
//     }

//     // Fungsi untuk mulai timer baru dan simpan ke localStorage
//     function startResendCountdown() {
//         const totalSeconds = 60 * 60; // Total durasi ujian (contoh: 1 jam)
//         const expireTime = Date.now() + totalSeconds * 1000; // Waktu berakhir (dalam ms)
//         const startTime = Date.now(); // Waktu mulai sekarang
//         localStorage.setItem('timer_exam_start', startTime); // Simpan waktu mulai ke localStorage
//         localStorage.setItem(COUNTDOWN_KEY, expireTime); // Simpan waktu selesai ke localStorage
//         runCountdown(totalSeconds); // Jalankan countdown dari awal
//     }

//     // Fungsi untuk menjalankan countdown dan update tampilan setiap detik
//     function runCountdown(seconds) {
//         updateTimerDisplay(seconds); // Tampilkan waktu pertama kali

//         countdown = setInterval(() => {
//             seconds--; // Kurangi waktu setiap detik
//             updateTimerDisplay(seconds); // Update tampilan

//             if (seconds <= 0) {
//                 clearInterval(countdown); // Hentikan timer
//                 countdown = null;
//                 localStorage.removeItem(COUNTDOWN_KEY); // Hapus data dari localStorage
//                 timerExam.textContent = 'Waktu habis'; // Tampilkan teks waktu habis
//                 emptyTime(); // Tampilkan modal/alert bahwa waktu habis

//                 autoSubmitUnSavedQuestions(); // Kirim otomatis jawaban yang belum disimpan
//             }
//         }, 1000); // Setiap 1000ms (1 detik)
//     }

//     // Fungsi untuk menampilkan waktu dalam format menit dan detik
//     function updateTimerDisplay(seconds) {
//         const minutes = Math.floor(seconds / 60); // Hitung menit
//         const secs = seconds % 60; // Sisa detik
//         timerExam.textContent = `${minutes} Menit ${secs.toString().padStart(2, '0')} Detik`; // Tampilkan waktu
//     }
// }

// function stopTimerExam() {
//     clearInterval(countdown); // Hentikan timer jika masih berjalan
//     countdown = null;

//     const COUNTDOWN_START_KEY = 'timer_exam_start'; // Key waktu mulai ujian
//     const COUNTDOWN_EXPIRE_KEY = 'timer_exam_expire'; // Key waktu berakhir ujian

//     const startTime = parseInt(localStorage.getItem(COUNTDOWN_START_KEY)); // Ambil waktu mulai dari localStorage
//     const expireTime = parseInt(localStorage.getItem(COUNTDOWN_EXPIRE_KEY)); // Ambil waktu berakhir

//     // Jika tidak valid (null/undefined), hentikan fungsi
//     if (!startTime || !expireTime) return;

//     const totalDuration = Math.floor((expireTime - startTime) / 1000); // Durasi total ujian dalam detik
//     const usedDuration = Math.floor((Date.now() - startTime) / 1000); // Waktu yang digunakan sampai sekarang

//     const finalUsed = Math.min(usedDuration, totalDuration); // Ambil waktu terkecil (jika user melebihi waktu karena delay)

//     const minutes = Math.floor(finalUsed / 60); // Hitung menit
//     const seconds = finalUsed % 60; // Sisa detik

//     const formatted = `${minutes} Menit ${seconds.toString().padStart(2, '0')} Detik`; // Format durasi

//     const babId = document.getElementById('exam-questions-form')?.dataset.babId; // Ambil ID bab dari atribut HTML
//     if (!babId) return;

//     $.ajax({
//         url: `/soal-pembahasan/kelas/${babId}/assessment/ujian`, // Endpoint untuk ambil data soal
//         method: 'GET',
//         success: function (response) {
//             const groupedQuestions = response.data; // Soal dikelompokkan per halaman
//             const questionsAnswer = response.questionsAnswer; // Jawaban user yang tersimpan
//             const scoreEachQuestion = response.scoreEachQuestion; // Skor per soal

//             groupedQuestions.forEach((group) => {
//                 const soal = group[0]; // Ambil soal dari grup
//                 const answerData = questionsAnswer[soal.id]; // Ambil jawaban user

//                 if (!answerData) return; // Lewati jika belum dijawab

//                 const formData = new FormData();
//                 formData.append('question_id', soal.id); // ID soal
//                 formData.append('user_answer_option', answerData.user_answer_option); // Jawaban user
//                 formData.append('status_answer', answerData.status_answer); // Status (Saved/Draft)
//                 formData.append('question_score', scoreEachQuestion); // Skor soal
//                 formData.append('exam_answer_duration', formatted); // Waktu yang digunakan

//                 $.ajax({
//                     url: `/soal-pembahasan/kelas/${babId}/assessment/ujian/answer`, // Endpoint simpan jawaban
//                     method: 'POST',
//                     headers: {
//                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token Laravel
//                     },
//                     data: formData,
//                     processData: false,
//                     contentType: false,
//                     success: function () {
//                         fetchExamQuestionsForm(babId, currentQuestionIndex); // Refresh soal setelah simpan
//                     }
//                 });
//             });
//         }
//     });

//     // Hapus data waktu dari localStorage setelah selesai
//     localStorage.removeItem(COUNTDOWN_START_KEY);
//     localStorage.removeItem(COUNTDOWN_EXPIRE_KEY);
// }

// JANGAN DIHAPUS DULU SEBELUM FITUR INI FIX
// RESET UJIAN PER HARI, (disimpan sementara karena nanti untuk testing ujian ke reset di pembelian paket soal yang di tanggal berbeda, bukan per hari lagi)
let currentQuestionIndex = 0; // Global, default ke soal pertama

function fetchExamQuestionsForm(babId, selectedIndex = 0) {
    $.ajax({
        url: `/soal-pembahasan/kelas/${babId}/assessment/ujian`,
        method: 'GET',
        success: function (response) {
            const container = document.getElementById('exam-questions-form');
            const containerExamForm = $('#exam-questions-content');
            if (!container) return;

            containerExamForm.empty();

const TODAY_DATE = response.today;
localStorage.setItem('timer_exam_today', TODAY_DATE); // simpan untuk semua fungsi timer


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

            // Hitung jumlah soal yang sudah dijawab (Saved)
            let jumlahSoalTerjawab = 0;
            groupedQuestions.forEach((group) => {
                // Mendapatkan id soal pada group pertama
                const questionId = group[0].id;
                // Mendapatkan jawaban sesuai id soal
                const jawaban = questionsAnswer[questionId];

                // Cek apakah jawaban sudah dijawab dan status jawaban adalah 'Saved'
                if (jawaban && (jawaban.status_answer === 'Saved')) {
                    jumlahSoalTerjawab++;
                }
            });

            // Cek apakah semua soal sudah dijawab
            const isAllAnswered = jumlahSoalTerjawab === totalSoal;

            // Jika semua soal sudah dijawab, tampilkan konten
            if (isAllAnswered) {
                const scoreExam = response.scoreExam; // mengambil nilai ujian
                const examAnswerDuration = response.examAnswerDuration; // mengambil durasi pengerjaan ujian

                $('#score-exam').text(scoreExam); // menampilkan nilai ujian
                $('#timer-duration').text(examAnswerDuration); // menampilkan durasi pengerjaan ujian
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
                        if (questionsAnswer[soal.id]?.status_answer === 'Saved') {
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
                        // jika jawaban user masih ditandai
                        }
                    } else {
                        if (questionsAnswer[soal.id]?.user_answer_option === item.options_key && (questionsAnswer[soal.id]?.status_answer === 'Saved' || questionsAnswer[soal.id]?.status_answer === 'Draft')) {
                            statusClass = 'bg-gray-200 font-bold opacity-70';
                        }
                    }

                    let optionsValue = '';

                    // memeriksa apakah soal sudah dijawab oleh pengguna atau jawaban masih ditandai
                    if (!questionsAnswer[soal.id] || questionsAnswer[soal.id]?.status_answer === 'Draft') {
                        // memeriksa apakah options_value terdapat image atau tidak
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
                    // memeriksa apakah soal sudah dijawab oleh pengguna dan jawaban sudah disimpan
                    } else if (questionsAnswer[soal.id] && questionsAnswer[soal.id]?.status_answer === 'Saved') {
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
                    if (questionsAnswer[group[0].id]?.status_answer === 'Saved' && questionsAnswer[group[0].id]?.user_answer_option === group[0].answer_key) {
                        statusClassNumberQuestions = '!bg-green-200 text-green-600 font-bold';

                    // Memeriksa apakah soal sudah disimpan oleh pengguna dan jawaban salah
                    } else if (questionsAnswer[group[0].id]?.status_answer === 'Saved' && questionsAnswer[group[0].id]?.user_answer_option !== group[0].answer_key) {
                        statusClassNumberQuestions = '!bg-red-200 text-red-600 font-bold';
                    }
                // Jika soal belum dijawab semua
                } else {
                    // Memeriksa apakah soal sudah disimpan oleh pengguna dan question_id sesuai dengan soal yang dilihat
                    // menggunakan group[0] jika ingin membuat dan melihat semua status Saved aktif, jika menggunakan soal.id hanya akan aktif jika soal nya sedang dilihat
                    if (questionsAnswer[group[0].id]?.status_answer === 'Saved' && questionsAnswer[group[0].id]?.question_id === group[0].id) {
                        statusClassNumberQuestions = '!bg-[--color-default] text-white font-bold';

                    // Memeriksa apakah soal masih ditandai oleh pengguna dan question_id sesuai dengan soal yang dilihat
                    } else if (questionsAnswer[group[0].id]?.status_answer === 'Draft' && questionsAnswer[group[0].id]?.question_id === group[0].id) {
                        statusClassNumberQuestions = '!bg-[#F79D65] text-white font-bold';

                    // memeriksa jika soal belum dijawab oleh pengguna
                    } else {
                        statusClassNumberQuestions = '';
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
            // Mengecek apakah soal sudah dijawab oleh pengguna dan jawaban sudah disimpan
            const isAnswered = !!questionsAnswer[soal.id] && questionsAnswer[soal.id]?.status_answer === 'Saved'; // `!!` akan mengubah nilai tersebut menjadi boolean `true` atau `false`.

            // show button submit answer
            // memeriksa apakah soal sudah dijawab oleh pengguna, jika sudah maka button menjadi disabled
            // kalau mau buat sistem waktu pengerjaan user setiap soal tambahkan onclick="stopTimerExam()"
            const buttonSubmitAnswerHTML = isAnswered
                ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Simpan Jawaban</button>`
                : `<button id="button-submit-exam-answer" class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[--color-default] text-white font-bold rounded-md hover:brightness-90" data-bab-id="${babId}">Simpan Jawaban</button>`;

            // show button correct or wrong answer
            // memeriksa apakah soal sudah dijawab oleh pengguna dan apakah jawaban user benar / salah
            // kalau mau buat sistem waktu pengerjaan user setiap soal tambahkan onclick="stopTimerExam()"
            const buttonCorrectOrWrongHTML = isAnswered
                ? `<button class="border py-[6px] w-full text-xs lg:text-sm text-center bg-gray-200 opacity-70 rounded-md" disabled>Tandai jawaban</button>`
                : `<button id="button-mark-exam-answer" class="border py-[6px] w-full text-xs lg:text-sm text-center bg-[#F79D65] text-white font-bold hover:brightness-90 rounded-md" data-bab-id="${babId}">Tandai jawaban</button>`;

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
            const buttonPembahasanHTML = isAllAnswered
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

            // waktu ujian akan berjalan jika ada salah satu soal yang telah dijawab / ditandai
            if (questionsAnswer[soal.id] && !isAllAnswered) {
                startTimerExam();
            } else if (isAllAnswered) {
                stopTimerExam();
            }

            // Final Render HTML
            const formHtml = `
                <form id="bank-soal-exam-question-form" data-bab-id="${babId}">
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
                                <input type="hidden" name="status_answer" id="statusAnswer"  value="">
                                <input type="hidden" name="question_score" id="question_score"  value="${response.scoreEachQuestion}">
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
                fetchExamQuestionsForm(babId, index);
            });
        }
    });
}

// Fetch soal latihan
function examQuestions() {
    const container = document.getElementById('exam-questions-form');
    if (!container) return;

    const babId = container.dataset.babId;
    if (!babId) return;

    fetchExamQuestionsForm(babId, currentQuestionIndex);
}

// Inisialisasi saat halaman siap
$(document).ready(function () {
    examQuestions();
});

// Listener untuk memilih jawaban pilihan ganda
$(document).on('change', 'input[type="radio"][name^="options_value_"]', function () {
    const soalId = $(this).data('soal-id'); // Ambil ID soal dari atribut data
    const selectedOption = $(this).val(); // Ambil nilai pilihan user
    $(`#userAnswer${soalId}`).val(selectedOption); // Update input hidden untuk jawaban user
    $('#error-user_answer_option').text(''); // Hapus pesan error jika ada
});

// Listener tombol untuk menyimpan jawaban
$(document).on('click', '#button-submit-exam-answer', function (e) {
    e.preventDefault(); // Cegah submit form default
    const form = $(this).closest('form')[0]; // Ambil form terdekat
    const babId = $(form).data('bab-id'); // Ambil babId dari atribut data
    submitExamAnswer('Saved', form, babId); // Kirim jawaban sebagai 'Saved'
});

// Listener tombol untuk menandai jawaban
$(document).on('click', '#button-mark-exam-answer', function (e) {
    e.preventDefault();
    const form = $(this).closest('form')[0];
    const babId = $(form).data('bab-id');
    submitExamAnswer('Draft', form, babId); // Kirim jawaban sebagai 'Draft'
});


// Submit form answers
function submitExamAnswer(status_answer, form, babId) {
    const formData = new FormData(form); // Ambil seluruh data dari form
    formData.append('status_answer', status_answer); // Tambahkan status jawaban

    $.ajax({
        url: `/soal-pembahasan/kelas/${babId}/assessment/ujian/answer`, // Endpoint penyimpanan jawaban
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Kirim token CSRF
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            // Cek apakah semua soal sudah dijawab
            $.get(`/soal-pembahasan/kelas/${babId}/assessment/ujian`, function (res) {
                const allAnswered = res.data.every(group => {
                    const qid = group[0].id;
                    return res.questionsAnswer[qid]?.status_answer === 'Saved';
                });

                if (allAnswered) {
                    stopTimerExam(); // Hentikan timer jika semua soal sudah disimpan
                } else {
                    fetchExamQuestionsForm(babId, currentQuestionIndex); // Refresh soal
                }
            });
        },
        error: function (xhr) {
            // Tampilkan error validasi
            if (xhr.status === 422) {
                const response = xhr.responseJSON.errors;
                $.each(response, function (field, messages) {
                    $(`#error-${field}`).text(messages[0]);
                });
            }
        }
    });
}

// Fungsi untuk auto submit soal yang belum disimpan (saat waktu habis, ujian belum selesai)
function autoSubmitUnSavedQuestions() {
    const babId = document.getElementById('exam-questions-form')?.dataset.babId;
    if (!babId) return;

    // Ambil waktu mulai ujian dari localStorage (dalam bentuk timestamp milidetik saat ujian dimulai)
    const TODAY_DATE = localStorage.getItem('timer_exam_today');
    const START_KEY = `timer_exam_start_${TODAY_DATE}`;
    const startTime = localStorage.getItem(START_KEY);
    // Inisialisasi default durasi waktu pengerjaan ujian sebagai 0 menit 0 detik (format string)
    let duration = '00 Menit 00 Detik';

    if (startTime) {
        // menghitung durasi waktu ujian yang sudah digunakan peserta dalam satuan detik.
        const timeUsed = Math.floor((Date.now() - parseInt(startTime)) / 1000);
        // Hitung jumlah menit dari total detik
        const minutes = Math.floor(timeUsed / 60);
        // Hitung sisa detik setelah dikonversi ke menit
        const seconds = timeUsed % 60;

        // Format durasi sebagai string dengan 2 digit: "00 Menit 00 Detik" (padStart(2, '0') = tambahkan angka 0 di depan jika waktu kurang dari 2 digit)
        duration = `${String(minutes).padStart(2, '0')} Menit ${String(seconds).padStart(2, '0')} Detik`;
    }

    $.ajax({
        url: `/soal-pembahasan/kelas/${babId}/assessment/ujian`,
        method: 'GET',
        success: function (response) {
            const groupedQuestions = response.data;
            const questionsAnswer = response.questionsAnswer;
            const formData = new FormData();
            const scoreEachQuestion = response.scoreEachQuestion;

            // Iterasi setiap soal
            groupedQuestions.forEach(group => {
                const soal = group[0];

                // Soal belum dijawab
                if (!questionsAnswer[soal.id]) {
                    formData.append('question_id', soal.id);
                    formData.append('user_answer_option', '-');
                    formData.append('status_answer', 'Saved');
                    formData.append('question_score', 0);
                    formData.append('exam_answer_duration', duration);
                }
                // jika soal sudah dijawab tetapi waktu habis duluan sebelum selesai ngerjain semua soal
                else if (questionsAnswer[soal.id]?.status_answer === 'Saved') {
                    formData.append('question_id', soal.id);
                    formData.append('user_answer_option', questionsAnswer[soal.id]?.user_answer_option);
                    formData.append('status_answer', 'Saved');
                    formData.append('question_score', scoreEachQuestion);
                    formData.append('exam_answer_duration', duration);
                }
                // Soal ditandai (Draft)
                else if (questionsAnswer[soal.id]?.status_answer === 'Draft') {
                    formData.append('question_id', soal.id);
                    formData.append('user_answer_option', questionsAnswer[soal.id]?.user_answer_option);
                    formData.append('status_answer', 'Saved');
                    formData.append('question_score', scoreEachQuestion);
                    formData.append('exam_answer_duration', duration);
                }

                // Submit setiap soal
                $.ajax({
                    url: `/soal-pembahasan/kelas/${babId}/assessment/ujian/answer`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                });
            });

            // Refresh tampilan setelah submit
            fetchExamQuestionsForm(babId, currentQuestionIndex);
            stopTimerExam();
        }
    });
}


// Tampilkan pembahasan soal ujian melalaui modal
function showExplanation(element) {
    const modal = document.getElementById('my_modal_1');
    const iframe = document.getElementById('video-frame');

    const videoId = element.getAttribute('data-video-id');

    if (iframe && videoId) {
        iframe.src = `https://www.youtube.com/embed/${videoId}`; // Set iframe ke video
    }

    modal.showModal(); // Tampilkan modal
}

function closePembahasanModal() {
    const iframe = document.getElementById('video-frame');
    if (iframe) {
        iframe.src = ''; // Reset iframe agar video berhenti setelah tutup modal
    }
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
    if (countdown !== null) return;

    const timerExam = document.getElementById('timer-exam');
    const TODAY_DATE = localStorage.getItem('timer_exam_today');
    if (!TODAY_DATE) return;

    const START_KEY = `timer_exam_start_${TODAY_DATE}`;
    const EXPIRE_KEY = `timer_exam_expire_${TODAY_DATE}`;

    const expireTime = localStorage.getItem(EXPIRE_KEY);

    if (expireTime) {
        const remaining = Math.floor((parseInt(expireTime) - Date.now()) / 1000);
        if (remaining > 0) {
            runCountdown(remaining);
        } else {
            localStorage.removeItem(EXPIRE_KEY);
            startNewCountdown();
        }
    } else {
        startNewCountdown();
    }

    function startNewCountdown() {
        const totalSeconds = 60 * 60; // ganti sesuai durasi ujian
        const expireTime = Date.now() + totalSeconds * 1000;
        const startTime = Date.now();

        localStorage.setItem(START_KEY, startTime);
        localStorage.setItem(EXPIRE_KEY, expireTime);

        runCountdown(totalSeconds);
    }

    function runCountdown(seconds) {
        updateTimerDisplay(seconds);

        countdown = setInterval(() => {
            seconds--;
            updateTimerDisplay(seconds);

            if (seconds <= 0) {
                clearInterval(countdown);
                countdown = null;


                timerExam.textContent = 'Waktu habis';
                emptyTime();
                autoSubmitUnSavedQuestions();

                localStorage.removeItem(START_KEY);
                localStorage.removeItem(EXPIRE_KEY);
            }
        }, 1000);
    }

    function updateTimerDisplay(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = seconds % 60;
        timerExam.textContent = `${minutes} Menit ${secs.toString().padStart(2, '0')} Detik`;
    }
}


function stopTimerExam() {
    clearInterval(countdown);
    countdown = null;

    const TODAY_DATE = localStorage.getItem('timer_exam_today');
    if (!TODAY_DATE) return;

    const START_KEY = `timer_exam_start_${TODAY_DATE}`;
    const EXPIRE_KEY = `timer_exam_expire_${TODAY_DATE}`;

    const startTime = parseInt(localStorage.getItem(START_KEY));
    const expireTime = parseInt(localStorage.getItem(EXPIRE_KEY));
    if (!startTime || !expireTime) return;

    const totalDuration = Math.floor((expireTime - startTime) / 1000);
    const usedDuration = Math.floor((Date.now() - startTime) / 1000);
    const finalUsed = Math.min(usedDuration, totalDuration);

    const minutes = Math.floor(finalUsed / 60);
    const seconds = finalUsed % 60;
    const formatted = `${minutes} Menit ${seconds.toString().padStart(2, '0')} Detik`;

    const babId = document.getElementById('exam-questions-form')?.dataset.babId;
    if (!babId) return;

    $.ajax({
        url: `/soal-pembahasan/kelas/${babId}/assessment/ujian`,
        method: 'GET',
        success: function (response) {
            const groupedQuestions = response.data;
            const questionsAnswer = response.questionsAnswer;
            const scoreEachQuestion = response.scoreEachQuestion;

            groupedQuestions.forEach(group => {
                const soal = group[0];
                const answerData = questionsAnswer[soal.id];
                if (!answerData) return;

                const formData = new FormData();
                formData.append('question_id', soal.id);
                formData.append('user_answer_option', answerData.user_answer_option);
                formData.append('status_answer', answerData.status_answer);
                formData.append('question_score', scoreEachQuestion);
                formData.append('exam_answer_duration', formatted);

                $.ajax({
                    url: `/soal-pembahasan/kelas/${babId}/assessment/ujian/answer`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function () {
                        fetchExamQuestionsForm(babId, currentQuestionIndex);
                    }
                });
            });
        }
    });

    // Hapus data dari localStorage setelah ujian selesai
    localStorage.removeItem(START_KEY);
    localStorage.removeItem(EXPIRE_KEY);
}
