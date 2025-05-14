const getUrl = document.getElementById('updateStatusSoalAll').getAttribute('data-url-id');

// UPDATE STATUS_SOAL_STUDENT (DITERIMA)

// UPDATE SATUAN STATUS_SOAL_STUDENT (TELAH DIBACA)
document.addEventListener("DOMContentLoaded", function () {
    let updateStatusButtons = document.querySelectorAll(".updateStatusSoal");
    let updateStatusSoalAll = document.getElementById("updateStatusSoalAll");

    function checkAnsweredQuestions() {
        let unanswered = document.querySelectorAll(".updateStatusSoal.unRead");
        if (unanswered.length === 0 && updateStatusSoalAll) {
            updateStatusSoalAll.classList.add("hidden");
        } else {
            updateStatusSoalAll.classList.remove("hidden");
        }
    }

    checkAnsweredQuestions();

    updateStatusButtons.forEach(button => {
        button.addEventListener("click", function () {
            let getUrl = button.getAttribute("data-url-id");

            fetch(getUrl, {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.remove("unRead", "bg-blue-50", "cursor-pointer");
                    button.classList.add("bg-none", "cursor-default");

                    let badge = document.getElementById("notifBadgeAnswered");
                    if (badge && badge.textContent) {
                        let count = parseInt(badge.textContent) || 0;
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.remove(); // Hapus badge jika count sudah 0
                        }
                    }

                    checkAnsweredQuestions();
                } else {
                    alert("Gagal memperbarui status.");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });

// UPDATE ALL STATUS_SOAL_STUDENT (TELAH DIBACA)
    document.querySelectorAll(".updateStatusSoal a").forEach(link => {
        link.addEventListener("click", function (event) {
            event.stopPropagation();
        });
    });

    if (updateStatusSoalAll) {
        updateStatusSoalAll.addEventListener("click", function () {
            let getUrl = updateStatusSoalAll.getAttribute("data-url-id");

            fetch(getUrl, {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll(".updateStatusSoal.unRead").forEach(item => {
                        item.classList.remove("unRead", "bg-blue-50", "cursor-pointer");
                        item.classList.add("bg-none", "cursor-default");
                    });

                    let badge = document.getElementById("notifBadgeAnswered");
                    if (badge) {
                        badge.remove(); // Hapus badge jika semua pertanyaan telah dibaca
                    }

                    checkAnsweredQuestions();
                } else {
                    alert("Gagal memperbarui status.");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    }
});


// UPDATE STATUS_SOAL_STUDENT (DITOLAK)

// UPDATE SATUAN STATUS_SOAL_STUDENT (TELAH DIBACA)
document.addEventListener("DOMContentLoaded", function () {
    let updateStatusButtonsRejected = document.querySelectorAll(".updateStatusSoalRejected");
    let updateStatusSoalAllRejected = document.getElementById("updateStatusSoalAllRejected");

    function checkAnsweredQuestionsRejected() {
        let unanswered = document.querySelectorAll(".updateStatusSoalRejected.rejectedUnRead");
        if (unanswered.length === 0 && updateStatusSoalAllRejected) {
            updateStatusSoalAllRejected.classList.add("hidden");
        } else {
            updateStatusSoalAllRejected.classList.remove("hidden");
        }
    }

    checkAnsweredQuestionsRejected();

    // Update Status Soal Rejected
    updateStatusButtonsRejected.forEach(button => {
        button.addEventListener("click", function () {
            let getUrl = button.getAttribute("data-url-id");

            fetch(getUrl, {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.remove("rejectedUnRead", "bg-blue-50", "cursor-pointer");
                    button.classList.add("bg-none", "cursor-default");

                    let badge = document.getElementById("notifBadgeRejected");
                    if (badge && badge.textContent) {
                        let count = parseInt(badge.textContent) || 0;
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.remove(); // Hapus badge jika count sudah 0
                        }
                    }

                    checkAnsweredQuestionsRejected();
                } else {
                    alert("Gagal memperbarui status.");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });

    document.querySelectorAll(".updateStatusSoalRejected a").forEach(link => {
        link.addEventListener("click", function (event) {
            event.stopPropagation();
        });
    });

// UPDATE ALL STATUS_SOAL_STUDENT (TELAH DIBACA)
    if (updateStatusSoalAllRejected) {
        updateStatusSoalAllRejected.addEventListener("click", function () {
            let getUrl = updateStatusSoalAllRejected.getAttribute("data-url-id");

            fetch(getUrl, {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll(".updateStatusSoalRejected.rejectedUnRead").forEach(item => {
                        item.classList.remove("rejectedUnRead", "bg-blue-50", "cursor-pointer");
                        item.classList.add("bg-none", "cursor-default");
                    });

                    let badge = document.getElementById("notifBadgeRejected");
                    if (badge) {
                        badge.remove(); // Hapus badge jika semua pertanyaan telah dibaca
                    }

                    checkAnsweredQuestionsRejected();
                } else {
                    alert("Gagal memperbarui status.");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    }
});

