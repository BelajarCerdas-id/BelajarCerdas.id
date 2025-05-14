const getChartDataTanyaHarian = document.getElementById('myChart-days').getAttribute('data-chart-tanya-harian');
const getChartDataTanyaBulanan = document.getElementById('myChart-months').getAttribute('data-chart-tanya-bulanan');
const getChartDataTanyaTahunan = document.getElementById('myChart-years').getAttribute('data-chart-tanya-tahunan');

// SCRIPT CHART UNTUK DATA TANYA HARIAN (ADMINISTRATOR)
document.addEventListener("DOMContentLoaded", function () {
        const monthDisplay = document.getElementById("monthDisplay");
        let currentMonth = new Date().getMonth() + 1; // Pastikan bulan dalam rentang (1-12)
        let currentYear = new Date().getFullYear();
        const prevMonthBtn = document.getElementById("prevMonth");
        const nextMonthBtn = document.getElementById("nextMonth");
        let maxMonth = null; // Menyimpan bulan & tahun terakhir yang tersedia
        let minMonth = null; // Menyimpan bulan & tahun pertama yang tersedia
        let myChart = null; // Untuk menyimpan instance Chart.js

        // Ambil minMonth dan maxMonth dari API sebelum pertama kali updateChart
        fetch(getChartDataTanyaHarian)
            .then(response => response.json())
            .then(data => {
                minMonth = data.minMonth; // mengambil bulan pada tahun pertama
                maxMonth = data.maxMonth; // mengambil bulan pada tahun terakhir update
                if (minMonth && maxMonth) {
                    fetchData(currentMonth, currentYear);
                }
            })
            .catch(error => console.error("Gagal mengambil min/max bulan:", error));

        function fetchData(month, year) {
            fetch(getChartDataTanyaHarian + `?bulan=${month}&tahun=${year}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Data API:", data); // Debugging
                    updateChart(month, year, data);
                })
                .catch(error => console.error("Gagal mengambil data:", error));
        }

        function updateChart(month, year, data) {
            if (!data || !data.data || Object.keys(data.data).length === 0) {
                console.warn("Data kosong untuk bulan", month, "tahun", year);
                return;
            }

            const days = Object.keys(data.data); // Ambil tanggal-tanggal
            const counts = Object.values(data.data); // Ambil jumlah pengguna per tanggal
            const monthLabel = data.bulan; // Nama bulan dalam format teks (Januari, Februari, dll.)
            const yearLabel = data.tahun; // Tahun

            // Update tampilan bulan dan tahun
            monthDisplay.innerText = `${monthLabel} ${yearLabel}`;

            // Cek apakah minMonth dan maxMonth sudah siap sebelum melakukan perbandingan
            if (minMonth && maxMonth) {
                prevMonthBtn.style.display = (year === Number(minMonth.year) && month <= Number(minMonth
                    .month)) ? "none" : "inline-block";
                nextMonthBtn.style.display = (year === Number(maxMonth.year) && month >= Number(maxMonth
                    .month)) ? "none" : "inline-block";
            }

            if (myChart) {
                // Jika grafik sudah ada, perbarui datanya
                myChart.data.labels = days;
                myChart.data.datasets[0].data = counts;
                myChart.data.datasets[0].label = `Data Harian Pengguna TANYA - ${monthLabel} ${yearLabel}`;
                myChart.update();
            } else {
                // Jika grafik belum ada, buat grafik baru
                const ctx = document.getElementById('myChart-days').getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: days,
                        datasets: [{
                            label: `Data Harian Pengguna TANYA - ${monthLabel} ${yearLabel}`,
                            data: counts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 205, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(201, 203, 207, 0.2)'
                            ],
                            borderColor: [
                                'rgb(255, 99, 132)',
                                'rgb(255, 159, 64)',
                                'rgb(255, 205, 86)',
                                'rgb(75, 192, 192)',
                                'rgb(54, 162, 235)',
                                'rgb(153, 102, 255)',
                                'rgb(201, 203, 207)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        prevMonthBtn.addEventListener("click", function() {
            if (currentYear === Number(minMonth.year) && currentMonth <= Number(minMonth.month)) {
                return; // Tidak bisa ke bulan sebelum batas minimal
            }

            if (currentMonth === 1) {
                currentMonth = 12;
                currentYear--;
            } else {
                currentMonth--;
            }
            fetchData(currentMonth, currentYear);
        });

        nextMonthBtn.addEventListener("click", function() {
            if (currentYear === Number(maxMonth.year) && currentMonth >= Number(maxMonth.month)) {
                return; // Tidak bisa ke bulan setelah batas maksimal
            }

            if (currentMonth === 12) {
                currentMonth = 1;
                currentYear++;
            } else {
                currentMonth++;
            }
            fetchData(currentMonth, currentYear);
        });
    });


// SCRIPT CHART UNTUK DATA TANYA BULANAN (ADMINISTRATOR)
    document.addEventListener("DOMContentLoaded", function() {
        const yearDisplay = document.getElementById("yearDisplay");
        const prevYearBtn = document.getElementById("prevYear");
        const nextYearBtn = document.getElementById("nextYear");
        let currentYear = new Date().getFullYear();
        let maxYear = currentYear;
        let minYear = 2024;
        let myChart = null;

        // Ambil maxYear dan minYear dari API sebelum updateChart pertama kali
        fetch(getChartDataTanyaBulanan + `?tahun=${currentYear}`)
            .then(response => response.json())
            .then(data => {
                maxYear = data.maxYear;
                minYear = data.minYear;
                updateChart(currentYear);
            });

        function updateChart(year) {
            fetch(getChartDataTanyaBulanan + `?tahun=${year}`)
                .then(response => response.json())
                .then(data => {
                    const months = Object.keys(data.data);
                    const counts = Object.values(data.data);
                    const yearLabel = data.tahun;

                    // Update tampilan tahun
                    yearDisplay.innerText = yearLabel;

                    // Atur visibilitas tombol berdasarkan batas tahun
                    prevYearBtn.style.display = (year <= minYear) ? "none" : "inline-block";
                    nextYearBtn.style.display = (year >= maxYear) ? "none" : "inline-block";

                    if (myChart) {
                        myChart.data.labels = months;
                        myChart.data.datasets[0].data = counts;
                        myChart.data.datasets[0].label = `Data Bulanan Pengguna TANYA Tahun ${yearLabel}`;
                        myChart.update();
                    } else {
                        const ctx = document.getElementById('myChart-months').getContext('2d');
                        myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: months,
                                datasets: [{
                                    label: `Data Bulanan Pengguna TANYA Tahun ${yearLabel}`,
                                    data: counts,
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(255, 159, 64, 0.2)',
                                        'rgba(255, 205, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(153, 102, 255, 0.2)',
                                        'rgba(201, 203, 207, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgb(255, 99, 132)',
                                        'rgb(255, 159, 64)',
                                        'rgb(255, 205, 86)',
                                        'rgb(75, 192, 192)',
                                        'rgb(54, 162, 235)',
                                        'rgb(153, 102, 255)',
                                        'rgb(201, 203, 207)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }
                });
        }

        // Event tombol Next
        nextYearBtn.addEventListener("click", function() {
            if (currentYear < maxYear) {
                currentYear++;
                updateChart(currentYear);
            }
        });

        // Event tombol Prev
        prevYearBtn.addEventListener("click", function() {
            if (currentYear > minYear) {
                currentYear--;
                updateChart(currentYear);
            }
        });
    });


// SCRIPT CHART UNTUK DATA TANYA TAHUNAN (ADMINISTRATOR)
    document.addEventListener("DOMContentLoaded", function() {
        fetch(getChartDataTanyaTahunan) // Ambil data dari route
            .then(response => response.json())
            .then(data => {
                const years = Object.keys(data); // Ambil tahun
                const counts = Object.values(data); // Ambil jumlah data per tahun

                // Buat Chart.js
                const ctx = document.getElementById('myChart-years').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: years,
                        datasets: [{
                            label: 'Data Tahunan Pengguna TANYA',
                            data: counts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 205, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(201, 203, 207, 0.2)'
                            ],
                            borderColor: [
                                'rgb(255, 99, 132)',
                                'rgb(255, 159, 64)',
                                'rgb(255, 205, 86)',
                                'rgb(75, 192, 192)',
                                'rgb(54, 162, 235)',
                                'rgb(153, 102, 255)',
                                'rgb(201, 203, 207)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    });
