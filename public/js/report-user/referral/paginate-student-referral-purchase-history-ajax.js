function paginateStudentReferralPurchaseHistory() {
    const container = document.getElementById('container-student-referral-purchase-history');
    if (!container) return;

    const referralCode = container.dataset.referralCode;
    if (!referralCode) return;

    fetchFilteredReferralStudentList(referralCode, 1);

    function fetchFilteredReferralStudentList(referralCode, page = 1) {
        $.ajax({
            url: `/paginate-riwayat-paket-pembelian-siswa/referral-code/${referralCode}`,
            method: 'GET',
            data: { page: page },
            success: function (response) {
                $('#tbody-student-referral-purchase-history').empty();
                $('.pagination-container-student-referral-purchase-history').empty();

                if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {
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

                        // masa aktif berlaku
                        const startDate = item.start_date ? `${formatDate(item.start_date)}` : 'Tanggal tidak tersedia';
                        const endDate = item.end_date ? `${formatDate(item.end_date)}` : 'Tanggal tidak tersedia';

                        $('#tbody-student-referral-purchase-history').append(`
                            <tr>
                                <td class="!text-center border border-gray-300">${index + 1}</td>
                                <td class="!text-center border border-gray-300">${item.user_account?.student_profiles?.nama_lengkap}</td>
                                <td class="!text-center border border-gray-300">${item.transactions?.features?.nama_fitur}</td>
                                <td class="!text-center border border-gray-300">${item.transactions?.feature_prices?.variant_name}</td>
                                <td class="!text-center border border-gray-300">${startDate}</td>
                                <td class="!text-center border border-gray-300">${endDate}</td>

                            </tr>
                        `);
                    })
                    // Insert pagination HTML
                    $('.pagination-container-student-referral-purchase-history').html(response.links).show();
                    $('#emptyMessage-student-referral-purchase-history').hide();
                    $('.thead-table-student-referral-purchase-history').show();

                    // Bind click event ke link pagination yang baru
                    bindPaginationLinks(referralCode);
                }
                else {
                    $('#emptyMessage-student-referral-purchase-history').show();
                    $('.thead-table-student-referral-purchase-history').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('ajax error:', status, error);
            }
        })
    }
}

$(document).ready(function () {
    paginateStudentReferralPurchaseHistory();
});

function bindPaginationLinks(referralCode) {
    $('.pagination-container-student-referral-purchase-history').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        fetchFilteredReferralStudentList(referralCode, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}
