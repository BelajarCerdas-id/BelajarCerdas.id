    // Script untuk mendengarkan event broadcast pada saat mentor menjawab pertanyaan student (status_soal diterima)
    let currentStatusAnswered = 'semua';
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.channel('tanya')
            .listen('.question.answered', (e) => {
                // Saat ada data baru, ambil ulang semua data dengan AJAX
                // mendengarkan event broadcast untuk menerima riwayat soal (diterima & ditolak)
                fetchFilteredDataRiwayatStudent(currentStatusAnswered);

                let badge = document.getElementById("notifBadgeAnswered");

                if (badge) {
                    let currentCount = parseInt(badge.textContent || '0');
                    badge.textContent = currentCount + 1;
                    badge.classList.remove("hidden");
                } else {
                    const span = document.createElement("span");
                    span.id = "notifBadgeAnswered";
                    span.classList.add("absolute", "top-0", "right-0", "bg-red-500", "text-white", "w-4",
                        "h-4", "rounded-full", "flex", "items-center", "justify-center");
                    span.textContent = "1";

                    const target = document.querySelector(".historyTanya .answeredText");
                    if (target) {
                        target.appendChild(span);
                    }
                }
                fetchDataTanyaUnAnswered();
                fetchDataTanyaAnswered();
            });
    });

    // Script untuk mendengarkan event broadcast pada saat mentor menjawab pertanyaan student (status_soal ditolak)
    let currentStatusRejected = 'semua';
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.channel('tanya')
            .listen('.question.rejected', (e) => {
                // Saat ada data baru, ambil ulang semua data dengan AJAX
                // mendengarkan event broadcast untuk menerima riwayat soal (diterima & ditolak)
                fetchFilteredDataRiwayatStudent(currentStatusRejected);

                let badge = document.getElementById("notifBadgeRejected");

                if (badge) {
                    let currentCount = parseInt(badge.textContent || '0');
                    badge.textContent = currentCount + 1;
                    badge.classList.remove("hidden");
                } else {
                    const span = document.createElement("span");
                    span.id = "notifBadgeRejected";
                    span.classList.add("absolute", "top-0", "right-0", "bg-red-500", "text-white", "w-4",
                        "h-4", "rounded-full", "flex", "items-center", "justify-center");
                    span.textContent = "1";

                    const target = document.querySelector(".historyTanya .rejectedText");
                    if (target) {
                        target.appendChild(span);
                    }
                }
                fetchDataTanyaUnAnswered();
                fetchDataTanyaRejected();
            });
    })
