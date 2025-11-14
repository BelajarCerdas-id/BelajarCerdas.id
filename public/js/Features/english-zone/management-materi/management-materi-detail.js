function paginateManagementMateriDetail() {
    const container = document.getElementById('container-management-materi-detail');
    if (!container) return;

    const levelId = container.dataset.levelId;
    if (!levelId) return;

    fetchFilteredManagementMateriDetail(levelId);

    function fetchFilteredManagementMateriDetail() {
        $.ajax({
            url: `/english-zone/management-materi/detail/paginate/${levelId}`,
            method: 'GET',
            success: function (response) {
                $('#table-list-management-materi-detail').empty(); // Clear previous entries
                document.getElementById('dynamic-modal-container-materi').innerHTML = '';

                function getFileIcon(filename) {
                    if (!filename) return;

                    const extension = filename.split('.').pop().toLowerCase();

                    switch (extension) {
                        case 'pdf':
                            return '<i class="fa-solid fa-file-pdf text-red-600"></i>';
                        default:
                            return '-';
                    }
                }

                if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {
                        const materiVocabulary = getFileIcon(item.materi_vocabulary);
                        const materiGrammar = getFileIcon(item.materi_grammar);
                        const materiLessonPlan = getFileIcon(item.materi_lesson_plan);
                        const materiReading = getFileIcon(item.materi_reading);
                        const materiWriting = getFileIcon(item.materi_writing);
                        const materiListening = getFileIcon(item.materi_listening);
                        const materiSpeaking = getFileIcon(item.materi_speaking);
                        const materiPembelajaran = getFileIcon(item.materi_pembelajaran);
                        const worksheet = getFileIcon(item.worksheet);

                        const videoId = item.video_id;
                        const modal = `
                            <dialog id="my_modal_1-${item.id}" class="modal">
                                <div class="modal-box bg-white max-w-7xl max-h-[600px]">
                                    <div class="flex justify-center w-full mb-4">
                                        <span class="text-2xl font-bold opacity-70">Video Materi</span>
                                    </div>
                                    <div class="border max-w-7xl h-[500px] flex justify-start">
                                        <div class="w-full h-full">
                                    <iframe id="video-frame-${item.id}" class="w-full h-full"
                                        src="https://www.youtube.com/embed/${videoId}"
                                        frameborder="0"
                                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                        </div>
                                    </div>
                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button onclick="closePembahasanModal(${item.id})">close</button>
                                </form>
                            </dialog>
                        `;

                        container.insertAdjacentHTML('beforeend', modal);

                        $('#table-list-management-materi-detail').append(`
                            <tr class="text-xs">
                                <td class="td-table !text-black !text-center">${index + 1}</td>
                                <td class="td-table !text-black !text-center">
                                    ${item.english_zone_level?.level_name}
                                </td>
                                <td class="td-table !text-black">
                                    ${item.english_zone_session?.session_name}
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <a href="" class="btn-materi text-lg" data-materi-id="${item.id}" data-materi="${item.materi_vocabulary}" data-materi-type="vocabulary">
                                        ${materiVocabulary}
                                    </a>
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <a href="" class="btn-materi text-lg" data-materi-id="${item.id}" data-materi="${item.materi_grammar}" data-materi-type="grammar">
                                        ${materiGrammar}
                                    </a>
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <a href="" class="btn-materi text-lg" data-materi-id="${item.id}" data-materi="${item.materi_lesson_plan}" data-materi-type="lessonPlan">
                                        ${materiLessonPlan}
                                    </a>
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <a href="" class="btn-materi text-lg" data-materi-id="${item.id}" data-materi="${item.materi_reading}" data-materi-type="reading">
                                        ${materiReading}
                                    </a>
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <a href="" class="btn-materi text-lg" data-materi-id="${item.id}" data-materi="${item.materi_writing}" data-materi-type="writing">
                                        ${materiWriting}
                                    </a>
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <a href="" class="btn-materi text-lg" data-materi-id="${item.id}" data-materi="${item.materi_listening}" data-materi-type="listening">
                                        ${materiListening}
                                    </a>
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <a href="" class="btn-materi text-lg" data-materi-id="${item.id}" data-materi="${item.materi_speaking}" data-materi-type="speaking">
                                        ${materiSpeaking}
                                    </a>
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <a href="" class="btn-materi text-lg" data-materi-id="${item.id}" data-materi="${item.materi_pembelajaran}" data-materi-type="pembelajaran">
                                        ${materiPembelajaran}
                                    </a>
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <a href="" class="btn-materi text-lg" data-materi-id="${item.id}" data-materi="${item.worksheet}" data-materi-type="worksheet">
                                        ${worksheet}
                                    </a>
                                </td>
                                <td class="td-table !text-black !text-center">
                                    <button type="button" onclick="showVideo(this)" data-materi-id="${item.id}" data-video-id="${videoId}"
                                        class="text-blue-600 hover:text-blue-800 text-lg">
                                        <i class="fa-solid fa-circle-play"></i>
                                    </button>
                                </td>
                                <td class="border text-center border-gray-300">
                                    <div class="dropdown dropdown-left">
                                        <div tabindex="0" role="button">
                                            <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                        </div>
                                        <ul tabindex="0"
                                            class="dropdown-content menu bg-base-100 rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                            <li class="text-xs">
                                                <a href="#" class="btn-edit-materi" data-materi-id="${item.id}" data-materi='${JSON.stringify(item)}'>
                                                    <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                    Edit Materi
                                                </a>
                                            </li>
                                            <li class="text-xs">
                                                <a href="#" class="btn-delete-materi" data-materi-id="${item.id}">
                                                    <i class="fa-solid fa-trash text-red-600"></i>
                                                    Delete Materi
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });
                    $('#empty-message-management-materi-detail').hide(); // sembunyikan pesan kosong
                    $('.thead-table-management-materi-detail').show(); // Tampilkan tabel thead
                } else {
                    $('#table-list-management-materi-detail').empty(); // Clear existing rows
                    $('#empty-message-management-materi-detail').show(); // Tampilkan pesan kosong
                    $('.thead-table-management-materi-detail').hide(); // sembunyikan tabel thead
                }
            }
        });
    }
}


$(document).ready(function () {
    paginateManagementMateriDetail();
});

// Tampilkan pembahasan soal latihan melalaui modal
function showVideo(element) {
    const materiId = element.getAttribute('data-materi-id');
    const modal = document.getElementById('my_modal_1-' + materiId);
    const iframe = document.getElementById('video-frame-' + materiId);

    const videoId = element.getAttribute('data-video-id');

    if (iframe && videoId) {
        iframe.src = `https://www.youtube.com/embed/${videoId}`;
    }

    modal.showModal();
}

function closePembahasanModal(materiId) {
    const iframe = document.getElementById('video-frame-' + materiId);
    if (iframe) {
        iframe.src = ''; // remove the video after close modal
    }
}

// show materi vocabulary, grammar
$(document).off('click', '.btn-materi').on('click', '.btn-materi', function (e) {
    e.preventDefault();
    const materiId = $(this).data('materi-id');
    const materi = $(this).data('materi');
    const materiType = $(this).data('materi-type');

    const modalId = 'my_modal_2-' + materiType + '-' + materiId + '-' + materi;

    showModal(materiId, materi, materiType);

    const modal = document.getElementById(modalId);
    if (modal) {
        modal.showModal();
    }
});

function showModal(materiId, materi, materiType) {
    const modalId = `my_modal_2-${materiType}-${materiId}-${materi}`;

    const container = document.getElementById('dynamic-modal-container-materi');

    container.insertAdjacentHTML('beforeend', `
        <dialog id="${modalId}" class="modal">
            <div class="modal-box bg-white max-w-6xl max-h-[600px]">
                <div class="flex justify-center w-full mb-4">
                    <span class="text-2xl font-bold opacity-70">${materiType.toUpperCase()}</span>
                </div>
                <div class="border max-w-6xl h-[500px] flex justify-start">
                    <iframe class="w-full h-full"
                        src="/english-zone-materi/${materi}"
                        frameborder="0"
                        allowfullscreen></iframe>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    `);
}

// Event listener tombol "edit materi" (open modal)
$(document).off('click', '.btn-edit-materi').on('click', '.btn-edit-materi', function (e) {
    e.preventDefault();

    const materi = $(this).data('materi'); // ← ambil object level lengkap
    const materiId = materi.id;

    // set id ke form
    $('#edit-materi-form').data('materi-id', materiId);

    // Reset error
    $('#edit-materi-form .text-red-500').text('');
    $('#edit-materi-form input', '#edit-materi-form select').removeClass('border-red-400 border');

    // isi semua field otomatis 
    $('#video_materi').val(materi.video_materi);

    // buka modal
    const modal = document.getElementById('my_modal_3');
    if (modal) modal.showModal();
});


// edit materi
let isProcessing = false;
$('#submit-button').on('click', function (e) {
    e.preventDefault();

    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const form = $(this).closest('form')[0]; // ambil DOM Form-nya
    const formData = new FormData(form); // otomatis ambil semua field input/select di form

    const materiId = $(form).data('materi-id');

    const btn = $(this);

    btn.prop('disabled', true); // Disable button UI

    // kosongkan error
    $('#edit-materi-form .text-red-500').text('');
    $('#edit-materi-form input').removeClass('border-red-400 border');

    $.ajax({
        url: `/english-zone/management-materi/edit/${materiId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            document.getElementById('my_modal_3').close();

            // alert sukses
            $('#alert-success-update-materi').html(`
                <div class="w-full flex justify-center">
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

            setTimeout(() => $('#alertSuccess').remove(), 3000);
            $('#btnClose').on('click', () => $('#alertSuccess').remove());

            // Reset form (input, select)
            $('#edit-materi-form')[0].reset();

            // reset form (upload file)
            $('.file-wrapper').each(function () {
                let prefix = $(this).data('prefix');
                $('#textPreview-' + prefix).text('');
                $('#textSize-' + prefix).text('');
                $('#textPages-' + prefix).text('');
                $('#textCircle-' + prefix).html('');
                $('#pdfLogo-' + prefix).attr('src', '').css('display', '');
                $('#fileArrowUp-' + prefix).show();
            })

            paginateManagementMateriDetail();

            isProcessing = false;
            btn.prop('disabled', false);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    // Tampilkan pesan error
                    $('#edit-materi-form').find(`#error-${field}`).text(messages[0]);

                    // Tambahkan style error ke input (jika ada)
                    $('#edit-materi-form').find(`[name="${field}"]`).addClass('border-red-400 border');
                });

            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }
            isProcessing = false;
            btn.prop('disabled', false);
        }
    });
});

// function close modal delete materi
function closeModal() {
    const closeModal = document.getElementById('my_modal_4');
    closeModal.close();
}

// Event listener tombol "delete level" (open modal)
$(document).off('click', '.btn-delete-materi').on('click', '.btn-delete-materi', function (e) {
    e.preventDefault();

    const materiId = $(this).data('materi-id');

    // (Optional) set id ke form untuk submit
    $('#delete-materi-form').data('materi-id', materiId);

    // Tampilkan modal
    const modal = document.getElementById('my_modal_4');
    if (modal) {
        modal.showModal();
    }
});

// delete materi
$('#delete-materi-form').on('submit', function (e) {
    e.preventDefault();

    const materiId = $(this).data('materi-id');

    $.ajax({
        url: `/english-zone/management-materi/delete/${materiId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_4');
            if (modal) {
                modal.close();

                $('#alert-success-delete-materi').html(
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
                paginateManagementMateriDetail();
            }
        },
    });
});