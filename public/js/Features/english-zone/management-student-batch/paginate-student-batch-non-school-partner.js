function paginateStudentBatchNonSchoolPartner(page = 1, search_mentor = '') {
    $.ajax({
        url: '/english-zone/management-student-batch/non-school-partner/paginate',
        method: 'GET',
        data: {
            page: page,
            search_mentor: search_mentor
        },
        success: function (response) {
            $('#tbody-table-management-student-batch').empty();

            if (response.data.length > 0) {
                $.each(response.data, function (i, items) {
                    let levelIds = items.level_ids.join(',');
                    let levelNames = items.level_names.join(', ');
                    let batchIds = items.batch_ids.join(' & ');
                    let batchNames = items.batch_names.join(' & ');
                    let daysList = items.days_of_week.join(' & ');
                    let hours = items.hours;

                    // Ambil mentor berdasarkan batch_schedule_ids
                    let mentorNames = [];
                    let mentorIds = [];
                    items.batch_schedule_ids.forEach(function (batchId) {
                        if (response.mentorSchedule[batchId]) {
                            response.mentorSchedule[batchId].forEach(function (m) {
                                mentorNames.push(m.user_account?.mentor_profiles.nama_lengkap ?? '-');
                                mentorIds.push(m.user_account?.id ?? '-');
                            });
                        }
                    });
                    mentorNames = [...new Set(mentorNames)]; // hapus duplikat
                    mentorIds = [...new Set(mentorIds)];

                    let mentorOption = '';
                    if (mentorNames.length > 0) {
                        mentorNames.forEach(function (mentorName, index) {
                            const limit = getLimitByScreenNonSchoolPartner();
                            const mentorId = mentorIds[index];
                            mentorOption += `
                                <div class="mentor-item">
                                    <label class="label-checkbox flex items-center px-3 py-3 hover:bg-gray-100 cursor-pointer text-xs border-b">
                                        <input type="checkbox"
                                            class="w-4 h-4 mr-2 cursor-pointer checkbox-activate-mentor"
                                            onchange="limitSelectionNonSchoolPartner(this, ${i + 1})"
                                            data-student-batch-ids="${items.student_batch_ids}"
                                            data-mentor-id="${mentorId}"
                                            ${mentorId == items.mentor_ids ? 'checked' : ''}>
                                            <span id="mentorItem_${index + 1}_non-school-partner" class="text-xs" data-original="${mentorName}">
                                                ${limitString(mentorName, limit)}
                                            </span>
                                    </label>
                                </div>
                            `;
                        });
                    } else {
                        mentorOption = `
                            <label
                                class="label-checkbox flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer text-xs">
                                <input type="checkbox" class="hidden"
                                    class="level-checkbox mr-2 rounded text-indigo-600 focus:ring-indigo-500" disabled>
                                <span>Tidak ada mentor</span>
                            </label>
                        `;
                    }

                    let explodeStudentIds = items.student_ids.join(',');
                    const managementStudentBatchDetail = response.studentBatchDetail.replace(':featureVariantId', items.variant_id).replace(':levelId', levelIds)
                        .replace(':batchId', batchIds).replace(':batchScheduleGroups', items.batch_schedule_groups.join(',')).replace(':batchScheduleIds', items.batch_schedule_ids.join(','))
                        .replace(':studentIds', explodeStudentIds);
                    
                    const formatDate = (dateString) => {
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Juni', 'Juli', 'Agust', 'Sept', 'Okt', 'Nov', 'Des'];
                        const date = new Date(dateString);
                        const day = date.getDate();
                        const monthName = months[date.getMonth()];
                        const year = date.getFullYear();

                        return `${day}-${monthName}-${year}`;
                    };

                    const startDate = items.start_date ? `${formatDate(items.start_date)}` : 'Tanggal tidak tersedia';
                    const endDate = items.end_date ? `${formatDate(items.end_date)}` : 'Tanggal tidak tersedia';

                    $('#tbody-table-management-student-batch').append(`
                    <tr class="text-xs">
                        <td class="td-table !text-black !text-center">${i + 1}</td>
                        <td class="td-table !text-black !text-center">${items.variant_name}</td>
                        <td class="td-table !text-black !text-center">${levelNames}</td>
                        <td class="td-table !text-black !text-center">${batchNames}</td>
                        <td class="td-table !text-black !text-center">${daysList}</td>
                        <td class="td-table !text-black !text-center">${hours}</td>
                        <td class="td-table !text-black !text-center">${startDate} - ${endDate}</td>
                        <td class="td-table !text-black !text-center w-[1%]">${items.count_students ?? 0}</td>
                        <td class="td-table !text-black !text-center">
                            <a href="${managementStudentBatchDetail}" class="text-[#4189E0] font-bold text-xs">Lihat Detail</a>
                        </td>
                        <td class="td-table !text-black !text-center w-[30%]">
                            <div id="mentorDropdown" class="dropdown-checkbox_${i + 1}_non-school-partner w-full bg-white h-12">
                                <div class="relative">
                                    <!-- Tombol dropdown -->
                                    <button type="button" id="dropdownButton" onclick="toggleOptionsNonSchoolPartner(event, ${i + 1})"
                                        class="w-full h-12 flex justify-between items-center p-2 border border-gray-300 rounded-lg bg-white text-xs outline-none">
                                        <span id="dropdownText_${i + 1}_non-school-partner">Pilih Mentor</span>
                                        <i class="fas fa-chevron-down text-[8px]"></i>
                                    </button>

                                    <!-- Options -->
                                    <div id="dropdownOptions_${i + 1}_non-school-partner"
                                        class="absolute mt-1 w-[260px] [@media(min-width:1226px)]:w-full bg-white border rounded-lg shadow-lg hidden max-h-48 overflow-y-auto z-[10] right-0">
                                        <label class="input flex items-center gap-2 w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-100 absolute left-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z" />
                                            </svg>
                                            <input type="search" class="search_mentor grow text-sm placeholder-black ml-4" autocomplete="OFF"
                                                placeholder="Cari Mentor..." />
                                        </label>
                                        <div class="dropdown-list-mentor border-t">
                                            ${mentorOption}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                `);
                });

                // Append pagination links
                $('.pagination-container-management-student-batch').html(response.links);
                bindPaginationLinks(); // Bind click event ke link pagination yang baru
                $('#empty-message-management-student-batch').hide(); // sembunyikan pesan kosong
                $('.thead-table-management-student-batch').show(); // Tampilkan tabel thead
                for (let j = 1; j <= response.data.length; j++) {
                    applyLimitSelectionNonSchoolPartner(j);
                }
            } else {
                $('#tbody-table-management-student-batch').empty(); // Clear existing rows
                $('#empty-message-management-student-batch').show(); // Tampilkan pesan kosong
                $('.thead-table-management-student-batch').hide(); // sembunyikan tabel thead
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });
}


$(document).ready(function () {
    paginateStudentBatchNonSchoolPartner();

    // Event search mentor (delegated)
    $(document).on('input', '.search_mentor', function () {
        const search = $(this).val().toLowerCase().trim();
        const dropdown = $(this).closest('label').next('.dropdown-list-mentor'); // ambil <div class="dropdown-list-mentor"> tempat mentorOption

        // Sembunyikan semua dulu
        let visibleCount = 0;
        dropdown.find('.label-checkbox').each(function () {
            const mentorName = $(this).text().toLowerCase();
            const match = mentorName.includes(search);
            $(this).toggle(match);
            if (match) visibleCount++;
        });

        // Hapus placeholder "tidak ada mentor" sebelumnya (kalau ada)
        dropdown.find('.no-mentor-message').remove();

        // Jika tidak ada yang cocok → tampilkan pesan
        if (visibleCount === 0) {
            dropdown.append(`
            <div class="no-mentor-message text-center text-gray-500 text-xs py-2">
                Mentor tidak ditemukan.
            </div>
        `);
        }
    });
});

function bindPaginationLinks() {
    $('.pagination-container-management-student-batch').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault(); // Cegah perilaku default link
        const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
        const search_mentor = $('#search_mentor').val();
        paginateStudentBatchNonSchoolPartner(page, search_mentor); // Ambil data yang difilter untuk halaman yang ditentukan
    });
}

