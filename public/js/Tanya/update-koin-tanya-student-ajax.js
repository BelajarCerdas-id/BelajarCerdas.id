function updateJumlahKoinStudent() {
    $.ajax({
        'url': '/update-koin-student',
        method: 'GET',
        success: function(response) {
            $('#jumlahKoin').text(response.jumlah_koin);
        },
        error: function() {
            console.error('❌ Gagal memuat jumlah koin student.');
        }
    });
}


$(document).ready(function() {
    updateJumlahKoinStudent();
});
