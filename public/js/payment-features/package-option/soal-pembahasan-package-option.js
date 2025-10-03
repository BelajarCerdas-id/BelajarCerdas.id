function packageOption(element, id) {
    // tombol beli selalu disable dulu setelah ganti paket
    const btnPurchase = document.querySelector('.pay-button');
    btnPurchase.disabled = false;
    btnPurchase.classList.replace('bg-gray-300', 'bg-[#4189e0]');

    // ambil info harga & id paket
    const price = parseInt(element.getAttribute('data-price'));
    const dataFeatureId = element.getAttribute('data-feature-id');
    const dataFeatureVariantId = element.getAttribute('data-variant-id');
    const dataPrice = element.getAttribute('data-price');

    const formatPrice = price.toLocaleString('id-ID');

    // isi input hidden untuk dikirim ke server
    $('#input-feature-id').val(dataFeatureId);
    $('#input-feature-variant-id').val(dataFeatureVariantId)
    $('#input-price').val(dataPrice);

    // tampilkan harga di UI
    document.getElementById('harga-paket').innerHTML = `Rp.${formatPrice}`;
    document.getElementById('harga-total').innerHTML = `Rp.${formatPrice}`;

    return;
}