function paginateWorksheetDetail() {
    const container = document.getElementById('container-worksheet-detail');
    const levelId = container.dataset.levelId;
    
    if (!container) return;
    if (!levelId) return;

    fetchWorksheetDetail(levelId);

    function fetchWorksheetDetail() {
        $.ajax({
            url: `/english-zone/${levelId}/worksheet-detail/paginate`,
            method: 'GET',
            success: function (response) {
                const containerMateri = $('#grid-list-worksheet')
                containerMateri.empty();

                if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {
                        const card = `
                            <div class="bg-white shadow-md hover:shadow-xl transition-all duration-300 rounded-xl border border-gray-200 py-5 px-4 flex flex-col justify-between h-full">
                                <!-- Bagian isi atas -->
                                <div>
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-file-alt bg-[#4189E0] text-white w-6 h-6 flex items-center justify-center rounded-full text-xs"></i>
                                            <span class="text-xs font-semibold text-[#4189E0]">
                                                Worksheet ${index + 1}
                                            </span>
                                        </div>

                                        <span class="text-xs text-gray-500 group-hover:text-gray-700 transition">
                                            ${item.english_zone_level?.level_name}
                                        </span>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex flex-col gap-1 mb-5">
                                        <p class="text-sm font-bold text-gray-700 leading-tight line-clamp-2">
                                            ${item.english_zone_session?.session_name}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            Klik untuk membuka materi worksheet
                                        </p>
                                    </div>
                                </div>

                                <!-- Footer Button -->
                                <div class="w-max mt-auto cursor-pointer">
                                    <a 
                                        href="#"
                                        class="btn-materi flex items-center justify-between bg-[#4189E0] hover:bg-[#3573BA] text-white px-6 py-1 rounded-lg text-sm font-semibold transition"
                                        data-materi-id="${item.id}" 
                                        data-materi="${item.worksheet}"
                                        data-materi-type="worksheet"
                                    >
                                        <span>Lihat</span>
                                        <i class="fas fa-chevron-right text-sm pl-2"></i>
                                    </a>
                                </div>

                            </div>
                        `;

                        containerMateri.append(card);
                    })
                }
            }
        });
    }
}

$(document).ready(function () {
    paginateWorksheetDetail();
});

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