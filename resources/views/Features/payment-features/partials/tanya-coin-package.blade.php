<x-navbar></x-navbar>

<main>
    <section>
        <div class="flex flex-col lg:flex-row justify-center gap-10 mx-10 lg:mx-4 h-full">
            <!--- pilihhan paket koin tanya ----->
            <div>
                <h2 class="font-bold text-lg">Pilih Paket Koin Kamu</h2>
                @foreach ($dataFeaturesPrices as $item)
                    @if ($item->variant_name != 'Koin Satuan')
                        <label class="block cursor-pointer w-full lg:w-max h-max my-6"
                            onclick="coinOption(this, {{ $item->id }})" data-feature-id="{{ $item->feature_id }}"
                            data-variant-id="{{ $item->id }}" data-price="{{ $item->price }}"
                            data-quantity="{{ $item->quantity }}">
                            <input type="radio" name="radio1" id="paket-{{ $item->id }}"
                                value="{{ $item->id }}" class="hidden peer">
                            <div
                                class="w-full lg:w-[500px] h-[70px] border rounded-lg flex items-center justify-between px-4
                                peer-checked:border-2 peer-checked:border-[#4189e0] transition">
                                <span>Paket {{ $item->quantity }} Koin</span>
                                <span>Rp. {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                        </label>
                    @endif
                    @if ($item->variant_name === 'Koin Satuan')
                        <div class="w-full flex-flex-col mb-2">
                            <label class="font-bold text-md">Beli Koin Satuan</label>
                            <input type="number" id="koin-satuan"
                                class="w-full bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] text-sm mt-2"
                                placeholder="Masukkan Jumlah Koin" oninput="koinSatuan(this)"
                                onclick="resetCoinOption()" data-feature-id="{{ $item->feature_id }}"
                                data-variant-id="{{ $item->id }}" data-price="{{ $item->price }}">
                        </div>
                        <span id="error-message" class="text-red-500 text-sm mt-4"></span>
                    @endif
                @endforeach
            </div>

            <!--- pemilihan payment method koin tanya ----->
            <div
                class="w-full lg:w-[450px] h-[420px] lg:h-[500px] rounded-lg flex flex-col px-4 py-4 justify-between bg-white shadow-lg border">
                <div>
                    <span class="text-sm font-medium mb-2 block">Metode Pembayaran</span>

                    <div class="relative selected-payment">
                        {{-- Konten default yang akan diubah via JS --}}
                        <div class="border-2 border-gray-300 w-full h-[69px] rounded-lg flex justify-between items-center px-4 cursor-pointer"
                            onclick="my_modal_2.showModal()">
                            <div class="flex gap-2 items-center">
                                <div>
                                    <img src="{{ asset($paymentMethods[0]['logo-payment']) }}" alt=""
                                        class="w-[55px]">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-md">{{ $paymentMethods[0]['tipe_payment'] }}</span>
                                    <span class="text-sm font-bold">{{ $paymentMethods[0]['name'] }}</span>
                                </div>
                            </div>
                            <i class="fa solid fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-[5px]">
                        <h4 class="font-semibold text-sm mb-2">Detail pembelian :</h4>
                        <div class="flex justify-between text-sm mb-1">
                            <span>Harga Paket</span>
                            <span id="harga-paket" class="max-w-60 overflow-hidden">-</span>
                        </div>
                        <div class="border-b"></div>
                        <div class="flex justify-between text-sm">
                            <span>Harga Total</span>
                            <span id="harga-total" class="max-w-60 overflow-hidden">-</span>
                        </div>
                    </div>
                </div>

                <form id="form-pembelian" method="POST" action="{{ route('checkout') }}">
                    @csrf
                    <input type="hidden" name="payment_method_id" id="input-payment-method"
                        value="{{ $paymentMethods[0]['name'] }}">
                    <input type="hidden" id="input-feature-id" name="feature_id">
                    <input type="hidden" id="input-feature-variant-id" name="feature_variant_id">
                    <input type="hidden" id="input-quantity" name="jumlah_koin">
                    <input type="hidden" id="input-price" name="price">
                    @if (Auth::user() === null)
                        <button type="button" onclick="alertLogin()"
                            class="pay-button bg-gray-300 text-white rounded-full py-2 font-semibold text-sm w-full mt-4"
                            disabled>
                            Beli Sekarang
                        </button>
                    @else
                        <button id="btn-beli" type="button"
                            class="pay-button bg-gray-300 text-white rounded-full py-2 font-semibold text-sm w-full mt-4"
                            disabled>
                            Beli Sekarang
                        </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- Modal for displaying payment method -->
        <dialog id="my_modal_2" class="modal">
            <div class="modal-box !bg-white !w-[90%] md:w-full">
                <div class="container-accordion-payment">
                    <span class="header-popup flex justify-center !font-bold text-lg mb-6">
                        Pilih Metode Pembayaran
                    </span>
                    @foreach ($groupedPaymentMethods as $tipe => $items)
                        <div class="content-method-payment">
                            <header>
                                <span class="title-method">{{ $tipe }}</span>
                                <i class="fa-solid fa-plus"></i>
                            </header>

                            <div class="choose-payment">
                                @foreach ($items as $item)
                                    <input type="radio" id="bank-{{ $item['id'] }}" name="radio" class="hidden"
                                        @if ($item['id'] == 1) checked @endif onclick="selectPayment(this)"
                                        data-name="{{ $item['name'] }}" data-logo="{{ asset($item['logo-payment']) }}"
                                        data-id="{{ $item['id'] }}">
                                    <div class="content-menu">
                                        <label for="bank-{{ $item['id'] }}">
                                            <div class="logo-payment-card">
                                                <img src="{{ $item['logo-payment'] }}" alt="" class="w-[50px]">
                                                <span>{{ $item['name'] }}</span>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </section>
</main>


<script>
    function alertLogin() {
        swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Harap login terlebih dahulu untuk membeli paket ini!",
        });
    }
