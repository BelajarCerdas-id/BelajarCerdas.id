function fetchFilteredBatchDetailPaymentMentor(paymentMentorId, page = 1) {
    $.ajax({
        url: `/paginate/batch-detail-payment-mentor/${paymentMentorId}`,
        method: 'GET',
        data: { page: page },
        success: function (data) {
            $('#tbodyBatchDetailPaymentMentor').empty();
            $('.pagination-container-batch-detail-payment-mentor').empty();

            if (data.data.length > 0) {
                $.each(data.data, function (index, application) {
                    const formatCurrency = (number) =>
                        'Rp.' + new Intl.NumberFormat('id-ID').format(number);

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

                    const createdAt = application.created_at
                        ? `${formatDate(application.created_at)}`
                        : 'Tanggal tidak tersedia';

                    $('#tbodyBatchDetailPaymentMentor').append(`
                        <tr class="text-xs">
                            <td class="border border-gray-300 text-center">${index + 1}</td>
                            <td class="border border-gray-300 text-center">${formatCurrency(application.amount)}</td>
                            <td class="border border-gray-300 text-center">${application.source_payment_mentor}</td>
                            <td class="border border-gray-300 text-center">${createdAt}</td>
                        </tr>
                    `);
                });

                $('.pagination-container-batch-detail-payment-mentor').html(data.links).show();
                $('#emptyMessage-riwayat-batch-detail-payment-mentor').hide();
                $('.thead-table-batch-detail-payment-mentor').show();

                bindPaginationLinks(paymentMentorId);
            } else {
                $('#emptyMessage-riwayat-batch-detail-payment-mentor').show();
                $('.thead-table-batch-detail-payment-mentor').hide();
            }
        },
        error: function(xhr, status, error) {
            console.error('ajax error:', status, error);
        }
    });
}

function paginateBatchDetailPaymentMentor() {
    const container = document.getElementById('container-batch-detail-payment-mentor');
    if (!container) return;

    const paymentMentorId = container.dataset.paymentMentorId;
    if (!paymentMentorId) return;

    fetchFilteredBatchDetailPaymentMentor(paymentMentorId, 1);
}

function bindPaginationLinks(paymentMentorId) {
    $('.pagination-container-batch-detail-payment-mentor').off('click', 'a').on('click', 'a', function(event) {
            event.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchFilteredBatchDetailPaymentMentor(paymentMentorId, page);
        });
}

$(document).ready(function () {
    paginateBatchDetailPaymentMentor();
});
