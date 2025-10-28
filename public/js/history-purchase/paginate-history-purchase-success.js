function fetchPaginateHistoryTransactionSuccess(page = 1) {
    $.ajax({
        url: '/paginate-histori-pembelian-success',
        method: 'GET',
        data: {
            page: page
        },
        success: function (response) {
            const container = $('#grid-transaction-success-list');
            container.empty();
            $('.pagination-container-transaction-success').empty();

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

                    const createdAt = item.created_at ? `${formatDate(item.created_at)}, ${timeFormatter.format(new Date(item.created_at))}` : 'Tanggal tidak tersedia';

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
                                <span class="text-sm px-4 py-1 bg-green-200 text-green-600 font-bold rounded-xl">
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
                                <button class="button-detail-success text-[#4189FF] font-bold">Lihat Detail</button>
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
                            <form id="form-pembelian-${item.id}" action="" method="POST">
                                <input type="hidden" name="_token" value="">
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

                $('.pagination-container-transaction-success').html(response.links);
                $('.pagination-container-transaction-success').show();
                $('.noDataMessageSuccess').hide();
                bindTransactionPaginationSuccess();
                bindDetailToggleSuccess(); // agar tombol "Lihat Detail" aktif
            } else {
                $('.pagination-container-transaction-success').hide();
                $('.noDataMessageSuccess').show();
            }
        }
    });
}

function bindTransactionPaginationSuccess() {
    $('.pagination-container-transaction-success').off('click', 'a').on('click', 'a', function (e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        fetchPaginateHistoryTransactionSuccess(page);
    });
}

// Initial fetch
$(document).ready(function () {
    fetchPaginateHistoryTransactionSuccess();
});

function bindDetailToggleSuccess() {
    // Ambil semua tombol yang berfungsi untuk membuka/menutup detail (accordion)
    const toggles = document.querySelectorAll('.button-detail-success');

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