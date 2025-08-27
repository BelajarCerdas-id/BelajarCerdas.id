function paginateSchoolPartner(search_school, page = 1) {
    $.ajax({
        url: '/list-school-partner/paginate',
        method: 'GET',
        data: {
            search_school: search_school,
            page: page
        },
        success: function (response) {
            $('#tbody-school-partner-list').empty();
            $('.pagination-container-school-partner-list').empty();

            if (response.data.length > 0) {

                $.each(response.data, function (index, item) {

                    // untuk link detail siswa pada school partner yang dilihat
                    const listUserSchoolSubscription = response.listUserSchoolSubscription.replace(':schoolId', item.nama_sekolah);

                    $('#tbody-school-partner-list').append(`
                        <tr>
                            <td class="td-table !text-black !text-center">
                                ${(response.current_page - 1) * response.per_page + index + 1}
                            </td>
                            <td class="td-table !text-black !text-center">${item.nama_sekolah}</td>
                            <td class="td-table !text-black !text-center">${item.npsn}</td>
                            <td class="td-table !text-black !text-center">${item.nama_kepsek}</td>
                            <td class="td-table !text-black !text-center">${item.nik_kepsek}</td>
                            <td class="td-table !text-black !text-center">
                                <a href="${listUserSchoolSubscription}" class="flex items-center justify-center gap-2 text-xs text-[#4189E0] font-bold">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    `);
                })
                    $('.pagination-container-school-partner-list').html(response.links);
                    bindPaginationLinks();
                    $('#empty-message-school-partner-list').hide(); // sembunyikan pesan kosong
                    $('.thead-table-school-partner-list').show(); // Tampilkan tabel thead
            } else {
                    $('#tbody-school-partner-list').empty(); // Clear existing rows
                    $('.thead-table-school-partner-list').hide(); // Tampilkan tabel thead
                    $('#empty-message-school-partner-list').show();
            }
        }
    });
}

$(document).ready(function () {
    paginateSchoolPartner();
})

// Fungsi untuk memfilter data berdasarkan search_school (pakai on input karena ketika data yang user cari akan munul tanpa di enter atau apapun by click)
$('#search_school').on('input', function() {
    const search_school = $(this).val();
    paginateSchoolPartner(search_school); // Call the function to fetch data based on search_school
});


function bindPaginationLinks() {
    $('.pagination-container-school-partner-list').off('click', 'a').on('click', 'a', function(event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        const search_school = $('#search_school').val();
        paginateSchoolPartner(search_school, page); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

$(document).ready(function () {
    $('#school-partner-form').on('submit', function (e) {
        e.preventDefault();

        var $btn = $(this).find('#submit-button');

        // Cegah jika tombol sudah disable
        if ($btn.prop('disabled')) {
            return false;
        }

        $btn.prop('disabled', true); // disable tombol

        const formData = new FormData(this);

        $.ajax({
            url: '/school-subcsription/store',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {
                const modal = document.getElementById('my_modal_1');

                if (modal) {
                    modal.close();

                    $('#alert-success-insert-school-partner').html(
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
                }

                // Reset form
                $('#school-partner-form')[0].reset();
                $('#excelPreviewContainer-bulkUpload-excel').addClass('hidden');
                $('#textPreview-bulkUpload-excel').text('');
                $('#textSize-bulkUpload-excel').text('');
                $('#textPages-bulkUpload-excel').text('');
                $('#textCircle-bulkUpload-excel').html('');
                $('#logo-bulkUpload-excel img').attr('src', '').hide();

                setTimeout(function() {
                    document.getElementById('alertSuccess').remove();
                }, 3000);

                document.getElementById('btnClose').addEventListener('click', function () {
                    document.getElementById('alertSuccess').remove();
                });

                // inisialisasi fungsi untuk update data terbaru
                paginateSchoolPartner();

                $btn.prop('disabled', false); // enable tombol
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const response = xhr.responseJSON;

                    $btn.prop('disabled', false); // enable tombol

                    // error validation form dan bulkUpload
                    const formErrors = response.errors.form_errors ?? {};
                    const excelErrors = response.errors.excel_validation_errors ?? [];

                    let errorList = '';

                    $.each(formErrors, function (field, messages) {
                        $(`#error-${field}`).text(messages[0]);
                        $(`[name="${field}"]`).addClass('border-red-400 border-2');
                    });

                    if (excelErrors.length > 0) {
                        excelErrors.forEach(err => {
                            errorList += `<li class="text-sm">${err}</li>`;
                        });

                        const html = `
                            <ul class="text-red-500 text-sm list-disc pl-5">
                                ${errorList}
                            </ul>
                        `;

                        const showError = `
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-2 h-max rounded">
                                <span class="font-bold text-sm">Terjadi Kesalahan :</span>
                                ${html}
                            </div>
                        `;

                        $('#error-bulkUpload').html(showError);
                        my_modal_1.showModal();
                    }
                } else {
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            }
        });
    });
})
