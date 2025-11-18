$(document).ready(function () {
    const container = document.getElementById('btn-attendance');

    const subscriptionId = container.dataset.subscriptionId;
    const studentAttendanceHistory = container.dataset.studentAttendanceHistory;
    const date = container.dataset.date;
    const subscriptionStartDate = container.dataset.subscriptionStartDate;
    const subscriptionEndDate = container.dataset.subscriptionEndDate;

    if (!studentAttendanceHistory && subscriptionId) {
        if (date >= subscriptionStartDate && date <= subscriptionEndDate) {
            const btn = `
                <form id="form-submit-attendance">
                    <input type="hidden" name="subscription_history_id" value="${subscriptionId}">
                    <button class="bg-[#4189E0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all text-sm">
                        <i class="fa-solid fa-user-check"></i>
                        <span class="">Klik untuk Absen</span>
                    </button>
                </form>
            `;
    
            container.innerHTML = btn;
        }
    }
});

let isProcessing = false;
$(document).on('submit', '#form-submit-attendance', function (e) {
    e.preventDefault();

    if (isProcessing) return; // Abaikan jika sedang proses

    isProcessing = true; // Tandai sedang diproses

    const container = document.getElementById('btn-attendance');

    const subscriptionId = container.dataset.subscriptionId;
    const studentAttendanceHistory = container.dataset.studentAttendanceHistory;
    const date = container.dataset.date;
    const subscriptionStartDate = container.dataset.subscriptionStartDate;
    const subscriptionEndDate = container.dataset.subscriptionEndDate;

    const formData = $(this).serialize();

    const btn = $(this).find('button');
    btn.prop('disabled', true);

    $.ajax({
        url: '/english-zone-student/attendance',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        success: function (response) {
            if (!studentAttendanceHistory && subscriptionId) {
                if (date >= subscriptionStartDate && date <= subscriptionEndDate) {
                    $('#form-submit-attendance').remove();
                }
            }

            // alert sukses
            $('#alert-success-submit-attendance').html(`
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
            `);

            setTimeout(() => $('#alertSuccess').remove(), 3000);
            $('#btnClose').on('click', () => $('#alertSuccess').remove());

            fetchPaginateAttendance();
            paginateMateriStudent();

            isProcessing = false;
            btn.prop('disabled', false);
        },
        error: function (xhr, status, error) {
            alert('Terjadi kesalahan saat mengirim data.');
            isProcessing = false;
            btn.prop('disabled', false);
        }
    });
});