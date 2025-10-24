<x-navbar></x-navbar>

<main>
    <!--- alert ketika berhasil melakukan pembayaran yang sebelumnya berstatus menunggu --->
    <div id="alert-payment-success"></div>

    <section class="mx-4 lg:mx-10">
        <!--- left side --->
        <div class="flex flex-col lg:flex-row justify-center lg:gap-20 w-full">
            <!--- pilihhan paket koin tanya ----->
            <div class="w-full lg:w-[700px] flex flex-col gap-6 p-4 bg-white shadow-lg border rounded-lg">
                <div>
                    <h2 class="font-bold text-lg opacity-70 mb-4">Pilih paket langganan kamu</h2>
                    @foreach ($dataFeaturesPrices as $index => $item)
                        <label class="package-option block cursor-pointer w-full h-max mb-6"
                            onclick="packageOption(this, {{ $item->id }})" data-feature-id="{{ $item->feature_id }}"
                            data-variant-id="{{ $item->id }}" data-price="{{ $item->price }}"
                            data-index="{{ $index + 1 }}">
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

                <h2 class="font-bold text-lg opacity-70 mb-2">Pilih detail belajar kamu</h2>

                <!-- Level -->
                <div class="dropdown-checkbox w-full bg-white h-12 mb-6">
                    <label class="text-sm font-medium text-gray-700 mb-1 block">Level</label>
                    <div class="relative">
                        <!-- Tombol dropdown -->
                        <button type="button" id="dropdownButton" onclick="toggleOptions(event)"
                            class="w-full h-12 flex justify-between items-center pl-3 pr-4 py-2 border border-gray-300 rounded-lg bg-white text-sm outline-none opacity-50"
                            disabled>
                            <span id="dropdownText">Choose level</span>
                            <i class="fas fa-chevron-down text-[8px]"></i>
                        </button>

                        <!-- Options -->
                        <div id="dropdownOptions"
                            class="absolute mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto z-10">
                            @foreach ($getLevels as $item)
                                <label
                                    class="label-checkbox flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm">
                                    <input type="checkbox" id="level_id" name="level_id[]" value="{{ $item->id }}"
                                        class="level-checkbox mr-2 rounded text-indigo-600 focus:ring-indigo-500"
                                        onchange="limitSelection(this)">
                                    <span>{{ $item->level_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Batch -->
                <div>
                    <label class="text-sm font-medium">Batch</label>
                    <select id="batch_id" name="batch_id"
                        class="select select-bordered w-full bg-white opacity-50 !cursor-default" disabled>
                        <option value="" class="hidden">Choose Batch</option>
                        <!-- show option in ajax -->
                    </select>
                </div>

                <!-- Hari -->
                <div>
                    <label class="text-sm font-medium">Hari</label>
                    <select id="days_id" name="days_id"
                        class="select select-bordered w-full bg-white opacity-50 !cursor-default" disabled>
                        <option value="" class="hidden">Choose Day</option>
                        <!-- show option in ajax -->
                    </select>
                </div>

                <!-- Jam -->
                <div>
                    <label class="text-sm font-medium">Jam</label>
                    <select id="hours_id" name="hours_id"
                        class="select select-bordered w-full bg-white opacity-50 !cursor-default" disabled>
                        <option value="" class="hidden">Choose Hour</option>
                        <!-- show option in ajax -->
                    </select>
                </div>
            </div>

            <!--- pemilihan payment method langganan soal dan pembahasan ----->
            <div class="flex flex-col w-full lg:w-[700px] gap-6 my-6 lg:my-0">
                <div
                    class="max-h-[420px] lg:h-full rounded-lg flex flex-col p-4 justify-between bg-white shadow-lg border">
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

                        <div class="mt-6 flex flex-col gap-[5px]">
                            <h4 class="font-semibold text-sm mb-2">Detail Paket :</h4>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Masa aktif</span>
                                <span id="masa-aktif" class="max-w-60 overflow-hidden">-</span>
                            </div>
                        </div>
                    </div>

                    <form id="form-pembelian" method="POST" action="{{ route('checkout.english-zone') }}">
                        @csrf
                        <input type="hidden" name="payment_method_id" id="input-payment-method"
                            value="{{ $paymentMethods[0]['name'] }}">
                        <input type="hidden" id="input-feature-id" name="feature_id">
                        <input type="hidden" id="input-feature-variant-id" name="feature_variant_id">
                        <input type="hidden" id="input-price" name="price">
                        <input type="hidden" id="input-level-id" name="level_id">
                        <input type="hidden" id="input-batch-id" name="batch_id">
                        <input type="hidden" id="input-batch-schedule-group" name="batch_schedule_group">
                        <input type="hidden" id="input-batch-schedule-id" name="batch_schedule_id" value="">
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
                                    <input type="radio" id="bank-{{ $item['id'] }}" name="radio"
                                        class="hidden" @if ($item['id'] == 1) checked @endif
                                        onclick="selectPayment(this)" data-name="{{ $item['name'] }}"
                                        data-logo="{{ asset($item['logo-payment']) }}"
                                        data-id="{{ $item['id'] }}">
                                    <div class="content-menu">
                                        <label for="bank-{{ $item['id'] }}">
                                            <div class="logo-payment-card">
                                                <img src="{{ $item['logo-payment'] }}" alt=""
                                                    class="w-[50px]">
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

<script src="{{ asset('js/payment-features/package-option/english-zone-package-option.js') }}"></script> <!--- untuk menampilkan opsi paket yang tersedia pada suatu fitur seperti harga, dll ---->
<script src="{{ asset('js/payment-features/snap-midtrans/popup-snap-midtrans-english-zone-feature.js') }}"></script> <!--- untuk menampilkan popup pembayaran menggunakan snap midtrans ---->

<!----- COMPONENTS ----->
<script src="{{ asset('js/payment-features/components/payment-method.js') }}"></script> <!--- untuk menampilkan metode pembayaran apa saja yang tersedia menggunakan popup  ---->
<script src="{{ asset('js/payment-features/components/open-close-dropdown-payment-method.js') }}"></script> <!--- untuk menampilkan dan menutup popup metode pembayaran setelah memilih ---->
<script src="{{ asset('js/payment-features/components/level-dropdown-ez-purchase.js') }}"></script> <!--- level dropdown ez purchase (dropdown custom) ---->
<script src="{{ asset('js/components/english-zone/ez-purchase-dropdown.js') }}"></script> <!--- dorpodown bertingkat ez (pemilihan detail belajar) ---->

<script>
    function alertLogin() {
        swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Harap login terlebih dahulu untuk membeli paket ini!",
        });
    }
</script>
