document.addEventListener('DOMContentLoaded', function() {
        var question = document.getElementById('tanyaSiswa');
        var history = document.getElementById('riwayatSiswa');

        window.tanyaSiswa = function() {
            question.style.display = "block";
            history.style.display = "none"; // Geser keluar ke kanan
        };

        window.riwayatSiswa = function() {
            question.style.display = "none"; // Geser keluar ke kiri
            history.style.display = "block";
        };
    });