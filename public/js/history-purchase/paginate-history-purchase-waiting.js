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

                    const renewCheckout = response.renewCheckout.replace(':id', item.id);
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    const createdAt = item.created_at ? `${formatDate(item.created_at)}, ${timeFormatter.format(new Date(item.created_at))}` : 'Tanggal tidak tersedia';

                    let payButton = '';

                    payButton = `
                            <form id="form-pembelian-${item.id}" action="${renewCheckout}" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                    <button type="button"
                                    class="btn-beli-waiting bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm my-4"
                                    data-id="${item.id}">
                                    Beli Sekarang
                                </button>
                            </form>
                    `;

                    let englishZoneDetail = '';

                    if (item.feature_id == 3 || item.features?.nama_fitur == 'English Zone') {
                        
                        englishZoneDetail += `
                            <span class="font-bold opacity-70">
                                Level : ${item.levels.join(', ')}
                            </span>

                            <span class="font-bold opacity-70">
                                Batch : ${item.batchSchedules[0].batch}
                            </span>

                            <span class="font-bold opacity-70">
                                Hari : ${item.batchSchedules.map(s => s.day).join(' & ')}
                            </span>

                            <span class="font-bold opacity-70">
                                Jam : ${item.batchSchedules[0].startTime} - ${item.batchSchedules[0].endTime}
                            </span>
                        `;
                    }

                    const card = `
                    <div class="list-item-history-purchase">
                        <div class="bg-white shadow-lg rounded-md p-4 border">
                            <div class="flex justify-between">
                                <span class="text-md font-bold opacity-60">${item.features.nama_fitur}</span>
                                <span class="text-sm px-4 py-1 bg-[#f9d3ba] text-[#f77a2c] font-bold rounded-xl">
                                    ${item.transaction_status}
                                </span>
                            </div>
                            <span class="text-md font-bold opacity-70 block mt-1">
                                ${item.features.nama_fitur === 'TANYA' ? item.transaction_callback['jumlah_koin'] + ' Koin' : item.feature_prices.variant_name}
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
                                        ${englishZoneDetail}
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
    // Ambil semua tombol yang berfungsi untuk membuka/menutup detail (accordion)
    const toggles = document.querySelectorAll('.button-detail-waiting');

    // Flag untuk mencegah klik berulang cepat saat animasi masih jalan
    let isTransitioning = false;

    // Fungsi untuk menutup semua dropdown utama, kecuali yang sedang dibuka (if except diberikan)
    function closeAllMainDropdowns(except = null) {
        document.querySelectorAll(".list-item-history-purchase").forEach(item => {
            const dropdown = item.querySelector('.content-dropdown-histori-pembelian');

            // Kalau elemen ini bukan yang sedang dikecualikan, tutup dia
            if (item !== except) {
                item.classList.remove("show");

                if (dropdown) {
                    // Buat animasi penutupan halus:
                    // Set maxHeight ke tinggi saat ini
                    dropdown.style.maxHeight = dropdown.scrollHeight + "px";
                    // Trigger reflow supaya browser mendeteksi perubahan
                    dropdown.offsetHeight;
                    // Baru set maxHeight ke 0 agar animasi tutup berjalan
                    dropdown.style.maxHeight = "0";
                    dropdown.style.overflow = "hidden";
                    dropdown.style.opacity = "0";
                }
            }
        });
    }

    // Pasang event click ke setiap tombol accordion
    toggles.forEach(toggle => {
        toggle.addEventListener("click", (e) => {
            e.preventDefault(); // Cegah perilaku default tombol/link

            // Kalau sedang ada animasi jalan, abaikan klik
            if (isTransitioning) return;
            isTransitioning = true; // Tandai bahwa animasi sedang berjalan

            // Ambil elemen parent utama (list-item-history-purchase)
            const parent = toggle.closest('.list-item-history-purchase');
            // Ambil kontainer dropdown di dalam parent itu
            const dropdown = parent.querySelector('.content-dropdown-histori-pembelian');
            // Cek apakah dropdown ini sedang terbuka
            const isOpen = parent.classList.contains("show");

            // Tutup semua dropdown lain kecuali yang diklik
            closeAllMainDropdowns(parent);

            // Jika dropdown sedang tertutup → buka
            if (!isOpen) {
                parent.classList.add("show"); // Tambahkan class penanda terbuka

                if (dropdown) {
                    // Siapkan animasi buka
                    dropdown.style.transition = "max-height 0.4s ease, opacity 0.4s ease";
                    dropdown.style.opacity = "1"; // Tampilkan isinya
                    dropdown.style.overflow = "hidden"; // Sembunyikan overflow saat transisi
                    // Atur tinggi sementara berdasarkan konten
                    dropdown.style.maxHeight = dropdown.scrollHeight + "px";

                    // Setelah animasi selesai
                    dropdown.addEventListener('transitionend', function onEnd(e) {
                        // Pastikan hanya menangkap event untuk properti max-height
                        if (e.propertyName === 'max-height') {
                            // Biarkan tinggi otomatis supaya konten panjang bisa ikut
                            dropdown.style.maxHeight = "none";
                            dropdown.style.overflow = "visible"; // Izinkan konten penuh terlihat
                            dropdown.removeEventListener('transitionend', onEnd); // Hapus listener agar tidak dobel
                            isTransitioning = false; // Selesai animasi → bisa klik lagi
                        }
                    });
                } else {
                    // Kalau dropdown tidak ada, langsung izinkan klik lagi
                    isTransitioning = false;
                }

                // Jika dropdown sedang terbuka → tutup
            } else {
                parent.classList.remove("show"); // Hapus class show

                if (dropdown) {
                    // Siapkan animasi tutup
                    dropdown.style.transition = "max-height 0.4s ease, opacity 0.4s ease";
                    // Set tinggi awal agar animasi dari tinggi penuh ke 0 berjalan halus
                    dropdown.style.maxHeight = dropdown.scrollHeight + "px";
                    dropdown.offsetHeight; // reflow agar perubahan terbaca
                    dropdown.style.maxHeight = "0"; // animasi tutup dimulai
                    dropdown.style.opacity = "0";
                    dropdown.style.overflow = "hidden";

                    // Setelah animasi tutup selesai
                    dropdown.addEventListener('transitionend', function onEnd(e) {
                        if (e.propertyName === 'max-height') {
                            dropdown.removeEventListener('transitionend', onEnd);
                            isTransitioning = false; // izinkan klik lagi
                        }
                    });
                } else {
                    isTransitioning = false;
                }
            }
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

    setTimeout(function () {
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
                        throw {
                            status: res.status,
                            message: data.message
                        };
                    }

                    return data;
                })
                .then(data => {
                    if (!data || !data.snap_token) return;

                    window.snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            alertPaymentSuccess();
                            fetchPaginateHistoryTransactionWaiting();
                            fetchPaginateHistoryTransactionSuccess();
                            updateJumlahKoinStudent();
                            isProcessing = false;
                            btn.prop('disabled', false);
                        },
                        onPending: function (result) {
                            checkTransactionAndUpdateUI(id, btn);
                        },
                        onError: function (result) {
                            isProcessing = false;
                            btn.prop('disabled', false);
                            fetchPaginateHistoryTransactionWaiting();
                            updateJumlahKoinStudent();
                        },
                        onClose: function () {
                            checkTransactionAndUpdateUI(id, btn);
                        }
                    });
                })
                .catch(error => {
                    if (error.status === 422) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message,
                        });
                        fetchPaginateHistoryTransactionWaiting();
                        fetchPaginateHistoryTransactionFailed();
                    } else {
                        alert("Terjadi kesalahan.");
                    }
                    isProcessing = false;
                    btn.prop('disabled', false);
                });
            });
    });
}


