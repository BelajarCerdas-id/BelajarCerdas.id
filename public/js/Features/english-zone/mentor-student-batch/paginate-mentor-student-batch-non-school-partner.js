function paginateMentorStudentBatchNonSchoolPartner(page = 1) {
    $.ajax({
        url: '/english-zone-mentor/student-batch/non-school-partner/paginate',
        method: 'GET',
        data: {
            page: page,
        },
        success: function (response) {
            $('#tbody-table-management-student-batch-non-school-partner').empty();

            if (response.data.length > 0) {
                $.each(response.data, function (i, items) {
                    let levelIds = items.level_ids.join(',');
                    let levelNames = items.level_names.join(', ');
                    let batchIds = items.batch_ids.join(' & ');
                    let batchNames = items.batch_names.join(' & ');
                    let daysList = items.days_of_week.join(' & ');
                    let hours = items.hours;

                    let explodeStudentIds = items.student_ids.join(',');
                    const managementStudentBatchDetail = response.studentBatchDetail.replace(':featureVariantId', items.variant_id).replace(':levelId', levelIds)
                        .replace(':batchId', batchIds).replace(':batchScheduleGroups', items.batch_schedule_groups.join(',')).replace(':batchScheduleIds', items.batch_schedule_ids.join(','))
                        .replace(':studentIds', explodeStudentIds);

                    const formatDate = (dateString) => {
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Juni', 'Juli', 'Agust', 'Sept', 'Okt', 'Nov', 'Des'];
                        const date = new Date(dateString);
                        const day = date.getDate();
                        const monthName = months[date.getMonth()];
                        const year = date.getFullYear();

                        return `${day}-${monthName}-${year}`;
                    };

                    const startDate = items.start_date ? `${formatDate(items.start_date)}` : 'Tanggal tidak tersedia';
                    const endDate = items.end_date ? `${formatDate(items.end_date)}` : 'Tanggal tidak tersedia';

                    $('#tbody-table-management-student-batch-non-school-partner').append(`
                    <tr class="text-xs">
                        <td class="td-table !text-black !text-center">${i + 1}</td>
                        <td class="td-table !text-black !text-center">${items.variant_name}</td>
                        <td class="td-table !text-black !text-center">${levelNames}</td>
                        <td class="td-table !text-black !text-center">${batchNames}</td>
                        <td class="td-table !text-black !text-center">${daysList}</td>
                        <td class="td-table !text-black !text-center">${hours}</td>
                        <td class="td-table !text-black !text-center">${startDate} - ${endDate}</td>
                        <td class="td-table !text-black !text-center w-[1%]">${items.count_students ?? 0}</td>
                        <td class="td-table !text-black !text-center">
                            <a href="${managementStudentBatchDetail}" class="text-[#4189E0] font-bold text-xs">Lihat Detail</a>
                        </td>
                    </tr>
                `);
                });

                // Append pagination links
                $('.pagination-container-management-student-batch').html(response.links);
                bindPaginationLinks(); // Bind click event ke link pagination yang baru
                $('#empty-message-management-student-batch-non-school-partner').hide(); // sembunyikan pesan kosong
                $('.thead-table-management-student-batch-non-school-partner').show(); // Tampilkan tabel thead
            } else {
                $('#tbody-table-management-student-batch-non-school-partner').empty(); // Clear existing rows
                $('#empty-message-management-student-batch-non-school-partner').show(); // Tampilkan pesan kosong
                $('.thead-table-management-student-batch-non-school-partner').hide(); // sembunyikan tabel thead
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });
}

$(document).ready(function () {
    paginateMentorStudentBatchNonSchoolPartner();
});

function bindPaginationLinks() {
    $('.pagination-container-management-student-batch').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        paginateMentorStudentBatchNonSchoolPartner(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// buat kembalikan header radio nya ketika di back ke student batch view menggunakan arrow back chrome
window.addEventListener("pageshow", function (event) {
    document.getElementById('radio1').checked = true;
});