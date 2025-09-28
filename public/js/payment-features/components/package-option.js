let dataFeatureId = null; // default, nanti diisi saat pilih paket
let MAX_SELECTED = 0; // default, nanti diisi saat pilih paket

function validatePurchase(featureId) {
    const btnPurchase = document.querySelector('.pay-button');
    let ready = false;

    const mentorId = document.getElementById('mentors_id')?.value || "";
    const batchId = document.getElementById('batch_id')?.value || "";
    const dayId = document.getElementById('days_id')?.value || "";
    const hourId = document.getElementById('hours_id')?.value || "";
    const levelId = document.getElementById('input-level-id')?.value || "";

    const selectedLevels = levelId ? levelId.split(',').filter(l => l.trim() !== "") : [];

    if (featureId == 3) {
        // ✅ Harus semua dropdown diisi dan jumlah level sesuai MAX_SELECTED
        if (mentorId && batchId && dayId && hourId && selectedLevels.length === MAX_SELECTED) {
            ready = true;
        }
    } else if (featureId && featureId != 3) {
        ready = true;
    }

    if (ready) {
        btnPurchase.disabled = false;
        btnPurchase.classList.replace('bg-gray-300', 'bg-[#4189e0]');
    } else {
        btnPurchase.disabled = true;
        btnPurchase.classList.replace('bg-[#4189e0]', 'bg-gray-300');
    }
}


function packageOption(element, id) {
    const dropdownButton = document.getElementById('dropdownButton');

    // 🔹 Enable dropdown button ketika function package option dipanggil
    dropdownButton.disabled = false;
    dropdownButton.classList.replace('opacity-50', 'opacity-100');

    let index = element.getAttribute('data-index');

    if (index == 1) {
        MAX_SELECTED = 1;
    } else if (index == 2) {
        MAX_SELECTED = 2;
    } else {
        MAX_SELECTED = 3;
    }

    // 🔹 Reset semua input checked ketika pindah package option
    document.querySelectorAll('#dropdownOptions input').forEach(cb => {
        cb.checked = false;
        cb.disabled = false;
    })

    // 🔹 Reset semua label ketika pindah package option
    document.querySelectorAll('.label-checkbox').forEach(label => {
        label.classList.add('cursor-pointer');
        label.classList.remove('opacity-50');
    });

    // 🔹 Reset teks tombol dropdown ketika pindah package option
    if (index == 1) {
        document.getElementById('dropdownText').textContent = 'Choose one level';
    } else if (index == 2) {
        document.getElementById('dropdownText').textContent = 'Choose two levels';
    } else {
        document.getElementById('dropdownText').textContent = 'Choose three levels';
    }

    // 🔹 Reset value level ketika pindah package option
    $('#input-level-id').val('');

    const btnPurchase = document.querySelector('.pay-button');
    // 🔹 Pastikan tombol beli disable dulu
    btnPurchase.disabled = true;
    btnPurchase.classList.replace('bg-[#4189e0]', 'bg-gray-300');

    const price = parseInt(element.getAttribute('data-price')); // ambil harga paket
    dataFeatureId = element.getAttribute('data-feature-id'); // ambil feature id
    const dataFeatureVariantId = element.getAttribute('data-variant-id'); // ambil variant id
    const dataPrice = element.getAttribute('data-price'); // harga mentah
    const dataQuantity = element.getAttribute('data-quantity'); // kuantitas

    const formatPrice = price.toLocaleString('id-ID'); // format harga ke Rupiah

    // isi input hidden dengan data paket yang dipilih
    document.getElementById('input-feature-id').value = dataFeatureId;
    document.getElementById('input-feature-variant-id').value = dataFeatureVariantId;
    document.getElementById('input-price').value = dataPrice;
    document.getElementById('input-quantity').value = dataQuantity;

    // tampilkan harga ke UI
    document.getElementById('harga-paket').innerHTML = `Rp.${formatPrice}`;
    document.getElementById('harga-total').innerHTML = `Rp.${formatPrice}`;

    // cek apakah tombol bisa diaktifkan setelah pilih paket
    validatePurchase(dataFeatureId);

    return;
}

// kalau pilih paket
document.querySelectorAll('[name="feature_id"]').forEach(radio => {
    radio.addEventListener('change', e => {
        dataFeatureId = e.target.value;
        validatePurchase(dataFeatureId);
    });
});

// kalau pilih dropdown lain (mentor, batch, hari, jam, level)
['mentors_id', 'batch_id', 'days_id', 'hours_id', 'level_id'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('change', () => {
            validatePurchase(dataFeatureId);
        });
    }
});
