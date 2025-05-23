function fetchPaginateHistoryTransactionWaiting(page = 1) {
    $.ajax({
        url: '/paginate-histori-pembelian-waiting',
        method: 'GET',
        data: {
            page: page
        },
        success: function (response) {
            const container = $('#grid-transaction-waiting-list');
            container.empty();
            $('.pagination-container-transaction-waiting').empty();

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

                    const renewCheckout = item.renewCheckout;
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    const createdAt = item.created_at ? `${formatDate(item.created_at)}, ${timeFormatter.format(new Date(item.created_at))}` : 'Tanggal tidak tersedia';

                    const card = `
                    <div class="list-item">
                        <div class="bg-white shadow-lg rounded-md p-4">
                            <div class="flex justify-between">
                                <span class="text-md font-bold opacity-60">${item.features.nama_fitur}</span>
                                <span class="text-sm px-4 py-1 bg-[#f9d3ba] text-[#f77a2c] font-bold rounded-xl">
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
                                <button class="button-detail-waiting text-[#4189FF] font-bold">Lihat Detail</button>
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
                                <form id="form-pembelian-${item.id}" action="${renewCheckout}" method="POST">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <button type="button"
                                        class="btn-beli-waiting bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm my-4"
                                        data-id="${item.id}">
                                        Beli Sekarang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    `;

                    container.append(card);
                });

                $('.pagination-container-transaction-waiting').html(response.links);
                $('.pagination-container-transaction-waiting').show();
                $('.noDataMessageWaiting').hide();
                bindTransactionPaginationWaiting();
                bindDetailToggleWaiting(); // agar tombol "Lihat Detail" aktif
                binFetchingCheckout();
            } else {
                $('.pagination-container-transaction-waiting').hide();
                $('.noDataMessageWaiting').show();
            }
        }
    });
}

function bindTransactionPaginationWaiting() {
    $('.pagination-container-transaction-waiting').off('click', 'a').on('click', 'a', function (e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        fetchPaginateHistoryTransactionWaiting(page);
    });
}

// Initial fetch
$(document).ready(function () {
    fetchPaginateHistoryTransactionWaiting();
});

function bindDetailToggleWaiting() {
    const toggles = document.querySelectorAll('.button-detail-waiting');

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

function binFetchingCheckout() {
    document.querySelectorAll('.btn-beli-waiting').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/checkout-pending/${id}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                // console.log(result);
                                location.reload();
                            },
                            onPending: function(result) {
                                // console.log(result);
                            },
                            onError: function(result) {
                                // console.log(result);
                            },
                        });
                    } else {
                        alert("Gagal mendapatkan snap token.");
                        console.error(data);
                    }
                })
                .catch(error => {
                    alert("Terjadi kesalahan.");
                    console.error(error);
                });
        });
    });
    }
