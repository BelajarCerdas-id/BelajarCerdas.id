@include('components/sidebar_beranda', [
    'headerSideNav' => 'Paket Aktif',
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
    'linkBackButton' => route('profile'),
])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda mt-[120px]">

            <div class="w-full hover:bg-gray-100">
                <input type="radio" class="hidden" name="radio" id="radio1" checked>
                <div class="checked-timeline">
                    <label for="radio1" class="cursor-pointer">
                        <span class="text-lg flex justify-center relative top-1">Riwayat Paket Aktif</span>
                        <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                    </label>
                </div>
            </div>

            <!-- Content Riwayat Paket Aktif -->
            @if ($getPacketActive->isNotEmpty())
                <div id="grid-history-coin-in-list" class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full">
                    @foreach ($getPacketActive as $item)
                        <div class="list-item">
                            <div class="bg-white shadow-lg rounded-md p-4 flex justify-between items-center">
                                <div class="flex flex-col gap-4">
                                    <span class="font-bold py-1 rounded-xl w-max h-7 opacity-70">
                                        {{ $item->Transactions->Features->nama_fitur ?? '-' }}
                                    </span>
                                    <span class="text-sm font-bold opacity-60">
                                        Paket Varian : {{ $item->Transactions->FeaturePrices->variant_name ?? '-' }}
                                    </span>
                                    <span class="text-sm font-bold opacity-60">
                                        Masa Berlaku :
                                        {{ \Carbon\Carbon::parse($item->start_date)->locale('id')->translatedFormat('d F Y') ?? '-' }}
                                        -
                                        {{ \Carbon\Carbon::parse($item->end_date)->locale('id')->translatedFormat('d F Y') ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="w-full h-96">
                    <span class="w-full h-full flex items-center justify-center">
                        Tidak ada riwayat paket yang sedang aktif.
                    </span>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif
