function paginateReportPaymentTanyaMentor(page = 1) {
        $.ajax({
            url: '/paginate/report-mentor',
            data: { page: page },
            method: 'GET',
            success: function (data) {
                $('#tbodyReportPaymentTanyaMentor').empty();
                $('.pagination-container-report-payment-tanya-mentor').empty();

                if (data.data.length > 0) {
                    $.each(data.data, function (index, item) {
                        const formatCurrency = (number) =>
                        'Rp.' + new Intl.NumberFormat('id-ID').format(number);

                        let statusPayment = '';

                        if (item.total_amount < 50000) {
                            statusPayment = `-`;

                        } else if(item.status_payment === 'Unpaid') {
                            statusPayment = `
                                <button
                                    class="text-xs px-4 py-2 bg-[#f9d3ba] text-[#f77a2c] font-bold rounded-md" disabled>
                                    Menunggu Pembayaran
                                </button>
                            `;
                        } else {
                            statusPayment = `
                                <button
                                    class="text-xs px-4 py-2 bg-green-200 text-green-500 font-bold rounded-md" disabled>
                                    Pembayaran Berhasil
                                </button>
                            `;
                        }

                        const batchDetailPaymentMentor = data.batchDetailPaymentMentor.replace(':id', item.id);

                        $('#tbodyReportPaymentTanyaMentor').append(`
                            <tr>
                                <td class="border text-center">${index + 1}</td>
                                <td class="border text-center">${formatCurrency(item.total_amount)}</td>
                                <td class="border text-center">${statusPayment}</td>
                                <td class="border text-center font-bold text-[#4189e0] text-xs">
                                    <a href="${batchDetailPaymentMentor}" data-payment-mentor-id="${item.id}">Lihat Detail</a>
                                </td>
                            </tr>
                        `);
                    });

                    $('.pagination-container-report-payment-tanya-mentor').html(data.links).show();
                    $('#thead-table-report-payment-tanya-mentor').show();
                    $('#emptyMessage-riwayat-report-payment-tanya-mentor').hide();

                    bindPaginationLinks();
                } else {
                    $('#thead-table-report-payment-tanya-mentor').hide();
                    $('.pagination-container-report-payment-tanya-mentor').hide();
                    $('#emptyMessage-riwayat-report-payment-tanya-mentor').show();
                }
            }
        });
    }

    // Inisialisasi saat DOM siap
$(document).ready(function () {
    paginateReportPaymentTanyaMentor();

    const paymentMentorId = $(this).data('payment-mentor-id');

    currentPaymentMentorId = paymentMentorId;
});

    function bindPaginationLinks() {
        $('.pagination-container-report-payment-tanya-mentor').off('click', 'a').on('click', 'a', function (e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchTanyaVerifications(page);
        });
    }
