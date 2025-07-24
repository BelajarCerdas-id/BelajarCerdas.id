    function packageOption(element, id) {
        const btnPurchase = document.querySelector('.pay-button');
        const price = parseInt(element.getAttribute('data-price'));
        const dataFeatureId = element.getAttribute('data-feature-id');
        const dataFeatureVariantId = element.getAttribute('data-variant-id');
        const dataPrice = element.getAttribute('data-price');
        const dataQuantity = element.getAttribute('data-quantity');

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
