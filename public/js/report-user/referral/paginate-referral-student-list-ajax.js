function paginateReferralStudentList() {
    const container = document.getElementById('container-paginate-referral-student-list');
    if (!container) return;

    const referralCode = container.dataset.referralCode;
    if (!referralCode) return;

    fetchFilteredReferralStudentList(referralCode, 1);

    function fetchFilteredReferralStudentList(referralCode, page = 1) {
        $.ajax({
            url: `/paginate-user-terdaftar/referral-code/${referralCode}`,
            method: 'GET',
            data: { page: page },
            success: function (response) {
                $('#tbody-paginate-referral-student-list').empty();
                $('.pagination-container-paginate-referral-student-list').empty();

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

                        const mentorReferralJoinedAt = item.mentor_referral_joined_at ? `${formatDate(item.mentor_referral_joined_at)}` : 'Tanggal tidak tersedia';

                        $('#tbody-paginate-referral-student-list').append(`
                            <tr>
                                <td class="!text-center border border-gray-300">${index + 1}</td>
                                <td class="border border-gray-300">${item.nama_lengkap}</td>
                                <td class="!text-center border border-gray-300">${mentorReferralJoinedAt}</td>
                            </tr>
                        `);
                    })
                    // Insert pagination HTML
                    $('.pagination-container-paginate-referral-student-list').html(response.links).show();
                    $('#emptyMessage-paginate-referral-student-list').hide();
                    $('.thead-table-paginate-referral-student-list').show();

                    // Bind click event ke link pagination yang baru
                    bindPaginationLinks(referralCode);
                }
                else {
                    $('#emptyMessage-paginate-referral-student-list').show();
                    $('.thead-paginate-referral-student-list').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('ajax error:', status, error);
            }
        })
    }
}

$(document).ready(function () {
    paginateReferralStudentList();
});

function bindPaginationLinks(referralCode) {
    $('.pagination-container-paginate-referral-student-list').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        fetchFilteredReferralStudentList(referralCode, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}
