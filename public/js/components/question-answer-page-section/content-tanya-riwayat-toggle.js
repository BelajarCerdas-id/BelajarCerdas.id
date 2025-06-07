var c = document.getElementById('content');
var r = document.getElementById('riwayat');
    function content() {
        r.style.display = "none";
        c.style.display = "block";
        // (kalau mau di reset kembali ke status terakhir yang dipilih)
        // fetchFilteredDataRiwayatStudent($('#statusFilterRiwayatStudent').val()); // menyimpan value terakhir statusFilterRiwayatStudent
        // fetchFilteredDataRiwayatMentor($('#statusFilterRiwayatMentor').val()); // menyimpan value terakhir statusFilterRiwayatMentor

        // Reset filter ke 'semua' (ketika function di klik maka filter yang sudah dipilih sebelumnya akan direset kembali ke awal)
        $('#statusFilterRiwayatStudent').val('semua');
        fetchFilteredDataRiwayatStudent('semua'); // panggil ulang data Riwayat student

        $('#statusFilterRiwayatMentor').val('semua');
        fetchFilteredDataRiwayatMentor('semua'); // panggil ulang data Riwayat mentor
    }

    function riwayat() {
        c.style.display = "none";
        r.style.display = "block";
    }
