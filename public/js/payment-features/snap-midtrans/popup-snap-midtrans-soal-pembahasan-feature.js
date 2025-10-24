let isProcessing = false;

document.getElementById('btn-beli').addEventListener('click', function () {
    if (isProcessing) return; // ❌ Abaikan jika sedang proses

    isProcessing = true; // ✅ Tandai sedang diproses

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const btn = $(this);

    const form = $('#form-pembelian')[0]; // ambil DOM Form-nya
    const formData = new FormData(form);

    btn.prop('disabled', true); // Disable button UI

    fetch("/checkout-soal-pembahasan", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': csrf
        },
        body: formData, // kalau pakai fetch (bukan ajax) maka gunakan body: formData (bukan data: formData)
    })
    .then(async res => {
        const data = await res.json();

        if (!res.ok) {
            throw {
                status: res.status,
                message: data.message
            };
        }
        return data;
    })
    .then(data => {
        if (data.snap_token) {
            window.snap.pay(data.snap_token, {
                onSuccess: function (result) {
                    location.reload();
                    alertPaymentSuccess();
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
            isProcessing = false;
            btn.prop('disabled', false);
        }
    })
    .catch(error => {
        if (error.status === 422) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message,
            });
        } else {
            alert("Terjadi kesalahan.");
        }
            isProcessing = false;
            btn.prop('disabled', false);
        });
});