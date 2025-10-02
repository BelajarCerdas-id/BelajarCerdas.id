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
                                class="w-full bg-white shadow-lg h-12 border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] text-sm mt-2"
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

                <form id="form-pembelian" method="POST" action="{{ route('checkout.tanya') }}">
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

<script src="{{ asset('js/payment-features/snap-midtrans/popup-snap-midtrans-tanya-feature.js') }}"></script> <!--- untuk menampilkan popup pembayaran menggunakan snap midtrans ---->
<script src="{{ asset('js/payment-features/package-option/tanya-package-option.js') }}"></script> <!--- untuk menampilkan opsi paket yang tersedia pada suatu fitur seperti harga, dll ---->

<!----- COMPONENTS ----->
<script src="{{ asset('js/payment-features/components/payment-method.js') }}"></script> <!--- untuk menampilkan metode pembayaran apa saja yang tersedia menggunakan popup  ---->
<script src="{{ asset('js/payment-features/components/open-close-dropdown-payment-method.js') }}"></script> <!--- untuk menampilkan dan menutup popup metode pembayaran setelah memilih ---->

<script>
    function alertLogin() {
        swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Harap login terlebih dahulu untuk membeli paket ini!",
        });
    }
</script>
