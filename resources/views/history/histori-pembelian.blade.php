@include('components/sidebar_beranda', ['headerSideNav' => 'Histori Pembelian'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <!--- alert ketika berhasil melakukan pembayaran yang sebelumnya berstatus menunggu --->
            <div id="alert-payment-success"></div>

            <div class="w-full h-26 bg-[#153569] rounded-lg flex items-center">
                <div class="bg-[#FFE588] w-20 h-full flex items-center justify-center rounded-r-full">
                    <i class="fa-solid fa-clock-rotate-left text-2xl text-[--color-second]"></i>
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
                <div id="grid-transaction-success-list" class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full">
                    {{-- cards akan di-append via AJAX --}}
                </div>

                <div class="pagination-container-transaction-success flex justify-center mt-4"></div>

                <div class="flex items-center justify-center min-h-96">
                    <span class="noDataMessageSuccess hidden text-gray-500">Tidak ada riwayat</span>
                </div>
            </div>

            <!----- content pembelian menunggu ------->
            <div id="menunggu" class="hidden">
                <div id="attention" class="border bg-white shadow-lg rounded-md p-4 flex items-center gap-4">
                    <i class="fa-solid fa-triangle-exclamation text-3xl text-red-400"></i>
                    <div>
                        <span class="text-md text-red-500 font-bold">PERHATIAN</span>
                        <p>Riwayat pembelian yang berstatus menunggu akan menjadi kadaluarsa setelah lewat dari 24 jam
                            dari
                            waktu pembelian paket tersebut.
                        </p>
                    </div>
                </div>
                <div id="grid-transaction-waiting-list" class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full">
                    {{-- cards akan di-append via AJAX --}}
                </div>

                <div class="pagination-container-transaction-waiting flex justify-center mt-4"></div>

                <div class="flex items-center justify-center min-h-96">
                    <span class="noDataMessageWaiting hidden text-gray-500">Tidak ada riwayat</span>
                </div>
            </div>

            <!----- content pembelian menunggu ------->
            <div id="gagal" class="hidden">
                <div id="grid-transaction-failed-list" class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full">
                    {{-- cards akan di-append via AJAX --}}
                </div>

                <div class="pagination-container-transaction-failed flex justify-center mt-4"></div>

                <div class="flex items-center justify-center min-h-96">
                    <span class="noDataMessageFailed hidden text-gray-500">Tidak ada riwayat</span>
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

<script src="{{ asset('js/history-purchase/paginate-history-purchase-success.js') }}"></script>
<script src="{{ asset('js/history-purchase/paginate-history-purchase-waiting.js') }}"></script>
<script src="{{ asset('js/history-purchase/paginate-history-purchase-failed.js') }}"></script>

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
