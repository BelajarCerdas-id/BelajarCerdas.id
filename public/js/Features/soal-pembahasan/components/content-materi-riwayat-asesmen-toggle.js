var c = document.getElementById('content');
var r = document.getElementById('riwayat');
    function content() {
        r.style.display = "none";
        c.style.display = "flex";
    }

    function riwayat() {
        c.style.display = "none";
        r.style.display = "block";
    }
