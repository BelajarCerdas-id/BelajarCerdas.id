function fetchPaginateHistoryCoinIn(page = 1) {
    $.ajax({
        url: '/paginate-histori-koin-masuk',
        method: 'GET',
        data: {
            page: page
        },
        success: function (response) {
            const container = $('#grid-history-coin-in-list');
            container.empty();
            $('.pagination-container-coin-in').empty();

            if (response.data.length > 0) {
                response.data.forEach((item) => {

                    const formatDate = (dateString) => {
                        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                        const date = new Date(dateString);
                        const dayName = days[date.getDay()];
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

                    const createdAt = item.created_at ? `${formatDate(item.created_at)}` : 'Tanggal tidak tersedia';
                    const card = `
                    <div class="list-item">
                        <div class="bg-white shadow-lg rounded-md p-4 flex justify-between items-center">
                            <div class="flex flex-col gap-4">
                                <span class="text-sm px-4 py-1 bg-green-200 text-green-600 font-bold rounded-xl w-max h-7 opacity-70">
                                    ${item.tipe_koin}
                                </span>
                                <span class="text-md font-bold opacity-60">${item.sumber_koin}</span>
                                <span class="text-sm font-bold opacity-60">
                                    tanggal : ${createdAt}
                                </span>
                            </div>
                            <div class="bg-green-200 text-green-600 rounded-full flex items-center justify-center py-1 px-4">
                                <span class="text-md lg:text-sm font-bold opacity-70 block">
                                    ${'+' + item.jumlah_koin + ' Koin' }
                                </span>
                            </div>
                        </div>
                    </div>
                    `;

                    container.append(card);
                });

                $('.pagination-container-coin-in').html(response.links);
                $('.pagination-container-coin-in').show();
                $('.noDataMessageHistoryCoinIn').hide();
                bindHistoryPaginationCoinIn();
            } else {
                $('.pagination-container-coin-in').hide();
                $('.noDataMessageHistoryCoinIn').show();
            }
        }
    });
}

function bindHistoryPaginationCoinIn() {
    $('.pagination-container-coin-in').off('click', 'a').on('click', 'a', function (e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        fetchPaginateHistoryCoinIn(page);
    });
}

// Initial fetch
$(document).ready(function () {
    fetchPaginateHistoryCoinIn();
});

