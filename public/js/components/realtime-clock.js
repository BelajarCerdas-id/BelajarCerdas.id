document.addEventListener('DOMContentLoaded', function() {
    function formatNumber(num) {
        return num < 10 ? '0' + num : num; // Menambahkan 0 di depan jika kurang dari 10
    }

    function updateTimestamp() {
        const date = new Date();

        const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
        const day = days[date.getDay()]; // Mendapatkan hari dalam minggu

        const dayNumber = formatNumber(date.getDate()); // Tanggal

        const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
            "Oktober", "November", "Desember",
        ];
        const month = months[date.getMonth()]; // Bulan (Januari - Desember)

        const year = date.getFullYear(); // Tahun

        const hours = formatNumber(date.getHours()); // Jam
        const minutes = formatNumber(date.getMinutes()); // Minute
        const seconds = formatNumber(date.getSeconds()); // Detik

        const formattedTimestamp =
            `${day}, ${dayNumber} ${month} ${year}
                                            ${hours}:${minutes}:${seconds}`; // Format: Day, DD/MM/YYYY HH:MM:SS
        document.getElementById('timestamp').innerText = formattedTimestamp;
    }

    // Update timestamp setiap detik
    setInterval(updateTimestamp, 1000);

    // Panggil fungsi sekali untuk menampilkan timestamp saat pertama kali
    updateTimestamp();

});
