function paginatePaymentTanyaMentor(page = 1) {
        $.ajax({
            url: '/paginate/list-pembayaran-mentor',
            data: { page: page },
            method: 'GET',
            success: function (data) {
                $('#tbodyPaymentTanyaMentor').empty();
                $('.pagination-container-payment-tanya-mentor').empty();

                if (data.data.length > 0) {
                    $.each(data.data, function (index, item) {

                        let statusPayment = '';

                        if (item.total_ammount < 50000) {
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

                        let buttonPayment = '';

                        if(item.status_payment === 'Paid' || item.total_ammount < 50000) {
                            buttonPayment = `-`;

                        } else {
                            buttonPayment = `
                                <button
                                    class="text-xs px-4 py-2 bg-[#D0EBFF] text-[#4189e0] font-bold rounded-md">
                                    Bayar Sekarang
                                </button>
                            `;
                        }

                        const paymentTanyaMentorUpdate = data.paymentTanyaMentorUpdate.replace(':id', item.id);

                        $('#tbodyPaymentTanyaMentor').append(`
                            <tr>
                                <td class="border text-center">${index + 1}</td>
                                <td class="border text-center">${item.mentor?.mentor_profiles?.nama_lengkap}</td>
                                <td class="border text-center">-</td>
                                <td class="border text-center">${item.total_ammount}</td>
                                <td class="border text-center">${statusPayment}</td>
                                <td class="border text-center">
                                    <a href="${paymentTanyaMentorUpdate}" class="btn-payment-tanya-mentor" data-id="${item.id}">${buttonPayment}</a>
                                </td>
                                <td class="border text-center">
                                    <a href="${paymentTanyaMentorUpdate}" class="text-[#4189E0] font-bold text-xs">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        `);
                    });

                    $('.pagination-container-payment-tanya-mentor').html(data.links).show();
                    $('#thead-table-payment-tanya-mentor').show();
                    $('#emptyMessage-riwayat-payment-tanya-mentor').hide();

                    bindPaginationLinks();
                } else {
                    $('#thead-table-payment-tanya-mentor').hide();
                    $('.pagination-container-payment-tanya-mentor').hide();
                    $('#emptyMessage-riwayat-payment-tanya-mentor').show();
                }
            }
        });
    }

    // Inisialisasi saat DOM siap
$(document).ready(function () {
    paginatePaymentTanyaMentor();

    $(document).on('click', '.btn-payment-tanya-mentor', function(e) {
            e.preventDefault();

            const paymentId = $(this).data('id');
            const urlUpdate = $(this).attr('href');
            const btn = $(this);

            $.ajax({
                url: `/pembayaran-mentor/update/${paymentId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    paginatePaymentTanyaMentor();
                },
                error: function (xhr) {
                    '';
                }
            });
        });
});

    function bindPaginationLinks() {
        $('.pagination-container-payment-tanya-mentor').off('click', 'a').on('click', 'a', function (e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchTanyaVerifications(page);
        });
    }
