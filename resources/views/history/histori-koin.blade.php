@include('components/sidebar_beranda', ['headerSideNav' => 'Histori Koin'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <div class="w-full h-26 bg-[#153569] rounded-lg flex items-center">
                <div class="bg-[#FFE588] w-20 h-full flex items-center justify-center rounded-r-full">
                    <img src="{{ asset('image/koin.png') }}" alt="" class="w-[45px]">
                </div>
                <div class="text-white font-bold text-lg ml-4">
                    <span>RIWAYAT KOIN</span>
                </div>
            </div>

            <div class="flex mt-10">
                <div class="w-full hover:bg-gray-100" onclick="CoinsIn()">
                    <input type="radio" class="hidden" name="radio" id="radio1" checked>
                    <div class="checked-timeline">
                        <label for="radio1" class="cursor-pointer">
                            <span class="text-md flex justify-center relative top-1">Koin Masuk</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
                <div class="w-full hover:bg-gray-100" onclick="CoinsOut()">
                    <input type="radio" class="hidden" name="radio" id="radio2">
                    <div class="checked-timeline">
                        <label for="radio2" class="cursor-pointer">
                            <span class="text-md flex justify-center relative top-1">Koin Keluar</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div id="contentCoinsIn" class="">
                <div id="grid-history-coin-in-list" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 w-full">
                    {{-- cards akan di-append via AJAX --}}
                </div>

                <div class="pagination-container-coin-in flex justify-center mt-4"></div>

                <div class="flex items-center justify-center min-h-96">
                    <span class="noDataMessageHistoryCoinIn hidden text-gray-500">Tidak
                        ada riwayat
                        koin masuk.</span>
                </div>
            </div>
            <div id="contentCoinsOut" class="hidden">
                <div id="grid-history-coin-out-list"
                    class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 w-full">
                    {{-- cards akan di-append via AJAX --}}
                </div>

                <div class="pagination-container-coin-out flex justify-center mt-4"></div>

                <div class="flex items-center justify-center min-h-96">
                    <span class="noDataMessageHistoryCoinOut hidden text-gray-500">Tidak ada riwayat koin keluar.</span>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/history-purchase/paginate-history-coin-in.js') }}"></script>
<script src="{{ asset('js/history-purchase/paginate-history-coin-out.js') }}"></script>

<script>
    var koinMasuk = document.getElementById('contentCoinsIn');
    var koinKeluar = document.getElementById('contentCoinsOut');

    function CoinsIn() {
        koinMasuk.classList.remove('hidden');
        koinKeluar.classList.add('hidden');
    }

    function CoinsOut() {
        koinKeluar.classList.remove('hidden');
        koinMasuk.classList.add('hidden');
    }
</script>