// Fungsi untuk menentukan batas (limit) karakter berdasarkan ukuran layar
function getLimitByScreenNonSchoolPartner() {
    // Jika lebar layar ≥ 1260px → tampilkan maksimal 35 karakter
    if (window.matchMedia('(min-width: 1260px)').matches) return 35;
    // Jika layar lebih kecil dari 1260px → tampilkan maksimal 9 karakter
    return 9;
}


// Fungsi untuk memangkas teks sesuai limit, dan menambahkan "..." jika terlalu panjang
function limitString(str, limit) {
    return str ? (str.length > limit ? str.substring(0, limit) + '...' : str) : '-';
}

// Event listener yang dijalankan setiap kali ukuran layar diubah (resize)
window.addEventListener('resize', () => {
    // Bikin fungsi limitString lokal (sebenernya sama kayak di atas)
    const limitString = (str, limit) => (str ? (str.length > limit ? str.substring(0, limit) + '...' : str) : '-');

    // Update teks dropdown mentor (yang di tombol)
    document.querySelectorAll('[id^="dropdownText_"]').forEach(dropdownText => {
        // Ambil index dari ID, misal: dropdownText_3_non-school-partner → index = 3
        const indexMatch = dropdownText.id.match(/^dropdownText_(\d+)_non-school-partner$/);
        if (!indexMatch) return;
        const index = indexMatch[1];

        // Ambil semua mentor yang dicentang di dropdown tersebut
        const checked = document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input:checked`);
        // Ambil teks (nama mentor) dari label setelah input
        const values = Array.from(checked).map(cb => cb.nextElementSibling.textContent.trim());

        // Tentukan limit berdasarkan ukuran layar
        const limit = getLimitByScreenNonSchoolPartner();

        // Tampilkan hasil nama mentor yang dipangkas (sesuai limit)
        document.getElementById(`dropdownText_${index}_non-school-partner`).textContent =
            values.length ? limitString(values.join(', '), limit) : 'Pilih Mentor';
    });

    // Update tampilan nama mentor di daftar (mentor-item)
    document.querySelectorAll('.mentor-item span[id^="mentorItem_"]').forEach(span => {
        // Ambil index dari ID, misal: mentorItem_3_non-school-partner → index = 3
        const indexMatch = span.id.match(/^mentorItem_(\d+)_non-school-partner$/);
        if (!indexMatch) return;

        // Dapatkan limit berdasarkan ukuran layar
        const limit = getLimitByScreenNonSchoolPartner();
        // Ambil teks asli dari dataset (kalau ada), kalau nggak pakai teks yang sekarang
        const mentorName = span.dataset.original || span.textContent.trim();
        // Potong teks sesuai limit
        span.textContent = limitString(mentorName, limit);
    });
});


// Fungsi untuk toggle (buka/tutup) dropdown mentor
function toggleOptionsNonSchoolPartner(event, index) {
    event.stopPropagation(); // cegah klik bubbling supaya dropdown gak ketutup otomatis
    const dropdown = document.getElementById(`dropdownOptions_${index}_non-school-partner`);
    if (!dropdown) return;

    // Tutup semua dropdown lain dulu biar gak dobel buka
    document.querySelectorAll('[id^="dropdownOptions_"]').forEach(opt => {
        if (opt !== dropdown) opt.classList.add('hidden');
    });

    // Toggle dropdown yang diklik
    dropdown.classList.toggle('hidden');

    // Klik di luar dropdown → otomatis tutup
    document.addEventListener('click', function (e) {
        const dropdown = document.querySelector(`.dropdown-checkbox_${index}_non-school-partner`);
        if (!dropdown.contains(e.target)) {
            document.getElementById(`dropdownOptions_${index}_non-school-partner`).classList.add('hidden');
        }
    });
}

// Fungsi saat user centang/klik checkbox mentor
function limitSelectionNonSchoolPartner(checkbox, index) {
    const MAX_SELECTED = 1; // hanya boleh pilih 1 mentor

    // Ambil semua checkbox yang dicentang di dropdown ini
    const checked = Array.from(document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input[type="checkbox"]:checked`));
    // Ambil semua label mentor (buat atur opasitas)
    const labels = Array.from(document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner .label-checkbox`));

    // Update hidden input agar menyimpan ID mentor yang dipilih
    const selectedIds = checked.map(cb => cb.value);
    const hiddenLevelInput = document.getElementById('input-level-id');
    if (hiddenLevelInput) hiddenLevelInput.value = selectedIds.join(',');

    // Kalau sudah pilih 1 → disable semua checkbox lainnya
    if (checked.length >= MAX_SELECTED) {
        document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input:not(:checked)`).forEach(cb => cb.disabled = true);
        document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input[type="search"]`).forEach(cb => cb.disabled = false);
        labels.forEach(label => {
            if (!label.querySelector('input:checked')) {
                label.classList.remove('cursor-pointer');
                label.classList.add('opacity-50');
            }
        });
    } else {
        // Kalau belum maksimal → aktifkan kembali semua checkbox
        document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input`).forEach(cb => cb.disabled = false);
        labels.forEach(label => {
            label.classList.add('cursor-pointer');
            label.classList.remove('opacity-50');
        });
    }

    // Update teks di tombol dropdown agar sesuai dengan mentor yang dipilih
    updateButtonText();

    function updateButtonText() {
        const checked = document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input:checked`);
        const limitString = (str, limit) => (str ? (str.length > limit ? str.substring(0, limit) + '...' : str) : '-');
        const values = Array.from(checked).map(cb => cb.nextElementSibling.textContent.trim());
        const limit = getLimitByScreenNonSchoolPartner();

        document.getElementById(`dropdownText_${index}_non-school-partner`).textContent =
            values.length ? limitString(values.join(', '), limit) : 'Pilih Mentor';
    }

    // AJAX: aktif/nonaktifkan mentor
    $(document).on('change', '.checkbox-activate-mentor', function () {
        let studentBatchIds = $(this).data('student-batch-ids'); // ambil ID student_batch
        let mentorId = $(this).is(':checked') ? $(this).data('mentor-id') : null; // kalau dicentang → aktif
        let csrf = $('meta[name="csrf-token"]').attr('content'); // token CSRF

        $.ajax({
            url: `/english-zone/management-student-batch/activate-mentor/${studentBatchIds}`,
            type: 'PUT',
            data: {
                _token: csrf,
                mentor_id: mentorId
            },
            success: function (response) {
                // console.log(response.message); // berhasil, opsional tampilkan pesan
            },
            error: function (xhr) {
                alert('Gagal mengubah mengaktifkan mentor.');
                // kalau gagal, balikin status checkbox ke sebelumnya
                $(this).prop('checked', !$(this).is(':checked'));
            }
        });
    });
}

// Fungsi ini dipanggil setelah halaman di-refresh
// agar tampilan dropdown & limit karakter tetap sesuai kondisi terakhir
function applyLimitSelectionNonSchoolPartner(index) {
    const MAX_SELECTED = 1;
    const checked = Array.from(document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input[type="checkbox"]:checked`));
    const labels = Array.from(document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner .label-checkbox`));
    const limitString = (str, limit) => (str ? (str.length > limit ? str.substring(0, limit) + '...' : str) : '-');

    // Sama seperti fungsi sebelumnya → nonaktifkan kalau sudah mencapai batas
    if (checked.length >= MAX_SELECTED) {
        document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input:not(:checked)`).forEach(cb => cb.disabled = true);
        document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input[type="search"]`).forEach(cb => cb.disabled = false);
        labels.forEach(label => {
            if (!label.querySelector('input:checked')) {
                label.classList.remove('cursor-pointer');
                label.classList.add('opacity-50');
            }
        });
    } else {
        document.querySelectorAll(`#dropdownOptions_${index}_non-school-partner input`).forEach(cb => cb.disabled = false);
        labels.forEach(label => {
            label.classList.add('cursor-pointer');
            label.classList.remove('opacity-50');
        });
    }

    // Update teks pada tombol dropdown sesuai pilihan
    const values = checked.map(cb => cb.nextElementSibling.textContent.trim());
    const limit = getLimitByScreenNonSchoolPartner();
    document.getElementById(`dropdownText_${index}_non-school-partner`).textContent =
        values.length ? limitString(values.join(', '), limit) : 'Pilih Mentor';
}

// buat kembalikan header radio nya ketika di back ke student batch view menggunakan arrow back chrome
window.addEventListener("pageshow", function (event) {
    document.getElementById('radio1').checked = true;
});