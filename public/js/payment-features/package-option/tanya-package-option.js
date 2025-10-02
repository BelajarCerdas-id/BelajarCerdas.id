function coinOption(element, id) {
    const btnPurchase = document.querySelector('.pay-button');
    const price = parseInt(element.getAttribute('data-price'));
    const dataFeatureId = element.getAttribute('data-feature-id');
    const dataFeatureVariantId = element.getAttribute('data-variant-id');
    const dataPrice = element.getAttribute('data-price');
    const dataQuantity = element.getAttribute('data-quantity');

    const inputKoin = document.getElementById('koin-satuan');
    const errorMessage = document.getElementById('error-message');

    inputKoin.value = '';
    inputKoin.classList.remove('border-red-500');
    errorMessage.innerHTML = '';

    const formatPrice = price.toLocaleString('id-ID');

    document.getElementById('input-feature-id').value = dataFeatureId;
    document.getElementById('input-feature-variant-id').value = dataFeatureVariantId;
    document.getElementById('input-price').value = dataPrice;
    document.getElementById('input-quantity').value = dataQuantity;

    document.getElementById('harga-paket').innerHTML = `Rp.${formatPrice}`;
    document.getElementById('harga-total').innerHTML = `Rp.${formatPrice}`;

    btnPurchase.disabled = false;
    btnPurchase.classList.replace('bg-gray-300', 'bg-[#4189e0]');

    return;
}

function koinSatuan(element) {
    const inputKoin = document.getElementById('koin-satuan');
    const priceCoin = element.getAttribute('data-price');
    const dataFeatureId = element.getAttribute('data-feature-id');
    const dataFeatureVariantId = element.getAttribute('data-variant-id');
    const value = parseInt(inputKoin.value);

    const priceSatuan = value * priceCoin;

    const formatPrice = priceSatuan.toLocaleString('id-ID');

    // mengecek apakah value lebih kecil atau sama dengan 0
    if (!value || value <= 0) {
        const btnPurchase = document.querySelector('.pay-button');
        const errorMessage = document.getElementById('error-message');
        const inputKoin = document.getElementById('koin-satuan');

        document.getElementById('harga-paket').innerHTML = '';
        document.getElementById('harga-total').innerHTML = '';

        // reset input value
        $('#input-feature-id').val('');
        $('#input-feature-variant-id').val('');
        $('#input-price').val('');
        $('#input-quantity').val('');

        btnPurchase.disabled = true;
        btnPurchase.classList.replace('bg-[#4189e0]', 'bg-gray-300');

        inputKoin.classList.remove('border-red-500');
        errorMessage.innerHTML = '';
        return;
        // mengecek apakah value lebih besar dari 1000
    } else if (value > 1000) {
        const btnPurchase = document.querySelector('.pay-button');
        const errorMessage = document.getElementById('error-message');
        const inputKoin = document.getElementById('koin-satuan');

        document.getElementById('harga-paket').innerHTML = `-`;
        document.getElementById('harga-total').innerHTML = `-`;

        // reset input value
        $('#input-feature-id').val('');
        $('#input-feature-variant-id').val('');
        $('#input-price').val('');
        $('#input-quantity').val('');

        inputKoin.classList.add('border-red-500');

        btnPurchase.disabled = true;
        btnPurchase.classList.replace('bg-[#4189e0]', 'bg-gray-300');

        errorMessage.innerHTML = 'Jumlah koin tidak boleh lebih dari 1000';
        return;

        // mengecek apakah value kurang dari 1000
    } else {
        const errorMessage = document.getElementById('error-message');
        const inputKoin = document.getElementById('koin-satuan');

        inputKoin.classList.remove('border-red-500');
        errorMessage.innerHTML = '';
    }

    document.getElementById('input-feature-id').value = dataFeatureId;
    document.getElementById('input-feature-variant-id').value = dataFeatureVariantId;
    document.getElementById('input-price').value = priceSatuan;
    document.getElementById('input-quantity').value = value;
    document.getElementById('harga-paket').innerHTML = `Rp.${formatPrice}`;
    document.getElementById('harga-total').innerHTML = `Rp.${formatPrice}`;

    const btnPurchase = document.querySelector('.pay-button');
    btnPurchase.disabled = false;
    btnPurchase.classList.replace('bg-gray-300', 'bg-[#4189e0]');

}

function resetCoinOption() {
    // Reset radio dari coinOption
    document.querySelectorAll('input[name="radio1"]').forEach(r => r.checked = false);

    // rest harga paket & harga total
    document.getElementById('harga-paket').innerHTML = '-';
    document.getElementById('harga-total').innerHTML = '-';

    // reset input value
    $('#input-feature-id').val('');
    $('#input-feature-variant-id').val('');
    $('#input-price').val('');
    $('#input-quantity').val('');

    // reset button
    const btnPurchase = document.querySelector('.pay-button');
    btnPurchase.disabled = true;
    btnPurchase.classList.replace('bg-[#4189e0]', 'bg-gray-300');
}