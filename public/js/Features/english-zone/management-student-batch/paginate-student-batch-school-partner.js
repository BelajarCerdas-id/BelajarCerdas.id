function paginateStudentBatchSchoolPartner(page = 1, search_mentor = '', search_school_partner = '') {
    $.ajax({
        url: '/english-zone/management-student-batch/school-partner/paginate',
        method: 'GET',
        data: {
            page: page,
            search_mentor: search_mentor,
            search_school_partner: search_school_partner,
        },
        success: function (response) {
            $('#tbody-table-management-student-batch-school-partner').empty();

            if (response.data.length > 0) {
                $.each(response.data, function (i, items) {
                    let levelIds = items.level_ids.join(',');
                    let levelNames = items.level_names.join(', ');
                    let batchIds = items.batch_ids.join(' & ');
                    let batchNames = items.batch_names.join(' & ');
                    let daysList = items.days_of_week.join(' & ');
                    let hours = items.hours;

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
                    mentorNames = [...new Set(mentorNames)];
                    mentorIds = [...new Set(mentorIds)];

                    let mentorOption = '';
                    if (mentorNames.length > 0) {
                        mentorNames.forEach(function (mentorName, index) {
                            const limit = getLimitByScreenSchoolPartner();
                            const mentorId = mentorIds[index];
                            mentorOption += `
                                <div class="mentor-item">
                                    <label class="label-checkbox flex items-center px-3 py-3 hover:bg-gray-100 cursor-pointer text-xs border-b">
                                        <input type="checkbox"
                                            class="w-4 h-4 mr-2 cursor-pointer checkbox-activate-mentor"
                                            onchange="limitSelectionSchoolPartner(this, ${i + 1})"
                                            data-student-batch-ids="${items.student_batch_ids}"
                                            data-mentor-id="${mentorId}"
                                            ${mentorId == items.mentor_ids ? 'checked' : ''}>
                                            <span id="mentorItem_${index + 1}_school-partner" class="text-xs" data-original="${mentorName}">
                                                ${limitString(mentorName, limit)}
                                            </span>
                                    </label>
                                </div>
                            `;
                        });
                    } else {
                        mentorOption = `
                            <label class="label-checkbox flex items-center px-3 py-2 text-sm text-gray-500">
                                <span>Tidak ada mentor</span>
                            </label>
                        `;
                    }

                    let explodeStudentIds = items.student_ids.join(',');
                    const managementStudentBatchDetail = response.studentBatchDetail
                        .replace(':featureVariantId', items.variant_id)
                        .replace(':levelId', levelIds)
                        .replace(':batchId', batchIds)
                        .replace(':batchScheduleGroups', items.batch_schedule_groups.join(','))
                        .replace(':batchScheduleIds', items.batch_schedule_ids.join(','))
                        .replace(':studentIds', explodeStudentIds)
                        .replace(':schoolId', items.school);
                    
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

                    $('#tbody-table-management-student-batch-school-partner').append(`
                        <tr class="text-xs">
                            <td class="td-table !text-black !text-center">${i + 1}</td>
                            <td class="td-table !text-black !text-center">${items.school}</td>
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
                                <div class="dropdown-checkbox_${i + 1}_school-partner w-full bg-white h-12">
                                    <div class="relative">
                                        <button type="button" id="dropdownButton" onclick="toggleOptionsSchoolPartner(event, ${i + 1})"
                                            class="w-full h-12 flex justify-between items-center p-2 border border-gray-300 rounded-lg bg-white text-xs outline-none">
                                            <span id="dropdownText_${i + 1}_school-partner">Pilih Mentor</span>
                                            <i class="fas fa-chevron-down text-[8px]"></i>
                                        </button>
                                        <div id="dropdownOptions_${i + 1}_school-partner"
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

                // ✅ hanya tampilkan pagination jika ada link (data tidak kosong)
                if (response.links) {
                    $('.pagination-container-management-student-batch-school-partner').show().html(response.links);
                    bindPaginationLinks();
                    for (let j = 1; j <= response.data.length; j++) {
                        applyLimitSelection(j);
                    }
                } else {
                    $('.pagination-container-management-student-batch-school-partner').hide();
                }

                $('#empty-message-management-student-batch-school-partner').hide();
                $('.thead-table-management-student-batch-school-partner').show();

            } else {
                // ✅ ketika tidak ada data, sembunyikan pagination
                $('#tbody-table-management-student-batch-school-partner').empty();
                $('.pagination-container-management-student-batch-school-partner').hide();
                $('#empty-message-management-student-batch-school-partner').show();
                $('.thead-table-management-student-batch-school-partner').hide();
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });
}

$(document).ready(function () {
    paginateStudentBatchSchoolPartner();

    $(document).on('input', '.search_mentor', function () {
        const search = $(this).val().toLowerCase().trim();
        const dropdown = $(this).closest('label').next('.dropdown-list-mentor');
        let visibleCount = 0;

        dropdown.find('.label-checkbox').each(function () {
            const mentorName = $(this).text().toLowerCase();
            const match = mentorName.includes(search);
            $(this).toggle(match);
            if (match) visibleCount++;
        });

        dropdown.find('.no-mentor-message').remove();
        if (visibleCount === 0) {
            dropdown.append(`<div class="no-mentor-message text-center text-gray-500 text-xs py-2">Mentor tidak ditemukan.</div>`);
        }
    });
});

$('.search_school_partner').on('input', function () {
    const search_school_partner = $(this).val();
    paginateStudentBatchSchoolPartner(1, '', search_school_partner);
});

function bindPaginationLinks() {
    $('.pagination-container-management-student-batch-school-partner').off('click', 'a').on('click', 'a', function (event) {
        event.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        const search_mentor = $('#search_mentor').val();
        const search_school_partner = $('#search_school_partner').val();
        paginateStudentBatchSchoolPartner(page, search_mentor, search_school_partner);
    });
}

// Fungsi untuk menentukan batas (limit) karakter berdasarkan ukuran layar
function getLimitByScreenSchoolPartner() {
    // Jika lebar layar ≥ 1320px → tampilkan maksimal 35 karakter
    if (window.matchMedia('(min-width: 1320px)').matches) return 35;
    // Jika layar lebih kecil dari 1320px → tampilkan maksimal 9 karakter
    return 9;
}

// Fungsi untuk memangkas teks sesuai limit dan menambahkan "..." jika terlalu panjang
function limitString(str, limit) {
    // Jika string ada → potong sesuai limit dan tambahkan "..." bila panjang
    // Jika string kosong → tampilkan '-'
    return str ? (str.length > limit ? str.substring(0, limit) + '...' : str) : '-';
}

// Event listener: dijalankan setiap kali ukuran layar diubah (resize)
window.addEventListener('resize', () => {
    // Membuat fungsi limitString versi lokal (sama fungsinya dengan di atas)
    const limitString = (str, limit) => (str ? (str.length > limit ? str.substring(0, limit) + '...' : str) : '-');

    // Update teks dropdown utama (tombol dropdown) saat layar di-resize
    document.querySelectorAll('[id^="dropdownText_"]').forEach(dropdownText => {
        // Ambil index dari ID, contoh: dropdownText_3_school-partner → index = 3
        const indexMatch = dropdownText.id.match(/^dropdownText_(\d+)_school-partner$/);
        if (!indexMatch) return;
        const index = indexMatch[1];

        // Ambil nama mentor yang sedang dicentang di dropdown
        const checked = document.querySelectorAll(`#dropdownOptions_${index}_school-partner input:checked`);
        // Ambil teks dari label yang bersebelahan dengan input checkbox
        const values = Array.from(checked).map(cb => cb.nextElementSibling.textContent.trim());

        // Tentukan limit karakter berdasarkan ukuran layar
        const limit = getLimitByScreenSchoolPartner();

        // Update teks tombol dropdown sesuai dengan nama mentor (dipotong sesuai limit)
        document.getElementById(`dropdownText_${index}_school-partner`).textContent =
            values.length ? limitString('dfkbfdkbkfdb fbfdlb,fldb,fdbdf fbfl;db,lfdb,fdb,fdl;b dflb,fdlb,fd,blfdb', limit) : 'Pilih Mentor';
    });

    // Update tampilan nama mentor di daftar (element mentor-item)
    document.querySelectorAll('.mentor-item span[id^="mentorItem_"]').forEach(span => {
        // Ambil index dari ID, contoh: mentorItem_3_school-partner → index = 3
        const indexMatch = span.id.match(/^mentorItem_(\d+)_school-partner$/);
        if (!indexMatch) return;

        // Tentukan limit berdasarkan ukuran layar
        const limit = getLimitByScreenSchoolPartner();
        // Ambil nama asli mentor dari dataset (kalau disimpan), kalau tidak ada ambil dari teks yang tampil
        const mentorName = span.dataset.original || span.textContent.trim();
        // Potong teks sesuai limit dan tampilkan kembali
        span.textContent = limitString(mentorName, limit);
    });
});

// Fungsi untuk menampilkan/menyembunyikan dropdown daftar mentor
function toggleOptionsSchoolPartner(event, index) {
    event.stopPropagation(); // cegah klik event bubble ke elemen lain
    const dropdown = document.getElementById(`dropdownOptions_${index}_school-partner`);
    if (!dropdown) return;

    // Tutup semua dropdown lain sebelum membuka dropdown yang baru
    document.querySelectorAll('[id^="dropdownOptions_"]').forEach(opt => {
        if (opt !== dropdown) opt.classList.add('hidden');
    });

    // Toggle dropdown yang diklik (tampilkan atau sembunyikan)
    dropdown.classList.toggle('hidden');

    // Jika klik di luar dropdown → otomatis tutup dropdown
    document.addEventListener('click', function (e) {
        const dropdown = document.querySelector(`.dropdown-checkbox_${index}_school-partner`);
        if (!dropdown.contains(e.target)) {
            document.getElementById(`dropdownOptions_${index}_school-partner`).classList.add('hidden');
        }
    });
}

// Fungsi untuk membatasi jumlah mentor yang bisa dipilih (maksimal 1)
function limitSelectionSchoolPartner(checkbox, index) {
    const MAX_SELECTED = 1; // hanya boleh memilih satu mentor

    // Ambil semua checkbox yang sudah dicentang
    const checked = Array.from(document.querySelectorAll(`#dropdownOptions_${index}_school-partner input[type="checkbox"]:checked`));
    // Ambil semua label (untuk ubah tampilan visual)
    const labels = Array.from(document.querySelectorAll(`#dropdownOptions_${index}_school-partner .label-checkbox`));

    // Update hidden input agar menyimpan ID mentor yang dipilih
    const selectedIds = checked.map(cb => cb.value);
    const hiddenLevelInput = document.getElementById('input-level-id');
    if (hiddenLevelInput) hiddenLevelInput.value = selectedIds.join(',');

    // Jika sudah mencapai batas maksimal pilihan → disable checkbox lainnya
    if (checked.length >= MAX_SELECTED) {
        document.querySelectorAll(`#dropdownOptions_${index}_school-partner input:not(:checked)`).forEach(cb => cb.disabled = true);
        document.querySelectorAll(`#dropdownOptions_${index}_school-partner input[type="search"]`).forEach(cb => cb.disabled = false);
        labels.forEach(label => {
            if (!label.querySelector('input:checked')) {
                label.classList.remove('cursor-pointer'); // hilangkan pointer
                label.classList.add('opacity-50'); // ubah opacity agar terlihat tidak aktif
            }
        });
    } else {
        // Jika belum mencapai batas → aktifkan kembali semua checkbox
        document.querySelectorAll(`#dropdownOptions_${index}_school-partner input`).forEach(cb => cb.disabled = false);
        labels.forEach(label => {
            label.classList.add('cursor-pointer');
            label.classList.remove('opacity-50');
        });
    }

    // Update teks di tombol dropdown agar menampilkan mentor yang dipilih
    updateButtonText();

    function updateButtonText() {
        // Ambil kembali semua mentor yang dicentang
        const checked = document.querySelectorAll(`#dropdownOptions_${index}_school-partner input:checked`);
        // Potong teks sesuai limit karakter
        const limitString = (str, limit) => (str ? (str.length > limit ? str.substring(0, limit) + '...' : str) : '-');
        const values = Array.from(checked).map(cb => cb.nextElementSibling.textContent.trim());
        const limit = getLimitByScreenSchoolPartner();

        // Update teks tombol dropdown (menampilkan nama mentor)
        document.getElementById(`dropdownText_${index}_school-partner`).textContent =
            values.length ? limitString(values.join(', '), limit) : 'Pilih Mentor';
    }

    // Event AJAX: aktifkan/nonaktifkan mentor di server
    $(document).on('change', '.checkbox-activate-mentor', function () {
        let studentBatchIds = $(this).data('student-batch-ids'); // ambil ID student_batch
        let mentorId = $(this).is(':checked') ? $(this).data('mentor-id') : null; // jika dicentang → aktifkan mentor
        let csrf = $('meta[name="csrf-token"]').attr('content'); // ambil token CSRF dari meta tag

        $.ajax({
            url: `/english-zone/management-student-batch/activate-mentor/${studentBatchIds}`, // endpoint Laravel
            type: 'PUT', // metode PUT untuk update data
            data: {
                _token: csrf,
                mentor_id: mentorId
            },
            success: function (response) {
                // console.log(response.message); // bisa ditampilkan jika perlu
            },
            error: function (xhr) {
                alert('Gagal mengubah mengaktifkan mentor.'); // tampilkan alert jika gagal
                // Balikkan status checkbox agar tidak tertukar
                $(this).prop('checked', !$(this).is(':checked'));
            }
        });
    });
}

// Fungsi untuk menerapkan ulang batasan pilihan mentor setelah halaman di-refresh
function applyLimitSelection(index) {
    const MAX_SELECTED = 1; // hanya bisa pilih 1 mentor

    // Ambil semua checkbox yang dicentang
    const checked = Array.from(document.querySelectorAll(`#dropdownOptions_${index}_school-partner input[type="checkbox"]:checked`));
    // Ambil semua label di dalam dropdown
    const labels = Array.from(document.querySelectorAll(`#dropdownOptions_${index}_school-partner .label-checkbox`));

    // Jika sudah mencapai batas maksimal pilihan → disable checkbox lain
    if (checked.length >= MAX_SELECTED) {
        document.querySelectorAll(`#dropdownOptions_${index}_school-partner input:not(:checked)`).forEach(cb => cb.disabled = true);
        document.querySelectorAll(`#dropdownOptions_${index}_school-partner input[type="search"]`).forEach(cb => cb.disabled = false);
        labels.forEach(label => {
            if (!label.querySelector('input:checked')) {
                label.classList.remove('cursor-pointer');
                label.classList.add('opacity-50');
            }
        });
    } else {
        // Jika belum mencapai batas → aktifkan semua checkbox
        document.querySelectorAll(`#dropdownOptions_${index}_school-partner input`).forEach(cb => cb.disabled = false);
        labels.forEach(label => {
            label.classList.add('cursor-pointer');
            label.classList.remove('opacity-50');
        });
    }

    // Update teks pada tombol dropdown agar tetap sesuai pilihan saat reload
    const values = checked.map(cb => cb.nextElementSibling.textContent.trim());
    const limit = getLimitByScreenSchoolPartner();

    // Potong teks sesuai limit dan tampilkan
    document.getElementById(`dropdownText_${index}_school-partner`).textContent =
        values.length ? limitString(values.join(', '), limit) : 'Pilih Mentor';
}

// buat kembalikan header radio nya ketika di back ke student batch view menggunakan arrow back chrome
window.addEventListener("pageshow", function (event) {
    document.getElementById('radio1').checked = true;
});

