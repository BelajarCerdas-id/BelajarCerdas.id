function riwayatAssessmentPracticeExam(page = 1) {
    $.ajax({
        url: '/soal-pembahasan/riwayat-assessment/paginate',
        method: 'GET',
        data: {
            page: page // Include the page parameter
        },
        success: function (response) {
            const container = $('#grid-history-assessment-exam-practice');
            container.empty();

            $('.pagination-container-history-assessment').empty();

            if (response.data.length > 0) {
                response.data.forEach((group) => {
                    const groupedAnswers = group.at(-1);

                    // Fungsi untuk mengonversi tanggal dalam format hari, tanggal-bulan-tahun
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

                    const createdAt = groupedAnswers.created_at ? `${formatDate(groupedAnswers.created_at)}` : 'Tanggal tidak tersedia';

                    // Fungsi untuk mengonversi tanggal dalam format YYYY-MM-DD
                    const dateFormatter = (dateString) => {
                        const date = new Date(dateString);
                        const day = String(date.getDate()).padStart(2, '0'); // hari 0–31
                        const month = String(date.getMonth() + 1).padStart(2, '0'); // bulan 0–11
                        const year = date.getFullYear();

                        return `${year}-${month}-${day}`; // YYYY-MM-DD
                    };

                    // untuk link href lihat detail
                    const linkDate = groupedAnswers.created_at ? `${dateFormatter(groupedAnswers.created_at)}` : 'Tanggal tidak tersedia';

                    let historyAssessmentLink = '';

                    // memeriksa apakah tipe_soal pada history adalah 'Latihan', jika ya maka link menggunakan sub_bab_id
                    if (groupedAnswers.soal_pembahasan_questions?.tipe_soal === 'Latihan') {
                        historyAssessmentLink = response.historyQuestionsAssessment.replace(':tipe_soal', groupedAnswers.soal_pembahasan_questions?.tipe_soal)
                            .replace(':materi_id', groupedAnswers.soal_pembahasan_questions?.sub_bab_id).replace(':date', linkDate)
                            .replace(':kelas', groupedAnswers.soal_pembahasan_questions?.kelas?.kelas).replace(':mata_pelajaran', groupedAnswers.soal_pembahasan_questions?.mapel?.mata_pelajaran);                    // memeriksa apakah tipe_soal pada history adalah 'Ujian', jika ya maka link menggunakan bab_id
                    } else {
                        historyAssessmentLink = response.historyQuestionsAssessment.replace(':tipe_soal', groupedAnswers.soal_pembahasan_questions?.tipe_soal)
                            .replace(':materi_id', groupedAnswers.soal_pembahasan_questions?.bab_id).replace(':date', linkDate)
                            .replace(':kelas', groupedAnswers.soal_pembahasan_questions?.kelas?.kelas).replace(':mata_pelajaran', groupedAnswers.soal_pembahasan_questions?.mapel?.mata_pelajaran);
                    }

                    let babOrSubBab = '';

                    if (groupedAnswers.soal_pembahasan_questions?.tipe_soal === 'Latihan') {
                        babOrSubBab = groupedAnswers.soal_pembahasan_questions?.sub_bab?.sub_bab;
                    } else {
                        babOrSubBab = groupedAnswers.soal_pembahasan_questions?.bab?.nama_bab;
                    }

                        const card = `
                            <div class="list-item">
                                <div class="bg-white shadow-lg rounded-md p-4 border">
                                    <div class="flex justify-between w-full">
                                        <span class="text-md py-1 font-bold opacity-70">
                                            ${groupedAnswers.soal_pembahasan_questions?.mapel?.mata_pelajaran}
                                        </span>
                                        <span class="text-md py-1 font-bold opacity-70">
                                            ${groupedAnswers.soal_pembahasan_questions?.tipe_soal}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex flex-col w-full">
                                            <span class="text-sm py-1 font-bold opacity-70">
                                                ${groupedAnswers.soal_pembahasan_questions?.kelas?.kelas}
                                            </span>
                                            <span class="text-sm py-1 font-bold opacity-70">
                                                ${babOrSubBab}
                                            </span>
                                            <div class="flex justify-between w-full">
                                                <span class="text-xs py-1 font-bold opacity-70">
                                                    Tanggal: ${createdAt}
                                                </span>
                                                <a href="${historyAssessmentLink}" class="text-md">
                                                    <button class="text-[#4189e0] font-bold">
                                                        Detail
                                                        <i class="fas fa-chevron-right"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    container.append(card);
                })
                $('.pagination-container-history-assessment').html(response.links);
                $('.pagination-container-history-assessment').show();
                $('.noDataMessageHistoryAssessment').hide();
                bindHistoryPaginationAssessment();
            } else {
                $('.noDataMessageHistoryAssessment').show();
                $('.pagination-container-history-assessment').hide();
            }
        }
    });
}

function bindHistoryPaginationAssessment() {
    $('.pagination-container-history-assessment').off('click', 'a').on('click', 'a', function (e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        riwayatAssessmentPracticeExam(page);
    });
}

$(document).ready(function () {
    riwayatAssessmentPracticeExam();
});
