function paginateManagementPassageDetail() {
    const container = document.getElementById('container-passage-list');
    if (!container) return;

    const levelId = container.dataset.levelId;
    const passageType = container.dataset.passageType;

    if (!levelId) return;
    if (!passageType) return;
    
    fetchDataManagementPassageDetail(levelId, passageType);

    function fetchDataManagementPassageDetail(levelId, passageType) {
        $.ajax({
            url: `/english-zone/management-quiz/management-passage-detail/${levelId}/${passageType}/paginate`,
            method: 'GET',
            success: function (data) {
                const containerPassageList = $('#passage-list');
                containerPassageList.empty();
    
                if (data.data.length > 0) {
                    $.each(data.data, function (index, item) {

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

                        const previewBankSoalQuiz = data.previewBankSoalQuiz.replace(':level_id', item.level_id).replace(':passage_type', item.passage_type)
                            .replace(':passage_id', item.id);

                        let = audioFile = '';
                        let = audioScript = '';

                        let contentTextOnly = '';
                        let contentHTML = '';

                        if (item.passage_type === 'Listening Practice Test' || item.passage_type === 'Listening Exam Test') {
                            audioFile = `
                                <audio controls class="relative z-[9999]" id="audio-${item.id}">
                                    <source src="/english-zone-audio/${item.audio_file}" type="audio/mpeg">
                                    Browser kamu tidak mendukung audio tag.
                                </audio>
                            `;

                            audioScript = `
                                <div
                                    class="preview-text-only passage-text max-w-[1450px] space-y-4 text-justify"
                                    data-fulltext="${item.audio_script}">
                                    ${item.audio_script}
                                </div>
                                
                            `;
                        } else {
                            const containsImage = /<img\s+[^>]*src=/.test(item.passage_content);

                            // Tambahkan class img jika ada gambar
                            if (containsImage) {
                                content = addClassToImgTags(item.passage_content, 'max-w-[300px] rounded my-2');
                            }

                            // untuk memisahkan teks sebelum dengan img dan text setelah img
                            const splitPassage = item.passage_content.split('<img'); // split sebelum <img>
                            const passageContentTextOnly = splitPassage[0]; // sebelum <img> ( [0] dan [1] digunakan untuk memisahkan 2 element berbeda )

                            // Inisialisasi variabel kosong untuk menampung elemen gambar dan teks setelah gambar
                            let passageImage = '', textAfterImage = '';

                            // Cek apakah hasil split punya bagian setelah <img (artinya ada gambar)
                            if (splitPassage.length > 1) {
                                const imgSplit = splitPassage[1].split('>'); // pisahkan tag <img> dan sisa teks
                                const imgTag = imgSplit[0]; // bagian src dan atribut gambar
                                const restText = imgSplit.slice(1).join('>'); // gabungkan sisa setelah tag img

                                passageImage = `<img class="lg:max-w-[65%] xl:max-w-[45%]" ${imgTag}>`; // Susun tag <img> lengkap dengan class tambahan
                                textAfterImage = restText.trim(); // Hapus spasi berlebih pada teks setelah gambar
                            }

                            // Gabungkan menjadi HTML: bungkus gambar dan teks
                            const passageContentHTML = `
                                <div class="flex flex-col gap-6 items-start">
                                    ${passageImage}
                                    <div class="space-y-4">${textAfterImage}</div>
                                </div>
                            `;
                            
                            const previewLimit = 350;

                            const previewTextOnly = passageContentTextOnly.length > previewLimit ? passageContentTextOnly.slice(0, previewLimit) + "..." : passageContentTextOnly;
                            
                            contentTextOnly = `
                                <span
                                    class="preview-text-only w-full passage-text max-w-[1450px] space-y-4 text-justify"
                                    data-fulltext="${passageContentTextOnly}">
                                    ${previewTextOnly}
                                </span>
                            `;

                            contentHTML = `
                                ${passageContentHTML}
                            `;
                        }

                        const listItem = `
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
                                            <input type="checkbox" class="hidden peer toggle-active-passage"
                                                data-passage-id="${item.id}"
                                                ${item.passage_status === 'Publish' ? 'checked' : ''} />

                                            <div class="w-8 h-4 bg-gray-300 peer-checked:bg-green-500 rounded-full relative transition-colors duration-300 ease-in-out">
                                            </div>

                                            <div class="absolute left-4 top-2.4 w-3 h-3 bg-white rounded-full shadow-md 
                                                transition-transform duration-300 ease-in-out peer-checked:translate-x-[10px]"></div>

                                            <span>Action</span>
                                        </label>
                                    </li>

                                    <!-- Edit Passage -->
                                    <li class="text-xs">
                                        <a href="#" class="btn-edit-passage" data-passage-id="${item.id}">
                                            <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                            Edit Passage
                                        </a>
                                    </li>

                                    <!-- preview bank soal quiz -->
                                    <li class="text-xs">
                                        <a href="${previewBankSoalQuiz}">
                                            <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                            Lihat Detail
                                        </a>
                                    </li>

                                    <!-- Delete Passage -->
                                    <li class="text-xs">
                                        <a href="#" class="btn-delete-passage text-red-600" data-passage-id="${item.id}">
                                            <i class="fa-solid fa-trash text-red-600"></i>
                                            Delete Passage
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                            <div class="bg-[#fff] border px-5 mt-2 rounded-md mb-6">

                            <div class="toggleButton-passage pt-5 cursor-pointer block">
                                <div class="flex flex-col gap-4">
                                    <div class="flex justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs px-2 py-1 rounded-md ${item.passage_status === 'Publish' ? 'bg-green-200 text-green-600 font-bold' : 'bg-gray-200 text-gray-700'}">
                                                ${item.passage_status}
                                            </span>
                                                <h3 class="font-semibold text-lg">${item.english_zone_level?.level_name}</h3>
                                        </div>
                                        <i class="fa-solid fa-chevron-up icon"></i>
                                    </div>

                                    ${audioFile}
                                    ${contentTextOnly}
                                    </div>
                                </div>

                                <div class="content-accordion-passage">
                                    <div class="max-w-[1450px] text-sm mt-6">
                                        <div class="passage-text">
                                            ${audioScript}
                                            ${contentHTML}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;

                        containerPassageList.append(listItem);
                    });
                    
                    initAccordionPassage();
                    initAudioControl();
                    $('#empty-message-management-passage-detail').hide(); // sembunyikan pesan kosong
                } else {
                    $('#empty-message-management-passage-detail').show(); // Tampilkan pesan kosong
                }
            }
        });
    }
}

$(document).ready(function () {
    paginateManagementPassageDetail();
});

// function initAudioControl
function initAudioControl() {
    const allAudio = document.querySelectorAll("audio[id^='audio-']");

    allAudio.forEach(current => {
        current.addEventListener("play", () => {
            allAudio.forEach(a => {
                if (a !== current) {
                    a.pause();
                }
            });
        });
    });
}

// action Unpublish dan Publish passage
$(document).ready(function () {
    const passageId = $(this).data('passage-id');

    $(document).on('change', '.toggle-active-passage', function () {
        let passageId = $(this).data('passage-id'); // Ambil passage id dari atribut data-id di checkbox
        let status = $(this).is(':checked') ? 'Publish' : 'Unpublish'; // Jika toggle ON maka publish, kalau OFF maka unpublish

        $.ajax({
            url: `/english-zone/management-quiz/management-passage/${passageId}/activate`, // Endpoint ke server
            method: 'PUT', // Method HTTP PUT untuk update data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                passage_status: status // Kirim status baru (publish/unpublish)
            },
            success: function (response) {
                // inisialisasi update data terbaru setelah berhasil update data
                paginateManagementPassageDetail();
            },
            error: function (xhr) {
                alert('Gagal mengubah status.');
                checkbox.prop('checked', !checkbox.is(':checked')); // ← GUNAKAN INI
            }
        });
    });
});

// Event listener tombol "edit passage" (open modal)
$(document).off('click', '.btn-edit-passage').on('click', '.btn-edit-passage', function (e) {
    e.preventDefault();

    const passageId = $(this).data('passage-id'); // ← ambil passage id

    // set id ke form
    $('#edit-passage-form').data('passage-id', passageId);

    $('#passage_id').val(passageId);

    // Reset error
    $('#edit-passage-form .text-red-500').text('');
    $('#edit-passage-form input, #edit-passage-form select').removeClass('border-red-400 border');

    // buka modal
    const modal = document.getElementById('my_modal_1');
    if (modal) modal.showModal();
});


// edit passage
$('#submit-button').on('click', function (e) {
    e.preventDefault();

    const passageId = $('#passage_id').val();

    const form = $('#edit-passage-form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // buat FormData dari form, BUKAN dari tombol

    // kosongkan error
    $('#edit-passage-form .text-red-500').text('');
    $('#edit-passage-form input').removeClass('border-red-400 border');

    $.ajax({
        url: `/english-zone/management-quiz/management-passage/${passageId}/edit`,
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

                $('#alert-success-update-passage').html(`
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

            $('#edit-passage-form')[0].reset();

            // reset audio passage
            $('#textPreview-audio-passage').text('');
            $('#textSize-audio-passage').text('');
            $('#textPages-audio-passage').text('');
            $('#textCircle-audio-passage').html('');

            // reset bulk upload word
            $('#wordPreviewContainer-bulkUpload-word').addClass('hidden');
            $('#textPreview-bulkUpload-word').text('');
            $('#textSize-bulkUpload-word').text('');
            $('#textPages-bulkUpload-word').text('');
            $('#textCircle-bulkUpload-word').html('');
            $('#logo-bulkUpload-word img').attr('src', '').hide();

            paginateManagementPassageDetail();
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

// function close modal delete passage
function closeModal() {
    const closeModal = document.getElementById('my_modal_2');
    closeModal.close();
}

// Event listener tombol "delete passage" (open modal)
$(document).off('click', '.btn-delete-passage').on('click', '.btn-delete-passage', function (e) {
    e.preventDefault();

    const passageId = $(this).data('passage-id');

    // (Optional) set id ke form untuk submit
    $('#delete-passage-form').data('passage-id', passageId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_2');
    if (modal) {
        modal.showModal();
    }
});

// delete passage
$('#delete-passage-form').on('submit', function (e) {
    e.preventDefault();

    const passageId = $(this).data('passage-id');

    $.ajax({
        url: `/english-zone/management-quiz/management-passage/${passageId}/delete`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_2');
            if (modal) {
                modal.close();

                $('#alert-success-delete-passage').html(
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
                paginateManagementPassageDetail();
            }
        },
    });
});

function initAccordionPassage() {
    let toggles = document.getElementsByClassName('toggleButton-passage');
    let contentDiv = document.getElementsByClassName('content-accordion-passage');
    let icons = document.getElementsByClassName('icon');
    let previewTexts = document.getElementsByClassName('preview-text-only');

    for (let i = 0; i < toggles.length; i++) {

        const fullText = previewTexts[i].dataset.fulltext;
        const shortText = fullText.length > 350 ? fullText.slice(0, 350) + "..." : fullText;

        // set default
        previewTexts[i].innerHTML = shortText;

        toggles[i].addEventListener('click', () => {

            const isOpen = parseInt(contentDiv[i].style.height) === contentDiv[i].scrollHeight;

            // ====== TUTUP SEMUA ACCORDION LAIN ======
            for (let j = 0; j < contentDiv.length; j++) {
                if (j !== i) {
                    contentDiv[j].style.height = "0px";
                    toggles[j].style.color = "#111130";
                    icons[j].classList.remove('fa-chevron-down');
                    icons[j].classList.add('fa-chevron-up');

                    // kembalikan shortText accordion lain
                    const otherFullText = previewTexts[j].dataset.fulltext;
                    const otherShortText =
                        otherFullText.length > 350
                            ? otherFullText.slice(0, 350) + "..."
                            : otherFullText;

                    previewTexts[j].innerHTML = otherShortText;
                }
            }

            // ====== TOGGLE ACCORDION YANG DIKLIK ======
            if (!isOpen) {
                // buka
                previewTexts[i].innerHTML = fullText;
                contentDiv[i].style.height = contentDiv[i].scrollHeight + "px";
                toggles[i].style.color = "";
                icons[i].classList.remove('fa-chevron-up');
                icons[i].classList.add('fa-chevron-down');
            } else {
                // tutup
                previewTexts[i].innerHTML = shortText;
                contentDiv[i].style.height = "0px";
                toggles[i].style.color = "#111130";
                icons[i].classList.remove('fa-chevron-down');
                icons[i].classList.add('fa-chevron-up');
            }
        });
    }
}