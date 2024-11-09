document.addEventListener('DOMContentLoaded', function() {
        var question = document.getElementById('tanyaSiswa');
        var history = document.getElementById('riwayatSiswa');

        window.tanyaSiswa = function() {
            question.style.transform = "translateX(0)";
            history.style.transform = "translateX(100%)"; // Geser keluar ke kanan
        };

        window.riwayatSiswa = function() {
            question.style.transform = "translateX(-100%)"; // Geser keluar ke kiri
            history.style.transform = "translateX(0)";
        };
    });