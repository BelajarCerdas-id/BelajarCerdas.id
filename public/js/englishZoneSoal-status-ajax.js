function fetchFilteredDataQuestions(status_soal, modul_soal, jenjang, page = 1) {
    $.ajax({
        url: '/filter-questions',
        method: 'GET',
        data: {
            status_soal: status_soal,
            modul_soal: modul_soal,
            jenjang: jenjang,
            page: page
        },
        success: function (data) {
            $('#tableListQuestion').empty(); // Clear previous entries
            $('.pagination-container-question').empty(); // Clear previous pagination links
            if (data.data.length > 0) {
                $.each(data.data, function (index, application) {
                    const formatDate = (dateString) => {
                        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                        const date = new Date(dateString);
                        const dayName = days[date.getDay()];
                        const day = date.getDate();
                        const monthName = months[date.getMonth()];
                        const year = date.getFullYear();

                        return `${dayName}, ${day}-${monthName}-${year}`;
                    };
                    const timeFormatter = new Intl.DateTimeFormat('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                    });

                    const createdAt = application.created_at ? `${formatDate(application.created_at)}, ${timeFormatter.format(new Date(application.created_at))}` : 'Tanggal tidak tersedia';

                    $('#tableListQuestion').append(`
                        <tr class="text-xs">
                            <td class="td-table !text-center">
                                <input type="checkbox" name="id[]" value="${application.id}" onclick="showButton()" class="checkboxButton cursor-pointer">
                            </td>
                            <td class="td-table !text-center">${application.modul_soal}</td>
                            <td class="td-table !text-center">${application.jenjang}</td>
                            <td class="td-table">${application.soal}</td>
                            <td class="td-table !text-center">${application.jawaban_benar}</td>
                            <td class="td-table">${application.deskripsi_jawaban}</td>
                            <td class="td-table">${createdAt}</td>
                            <td class="td-table !text-center">
                                <button class="bg-${application.status_soal === 'published' ? 'green-500' : 'gray-300'} text-white p-2 rounded-lg">
                                    ${application.status_soal}
                                </button>
                            </td>
                            <td class="td-table !text-center">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </td>
                        </tr>
                    `);
                });
                $('.pagination-container-question').html(data.links);
            } else {
                $('#tableListQuestion').html('<tr><td colspan="12">Data tidak tersedia.</td></tr>');
            }
        }
    });
}

function showButton() {
    const checkboxes = document.querySelectorAll('.checkboxButton');
    const sendButton = document.getElementById('sendButton');
    const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    console.log('Checkbox status:', anyChecked); // Tambahkan log untuk melihat status checkbox

    if (anyChecked) {
        sendButton.style.display = 'block';
    } else {
        sendButton.style.display = 'none';
    }
}


$('#sendButton').on('click', function () {
    const selectedIds = $('.checkboxButton:checked').map(function () {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        alert('Pilih setidaknya satu soal untuk diperbarui.');
        return;
    }

    $.ajax({
        url: '/question-for-release/update',
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { ids: selectedIds },
        success: function (response) {
            // alert(response.message);
            const status_soal = $('#questionStatusFilter').val();
            fetchFilteredDataQuestions(status_soal);
            $('#sendButton').hide();
        },
        // error: function (xhr) {
        //     alert('Terjadi kesalahan: ' + xhr.responseText);
        // }
    });
});

// ketika memiliki lebih dari 1 filtering maka harus dilakukan seperti ini, ketika onchange pada status_soal maka modul harus ada, dst
// filtering status_soal
$(document).on('change', '#questionStatusFilter', function () {
    const status_soal = $(this).val();
    const modul_soal = $('#questionModulFilter').val();  // Ambil nilai modul
    const jenjang = $('#questionJenjangFilter').val(); // Ambil nilai jenjang
    fetchFilteredDataQuestions(status_soal, modul_soal, jenjang); // Kirim seluruhnya ke server
});

// ketika memiliki lebih dari 1 filtering maka harus dilakukan seperti ini, ketika onchange pada modul maka status_soal harus ada, dst
// filtering modul
$(document).on('change', '#questionModulFilter', function () {
    const modul_soal = $(this).val();
    const status_soal = $('#questionStatusFilter').val(); // Ambil nilai status_soal
    const jenjang = $('#questionJenjangFilter').val(); // Ambil nilai jenjang
    fetchFilteredDataQuestions(status_soal, modul_soal, jenjang); // Kirim seluruhnya ke server
});

// filtering jenjang
$(document).on('change', '#questionJenjangFilter', function () {
    const jenjang = $(this).val();
    const status_soal = $('#questionStatusFilter').val(); // Ambil nilai status_soal
    const modul_soal = $('#questionModulFilter').val(); // Ambil nilai modul
    fetchFilteredDataQuestions(status_soal, modul_soal, jenjang); // Kirim seluruhnya ke server
});


$(document).on('click', '.pagination-container-question', function (event) {
    event.preventDefault();
    const page = new URL(this.href).searchParams.get('page');
    const status_soal = $('#questionStatusFilter').val();
    const modul_soal = $('#questionModulFilter').val();
    const jenjang = $('#questionJenjangFilter').val();
    fetchFilteredDataQuestions(page, status_soal, modul_soal, jenjang);
});

$(document).ready(function () {
    fetchFilteredDataQuestions('semua'); // Load all questions initially
});
