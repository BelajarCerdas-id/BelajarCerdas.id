@include('components/sidebar_beranda', ['headerSideNav' => 'Mentor Tanya'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <span class="text-lg font-bold opacity-70">Data Mentor Tanya</span>
            <div id="grid-list-mentor-tanya" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- cards akan di-append via AJAX --}}
            </div>

            <div class="pagination-container-list-mentor-tanya flex justify-center mt-4"></div>

            <div class="flex justify-center">
                <span class="noDataMessageListMentorTanya hidden text-gray-500">Tidak ada riwayat</span>
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif


<script src="js/Tanya/payment-mentor/list-mentor-tanya-ajax.js"></script> <!--- list mentor tanya dengan jumlah pertanyaan menunggu setiap mentor  ---->

<!--- PUSHER LISTENER TANYA ---->
<script src="{{ asset('js/pusher-listener/tanya/count-mentor-questions-await-verification.js') }}"></script>
