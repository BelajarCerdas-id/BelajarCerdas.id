function fetchPaginateHistoryCoinOut(page = 1) {
    $.ajax({
        url: '/paginate-histori-koin-keluar',
        method: 'GET',
        data: {
            page: page
        },
        success: function (response) {
            const container = $('#grid-history-coin-out-list');
            container.empty();
            $('.pagination-container-coin-out').empty();

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
                        <div class="bg-white shadow-lg rounded-md p-4 border">
                            <div class="flex justify-between items-center mb-4">
                                <span class="bg-red-200 text-red-600 rounded-full flex items-center justify-center py-1 px-4 text-sm font-bold opacity-70">
                                    ${item.tipe_koin}
                                </span>
                                <span class="bg-red-200 text-red-600 rounded-full flex items-center justify-center py-1 px-4 text-sm font-bold opacity-70">
                                    ${'-' + item.jumlah_koin + ' Koin' }
                                </span>
                            </div>
                                <span class="text-sm font-bold opacity-60">${item.sumber_koin}</span>
                            <div class="flex justify-between w-full mt-4">
                                <span class="text-sm font-bold opacity-60">
                                    tanggal : ${createdAt}
                                </span>
                                    <button class="button-detail-history-coin-out text-[#4189FF] font-bold text-sm">Lihat Detail</button>
                            </div>

                            <div class="content-dropdown-history-coin-out">
                                <div class="flex flex-col gap-2 mt-10">
                                    <span class="font-bold opacity-60">Detail berTANYA :</span>
                                    <div class="bg-blue-100 flex flex-col gap-2 rounded-md p-2">
                                        <span class="font-bold opacity-70">
                                            Fase : ${item.tanya?.fase?.nama_fase}
                                        </span>

                                        <span class="font-bold opacity-70">
                                            Kelas : ${item.tanya?.kelas?.kelas}
                                        </span>

                                        <span class="font-bold opacity-70">
                                            Mata Pelajaran : ${item.tanya?.mapel?.mata_pelajaran}
                                        </span>

                                        <span class="font-bold opacity-70">
                                            Bab : ${item.tanya?.bab?.nama_bab}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;

                    container.append(card);
                });

                $('.pagination-container-coin-out').html(response.links);
                $('.pagination-container-coin-out').show();
                $('.noDataMessageHistoryCoinOut').hide();
                bindHistoryPaginationCoinOut();
                bindDetailToggleHistoryCoinOut(); // agar tombol "Lihat Detail" aktif
            } else {
                $('.pagination-container-coin-out').hide();
                $('.noDataMessageHistoryCoinOut').show();
            }
        }
    });
}

function bindHistoryPaginationCoinOut() {
    $('.pagination-container-coin-out').off('click', 'a').on('click', 'a', function (e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        fetchPaginateHistoryCoinOut(page);
    });
}

// Initial fetch
$(document).ready(function () {
    fetchPaginateHistoryCoinOut();
});


function bindDetailToggleHistoryCoinOut() {
    const toggles = document.querySelectorAll('.button-detail-history-coin-out');

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

