function paginateMateriStudent(selectedLevel = null) {
    const container = document.getElementById('container-materi-student');
    const levelIds = container.dataset.levelId.split(',');

    const activeLevel = selectedLevel || levelIds[0];

    fetchDataPaginateMateriStudent(levelIds.join(','), activeLevel)

    function fetchDataPaginateMateriStudent() {
        $.ajax({
            url: `/english-zone-student/materi/${levelIds}/${activeLevel}/paginate`,
            method: 'GET',
            data: {
                levelIds: levelIds
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

                        const worksheetDetail = response.worksheetDetail.replace(':levelId', activeLevel);
    
                        if (response.getSubscriptionStudent &&  response.date == item.session_date_check) {
                            checkSession = `
                                <a href="${item.zoom_link ? item.zoom_link : ''}" class="text-[#4189E0] flex items-center gap-2 text-md font-bold">
                                    Link Zoom
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            `;
                        } else if (response.getSubscriptionStudent && response.date != item.session_date_check) {
                            checkSession = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row items-center sm:gap-1 lg::gap-2 text-xs md:text-[13px]">
                                    <p>${item.day_of_week},</p>
                                    <p>${sessionDate}</p>
                                </span>
                            `;
                        } else {
                            checkSession = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                        }
    
                        // jika level sudah dimulai, maka tampilkan tombol lihat materi, jika belum maka tampilkan tanggal mulai level
                        if (response.date >= item.level_start_date) {
                            lockMateriVocabulary = `
                                    <a href="" class="btn-materi text-[#4189E0] flex items-center gap-2 text-md font-bold" data-materi-id="${item.id}" data-materi="${item.materi_vocabulary}"
                                        data-materi-type="vocabulary">
                                        Lihat Materi
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                `;
    
                            lockMateriGrammar = `
                                    <a href="" class="btn-materi text-[#4189E0] flex items-center gap-2 text-md font-bold" data-materi-id="${item.id}" data-materi="${item.materi_grammar}"
                                        data-materi-type="grammar">
                                        Lihat Materi
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                `;
    
                            lockMateriReading = `
                                    <a href="" class="btn-materi text-[#4189E0] flex items-center gap-2 text-md font-bold" data-materi-id="${item.id}" data-materi="${item.materi_reading}"
                                        data-materi-type="reading">
                                        Lihat Materi
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                `;
    
                            lockMateriWriting = `
                                    <a href="" class="btn-materi text-[#4189E0] flex items-center gap-2 text-md font-bold" data-materi-id="${item.id}" data-materi="${item.materi_writing}"
                                        data-materi-type="writing">
                                        Lihat Materi
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                `;
    
                            lockMateriListening = `
                                    <a href="" class="btn-materi text-[#4189E0] flex items-center gap-2 text-md font-bold" data-materi-id="${item.id}" data-materi="${item.materi_listening}"
                                        data-materi-type="listening">
                                        Lihat Materi
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                `;
    
                            lockMateriSpeaking = `
                                    <a href="" class="btn-materi text-[#4189E0] flex items-center gap-2 text-md font-bold" data-materi-id="${item.id}" data-materi="${item.materi_speaking}"
                                        data-materi-type="speaking">
                                        Lihat Materi
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                `;
    
                            lockMateriPembelajaran = `
                                    <a href="" class="btn-materi text-[#4189E0] flex items-center gap-2 text-md font-bold" data-materi-id="${item.id}" data-materi="${item.materi_pembelajaran}"
                                        data-materi-type="pembelajaran">
                                        Lihat Materi
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                `;
    
                            lockWorksheet = `
                                    <a href="${worksheetDetail}" class="text-[#4189E0] flex items-center gap-2 text-sm font-bold"
                                        data-materi-type="worksheet">
                                        Detail
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
    
                        } else if (response.date < item.level_start_date) {
                            lockMateriVocabulary = `
                                    <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-2 text-xs md:text-[13px]">
                                        <p>${levelStartDate}</p>
                                        -
                                        <p>${levelEndDate}</p>
                                    </span>
                                `;
    
                            lockMateriGrammar = `
                                    <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-2 text-xs md:text-[13px]">
                                        <p>${levelStartDate}</p>
                                        -
                                        <p>${levelEndDate}</p>
                                    </span>
                                `;
    
                            lockMateriReading = `
                                    <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-2 text-xs md:text-[13px]">
                                        <p>${levelStartDate}</p>
                                        -
                                        <p>${levelEndDate}</p>
                                    </span>
                                `;
    
                            lockMateriWriting = `
                                    <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-2 text-xs md:text-[13px]">
                                        <p>${levelStartDate}</p>
                                        -
                                        <p>${levelEndDate}</p>
                                    </span>
                                `;
    
                            lockMateriListening = `
                                    <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-2 text-xs md:text-[13px]">
                                        <p>${levelStartDate}</p>
                                        -
                                        <p>${levelEndDate}</p>
                                    </span>
                                `;
    
                            lockMateriSpeaking = `
                                    <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-2 text-xs md:text-[13px]">
                                        <p>${levelStartDate}</p>
                                        -
                                        <p>${levelEndDate}</p>
                                    </span>
                                `;
    
                            lockMateriPembelajaran = `
                                    <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-2 text-xs md:text-[13px]">
                                        <p>${levelStartDate}</p>
                                        -
                                        <p>${levelEndDate}</p>
                                    </span>
                                `;
    
                            lockWorksheet = `
                                    <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-2 text-xs md:text-[13px]">
                                        <p>${levelStartDate}</p>
                                        -
                                        <p>${levelEndDate}</p>
                                    </span>
                                `;
    
                            lockMateriVideo = `
                                    <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-2 text-xs md:text-[13px]">
                                        <p>${levelStartDate}</p>
                                        -
                                        <p>${levelEndDate}</p>
                                    </span>
                                `;
    
                            lockIcon = `
                                    <i class="fa-solid fa-lock text-[#4189E0] font-bold text-md"></i>
                                `;
                        } else {
                            lockMateriVocabulary = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                            lockMateriGrammar = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                            lockMateriReading = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                            lockMateriWriting = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                            lockMateriListening = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                            lockMateriSpeaking = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                            lockMateriPembelajaran = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                            lockWorksheet = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                            lockMateriVideo = `
                                <span class="font-bold opacity-70 flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center sm:gap-1 lg:gap-0 xl:gap-1 text-xs md:text-[13px]">
                                    <p>Belum</p>
                                    <p>langganan</p>
                                </span>
                            `;
                            lockIcon = `
                                <i class="fa-solid fa-lock text-[#4189E0] font-bold text-md"></i>
                            `;
                        }
    
                        const card = `
                                <div class="wrapper-content-accordion !mt-2 mb-8 !px-6">
                                    <div class="toggleButton">
                                        <div class="flex pr-6 items-center">
                                            <span class="w-full opacity-70 mr-4 text-sm">${item.english_zone_session?.session_name}</span>
                                            ${lockIcon}
                                        </div>
                                        <i class="fa-solid fa-chevron-up icon"></i>
                                    </div>
    
                                    <div class="content-accordion">
                                        <div class="w-full text-sm mt-6 flex flex-col gap-8">
                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                                <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div> 
                                                            <i class="fa-solid fa-book bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-xs md:text-sm text-[#4189E0]">Materi</span>
                                                            <p class="text-xs md:text-md font-semibold opacity-70">Vocabulary</p>
                                                        </div>
                                                    </div>
                                                        ${lockMateriVocabulary}
                                                </div>
                                                <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div> 
                                                            <i class="fa-solid fa-spell-check bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-xs md:text-sm text-[#4189E0]">Materi</span>
                                                            <p class="text-xs md:text-md font-semibold opacity-70">Grammar</p>
                                                        </div>
                                                    </div>
                                                    ${lockMateriGrammar}
                                                </div>
                                                <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div> 
                                                            <i class="fa-brands fa-readme bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-xs md:text-sm text-[#4189E0]">Materi</span>
                                                            <p class="text-xs md:text-md font-semibold opacity-70">Reading</p>
                                                        </div>
                                                    </div>
                                                    ${lockMateriReading}
                                                </div>
                                                <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div> 
                                                            <i class="fa-solid fa-file-pen bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full pl-1"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-xs md:text-sm text-[#4189E0]">Materi</span>
                                                            <p class="text-xs md:text-md font-semibold opacity-70">Wrriting</p>
                                                        </div>
                                                    </div>
                                                    ${lockMateriWriting}
                                                </div>
                                                <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div> 
                                                            <i class="fa-solid fa-headphones bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-xs md:text-sm text-[#4189E0]">Materi</span>
                                                            <p class="text-xs md:text-md font-semibold opacity-70">Listening</p>
                                                        </div>
                                                    </div>
                                                    ${lockMateriListening}
                                                </div>
                                                <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div> 
                                                            <i class="fa-solid fa-microphone bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-xs md:text-sm text-[#4189E0]">Materi</span>
                                                            <p class="text-xs md:text-md font-semibold opacity-70">Speaking</p>
                                                        </div>
                                                    </div>
                                                    ${lockMateriSpeaking}
                                                </div>
                                                <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div> 
                                                            <i class="fa-solid fa-lightbulb bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-xs md:text-sm text-[#4189E0]">Materi</span>
                                                            <p class="text-xs md:text-md font-semibold opacity-70">Pembelajaran</p>
                                                        </div>
                                                    </div>
                                                    ${lockMateriPembelajaran}
                                                </div>
                                                <div class="w-full h-20 border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div> 
                                                            <i class="fa-solid fa-play bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-semibold text-xs md:text-sm text-[#4189E0]">Materi</span>
                                                            <p class="text-xs md:text-md font-semibold opacity-70">Video</p>
                                                        </div>
                                                    </div>
                                                    ${lockMateriVideo}
                                                </div>
                                                <div class="w-full h-20 border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <div> 
                                                            <i class="fa-solid fa-video bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs md:text-md font-semibold opacity-70">Zoom</p>
                                                        </div>
                                                    </div>
                                                    ${checkSession}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
    
                        containerMateri.append(card);

                        $('#container-worksheet-quiz-student').html(`
                            <div class="grid grid-cols-1 lg:grid-cols-2"> 
                                <div class="w-full border-2 border-gray-200 bg-white shadow-lg rounded-lg p-4 flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <i class="fa-solid fa-file-alt bg-[#4189E0] text-white w-10 h-10 flex items-center justify-center rounded-full"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs md:text-md font-semibold opacity-70">Worksheet</p>
                                        </div>
                                    </div>
                                    ${lockWorksheet}
                                </div>
                            </div>
                        `);
    
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
    paginateMateriStudent();
});

$(document).on('change', '#dropdown-filter-level', function () {
    paginateMateriStudent($(this).val());
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

// show materi
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
            <div class="modal-box bg-white max-w-6xl max-h-[600px] lg:max-h-[800px]">
                <div class="flex justify-center w-full mb-4">
                    <span class="text-2xl font-bold opacity-70">${materiType.toUpperCase()}</span>
                </div>
                <div class="border max-w-6xl h-[500px] lg:h-[700px] flex justify-start">
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