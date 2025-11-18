function fetchPaginateAttendance(page = 1) {
    $.ajax({
        url: '/english-zone-student/attendance/paginate',
        method: 'GET',
        data: {
            page: page
        },
        success: function (response) {
            $('#tbody-table-attendance-history').empty();

            if (response.data.length > 0) {
                $.each(response.data, function (i, item) {

                    const formatDate = (dateString) => {
                        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                        const date = new Date(dateString);
                        const day = date.getDate();
                        const monthName = months[date.getMonth()];
                        const year = date.getFullYear();

                        return `${day}-${monthName}-${year}`;
                    };

                    const timeFormatter = new Intl.DateTimeFormat('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                    });

                    const startDate = item.feature_subscription_history?.start_date ? `${formatDate(item.feature_subscription_history?.start_date)}` : 'Tanggal tidak tersedia';
                    const endDate = item.feature_subscription_history?.end_date ? `${formatDate(item.feature_subscription_history?.end_date)}` : 'Tanggal tidak tersedia';
                    const attendanceTimeIn = item.attendance_time_in ? `${formatDate(item.attendance_time_in)}, ${timeFormatter.format(new Date(item.attendance_time_in))}` : 'Tanggal tidak tersedia';

                    $('#tbody-table-attendance-history').append(`
                        <tr class="text-xs">
                            <td class="td-table !text-black !text-center">${i + 1 ?? '-'}</td>
                            <td class="td-table !text-black !text-center">${item.feature_subscription_history?.transactions?.feature_prices?.variant_name ?? '-'}</td>
                            <td class="td-table !text-black !text-center">${startDate ?? '-'} - ${endDate ?? '-'}</td>
                            <td class="td-table !text-black !text-center">${attendanceTimeIn ?? '-'}</td>
                        </tr>
                    `);
                });

                // Append pagination links
                $('.pagination-container-attendance-history').html(response.links);
                bindPaginationLinks(); // Bind click event ke link pagination yang baru
                $('#empty-message-attendance-history').hide(); // sembunyikan pesan kosong
                $('.thead-table-attendance-history').show(); // Tampilkan tabel thead
            } else {
                $('#tbody-table-attendance-history').empty(); // Clear existing rows
                $('#empty-message-attendance-history').show(); // Tampilkan pesan kosong
                $('.thead-table-attendance-history').hide(); // sembunyikan tabel thead
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

$(document).ready(function () {
    fetchPaginateAttendance();
});

function bindPaginationLinks() {
    $('.pagination-container-attendance-history').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        fetchPaginateAttendance(page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}