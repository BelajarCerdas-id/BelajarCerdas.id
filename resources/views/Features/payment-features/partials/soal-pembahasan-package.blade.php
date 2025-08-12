<x-navbar></x-navbar>

<main>
    <!--- alert ketika berhasil melakukan pembayaran yang sebelumnya berstatus menunggu --->
    <div id="alert-payment-success"></div>

    <section class="mx-4 lg:mx-10">
        <!--- left side --->
        <div class="flex flex-col lg:flex-row justify-center lg:gap-20 w-full">
            <!--- pilihhan paket koin tanya ----->
            <div class="w-full lg:w-[700px] flex flex-col gap-6">
                <div>
                    <h2 class="font-bold opacity-70 text-lg">Soal dan Pembahasan</h2>
                    <span class="text-justify leading-6">
                        Fitur ini digunakan untuk mempelajari Soal Latihan dengan Video Pembahasan berdasarkan Sub-bab
                        dari tiap-tiap mata pelajaran sesuai fase yang terdaftar. Kamu juga dapat menguji pemahaman
                        materi
                        melalui Soal Ujian yang dilengkapi juga dengan Pembahasan.
                        Kamu bisa belajar materi secara berulang-ulang hingga paham secara mandiri.
                    </span>

                </div>
                <div>
                    <h2 class="font-bold text-lg opacity-70 mb-4">Pilih paket langganan kamu</h2>
                    @foreach ($dataFeaturesPrices as $item)
                        <label class="block cursor-pointer w-full h-max mb-6"
                            onclick="packageOption(this, {{ $item->id }})" data-feature-id="{{ $item->feature_id }}"
                            data-variant-id="{{ $item->id }}" data-price="{{ $item->price }}">
                            <input type="radio" name="radio1" id="paket-{{ $item->id }}"
                                value="{{ $item->id }}" class="hidden peer">
                            <div
                                class="w-full lg:w-full h-[70px] border rounded-lg flex items-center justify-between px-4
                                        peer-checked:border-2 peer-checked:border-[#4189e0] transition">
                                <span>{{ $item->variant_name }}</span>
                                <span>Rp. {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!--- pemilihan payment method langganan soal dan pembahasan ----->
            <div class="flex flex-col w-full lg:w-[700px] gap-6 my-6 lg:my-0">
                <div
                    class="h-[420px] lg:h-full rounded-lg flex flex-col px-4 py-4 justify-between bg-white shadow-lg border">
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
                        @elseif ($getPacketSoalPembahasanActive)
                            <button type="button" onclick="alertPacketActive()"
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

<!----- COMPONENTS ----->
<script src="{{ asset('js/payment-features/components/package-option.js') }}"></script> <!--- untuk menampilkan opsi paket yang tersedia pada suatu fitur seperti harga, dll ---->
<script src="{{ asset('js/payment-features/components/payment-method.js') }}"></script> <!--- untuk menampilkan metode pembayaran apa saja yang tersedia menggunakan popup  ---->
<script src="{{ asset('js/payment-features/components/open-close-dropdown-payment-method.js') }}"></script> <!--- untuk menampilkan dan menutup popup metode pembayaran setelah memilih ---->

<script src="{{ asset('js/payment-features/snap-midtrans/popup-snap-midtrans-soal-pembahasan-feature.js') }}"></script>

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
    function alertPacketActive() {
        swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Maaf, kamu tidak bisa membeli paket ini, karena kamu masih memiliki paket Soal dan Pembahasan yang aktif.",
        });
    }
</script>