</script>


<script>
    let isProcessing = false;
    document.getElementById('btn-beli').addEventListener('click', function() {
        if (isProcessing) return; // ❌ Abaikan jika sedang proses

        isProcessing = true; // ✅ Tandai sedang diproses
        const featureId = document.getElementById('input-feature-id').value;
        const featureVariantId = document.getElementById('input-feature-variant-id').value;
        const jumlahKoin = document.getElementById('input-quantity').value;
        const price = document.getElementById('input-price').value;
        const paymentMethodId = document.getElementById('input-payment-method').value;

        const btn = $(this);

        btn.prop('disabled', true); // Disable button UI

        fetch("{{ route('checkout') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    feature_id: featureId,
                    feature_variant_id: featureVariantId,
                    jumlah_koin: jumlahKoin,
                    price: price,
                    payment_method_id: paymentMethodId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            // alert("Pembayaran berhasil!");
                            // console.log(result);
                            location.reload();
                        },
                        onPending: function(result) {
                            // alert("Menunggu pembayaran.");
                            // console.log(result);
                            isProcessing = false;
                            btn.prop('disabled', false);
                        },
                        onError: function(result) {
                            // alert("Pembayaran gagal.");
                            // console.log(result);
                            isProcessing = false;
                            btn.prop('disabled', false);
                        },
                        onClose: function() {
                            // ✅ Izinkan user mencoba lagi jika dia menutup modal tanpa bayar
                            isProcessing = false;
                            btn.prop('disabled', false);
                        }
                    });
                } else {
                    alert("Gagal mendapatkan snap token.");
                    // console.error(data);
                    isProcessing = false;
                    btn.prop('disabled', false);
                }
            })
            .catch(error => {
                alert("Terjadi kesalahan.");
                // console.error(error);
                isProcessing = false;
                btn.prop('disabled', false);
            });
    });
</script>


<script>
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

        document.getElementById('harga-paket').innerHTML = '-';
        document.getElementById('harga-total').innerHTML = '-';
    }
</script>

<script>
    function selectPayment(radio) {
        const logo = radio.dataset.logo;
        const name = radio.dataset.name;
        const id = radio.dataset.id;
        const type = radio.closest('.content-method-payment').querySelector('.title-method').innerText;

        document.getElementById('input-payment-method').value = name;
        // Update elemen utama
        const container = document.querySelector('.selected-payment');
        container.innerHTML = `
            <div class="border-2 border-gray-300 w-full h-[69px] rounded-lg flex justify-between items-center px-4 cursor-pointer"
                onclick="my_modal_2.showModal()">
                <div class="flex gap-2 items-center">
                    <div>
                        <img src="${logo}" alt="${name}" class="w-[55px]">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-md">${type}</span>
                        <span class="text-sm font-bold">${name}</span>
                    </div>
                </div>
                <i class="fa solid fa-chevron-down"></i>
            </div>
        `;

        // Tutup modal setelah pilih
        document.getElementById('my_modal_2').close();
    }
</script>

<script>
    const paymentContent = document.querySelectorAll(".content-method-payment");

    paymentContent.forEach((item, index) => {
        let header = item.querySelector("header");
        header.addEventListener("click", () => {
            item.classList.toggle("open");

            let choosePayment = item.querySelector(".choose-payment");
            if (item.classList.contains("open")) {
                choosePayment.style.height =
                    `${choosePayment.scrollHeight}px`; // scrollHeight prperty returns the height of an element including padding, but excluding borders, scrollbar or margin.
                item.querySelector("i").classList.replace("fa-plus", "fa-minus");
            } else {
                choosePayment.style.height = "0px";
                item.querySelector("i").classList.replace("fa-minus", "fa-plus");
            }
            removeOpen(
                index); // calling the function and also passing the index number of the clicked header
        })

    })

    function removeOpen(index1) {
        paymentContent.forEach((item2, index2) => {
            if (index1 != index2) {
                item2.classList.remove("open");

                let choosePay = item2.querySelector(".choose-payment");
                choosePay.style.height = "0px";
                item2.querySelector("i").classList.replace("fa-minus", "fa-plus");
            }
        })
    }
</script>


<script>
    var a = document.getElementById('paket1'); // paket 5 coin
    var b = document.getElementById('paket2'); // paket 25 coin
    var c = document.getElementById('paket3'); // paket 100 coin
    var d = document.getElementById('paket'); // non paket

    function paket1() {
        a.style.left = "-1px";
        b.style.right = "-400px";
        c.style.left = "-400px";
        d.style.right = "-400px";
    }

    function paket2() {
        a.style.left = "-400px";
        b.style.right = "-1px";
        c.style.left = "-400px";
        d.style.right = "-400px";
    }

    function paket3() {
        a.style.left = "-400px";
        b.style.right = "-400px";
        c.style.left = "-1px";
        d.style.right = "-400px";
    }
</script>

<script>
    var rad = document.querySelectorAll(".radio")


    rad.forEach((item) => {
        item.addEventListener("change", () => {
            input.innerHTML = item.nextElementSibling.innerHTML;
            input.click();
        });
    });
</script>
