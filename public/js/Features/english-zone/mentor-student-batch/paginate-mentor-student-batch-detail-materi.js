function paginateMentorStudentBatchDetailMateri(selectedLevel = null) {
    const container = document.getElementById('container-management-student-batch-detail-materi');
    if (!container) return;

    const levelId = container.dataset.levelId.split(',');
    const studentIds = container.dataset.studentId.split(',');
    const activeLevel = selectedLevel || levelId[0]; // Ambil level pertama untuk menampilkan materi

    if (!levelId) return;
    if (!studentIds) return;

    fetchDataMentorStudentBatchDetail(levelId.join(','), studentIds.join(','), activeLevel);

    function fetchDataMentorStudentBatchDetail() {
        $.ajax({
            url: `/english-zone-mentor/student-batch-detail/materi/${levelId}/${studentIds}/${activeLevel}/paginate`,
            method: 'GET',
            data: {
                level_id: levelId.join(',')
            },
            success: function (response) {
                const containerMateri = $('#grid-list-materi')
                containerMateri.empty();

                if (response.data.length > 0) {

                    // Cek apakah elemen dengan id 'dropdown-filter-level' BELUM ADA di halaman
                    if (!$('#dropdown-filter-level').length) {
                        const levelOptions = response.getLevels.map(level => `
                            <option value="${level.id}" ${level.id == activeLevel ? 'selected' : ''}>
                                ${level.level_name}
                            </option>
                        `).join('');
    
                        $('#container-dropdown-filter-level').html(`
                            <div class="flex justify-end w-full mb-6">
                                <select id="dropdown-filter-level" class="select border-2 border-gray-200 w-max bg-white">
                                    <option value="" class="hidden">Filter Level</option>
                                    ${levelOptions}
                                </select>
                            </div>
                        `);
                    }

                    $.each(response.data, function (index, item) {

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

                        if (item.materi_vocabulary) {
                            container.insertAdjacentHTML('beforeend', `
                                <dialog id="my_modal_2-${item.id}-${item.materi_vocabulary}" class="modal">
                                    <div class="modal-box bg-white max-w-6xl max-h-[600px]">
                                        <div class="flex justify-center w-full mb-4">
                                            <span class="text-2xl font-bold opacity-70">Vocabulary</span>
                                        </div>
                                        <div class="border max-w-6xl h-[500px] flex justify-start">
                                            <div class="w-full h-full">
                                                <iframe class="w-full h-full"
                                                    src="/english-zone-materi/${item.materi_vocabulary}"
                                                    frameborder="0" allowfullscreen>
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="dialog" class="modal-backdrop">
                                        <button>close</button>
                                    </form>
                                </dialog>
                            `);
                        }

                        if (item.materi_grammar) {
                            container.insertAdjacentHTML('beforeend', `
                                <dialog id="my_modal_2-${item.id}-${item.materi_grammar}" class="modal">
                                    <div class="modal-box bg-white max-w-6xl max-h-[600px]">
                                        <div class="flex justify-center w-full mb-4">
                                            <span class="text-2xl font-bold opacity-70">Grammar</span>
                                        </div>
                                        <div class="border max-w-6xl h-[500px] flex justify-start">
                                            <div class="w-full h-full">
                                                <iframe class="w-full h-full"
                                                    src="/english-zone-materi/${item.materi_grammar}"
                                                    frameborder="0" allowfullscreen>
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="dialog" class="modal-backdrop">
                                        <button>close</button>
                                    </form>
                                </dialog>
                            `);
                        }

                        const formatDate = (dateString) => {
                            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Juli', 'Agust', 'Sep', 'Okt', 'Nov', 'Des'];

                            const date = new Date(dateString);
                            const day = date.getDate();
                            const monthName = months[date.getMonth()];
                            const year = date.getFullYear();

                            return `${day} ${monthName} ${year}`;
                        };

                        const levelStartDate = item.level_start_date ? `${formatDate(item.level_start_date)}` : 'Tanggal tidak tersedia';
                        const levelEndDate = item.level_end_date ? `${formatDate(item.level_end_date)}` : 'Tanggal tidak tersedia';
                        const sessionDate = item.session_date ? `${formatDate(item.session_date)}` : 'Tanggal tidak tersedia';

                        if (response.date == item.session_date_check) {
                            checkSession = `
                                <a href="${item.zoom_link ? item.zoom_link : ''}" class="text-[#4189E0] flex items-center gap-2 text-md font-bold">
                                    Link Zoom
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            `;
                        } else {
                            checkSession = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row items-center sm:gap-2">
                                    <p>${item.day_of_week},</p>
                                    <p>${sessionDate}</p>
                                </span>
                            `;
                        }

                        // jika level sudah dimulai, maka tampilkan tombol lihat materi, jika belum maka tampilkan tanggal mulai level
                        if (response.date >= item.level_start_date ) {
                            lockMateriVocabulary = `
                                <a href="" class="btn-materi text-[#4189E0] flex items-center gap-2 text-md font-bold" data-materi-id="${item.id}" data-materi="${item.materi_vocabulary}">
                                    Lihat Materi
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            `;

                            lockMateriGrammar = `
                                <a href="" class="btn-materi text-[#4189E0] flex items-center gap-2 text-md font-bold" data-materi-id="${item.id}" data-materi="${item.materi_grammar}">
                                    Lihat Materi
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            `;

                            lockMateriVideo = `
                                <button type="button" onclick="showVideo(this)" data-materi-id="${item.id}" data-video-id="${videoId}"
                                    class="text-[#4189E0] flex items-center gap-2 text-md font-bold">
                                        Lihat Video
                                        <i class="fas fa-chevron-right"></i>
                                </button>
                            `;

                            lockIcon = ``;

                        } else {
                            lockMateriVocabulary = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row items-center sm:gap-2">
                                    <p>${levelStartDate}</p>
                                    -
                                    <p>${levelEndDate}</p>
                                </span>
                            `;

                            lockMateriGrammar = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row items-center sm:gap-2">
                                    <p>${levelStartDate}</p>
                                    -
                                    <p>${levelEndDate}</p>
                                </span>
                            `;

                            lockMateriVideo = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row items-center sm:gap-2">
                                    <p>${levelStartDate}</p>
                                    -
                                    <p>${levelEndDate}</p>
                                </span>
                            `;

                            lockIcon = `
                                <i class="fa-solid fa-lock text-[#4189E0] font-bold text-md"></i>
                            `;
                        }

                        const card = `
                            <div class="wrapper-content-accordion !mt-2 mb-8 !px-6">

                                <div class="toggleButton">
                                    <div class="flex gap-2 max-w-[1450px] items-center">
                                        <span class="w-full opacity-70">PERTEMUAN ${item.session}</span>
                                        ${lockIcon}
                                    </div>
                                    <i class="fa-solid fa-chevron-up icon"></i>
                                </div>

                                <div class="content-accordion">
                                    <div class="w-full text-sm mt-6 flex flex-col gap-8">
                                        <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                            <div>
                                                <span class="font-semibold text-sm text-[#4189E0]">Materi</span>
                                                <p class="text-md font-semibold opacity-70">Vocabulary</p>
                                            </div>
                                                ${lockMateriVocabulary}
                                        </div>
                                        <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                            <div>
                                                <span class="font-semibold text-sm text-[#4189E0]">Materi</span>
                                                <p class="text-md font-semibold opacity-70">Grammar</p>
                                            </div>
                                            ${lockMateriGrammar}
                                        </div>
                                        <div class="flex flex-col xl:flex-row gap-8 w-full">
                                            <div class="w-full h-20 border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                            <div>
                                                <span class="font-semibold text-sm text-[#4189E0]">Materi</span>
                                                <p class="text-md font-semibold opacity-70">Video</p>
                                            </div>
                                                ${lockMateriVideo}
                                            </div>
                                            <div class="w-full h-20 border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                <div>
                                                    <p class="text-md font-semibold opacity-70">Zoom</p>
                                                </div>
                                                ${checkSession}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
        
                        containerMateri.append(card);

                        $('#empty-message-materi').hide(); // sembunyikan pesan kosong
                    });
                } else {
                        $('#empty-message-materi').show(); // Tampilkan pesan kosong
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }
}


$(document).ready(function () {
    paginateMentorStudentBatchDetailMateri();
});

$(document).on('change', '#dropdown-filter-level', function () {
    paginateMentorStudentBatchDetailMateri($(this).val());
});

// Tampilkan video materi melalui modal
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
    const modal = document.getElementById('my_modal_2-' + materiId + '-' + materi);
    if (modal) {
        modal.showModal();
    }
});