function paginateListMentorTanya(page = 1) {
    $.ajax({
        url: '/paginate/list-mentor-tanya',
        method: 'GET',
        data: {
            page: page
        },
        success: function (response) {
            const container = $('#grid-list-mentor-tanya');
            container.empty();
            $('.pagination-container-list-mentor-tanya').empty();

            if (response.data.length > 0) {
                response.data.forEach((item) => {

                    const detailDataTanyaMentor = response.detailDataTanyaMentor.replace(':id', item.id);

                    const card = `
                        <div class="w-full bg-white shadow-lg rounded-lg h-32 flex items-center justify-between px-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-circle text-4xl"></i>
                                <div class="flex flex-col">
                                    ${item.mentor_profiles?.nama_lengkap ?? ''}
                                    <span class="text-sm">
                                        ${response.countData[item.id] ? response.countData[item.id] : 0}
                                        Soal menunggu verifikasi
                                    </span>
                                </div>
                            </div>
                                <a href="${detailDataTanyaMentor}" class="text-[#4189e0] font-bold btn-detail-mentor" data-id="${item.id}">Lihat Detail</a>
                        </div>
                    `;
                    container.append(card);
                })
                $('.pagination-container-list-mentor-tanya').html(response.links);
                $('.pagination-container-list-mentor-tanya').show();
                $('.noDataMessageListMentorTanya').hide();
                bindPaginateListMentorTanya();
            } else {
                $('.noDataMessageListMentorTanya').show();
            }
        }
    })
}


// Initial fetch
$(document).ready(function () {
    paginateListMentorTanya();

    // Tangani klik tombol "Lihat Detail"
    $(document).on('click', '.btn-detail-mentor', function (e) {;
        const mentorId = $(this).data('id');
        currentMentorId = mentorId; // <-- Tambahkan ini!
        paginateQuestionVerificationMentor(1, mentorId); // Panggil AJAX untuk daftar soal
    });
});

function bindPaginateListMentorTanya() {
    $('.pagination-container-list-mentor-tanya').off('click', 'a').on('click', 'a', function (e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        paginateListMentorTanya(page);
    });
}



