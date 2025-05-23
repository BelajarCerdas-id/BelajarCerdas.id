function fetchPaginateHistoryTransactionFailed(page = 1) {
    $.ajax({
        url: '/paginate-histori-pembelian-failed',
        method: 'GET',
        data: {
            page: page
        },
        success: function (response) {
            const container = $('#grid-transaction-failed-list');
            container.empty();
            $('.pagination-container-transaction-failed').empty();

            if (response.data.length > 0) {
                response.data.forEach((item) => {
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

                    const timeFormatter = new Intl.DateTimeFormat('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                    });

                    const createdAt = item.created_at ? `${formatDate(item.created_at)}, ${timeFormatter.format(new Date(item.created_at))}` : 'Tanggal tidak tersedia';

                    const card = `
                    <div class="list-item">
                        <div class="bg-white shadow-lg rounded-md p-4">
                            <div class="flex justify-between">
                                <span class="text-md font-bold opacity-60">${item.features.nama_fitur}</span>
                                <span class="text-sm px-4 py-1 bg-red-200 text-red-600 font-bold rounded-xl">
                                    ${item.transaction_status}
                                </span>
                            </div>
                            <span class="text-md font-bold opacity-70 block mt-1">
                                ${item.features.nama_fitur === 'TANYA' ? item.jumlah_koin + ' Koin' : item.feature_prices.variant_name}
                            </span>
                            <div class="flex justify-between mt-2">
                                <span class="text-md bg-[#D0EBFF] px-4 py-1 rounded-xl font-bold text-[#4189FF]">
                                    ${formatCurrency(item.price)}
                                </span>
                                <button class="button-detail-failed text-[#4189FF] font-bold">Lihat Detail</button>
                            </div>
                            <div class="content-dropdown-histori-pembelian">
                                <div class="flex flex-col gap-2 mt-10">
                                    <span class="font-bold opacity-60">Detail Pembelian :</span>
                                    <div class="bg-blue-100 flex flex-col gap-2 rounded-md p-2">
                                        <span class="font-bold opacity-70">
                                            Order ID : ${item.order_id}
                                        </span>
                                        ${item.features.nama_fitur === 'TANYA' ? `<span class="font-bold opacity-70">Varian : ${item.feature_prices.variant_name}</span>` : ''}
                                        <span class="font-bold opacity-70">
                                            Tanggal Pembelian :
                                            ${createdAt}
                                        </span>
                                    </div>

                                    <span class="font-bold opacity-60">Informasi Pembelian :</span>
                                    <div class="bg-blue-100 flex flex-col gap-2 rounded-md p-2">
                                        <span class="font-bold opacity-70">
                                            Nama Lengkap :
                                            ${item.user_account?.student_profiles?.nama_lengkap || 'Nama Lengkap tidak tersedia'}
                                        </span>
                                        <span class="font-bold opacity-70">
                                            Email : ${item.user_account?.email || 'Email tidak tersedia'}
                                        </span>
                                        <span class="font-bold opacity-70">
                                            No.Hp : ${item.user_account?.no_hp || 'No.Hp tidak tersedia'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;

                    container.append(card);
                });

                $('.pagination-container-transaction-failed').html(response.links);
                $('.pagination-container-transaction-failed').show();
                $('.noDataMessageFailed').hide();
                bindTransactionPaginationFailed();
                bindDetailToggleFailed(); // agar tombol "Lihat Detail" aktif
            } else {
                $('.pagination-container-transaction-failed').hide();
                $('.noDataMessageFailed').show();
            }
        }
    });
}

function bindTransactionPaginationFailed() {
    $('.pagination-container-transaction-failed').off('click', 'a').on('click', 'a', function (e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        fetchPaginateHistoryTransactionFailed(page);
    });
}

// Initial fetch
$(document).ready(function () {
    fetchPaginateHistoryTransactionFailed();
});

function bindDetailToggleFailed() {
    const toggles = document.querySelectorAll('.button-detail-failed');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const listItem = toggle.closest('.list-item');

            // Tutup semua dropdown lain
            document.querySelectorAll('.list-item').forEach(item => {
                if (item !== listItem) {
                    item.classList.remove('show');
                }
            });

            // Toggle dropdown ini
            listItem.classList.toggle('show');
        });
    });
}
