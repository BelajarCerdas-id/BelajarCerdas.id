function toggleOptions(event) {
    event.stopPropagation();
    const options = document.getElementById('dropdownOptions');
    options.classList.toggle('hidden');
}

function enableBatches() {
    const enabledBatches = document.getElementById('batch_id');
    enabledBatches.disabled = false;
    enabledBatches.classList.replace('opacity-50', 'opacity-100');
    enabledBatches.classList.replace('!cursor-default', 'cursor-pointer');
}

function updateButtonText() {
    enableBatches(); // enable batch dropdown

    // Ambil semua checkbox yang ada di dropdown (level) dan sedang dicentang
    const checked = document.querySelectorAll('#dropdownOptions input:checked');

    // Dari semua checkbox yang dicentang → ambil teks (nama level) setelah input
    const values = Array.from(checked).map(cb => cb.nextElementSibling.textContent.trim());

    // Cari paket (radio button) yang sedang dipilih user
    const selectedPackage = document.querySelector('.package-option input[type="radio"]:checked');

    let index = null
    if (selectedPackage) {
        // Ambil atribut data-index dari paket terpilih
        // data-index ini ditentukan saat looping foreach ($dataFeaturesPrices as $index => $item)
        index = selectedPackage.closest('.package-option').getAttribute('data-index');
    }

    // Kalau paket index = 1 → berarti langganan 4 bulan (misalnya)
    // Tampilkan teks level yang dipilih, kalau kosong fallback ke "Choose level"
    if (index == 1) {
        document.getElementById('dropdownText').textContent = values.length ? values.join(', ') :
            'Choose one level';

        // Kalau paket index = 2 → berarti langganan 8 bulan
        // Fallback text = "Choose two levels"
    } else if (index == 2) {
        document.getElementById('dropdownText').textContent = values.length ? values.join(', ') :
            'Choose two levels';

        // Kalau paket index lain (anggap 3) → berarti langganan 12 bulan
        // Fallback text = "Choose three levels"
    } else {
        document.getElementById('dropdownText').textContent = values.length ? values.join(', ') :
            'Choose three levels';
    }
}



function limitSelection(checkbox) {
    updateButtonText();


    const checked = Array.from(document.querySelectorAll('#dropdownOptions input[type="checkbox"]:checked'));
    const labels = Array.from(document.querySelectorAll('#dropdownOptions .label-checkbox'));

    // update hidden input agar konsisten
    const selectedIds = checked.map(cb => cb.value);
    const hiddenLevelInput = document.getElementById('input-level-id');
    if (hiddenLevelInput) {
        hiddenLevelInput.value = selectedIds.join(',');
    }

    if (selectedIds.length === 0) {
        $('#batch_id').empty().append('<option value="" class="hidden">Choose Batch</option>').prop('disabled', true).addClass('opacity-50 !cursor-default');
        $('#days_id').empty().append('<option value="" class="hidden">Choose Day</option>').prop('disabled', true).addClass('opacity-50 !cursor-default');
        $('#hours_id').empty().append('<option value="" class="hidden">Choose Hour</option>').prop('disabled', true).addClass('opacity-50 !cursor-default');

        $('#masa-aktif').text('-');
        $('#input-batch-id').val('');
        $('#input-batch-schedule-group').val('');
        $('#input-batch-schedule-id').val('');

        resetBatchDropdown();
    }

    if (checked.length >= MAX_SELECTED) {
        document.querySelectorAll('#dropdownOptions input:not(:checked)').forEach(cb => cb.disabled = true);
        labels.forEach(label => {
            if (!label.querySelector('input:checked')) {
                label.classList.remove('cursor-pointer');
                label.classList.add('opacity-50');
            }
        });
    } else {
        document.querySelectorAll('#dropdownOptions input').forEach(cb => cb.disabled = false);
        labels.forEach(label => {
            label.classList.add('cursor-pointer');
            label.classList.remove('opacity-50');
        });
    }

    // gunakan nilai dari hidden input sebagai source-of-truth untuk feature id
    validatePurchase(document.getElementById('input-feature-id')?.value || dataFeatureId);
}

document.addEventListener('click', function (e) {
    const dropdown = document.querySelector('.dropdown-checkbox');
    if (!dropdown.contains(e.target)) {
        document.getElementById('dropdownOptions').classList.add('hidden');
    }
});