let dataFeatureId = null; // default, nanti diisi saat pilih paket
let MAX_SELECTED = 0; // default, nanti diisi saat pilih paket

function validatePurchase(featureId) {
    const btnPurchase = document.querySelector('.pay-button');
    let ready = false;

    // ambil value dari semua dropdown + hidden input level
    const batchId = document.getElementById('batch_id')?.value || "";
    const dayId = document.getElementById('days_id')?.value || "";
    const hourId = document.getElementById('hours_id')?.value || "";
    const levelId = document.getElementById('input-level-id')?.value || "";
    const batchScheduleId = document.getElementById('input-batch-schedule-id')?.value || "";

    // split levelId jadi array level yang dipilih
    const selectedLevels = levelId ? levelId.split(',').filter(l => l.trim() !== "") : [];

    // jika featureId 3 (English Zone), semua dropdown harus diisi + jumlah level sesuai MAX_SELECTED
    if (batchId && dayId && hourId && batchScheduleId && selectedLevels.length === MAX_SELECTED) {
        ready = true;
    }

    // update tombol beli sesuai kondisi "ready"
    if (ready) {
        btnPurchase.disabled = false; // aktifkan tombol
        btnPurchase.classList.replace('bg-gray-300', 'bg-[#4189e0]'); // ganti warna ke biru
    } else {
        btnPurchase.disabled = true; // disable tombol
        btnPurchase.classList.replace('bg-[#4189e0]', 'bg-gray-300'); // ganti warna ke abu
    }
}


