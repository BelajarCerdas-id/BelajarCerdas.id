function fetchDataTanyaAccess(page = 1) {
        $.ajax({
            url: '/filter-tanya-access',
            data: { page: page },
            method: 'GET',
            success: function (data) {
                $('#tbody-table-tanya-access').empty();
                $('.pagination-container-tanya-access').empty();

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

                    const startDate = application.tanggal_mulai ? `${formatDate(application.tanggal_mulai)}` : 'Tanggal tidak tersedia';
                    const endDate = application.tanggal_akhir ? `${formatDate(application.tanggal_akhir)}` : 'Tanggal tidak tersedia';
                    const updatedAt = application.updated_at ? `${formatDate(application.updated_at)}, ${timeFormatter.format(new Date(application.updated_at))}` : 'Tanggal tidak tersedia';

                        $('#tbody-table-tanya-access').append(`
                            <tr>
                                <td class="border text-center">${startDate}</td>
                                <td class="border text-center">${endDate}</td>
                                <td class="border text-center">${application.status_access}</td>
                                <td class="border text-center">${application.status_access === 'Aktif' ? 'Locked' : 'Unlocked'}</td>
                                <td class="border text-center">
                                    <div class="dropdown dropdown-left">
                                        <div tabindex="0" role="button">
                                            <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                        </div>
                                        <ul tabindex="0"
                                            class="dropdown-content menu bg-white rounded-box z-1 w-max p-2 shadow-sm z-[9999]">
                                            <li class="text-xs" onclick="editAccess(this)"
                                                    data-tanggal-mulai="${application.tanggal_mulai}"
                                                    data-tanggal-akhir="${application.tanggal_akhir}">
                                                <a href="#" class="btn-update-tanya-access" data-id="${application.id}">
                                                    <i class="fa-solid fa-pen text-[#4189e0]"></i>
                                                    Atur Tanggal
                                                </a>
                                            </li>
                                            <li class="text-xs" onclick="historyTanyaAccess(this)"
                                                data-nama_lengkap="${application.user_account?.office_profiles?.nama_lengkap}"
                                                data-status="${application.user_account?.role}"
                                                data-updated_at="[${updatedAt}]">
                                                <a>
                                                    <i class="fa-solid fa-eye text-[#4189e0]"></i>
                                                    View History
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });

                    $('.pagination-container-tanya-access').html(data.links).show();
                    $('#thead-table-tanya-access').show();
                    $('#emptyMessage-riwayat-tanya-access').hide();

                    bindPaginationLinks();
                } else {
                    $('#thead-table-tanya-access').hide();
                    $('.pagination-container-tanya-access').hide();
                    $('#emptyMessage-riwayat-tanya-access').show();
                }
            }
        });
}

function bindPaginationLinks() {
    $('.pagination-container-tanya-access').off('click', 'a').on('click', 'a', function (e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        fetchDataTanyaAccess(page);
    });
}

// Inisialisasi saat DOM siap
$(document).ready(function () {
    fetchDataTanyaAccess();
});

// Event listener tombol "insert tanya access" (open modal)
$('#form-insert-tanya-access').on('submit', function (e) {
    e.preventDefault();

    const startDate = $('#datepicker-insert-tanggal-mulai').val();
    const endDate = $('#datepicker-insert-tanggal-akhir').val();

    // Kosongkan error sebelumnya
    $('#error-insert-tanggal-mulai').text('');
    $('#error-insert-tanggal-akhir').text('');

    const formData = new FormData(this);
    const csrf = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: `/tanya-access-store`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_1');
            if (modal) {
                modal.close();

                if (response.status === 'success' && response.message === 'Data berhasil disimpan.') {
                    $('#alert-success-insert-tanya-access').html(
                        `
                        <div class="w-full flex justify-center">
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

                    // Memanggil fungsi untuk memuat ulang data
                    fetchDataTanyaAccess();
                } else {
                // tampilkan sweetalert error ketika data sudah ada
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                        // Tampilkan pesan error
                    $('.form-insert').find(`#error-insert-${field}`).text(messages[0]);

                        // Tambahkan style error ke input (jika ada)
                    $('.form-insert').find(`[name="${field}"]`).addClass('border-red-400 border-2');
                })
            } else if (xhr.status === 419) {
                alert('CSRF token mismatch. Coba refresh halaman.');
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }
        }
    });
});


// Event listener tombol "update tanya access" (open modal)
$(document).off('click', '.btn-update-tanya-access').on('click', '.btn-update-tanya-access', function(e) {
    e.preventDefault();

    const tanyaAccessId = $(this).data('id');

    // (Optional) set id ke form untuk submit
    $('#form-update-tanya-access').data('id', tanyaAccessId);

    // Reset text error
    $('#error-tanggal-mulai').text('');
    $('#error-tanggal-akhir').text('');

    // Tampilkan modal
    const modal = document.getElementById('my_modal_2');
    if (modal) {
        modal.showModal();
    }
});

$('#form-update-tanya-access').on('submit', function (e) {
    e.preventDefault();

    const tanyaAccessId = $(this).data('id');
    const startDate = $('#datepicker-tanggal-mulai').val();
    const endDate = $('#datepicker-tanggal-akhir').val();

    // Kosongkan error sebelumnya
    $('#error-tanggal-mulai').text('');
    $('#error-tanggal-akhir').text('');

    const formData = new FormData(this);
    const csrf = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: `/tanya-access-update/${tanyaAccessId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            // Menutup modal
            const modal = document.getElementById('my_modal_2');
            if (modal) {
                modal.close();

                $('#alert-success-update-tanya-access').html(
                    `
                    <div class="w-full flex justify-center">
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

                // Memanggil fungsi untuk memuat ulang data
                fetchDataTanyaAccess();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $('.input-error').removeClass('border-red-400 border-2');

                $.each(errors, function (field, messages) {
                        // Tampilkan pesan error
                    $('.form-update').find(`#error-update-${field}`).text(messages[0]);

                        // Tambahkan style error ke input (jika ada)
                    $('.form-update').find(`[name="${field}"]`).addClass('border-red-400 border-2');
                })
            } else if (xhr.status === 419) {
                alert('CSRF token mismatch. Coba refresh halaman.');
            } else {
                alert('Terjadi kesalahan saat mengirim data.');
            }
        }
    });
});
