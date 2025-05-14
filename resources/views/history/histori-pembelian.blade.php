@include('components/sidebar_beranda', ['headerSideNav' => 'Histori Pembelian'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <div class="w-full h-26 bg-[#153569] rounded-lg flex items-center">
                <div class="bg-[#FFE588] w-20 h-full flex items-center justify-center rounded-r-full">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
                <div class="text-white font-bold text-lg ml-4">
                    <span>RIWAYAT PEMBELIAN</span>
                </div>
            </div>

            <div class="flex mt-10">
                <div class="w-full hover:bg-gray-100" onclick="successPayment()">
                    <input type="radio" class="hidden" name="radio" id="radio1" checked>
                    <div class="checked-timeline">
                        <label for="radio1" class="cursor-pointer">
                            <span class="text-lg flex justify-center relative top-1">Berhasil</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
                <div class="w-full hover:bg-gray-100" onclick="waitingPayment()">
                    <input type="radio" class="hidden" name="radio" id="radio2">
                    <div class="checked-timeline">
                        <label for="radio2" class="cursor-pointer">
                            <span class="text-lg flex justify-center relative top-1">Menunggu</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
                <div class="w-full hover:bg-gray-100" onclick="failedPayment()">
                    <input type="radio" class="hidden" name="radio" id="radio3">
                    <div class="checked-timeline">
                        <label for="radio3" class="cursor-pointer">
                            <span class="text-lg flex justify-center relative top-1">Gagal</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
            </div>
            <!----- content pembelian berhasil ------->
            <div id="berhasil">
                <div id="grid-transaction-success-list" class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                    {{-- cards akan di-append via AJAX --}}
                </div>

                <div class="pagination-container-transaction-success flex justify-center mt-4"></div>

                <div class="flex justify-center">
                    <span class="noDataMessageSuccess hidden text-gray-500">Tidak ada riwayat</span>
                </div>
            </div>

            <!----- content pembelian menunggu ------->
            <div id="menunggu">
                <div id="grid-transaction-waiting-list" class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                    {{-- cards akan di-append via AJAX --}}
                </div>

                <div class="pagination-container-transaction-waiting flex justify-center mt-4"></div>

                <div class="flex justify-center">
                    <span class="noDataMessageWaiting hidden text-gray-500">Tidak ada riwayat</span>
                </div>
            </div>

            <!----- content pembelian menunggu ------->
            <div id="gagal">
                <div id="grid-transaction-failed-list" class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                    {{-- cards akan di-append via AJAX --}}
                </div>

                <div class="pagination-container-transaction-failed flex justify-center mt-4"></div>

                <div class="flex justify-center">
                    <span class="noDataMessageFailed hidden text-gray-500">Tidak ada riwayat</span>
                </div>
            </div>
            {{-- <div id="berhasil">
                @if ($transactionUserSuccess->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        @foreach ($transactionUserSuccess as $item)
                            <div class="list-item">
                                <div class="dropdown-menu">
                                    <div class="toggle-menu border">
                                        <div class="w-full h-max bg-white shadow-lg rounded-md p-4">
                                            <!--- nama fitur & status transaksi --->
                                            <div class="flex justify-between">
                                                <span class="text-md font-bold opacity-60">
                                                    {{ $item->Features->nama_fitur }}
                                                </span>
                                                <span
                                                    class="text-sm p-[4px] px-6 bg-green-200 rounded-xl flex items-center text-green-600 font-bold">
                                                    {{ $item->transaction_status }}
                                                </span>
                                            </div>
                                            <!--- variant paket --->
                                            <span class="text-md font-bold opacity-70">
                                                @if ($item->Features->nama_fitur === 'TANYA')
                                                    {{ $item->jumlah_koin }} Koin
                                                @else
                                                    {{ $item->FeaturePrices->variant_name }}
                                                @endif
                                            </span>
                                            <!--- Harga beli & lihat detail --->
                                            <div class="flex justify-between mt-2">
                                                <span
                                                    class="text-md p-[3px] bg-[#D0EBFF] w-max px-4 rounded-xl font-bold text-[#4189FF]">
                                                    Rp. {{ number_format($item->price, 0, ',', '.') }}
                                                </span>
                                                <button class="button-detail text-[#4189FF] font-bold">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                            <div class="content-dropdown-histori-pembelian">
                                                <div class="flex flex-col gap-2 mt-10">
                                                    <!---- detail pembelian ----->
                                                    <span class="font-bold opacity-60">Detail Pembelian :</span>
                                                    <div class="bg-blue-100 flex flex-col gap-2 rounded-md p-2">
                                                        <span class="font-bold opacity-70">
                                                            Order ID : {{ $item->order_id }}
                                                        </span>
                                                        @if ($item->Features->nama_fitur === 'TANYA')
                                                            <span class="font-bold opacity-70">
                                                                Varian : {{ $item->FeaturePrices->variant_name }}
                                                            </span>
                                                        @endif
                                                        <span class="font-bold opacity-70">
                                                            Tanggal Pembelian :
                                                            {{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y') }}
                                                        </span>
                                                    </div>
                                                    <!--- informasi pembelian --->
                                                    <span class="font-bold opacity-60">Informasi Pembelian :</span>
                                                    <div class="bg-blue-100 flex flex-col gap-2 rounded-md p-2">
                                                        <span class="font-bold opacity-70">
                                                            Nama Lengkap :
                                                            {{ $item->UserAccount->Profile->nama_lengkap }}
                                                        </span>
                                                        <span class="font-bold opacity-70">
                                                            Email : {{ $item->UserAccount->email }}
                                                        </span>
                                                        <span class="font-bold opacity-70">
                                                            No.Hp : {{ $item->UserAccount->no_hp }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="w-full h-96 flex justify-center items-center bg-white shadow-lg rounded-md">
                        <span>Tidak ada riwayat</span>
                    </div>
                @endif
            </div> --}}

            <!----- content pembelian menunggu ------->
            {{-- <div id="menunggu" class="hidden">
                @if ($transactionUserWaiting->isNotEmpty())
                    <div class="grid
                        grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        @foreach ($transactionUserWaiting as $item)
                            <div id="transaction-pending-{{ $item->id }}" class="list-item">
                                <div class="dropdown-menu">
                                    <div class="toggle-menu border">
                                        <div class="w-full h-max bg-white shadow-lg rounded-md p-4">
                                            <!--- nama fitur & status transaksi --->
                                            <div class="flex justify-between">
                                                <span class="text-md font-bold opacity-60">
                                                    {{ $item->Features->nama_fitur }}
                                                </span>
                                                <span
                                                    class="text-sm p-[4px] px-6 text-[#f77a2c] opacity-80 rounded-xl flex items-center font-bold bg-[#f9d3ba]">
                                                    {{ $item->transaction_status }}
                                                </span>
                                            </div>
                                            <!--- variant paket --->
                                            <span class="text-md font-bold opacity-70">
                                                @if ($item->Features->nama_fitur === 'TANYA')
                                                    {{ $item->jumlah_koin }} Koin
                                                @else
                                                    {{ $item->FeaturePrices->variant_name }}
                                                @endif
                                            </span>
                                            <!--- Harga beli & lihat detail --->
                                            <div class="flex justify-between mt-2">
                                                <span
                                                    class="text-md p-[3px] bg-[#D0EBFF] w-max px-4 rounded-xl font-bold text-[#4189FF]">
                                                    Rp. {{ number_format($item->price, 0, ',', '.') }}
                                                </span>
                                                <button class="button-detail text-[#4189FF] font-bold">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                            <div class="content-dropdown-histori-pembelian">
                                                <div class="flex flex-col gap-2 mt-10">
                                                    <!---- detail pembelian ----->
                                                    <span class="font-bold opacity-60">Detail Pembelian :</span>
                                                    <div class="bg-[#D0EBFF] flex flex-col gap-2 rounded-md p-2">
                                                        <span class="font-bold opacity-70">
                                                            Order ID : {{ $item->order_id }}
                                                        </span>
                                                        @if ($item->Features->nama_fitur === 'TANYA')
                                                            <span class="font-bold opacity-70">
                                                                Varian : {{ $item->FeaturePrices->variant_name }}
                                                            </span>
                                                        @endif
                                                        <span class="font-bold opacity-70">
                                                            Tanggal Pembelian :
                                                            {{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y') }}
                                                        </span>
                                                    </div>
                                                    <!--- informasi pembelian --->
                                                    <span class="font-bold opacity-60">Informasi Pembelian :</span>
                                                    <div class="bg-[#D0EBFF] flex flex-col gap-2 rounded-md p-2">
                                                        <span class="font-bold opacity-70">
                                                            Nama Lengkap :
                                                            {{ $item->UserAccount->Profile->nama_lengkap }}
                                                        </span>
                                                        <span class="font-bold opacity-70">
                                                            Email : {{ $item->UserAccount->email }}
                                                        </span>
                                                        <span class="font-bold opacity-70">
                                                            No.Hp : {{ $item->UserAccount->no_hp }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <form id="form-pembelian-{{ $item->id }}"
                                                    action="{{ route('checkout.pending', $item->id) }}" method="POST"
                                                    class="w-full flex justify-end">
                                                    @csrf
                                                    <input type="text" id="item-id-{{ $item->id }}"
                                                        name="id" value="{{ $item->id }}">
                                                    <input type="text" id="input-payment-method-{{ $item->id }}"
                                                        name="payment_method_id" value="{{ $item->payment_method }}">
                                                    <input type="text" id="input-feature-id-{{ $item->id }}"
                                                        name="feature_id" value="{{ $item->Features->nama_fitur }}">
                                                    <input type="text"
                                                        id="input-feature-variant-id-{{ $item->id }}"
                                                        name="feature_variant_id"
                                                        value="{{ $item->FeaturePrices->variant_name }}">
                                                    <input type="text" id="input-quantity-{{ $item->id }}"
                                                        name="quantity" value="{{ $item->jumlah_koin }}">
                                                    <input type="text" id="input-price-{{ $item->id }}"
                                                        name="price" value="{{ $item->price }}">

                                                    <button type="button"
                                                        class="btn-beli bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-2 rounded-lg shadow-md transition-all text-sm my-4"
                                                        data-id="{{ $item->id }}">
                                                        Beli Sekarang
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div id="emptyMessageWaiting"
                    class="w-full h-96 flex justify-center items-center bg-white shadow-lg rounded-md {{ $transactionUserWaiting->isNotEmpty() ? 'hidden' : '' }}">
                    <span>
                        Tidak ada riwayat
                    </span>
                </div>
            </div> --}}

            <!----- content pembelian gagal ------->
            {{-- <div id="gagal" class="hidden">
                @if ($transactionUserFailed->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        @foreach ($transactionUserFailed as $item)
                            <div class="list-item">
                                <div class="dropdown-menu">
                                    <div class="toggle-menu border">
                                        <div class="w-full h-max bg-white shadow-lg rounded-md p-4">
                                            <!--- nama fitur & status transaksi --->
                                            <div class="flex justify-between">
                                                <span class="text-md font-bold opacity-60">
                                                    {{ $item->Features->nama_fitur }}
                                                </span>
                                                <span
                                                    class="text-sm p-[4px] px-6 bg-red-200 rounded-xl flex items-center text-red-600 font-bold">
                                                    {{ $item->transaction_status }}
                                                </span>
                                            </div>
                                            <!--- variant paket --->
                                            <span class="text-md font-bold opacity-70">
                                                @if ($item->Features->nama_fitur === 'TANYA')
                                                    {{ $item->jumlah_koin }} Koin
                                                @else
                                                    {{ $item->FeaturePrices->variant_name }}
                                                @endif
                                            </span>
                                            <!--- Harga beli & lihat detail --->
                                            <div class="flex justify-between mt-2">
                                                <span
                                                    class="text-md p-[3px] bg-[#D0EBFF] w-max px-4 rounded-xl font-bold text-[#4189FF]">
                                                    Rp. {{ number_format($item->price, 0, ',', '.') }}
                                                </span>
                                                <button class="button-detail text-[#4189FF] font-bold">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                            <div class="content-dropdown-histori-pembelian">
                                                <div class="flex flex-col gap-2 mt-10">
                                                    <!---- detail pembelian ----->
                                                    <span class="font-bold opacity-60">Detail Pembelian :</span>
                                                    <div class="bg-blue-100 flex flex-col gap-2 rounded-md p-2">
                                                        <span class="font-bold opacity-70">
                                                            Order ID : {{ $item->order_id }}
                                                        </span>
                                                        @if ($item->Features->nama_fitur === 'TANYA')
                                                            <span class="font-bold opacity-70">
                                                                Varian : {{ $item->FeaturePrices->variant_name }}
                                                            </span>
                                                        @endif
                                                        <span class="font-bold opacity-70">
                                                            Tanggal Pembelian :
                                                            {{ $item->created_at->locale('id')->translatedFormat('l, d-M-Y') }}
                                                        </span>
                                                    </div>
                                                    <!--- informasi pembelian --->
                                                    <span class="font-bold opacity-60">Informasi Pembelian :</span>
                                                    <div class="bg-blue-100 flex flex-col gap-2 rounded-md p-2">
                                                        <span class="font-bold opacity-70">
                                                            Nama Lengkap :
                                                            {{ $item->UserAccount->Profile->nama_lengkap }}
                                                        </span>
                                                        <span class="font-bold opacity-70">
                                                            Email : {{ $item->UserAccount->email }}
                                                        </span>
                                                        <span class="font-bold opacity-70">
                                                            No.Hp : {{ $item->UserAccount->no_hp }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="w-full h-96 flex justify-center items-center bg-white shadow-lg rounded-md">
                        <span>Tidak ada riwayat</span>
                    </div>
                @endif
            </div> --}}
        </div>
    </div>
@elseif(Auth::user()->role === 'Murid')
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/paginate-history-purchase-success.js') }}"></script>
<script src="{{ asset('js/paginate-history-purchase-waiting.js') }}"></script>
<script src="{{ asset('js/paginate-history-purchase-failed.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // script untuk mengubah status transaksi pending => expired
        Echo.channel('transactions')
            .listen('.transaction-expired', (e) => {
                console.log('Transaksi pending menjadi kadaluarsa:', e.transaction.id);

                // Hapus elemen transaksi yang kadaluarsa
                const el = document.getElementById(`transaction-pending-${e.transaction.id}`);
                if (el) {
                    el.remove();
                }

                // Cek apakah masih ada transaksi pending
                const remainingItems = document.querySelectorAll('#menunggu .list-item');
                const emptyMessage = document.getElementById('emptyMessageWaiting');

                if (remainingItems.length === 0) {
                    emptyMessage.classList.remove('hidden');
                } else {
                    emptyMessage.classList.add('hidden');
                }
            });
    });
</script>


<script>
    document.querySelectorAll('.btn-beli').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;

            const featureId = document.getElementById(`input-feature-id-${id}`).value;
            const featureVariantId = document.getElementById(`input-feature-variant-id-${id}`).value;
            const jumlahKoin = document.getElementById(`input-quantity-${id}`).value;
            const price = document.getElementById(`input-price-${id}`).value;
            const paymentMethodId = document.getElementById(`input-payment-method-${id}`).value;

            fetch(`/checkout-pending/${id}`, {
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
                                // console.log(result);
                                location.reload();
                            },
                            onPending: function(result) {
                                // console.log(result);
                            },
                            onError: function(result) {
                                // console.log(result);
                            },
                        });
                    } else {
                        alert("Gagal mendapatkan snap token.");
                        console.error(data);
                    }
                })
                .catch(error => {
                    alert("Terjadi kesalahan.");
                    console.error(error);
                });
        });
    });
</script>


{{-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggles = document.querySelectorAll('.button-detail');

        toggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const listItem = toggle.closest('.list-item');

                // Tutup semua dropdown lain
                document.querySelectorAll('.list-item').forEach(item => {
                    if (item !== listItem) {
                        item.classList.remove('show');
                    }
                });

                // Toggle dropdown ini
                listItem.classList.toggle('show');
            });
        });
    });
</script> --}}



<script>
    const riwayatBerhasil = document.getElementById('berhasil');
    const riwayatMenunggu = document.getElementById('menunggu');
    const riwayatGagal = document.getElementById('gagal');

    function successPayment() {
        riwayatBerhasil.classList.remove('hidden');
        riwayatMenunggu.classList.add('hidden');
        riwayatGagal.classList.add('hidden');
    }

    function waitingPayment() {
        riwayatMenunggu.classList.remove('hidden');
        riwayatBerhasil.classList.add('hidden');
        riwayatGagal.classList.add('hidden');
    }

    function failedPayment() {
        riwayatGagal.classList.remove('hidden');
        riwayatBerhasil.classList.add('hidden');
        riwayatMenunggu.classList.add('hidden');
    }
</script>
