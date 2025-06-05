$(document).ready(function () {
    var oldFase = $('#id_fase').attr('data-old-fase');
    var oldKelas = $('#id_kelas').attr('data-old-kelas');
    var selectKelas = document.getElementById('id_kelas');

    function enableKelasDropdown() {
        selectKelas.disabled = false;
        selectKelas.classList.replace('opacity-50', 'opacity-100');
        selectKelas.classList.replace('cursor-default', 'cursor-pointer');
    }

    $('#id_fase').on('change', function () {
        var fase_id = $(this).val();
        if (fase_id) {
            $.ajax({
                url: '/kelas/' + fase_id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#id_kelas').empty();
                    $('#id_kelas').append('<option value="" class="hidden">Pilih Kelas</option>');

                    if (data.length > 0) {
                        enableKelasDropdown();

                        $.each(data, function (key, kelas) {
                            $('#id_kelas').append(
                                '<option value="' + kelas.id + '"' +
                                (oldKelas == kelas.id ? ' selected' : '') +
                                '>' + kelas.kelas + '</option>'
                            );
                        });

                        if (oldKelas) {
                            $('#id_kelas').val(oldKelas).trigger('change');
                            oldKelas = null; // Reset agar tidak digunakan lagi
                        }
                    }
                }
            });
        } else {
            $('#id_kelas').empty();
        }
    });

    // Trigger hanya jika ada oldFase (misalnya setelah validasi error)
    if (oldFase) {
        $('#id_fase').val(oldFase).trigger('change');
    }
});
