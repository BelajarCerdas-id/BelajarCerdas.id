let isProcessing = false;
document.getElementById('btn-beli').addEventListener('click', function () {
    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const btn = $(this);

    const form = $('#form-pembelian')[0]; // ambil DOM Form-nya
    const formData = new FormData(form);

    btn.prop('disabled', true); // Disable button UI

    fetch("/checkout-tanya", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrf
        },
        body: formData, // kalau pakai fetch (bukan ajax) maka gunakan body: formData (bukan data: formData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function (result) {
                        location.reload();
                    },
                    onPending: function (result) {
                        isProcessing = false;
                        btn.prop('disabled', false);
                    },
                    onError: function (result) {
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
                isProcessing = false;
                btn.prop('disabled', false);
            }
        })
        .catch(error => {
            alert("Terjadi kesalahan.");
            isProcessing = false;
            btn.prop('disabled', false);
        });
});