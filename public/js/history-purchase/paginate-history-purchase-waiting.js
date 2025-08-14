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
                        'Rp. ' + new Intl.NumberFormat('id-ID').format(number);

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

                    const expiredTime = item.expiredTime;
                    const getPacketSoalPembahasanActive = item.getPacketSoalPembahasanActive;

                    let payButton = '';

                    if (expiredTime) {
                        payButton = `<button type="button" class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm my-4"
                                        onclick="alertExpiredCheckout()">Beli Sekarang</button>
                                    `;
                    } else if (getPacketSoalPembahasanActive) {
                        payButton = `<button type="button" class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm my-4"
                                        onclick="alertPacketAlreadyExist()">Beli Sekarang</button>
                                    `;
                    } else {
                        payButton = `
                                <form id="form-pembelian-${item.id}" action="${renewCheckout}" method="POST">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="button"
                                        class="btn-beli-waiting bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm my-4"
                                        data-id="${item.id}" data-expired="${expiredTime}">
                                        Beli Sekarang
                                    </button>
                                </form>
                        `;
                    }

                    const card = `
                    <div class="list-item">
                        <div class="bg-white shadow-lg rounded-md p-4 border">
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
                                    ${payButton}
                            </div>
                        </div>
                    </div>
                    `;

                    container.append(card);
                });

                $('.pagination-container-transaction-waiting').html(response.links);
                $('.pagination-container-transaction-waiting').show();
                $('.noDataMessageWaiting').hide();
                $('#attention').show();
                bindTransactionPaginationWaiting();
                bindDetailToggleWaiting(); // agar tombol "Lihat Detail" aktif
                binFetchingCheckout();
            } else {
                $('.pagination-container-transaction-waiting').hide();
                $('.noDataMessageWaiting').show();
                $('#attention').hide();
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

function bindExpireCheckout() {
    // Kirim request AJAX POST ke server untuk expire transaksi yang sudah kadaluarsa
    return $.ajax({
        url: '/expire-checkout-transaction',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // token keamanan
        }
    }).then(function(response) {
        // Setelah request expire selesai,
        // kita panggil fungsi refresh UI tapi tidak menunggu hasilnya
        return new Promise((resolve) => {
            fetchPaginateHistoryTransactionWaiting(); // panggil AJAX untuk refresh data dan UI
            resolve(); // langsung resolve Promise supaya then berikutnya bisa dijalankan
        });
    });
}

// function untuk menampilkan alert ketika user checkout tapi sudah kadaluarsa
function alertExpiredCheckout() {
    // Panggil fungsi expire dan refresh UI
    bindExpireCheckout().then(() => {
        // Setelah expire + refresh UI dipanggil, tampilkan popup alert ke user
        swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Maaf, riwayat pembelian ini telah kadaluarsa. Silahkan lakukan pembelian lain.",
        });
    });
}

function alertPacketAlreadyExist() {
    bindExpireCheckout().then(() => {
        swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Maaf, kamu tidak bisa membeli paket ini, karena kamu masih memiliki paket yang aktif pada fitur ini.",
        });
    });
}


function alertPaymentSuccess() {
    $('#alert-payment-success').html(
        `
            <div class=" w-full flex justify-center">
                <div class="fixed z-[9999]">
                    <div id="alertSuccess"
                        class="relative top-[-45px] opacity-100 scale-90 bg-green-200 w-max p-3 flex items-center space-x-2 rounded-lg shadow-lg transition-all duration-300 ease-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current text-green-600" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-green-600 text-sm">Pembayaran berhasil dilakukan.</span>
                            <i class="fas fa-times cursor-pointer text-green-600" id="btnClose"></i>
                    </div>
                </div>
            </div>
        `
    );

    setTimeout(function() {
        document.getElementById('alertSuccess').remove();
    }, 3000);

    document.getElementById('btnClose').addEventListener('click', function () {
        document.getElementById('alertSuccess').remove();
    });
}

function binFetchingCheckout() {
    let isProcessing = false;

    function checkTransactionAndUpdateUI(id, btn) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/check-transaction-status/${id}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alertPaymentSuccess();
                fetchPaginateHistoryTransactionWaiting();
                updateJumlahKoinStudent();
            }
        })
        .catch(err => console.error(err))
        .finally(() => {
            isProcessing = false;
            btn.prop('disabled', false);
        });
    }

    document.querySelectorAll('.btn-beli-waiting').forEach(button => {
        button.addEventListener('click', function () {
            if (isProcessing) return; // Cegah klik ganda

            const expiredTime = this.dataset.expired === 'true';
            if (expiredTime) {
                alertExpiredCheckout();
                return;
            }

            isProcessing = true;
            const id = this.dataset.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const btn = $(this);

            btn.prop('disabled', true);

            fetch(`/renew-checkout/${id}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
            })
            .then(async res => {
                const data = await res.json();

                if (!res.ok) {
                    if (data.status === 'expired') {
                        alertExpiredCheckout(); // popup kadaluarsa
                        return;
                    } else {
                        alert(data.error || "Terjadi kesalahan.");
                        return;
                    }
                }

                return data;
            })
            .then(data => {
                if (!data || !data.snap_token) return;

                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        alertPaymentSuccess();
                        fetchPaginateHistoryTransactionWaiting();
                        updateJumlahKoinStudent();
                        isProcessing = false;
                        btn.prop('disabled', false);
                    },
                    onPending: function(result) {
                        checkTransactionAndUpdateUI(id, btn);
                    },
                    onError: function(result) {
                        isProcessing = false;
                        btn.prop('disabled', false);
                        fetchPaginateHistoryTransactionWaiting();
                        updateJumlahKoinStudent();
                    },
                    onClose: function() {
                        checkTransactionAndUpdateUI(id, btn);
                    }
                });
            })
            .catch(error => {
                alert(error.message || "Terjadi kesalahan.");
                console.error(error);
                isProcessing = false;
                btn.prop('disabled', false);
            });
        });
    });
}