function packageOption(element, id) {
    // reset masa aktif
    $('#masa-aktif').text('-');

    // enable tombol dropdown level ketika paket dipilih
    const dropdownButton = document.getElementById('dropdownButton');
    dropdownButton.disabled = false;
    dropdownButton.classList.replace('opacity-50', 'opacity-100');

    // ambil index paket untuk tentukan jumlah level yang boleh dipilih
    let index = element.getAttribute('data-index');

    if (index == 1) {
        $('#batch_id').empty().append('<option value="" class="hidden">Choose Batch</option>').prop('disabled', true).addClass('opacity-50 !cursor-default');
        MAX_SELECTED = 1;
    } else if (index == 2) {
        MAX_SELECTED = 2;
    } else if (index == 3) {
        MAX_SELECTED = 3;
    } else {
        MAX_SELECTED = 4;
    }

    // reset semua input level (checkbox)
    document.querySelectorAll('#dropdownOptions input[type="checkbox"]').forEach(cb => {
        const clone = cb.cloneNode(true); // clone biar event listener lama hilang
        cb.parentNode.replaceChild(clone, cb);
    });

    // re-query ulang checkbox dan labelnya
    const levels = Array.from(document.querySelectorAll('#dropdownOptions input[type="checkbox"]'));
    const labels = Array.from(document.querySelectorAll('#dropdownOptions .label-checkbox'));

    // reset state visual tiap level
    levels.forEach((cb, i) => {
        cb.checked = false;
        cb.disabled = false;
        if (labels[i]) {
            labels[i].classList.remove('!cursor-default', 'opacity-50');
            labels[i].classList.add('cursor-pointer');
            labels[i].style.pointerEvents = 'auto';
        }
    });

    // ubah teks dropdown sesuai paket
    if (index == 1) {
        document.getElementById('dropdownText').textContent = 'Choose one level';
    } else if (index == 2) {
        document.getElementById('dropdownText').textContent = 'Choose two levels';
    } else if (index == 3) {
        document.getElementById('dropdownText').textContent = 'Choose three levels';
    } else {
        document.getElementById('dropdownText').textContent = 'Choose four levels';
    }

    // reset hidden input (level, batch, day, hour)
    $('#input-level-id').val('');
    $('#input-batch-id').val('');
    $('#input-batch-schedule-group').val('');
    $('#input-batch-schedule-id').val('');

    // reset dropdown day & hour
    const days_id = document.getElementById('days_id');
    const hours_id = document.getElementById('hours_id');
    days_id.classList.replace('cursor-pointer', '!cursor-default');
    hours_id.classList.replace('cursor-pointer', '!cursor-default');
    $('#days_id').empty().append('<option value="">Choose Day</option>').prop('disabled', true);
    $('#hours_id').empty().append('<option value="">Choose Hour</option>').prop('disabled', true);

    // jika paket index=2, auto pilih 2 level pertama
    if (index == 2) {
        $('#input-level-id').val(levels[0].value + ',' + levels[1].value);

        if (levels.length >= 2) {
            // lock level 1
            levels[0].checked = true;
            levels[0].addEventListener('click', e => e.preventDefault());
            labels[0].classList.replace('cursor-pointer', '!cursor-default');

            // lock level 2
            levels[1].checked = true;
            levels[1].addEventListener('click', e => e.preventDefault());
            labels[1].classList.replace('cursor-pointer', '!cursor-default');

            // disable level 3
            levels[2].disabled = true;
            levels[2].checked = false;
            labels[2].classList.replace('cursor-pointer', '!cursor-default');

            // disable level 4
            levels[3].disabled = true;
            levels[3].checked = false;
            labels[3].classList.replace('cursor-pointer', '!cursor-default');
        }
        updateButtonText();
    }
    // jika paket index=3, auto pilih semua level
    else if (index == 3) {
        $('#input-level-id').val(levels[0].value + ',' + levels[1].value + ',' + levels[2].value);

        if (levels.length >= 3) {
            // lock level 1
            levels[0].checked = true;
            levels[0].addEventListener('click', e => e.preventDefault());
            labels[0].classList.replace('cursor-pointer', '!cursor-default');

            // lock level 2
            levels[1].checked = true;
            levels[1].addEventListener('click', e => e.preventDefault());
            labels[1].classList.replace('cursor-pointer', '!cursor-default');

            // lock level 3
            levels[2].checked = true;
            levels[2].addEventListener('click', e => e.preventDefault());
            labels[2].classList.replace('cursor-pointer', '!cursor-default');

            // disable level 4
            levels[3].disabled = true;
            levels[3].checked = false;
            labels[3].classList.replace('cursor-pointer', '!cursor-default');
        }
        updateButtonText();
    } else if (index == 4) {
        $('#input-level-id').val(levels[0].value + ',' + levels[1].value + ',' + levels[2].value + ',' + levels[3].value);

        if (levels.length >= 4) {
            // lock level 1
            levels[0].checked = true;
            levels[0].addEventListener('click', e => e.preventDefault());
            labels[0].classList.replace('cursor-pointer', '!cursor-default');
    
            // lock level 2
            levels[1].checked = true;
            levels[1].addEventListener('click', e => e.preventDefault());
            labels[1].classList.replace('cursor-pointer', '!cursor-default');
    
            // lock level 3
            levels[2].checked = true;
            levels[2].addEventListener('click', e => e.preventDefault());
            labels[2].classList.replace('cursor-pointer', '!cursor-default');
    
            // lock level 4
            levels[3].checked = true;
            levels[3].addEventListener('click', e => e.preventDefault());
            labels[3].classList.replace('cursor-pointer', '!cursor-default');
        }
        updateButtonText();
    }

    // tombol beli selalu disable dulu setelah ganti paket
    const btnPurchase = document.querySelector('.pay-button');
    btnPurchase.disabled = true;
    btnPurchase.classList.replace('bg-[#4189e0]', 'bg-gray-300');

    // ambil info harga & id paket
    const price = parseInt(element.getAttribute('data-price'));
    dataFeatureId = element.getAttribute('data-feature-id');
    const dataFeatureVariantId = element.getAttribute('data-variant-id');
    const dataPrice = element.getAttribute('data-price');

    const formatPrice = price.toLocaleString('id-ID');

    // isi input hidden untuk dikirim ke server
    $('#input-feature-id').val(dataFeatureId);
    $('#input-feature-variant-id').val(dataFeatureVariantId).trigger('change'); // trigger agar batch refresh
    $('#input-price').val(dataPrice);

    // tampilkan harga di UI
    document.getElementById('harga-paket').innerHTML = `Rp.${formatPrice}`;
    document.getElementById('harga-total').innerHTML = `Rp.${formatPrice}`;

    // panggil validasi (supaya tombol aktif kalau semua dropdown terisi)
    validatePurchase(dataFeatureId);
    document.querySelectorAll('#dropdownOptions input[type="checkbox"]').forEach(cb => limitSelection(cb));

    return;
}


// event listener untuk radio paket
document.querySelectorAll('[name="feature_id"]').forEach(radio => {
    radio.addEventListener('change', e => {
        dataFeatureId = e.target.value;
        validatePurchase(dataFeatureId); // validasi ulang kalau ganti paket
    });
});

// pasang event listener ke semua field wajib
const requiredFields = ['input-level-id', 'batch_id', 'days_id', 'hours_id'];

requiredFields.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('change', () => {
            validatePurchase(dataFeatureId); // setiap field berubah → validasi ulang
        });
    }
});
