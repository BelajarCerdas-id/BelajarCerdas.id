$(document).ready(function () {
    // Submit form dengan AJAX
    $("#questions-form").on("submit", function (e) {
        e.preventDefault();

        var $btn = $(this).find('#submit-button');

        // Cegah jika tombol sudah disable
        if ($btn.prop('disabled')) {
            return false;
        }

        $btn.prop('disabled', true); // disable tombol


        const formData = new FormData(this);

        $.ajax({
            url: '/tanya/store',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // inisialisasi content tanya harian belum terjawab student agar muncul ke riwayat nya tanpa refresh
                fetchDataTanyaUnAnswered();
                // inisialisasi jumlah koin terbaru setelah success submit pertanyaan
                updateJumlahKoinStudent();

                $('#alert-success-insert-question').html(
                    `
                    <div class=" w-full flex justify-center">
                        <div class="fixed z-[9999]">
                            <div id="alertSuccess"
                                class="relative top-[-45px] opacity-100 scale-90 bg-green-200 w-max p-3 flex items-center space-x-2 rounded-lg shadow-lg transition-all duration-300 ease-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current text-green-600" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-green-600 text-sm">${response.message}</span>
                                <i class="fas fa-times cursor-pointer text-green-600" id="btnClose"></i>
                            </div>
                        </div>
                    </div>
                    `
                );

                setTimeout(function() {
                    document.getElementById('alertSuccess').remove();
                }, 3000);

                document.getElementById('btnClose').addEventListener('click', function () {
                    document.getElementById('alertSuccess').remove();
                });
                // Reset form (kecuali dropdown custom mapel sama image preview)
                $('#questions-form')[0].reset();

                $('#id_kelas').html('<option disabled selected>Pilih Kelas</option>').prop('disabled', true).removeClass('opacity-100 cursor-pointer').addClass('opacity-50 cursor-default');
                $('#id_mapel').html('<option disabled selected>Pilih Mata Pelajaran</option>').prop('disabled', true).removeClass('opacity-100 cursor-pointer').addClass('opacity-50 cursor-default');
                $('#id_bab').html('<option disabled selected>Pilih Bab</option>').prop('disabled', true).removeClass('opacity-100 cursor-pointer').addClass('opacity-50 cursor-default');

                // Reset image preview
                $('#file-upload').val('');
                $('#imagePreview img').attr('src', '').hide();
                $('#textPreview').text('');

                // Nonaktifkan dropdown button (custom UI)
                $('#dropdownButton').addClass('pointer-events-none opacity-50');

                // Aktifkan ulang dropdown custom mapel
                $('#id_mapel').prop('disabled', false); // jika pakai <select> tersembunyi

                // Reset state terpilih sebelumnya
                $('#selectedKurikulum').text('Pilih Mata Pelajaran');
                $('#selectedIconKoin').html('');
                $('#selectedKoin').text('');

                $btn.prop('disabled', false); // undisable tombol


                // inisialisasi pertanyaan student (pada halaman tanya mentor)
                fetchFilteredDataRiwayatMentor();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    $btn.prop('disabled', false); // undisable tombol

                    // Bersihkan semua error sebelumnya
                    // $('.text-error').text('');
                    $('.input-error').removeClass('border-red-400 border-2');
                    $('#dropdownWrapper').removeClass('border-red-400').addClass('border-gray-200');

                    $.each(errors, function (field, messages) {
                        // Tampilkan pesan error
                        $(`#error-${field}`).text(messages[0]);

                        // Tambahkan style error ke input (jika ada)
                        $(`[name="${field}"]`).addClass('border-red-400 border-2');

                        // error border untuk dropdown custom mapel
                        if (field === 'mapel_id') {
                            $('#dropdownWrapper').removeClass('border-gray-200').addClass('border-red-400');
                        }
                    })
                } else if (xhr.status === 419) {
                    alert('CSRF token mismatch. Coba refresh halaman.');
                } else {
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            }
        });
    });
});
