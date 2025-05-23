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
            const noDataHarianMessage = document.getElementById("noDataTanyaHarianMessage");

            // Selalu update tombol navigasi & tampilan bulan/tahun
            updateButtonVisibility(month, year);
            const monthLabel = data.bulan || new Date(year, month - 1).toLocaleString('default', { month: 'long' });
            const yearLabel = data.tahun || year;
            monthDisplay.innerText = `${monthLabel} ${yearLabel}`;

            const days = data.data ? Object.keys(data.data) : [];
            const counts = data.data ? Object.values(data.data) : [];

            const isDataKosong = !data || !data.data || days.length === 0;

            if (isDataKosong) {
                // Tampilkan pesan tidak ada data
                noDataHarianMessage.classList.remove("hidden");
            } else {
                noDataHarianMessage.classList.add("hidden");
            }

            const chartLabel = `Data Harian Pengguna TANYA - ${monthLabel} ${yearLabel}`;

            if (myChart) {
                myChart.data.labels = days;
                myChart.data.datasets[0].data = counts;
                myChart.data.datasets[0].label = chartLabel;
                myChart.update();
            } else {
                const ctx = document.getElementById('myChart-days').getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: days,
                        datasets: [{
                            label: chartLabel,
                            data: counts,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgb(54, 162, 235)',
                            borderWidth: 1,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: 10
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                enabled: days.length > 0 // Tooltip hanya aktif jika ada data
                            }
                        }
                    }
                });
            }

            // Pastikan fungsi tombol ini didefinisikan di luar updateChart jika belum
            function updateButtonVisibility(month, year) {
                if (minMonth && maxMonth) {
                    prevMonthBtn.style.display = (year === Number(minMonth.year) && month <= Number(minMonth.month)) ? "none" : "inline-block";
                    nextMonthBtn.style.display = (year === Number(maxMonth.year) && month >= Number(maxMonth.month)) ? "none" : "inline-block";
                }
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
document.addEventListener("DOMContentLoaded", function () {
    const yearDisplay = document.getElementById("yearDisplay");
    const prevYearBtn = document.getElementById("prevYear");
    const nextYearBtn = document.getElementById("nextYear");
    const noDataBulananMessage = document.getElementById("noDataTanyaBulananMessage");

    let currentYear = new Date().getFullYear();
    let maxYear = currentYear;
    let minYear = 2025;
    let myChart = null;

    // Ambil minYear dan maxYear sekali di awal
    fetch(getChartDataTanyaBulanan + `?tahun=${currentYear}`)
        .then(response => response.json())
        .then(data => {
            maxYear = data.maxYear;
            minYear = data.minYear;

            if (currentYear < minYear || currentYear > maxYear) {
                currentYear = maxYear;
            }

            updateChart(currentYear);
        });

    function updateChart(year) {
        fetch(getChartDataTanyaBulanan + `?tahun=${year}`)
            .then(response => response.json())
            .then(data => {
                const yearLabel = data.tahun;
                const monthData = data.data || {};
                const months = Object.keys(monthData);
                const counts = Object.values(monthData);

                yearDisplay.innerText = yearLabel;

                // Atur visibilitas tombol berdasarkan batas tahun
                prevYearBtn.style.display = (year > minYear) ? "inline-block" : "none";
                nextYearBtn.style.display = (year < maxYear) ? "inline-block" : "none";

                if (months.length === 0) {
                    // Tidak ada data bulan
                    noDataBulananMessage.classList.remove("hidden");

                    if (myChart) {
                        myChart.destroy();
                        myChart = null;
                    }

                    const ctx = document.getElementById('myChart-months').getContext('2d');
                    myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [yearLabel],
                            datasets: [{
                                label: `Tidak ada data TANYA untuk tahun ${yearLabel}`,
                                data: [0],
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

                } else {
                    noDataBulananMessage.classList.add("hidden");

                    if (myChart) {
                        myChart.destroy();
                    }

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

    // Navigasi tahun sebelumnya
    prevYearBtn.addEventListener("click", function () {
        if (currentYear > minYear) {
            currentYear--;
            updateChart(currentYear);
        }
    });

    // Navigasi tahun berikutnya
    nextYearBtn.addEventListener("click", function () {
        if (currentYear < maxYear) {
            currentYear++;
            updateChart(currentYear);
        }
    });
});



// SCRIPT CHART UNTUK DATA TANYA TAHUNAN (ADMINISTRATOR)
document.addEventListener("DOMContentLoaded", function () {
    const yearDisplay = document.getElementById("displayYear");
    const prevYearBtn = document.getElementById("prevYearButton");
    const nextYearBtn = document.getElementById("nextYearButton");
    const noDataMessage = document.getElementById("noDataTanyaTahunanMessage");
    const chartCanvas = document.getElementById('myChart-years');

    let currentYear = new Date().getFullYear();
    let allData = {};
    let myChart = null;
    let allYears = [];

    // Ambil URL dari atribut data
    const getChartDataTanyaTahunan = chartCanvas.dataset.chartTanyaTahunan;

    // Ambil data tahunan sekali
    fetch(getChartDataTanyaTahunan)
        .then(response => response.json())
        .then(data => {
            allData = data;

            const dataYears = Object.keys(data).map(Number);
            if (dataYears.length === 0) {
                noDataMessage.classList.remove("hidden");
                prevYearBtn.style.display = "none";
                nextYearBtn.style.display = "none";
                return;
            }

            const minYear = Math.min(...dataYears);
            const maxYear = Math.max(...dataYears);

            allYears = [];
            for (let y = minYear; y <= maxYear; y++) {
                allYears.push(y);
            }

            // Jika tahun sekarang tidak ada dalam rentang, pakai tahun max
            if (!allYears.includes(currentYear)) {
                currentYear = maxYear;
            }

            updateChart(currentYear);
        });

    function updateChart(year) {
        const data = allData[year];
        yearDisplay.innerText = year;

        const ctx = chartCanvas.getContext('2d');
        if (myChart) {
            myChart.destroy();
        }

        const hasData = typeof data !== "undefined";

        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [`${year}`],
                datasets: [{
                    label: `Data Pengguna TANYA Tahun ${year}`,
                    data: [hasData ? data : 0],
                    backgroundColor: hasData ? 'rgba(75, 192, 192, 0.2)' : 'rgba(200, 200, 200, 0.2)',
                    borderColor: hasData ? 'rgb(75, 192, 192)' : 'rgb(200, 200, 200)',
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

        noDataMessage.classList.toggle("hidden", hasData);
        updateButtonVisibility(year);
    }

    function updateButtonVisibility(year) {
        const yearIndex = allYears.indexOf(year);
        prevYearBtn.style.display = (yearIndex > 0) ? "inline-block" : "none";
        nextYearBtn.style.display = (yearIndex < allYears.length - 1) ? "inline-block" : "none";
    }

    prevYearBtn.addEventListener("click", function () {
        const yearIndex = allYears.indexOf(currentYear);
        if (yearIndex > 0) {
            currentYear = allYears[yearIndex - 1];
            updateChart(currentYear);
        }
    });

    nextYearBtn.addEventListener("click", function () {
        const yearIndex = allYears.indexOf(currentYear);
        if (yearIndex < allYears.length - 1) {
            currentYear = allYears[yearIndex + 1];
            updateChart(currentYear);
        }
    });
});



// document.addEventListener("DOMContentLoaded", function () {
//         myChart = null;
//         fetch(getChartDataTanyaTahunan) // Ambil data dari route
//             .then(response => response.json())
//             .then(data => {
//                 const noDataTahunanMessage = document.getElementById("noDataTanyaTahunanMessage");

//                 if (!data || Object.keys(data).length === 0) {
//                     // Jika data kosong
//                 if (myChart) {
//                     myChart.destroy(); // Hapus chart lama
//                     myChart = null;
//                 }

//                 noDataTahunanMessage.classList.remove("hidden"); // Tampilkan pesan

//                 console.warn("Data kosong untuk bulan", month, "tahun", year);
//                 return;
//             }

//             // Jika data ada
//             noDataTahunanMessage.classList.add("hidden"); // Sembunyikan pesan jika ada data

//             const years = Object.keys(data); // Ambil tahun
//             const counts = Object.values(data); // Ambil jumlah data per tahun

//             // Buat Chart.js
//                 const ctx = document.getElementById('myChart-years').getContext('2d');
//                 new Chart(ctx, {
//                     type: 'bar',
//                     data: {
//                         labels: years,
//                         datasets: [{
//                             label: 'Data Tahunan Pengguna TANYA',
//                             data: counts,
//                             backgroundColor: [
//                                 'rgba(255, 99, 132, 0.2)',
//                                 'rgba(255, 159, 64, 0.2)',
//                                 'rgba(255, 205, 86, 0.2)',
//                                 'rgba(75, 192, 192, 0.2)',
//                                 'rgba(54, 162, 235, 0.2)',
//                                 'rgba(153, 102, 255, 0.2)',
//                                 'rgba(201, 203, 207, 0.2)'
//                             ],
//                             borderColor: [
//                                 'rgb(255, 99, 132)',
//                                 'rgb(255, 159, 64)',
//                                 'rgb(255, 205, 86)',
//                                 'rgb(75, 192, 192)',
//                                 'rgb(54, 162, 235)',
//                                 'rgb(153, 102, 255)',
//                                 'rgb(201, 203, 207)'
//                             ],
//                             borderWidth: 1
//                         }]
//                     },
//                     options: {
//                             // responsive: true,
//                             // maintainAspectRatio: false,
//                         scales: {
//                             y: {
//                                 beginAtZero: true
//                             }
//                         }
//                     }
//                 });
//             });
//     });
