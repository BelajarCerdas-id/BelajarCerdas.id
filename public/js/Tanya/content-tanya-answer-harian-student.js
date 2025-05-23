// FUNCTION ADD CONTENT STUDENT (DITERIMA)
function fetchDataTanyaAnswered() {
    $.ajax({
        url: '/student/history-answered',
        method: 'GET',
        success: function (response) {
            const container = $('#cardAnswer');
            container.empty();


            if (response.data && response.data.length > 0) {
                response.data.forEach(item => {
                    const formatDate = (dateString) => {
                        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                        const date = new Date(dateString);
                        const dayName = days[date.getDay()];
                        const day = date.getDate();
                        const monthName = months[date.getMonth()];
                        const year = date.getFullYear();

                        return `${dayName}, ${day}-${monthName}-${year}`;
                    };

                    const timeFormatter = new Intl.DateTimeFormat('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                    });

                    const createdAt = item.created_at ? `${formatDate(item.created_at)}, ${timeFormatter.format(new Date(item.created_at))}` : 'Tanggal tidak tersedia';

                    let imageSection = '';

                    if (item.image_tanya) {
                        imageSection = `
                            <div class="w-[60px] h-[75px]">
                                <img src="/images_tanya/${item.image_tanya}"
                                alt="" class="h-full w-full">
                            </div>
                        `;
                    } else {
                        imageSection = `
                            <div class="w-[60px] h-[85px] flex items-center text-xs bg-white shadow-md rounded-md">
                                <span class="text-center w-full">No <br>Image</span>
                            </div>
                        `;
                    }

                    const questionAnswerDaily = item.questionAnswerDaily;

                    const urlQuestionAnswerId = `/updateStatusSoalAnswered/${item.id}`;

                    const cardAnswer = `
                        <div class="updateStatusSoal flex items-center justify-between mt-6 p-4 rounded-lg
                            ${item.status_soal_student === 'Belum Dibaca' ? 'unRead bg-blue-50 cursor-pointer' : '' }"
                            data-url-id="${urlQuestionAnswerId}">
                            <div class="flex items-center gap-8 leading-8">
                                ${imageSection}
                                <div class="flex flex-col justify-center">
                                    <span class="text-xs">${item.kelas.kelas}</span>
                                    <span class="text-sm">${item.mapel.mata_pelajaran}</span>
                                    <span class="text-sm font-bold">${item.bab.nama_bab}</span>
                                <div class="">
                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                    <span
                                        class="text-xs">${createdAt}</span>
                                </div>
                                </div>
                            </div>
                            <div class="">
                                <a href="${questionAnswerDaily}"
                                    class="text-[#4189e0] font-bold text-sm lihatSoal"
                                    data-url-id="${urlQuestionAnswerId}">
                                    Lihat
                                </a>
                            </div>
                        </div>
                    `;
                    container.append(cardAnswer);
                    bindUpdateStatusAnsweredListeners(); // panggil ini setelah append semua card
                    $('#emptyMessageTanyaHarianTerjawab').hide();
                });
                checkAnsweredQuestions();
            } else {
                $('#emptyMessageTanyaHarianTerjawab').show();
                $('#updateStatusSoalAll').addClass('hidden');
            }
        },
        error: function(err) {
            console.error(err);
        }
    });
}

$(document).ready(() => {
    fetchDataTanyaAnswered();
});

// FUNCTION UNTUK UPDATE STATUS SOAL STUDENT (DITOLAK)
function fetchDataTanyaRejected() {
    $.ajax({
        url: '/student/history-rejected',
        method: 'GET',
        success: function (response) {
            const container = $('#cardRejected');
            container.empty();


            if (response.data && response.data.length > 0) {
                response.data.forEach(item => {
                    const formatDate = (dateString) => {
                        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                        const date = new Date(dateString);
                        const dayName = days[date.getDay()];
                        const day = date.getDate();
                        const monthName = months[date.getMonth()];
                        const year = date.getFullYear();

                        return `${dayName}, ${day}-${monthName}-${year}`;
                    };

                    const timeFormatter = new Intl.DateTimeFormat('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                    });

                    const createdAt = item.created_at ? `${formatDate(item.created_at)}, ${timeFormatter.format(new Date(item.created_at))}` : 'Tanggal tidak tersedia';

                    let imageSection = '';

                    if (item.image_tanya) {
                        imageSection = `
                            <div class="w-[60px] h-[75px]">
                                <img src="/images_tanya/${item.image_tanya}"
                                alt="" class="h-full w-full">
                            </div>
                        `;
                    } else {
                        imageSection = `
                            <div class="w-[60px] h-[85px] flex items-center text-xs bg-white shadow-md rounded-md">
                                <span class="text-center w-full">No <br>Image</span>
                            </div>
                        `;
                    }

                    const questionRejectedDaily = item.questionRejectedDaily;

                    const urlQuestionRejectedId = `/updateStatusSoalAnswered/${item.id}`;

                    const cardRejected = `
                        <div class="updateStatusSoalRejected flex items-center justify-between mt-6 p-4 rounded-lg
                            ${item.status_soal_student === 'Belum Dibaca' ? 'rejectedUnRead bg-blue-50 cursor-pointer' : '' }"
                            data-url-id="${urlQuestionRejectedId}">
                            <div class="flex items-center gap-8 leading-8">
                                ${imageSection}
                                <div class="flex flex-col justify-center">
                                    <span class="text-xs">${item.kelas.kelas}</span>
                                    <span class="text-sm">${item.mapel.mata_pelajaran}</span>
                                    <span class="text-sm font-bold">${item.bab.nama_bab}</span>
                                <div class="">
                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                    <span
                                        class="text-xs">${createdAt}</span>
                                </div>
                                </div>
                            </div>
                            <div class="">
                                <a href="${questionRejectedDaily}"
                                    class="text-[#4189e0] font-bold text-sm">
                                    Lihat
                                </a>
                            </div>
                        </div>
                    `;
                    container.append(cardRejected);
                    bindUpdateStatusRejectedListeners(); // panggil ini setelah append semua card
                    $('#emptyMessageTanyaHarianRejected').hide();
                });
                // button.append(markButton);
                checkAnsweredQuestionsRejected();
            } else {
                $('#emptyMessageTanyaHarianRejected').show();
                $('#updateStatusSoalAllRejected').addClass('hidden');
            }
        },
        error: function(err) {
            console.error(err);
        }
    });
}

$(document).ready(() => {
    fetchDataTanyaRejected();
});
