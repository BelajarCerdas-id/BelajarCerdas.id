    // buat kembaklikan header radio nya ketika di back ke tanya menggunakan arrow back chrome
    window.addEventListener("pageshow", function(event) {
        document.getElementById('radio1').checked = true;
        document.getElementById('unanswered').checked = true;
    });
