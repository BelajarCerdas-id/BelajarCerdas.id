let isProcessing = false;

document.getElementById('btn-beli').addEventListener('click', function () {
    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const featureId = document.getElementById('input-feature-id').value;
    const featureVariantId = document.getElementById('input-feature-variant-id').value;
    const price = document.getElementById('input-price').value;
    const paymentMethodId = document.getElementById('input-payment-method').value;

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const btn = $(this);

    btn.prop('disabled', true); // Disable button UI

    fetch("/checkout-soal-pembahasan", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({
            feature_id: featureId,
            feature_variant_id: featureVariantId,
            price: price,
            payment_method_id: paymentMethodId
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.snap_token) {
            window.snap.pay(data.snap_token, {
                onSuccess: function (result) {
                    location.reload();
                },
                onPending: function (result) {
                    // Bisa diarahkan ke halaman riwayat pembayaran
                    isProcessing = false;
                    btn.prop('disabled', false);
                },
                onError: function (result) {
                    // alert("Pembayaran gagal.");
                    // console.log(result);
                    isProcessing = false;
                    btn.prop('disabled', false);
                },
                onClose: function () {
                    // ✅ Izinkan user mencoba lagi jika dia menutup modal tanpa bayar
                    isProcessing = false;
                    btn.prop('disabled', false);
                }
            });
        } else {
            alert("Gagal mendapatkan snap token.");
            console.error(data);
            isProcessing = false;
            btn.prop('disabled', false);
        }
    })
    .catch(error => {
        alert("Terjadi kesalahan.");
        console.error(error);
        isProcessing = false;
        btn.prop('disabled', false);
    });
});
