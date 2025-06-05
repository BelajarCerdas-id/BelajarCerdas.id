@include('components/sidebar_beranda', ['headerSideNav' => 'Rank'])

@if (Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section>
                    <div class="w-full h-max flex flex-col gap-6 bg-white shadow-lg rounded-lg p-4 border">
                        <span class="text-lg font-bold opacity-70">Rank Saat Ini</span>
                        <!---- Current Rank ---->
                        <div class="flex flex-col items-center gap-2">
                            @if ($currentRankMentor)
                                <span
                                    class="text-xl font-bold opacity-70">{{ $currentRankMentor->TanyaRank->nama_rank }}</span>
                                @if ($currentRankMentor->TanyaRank->nama_rank === 'Bronze')
                                    <i class="fas fa-medal text-6xl text-[#CD7F32] mb-2"></i>
                                @elseif($currentRankMentor->TanyaRank->nama_rank === 'Silver')
                                    <i class="fas fa-medal text-6xl text-[#D8D8D8] mb-2"></i>
                                @else
                                    <i class="fas fa-medal text-6xl text-[#E5B80B] mb-2"></i>
                                @endif
                            @else
                                <span class="text-xl font-bold opacity-70">Tidak ada rank saat ini</span>
                                <i class="fas fa-circle-question text-6xl text-gray-200 mb-2"></i>
                            @endif
                        </div>
                        <!---- Data Tanya Rank Progress Mentor ---->
                        <ul>
                            <li class="flex items-center justify-evenly gap-4 mt-4">
                                <div class="text-center">
                                    <span class="text-sm">Soal Diterima</span><br>
                                    <i class="fas fa-circle-check text-md text-success"></i>
                                    <span class="text-md">
                                        {{ $dataTanyaRankProgressDiterima ?? 0 }}
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="text-sm">Soal Ditolak</span><br>
                                    <i class="fas fa-circle-xmark text-md text-error"></i>
                                    <span class="text-md">
                                        {{ $dataTanyaRankProgressDitolak ?? 0 }}
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="text-sm">Approved</span><br>
                                    <i class="fas fa-thumbs-up text-md text-success">
                                    </i>
                                    <span class="text-md">
                                        {{ $dataTanyaRankProgressApproved ?? 0 }}
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="text-sm">Rejected</span><br>
                                    <i class="fas fa-thumbs-down text-md text-error"></i>
                                    <span class="text-md">
                                        {{ $dataTanyaRankProgressRejected ?? 0 }}
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!--- Timeline Rank ---->
                    <div id="slider" class="!w-full">
                        <header class="text-lg font-bold opacity-70 flex flex-start pt-8 px-2">Timeline Rank</header>
                        <input type="radio" name="slider" id="slide1" checked>
                        <input type="radio" name="slider" id="slide2">
                        <input type="radio" name="slider" id="slide3">
                        <div id="slides">
                            <div id="overflow">
                                <div class="inner">
                                    @foreach ($rewardRankMentor as $item)
                                        <div class="slide slide_1">
                                            <div class="slide-content">
                                                <div class="flex flex-col w-full h-full text-center">
                                                    <span
                                                        class="text-xl font-bold opacity-70">{{ $item->nama_rank }}</span>
                                                    @if ($item->nama_rank === 'Bronze')
                                                        <i class="fas fa-medal text-6xl text-[#CD7F32] mb-2"></i>
                                                    @elseif($item->nama_rank === 'Silver')
                                                        <i class="fas fa-medal text-6xl text-[#D8D8D8] mb-2"></i>
                                                    @else
                                                        <i class="fas fa-medal text-6xl text-[#E5B80B] mb-2"></i>
                                                    @endif
                                                    <span class="mb-2 text-sm">Required :</span>
                                                    <footer class="flex flex-col leading-6">
                                                        <div class="flex items-center justify-center gap-1">
                                                            <i class="fas fa-circle-check text-md text-success"> </i>
                                                            <span> {{ $item->jumlah_soal_diterima }} </span>
                                                        </div>
                                                        <div class="flex items-center justify-center gap-1">
                                                            <i class="fas fa-thumbs-up text-md text-success">
                                                            </i>
                                                            <span> {{ $item->jumlah_soal_approved }} </span>
                                                        </div>
                                                    </footer>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div id="controls">
                            <label for="slide1"></label>
                            <label for="slide2"></label>
                            <label for="slide3"></label>
                        </div>
                    </div>
                </section>
                <!---- S&K dan REWARD RANK ---->
                <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="bg-white shadow-lg rounded-md border p-4">
                        <span class="text-xl font-bold opacity-70">Syarat & Ketentuan</span>
                        <ul class="flex flex-col items-start mt-2">
                            <li class="flex gap-1 mb-1">
                                <i class="fas fa-circle text-[6px] text-[#4189E0] pt-1.5"></i>
                                <span class="text-sm">Jawaban akan di verifikasi oleh Team Leader untuk kemudian
                                    dilakukan pembayaran.</span>
                            </li>
                            <li class="flex gap-1 mb-1">
                                <i class="fas fa-circle text-[6px] text-[#4189E0] pt-1.5"></i>
                                <span class="text-sm">Minimal pembayaran akan dilakukan saat mencapai nilai
                                    Rp.50.000.</span>
                            </li>
                            <li class="flex gap-1 mb-1">
                                <i class="fas fa-circle text-[6px] text-[#4189E0] pt-1.5"></i>
                                <span class="text-sm">Proses pembayaran akan dilakukan maksimal 14 hari kerja.</span>
                            </li>
                            <li class="flex gap-1 mb-1">
                                <i class="fas fa-circle text-[6px] text-[#4189E0] pt-1.5"></i>
                                <span class="text-sm">Pembayaran dilakukan melalui E-Wallet (Dana).</span>
                            </li>
                            <li class="flex gap-1 mb-1">
                                <i class="fas fa-circle text-[6px] text-[#4189E0] pt-1.5"></i>
                                <span class="text-sm">Setiap jawaban akan dibayarkan sesuai rank.</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-white shadow-lg rounded-md border p-4">
                        <span class="text-xl font-bold opacity-70">Reward Rank</span>
                        <ul class="flex flex-col items-start mt-2">
                            @foreach ($rewardRankMentor as $item)
                                <li class="flex items-center gap-2">
                                    <div class="flex items-center gap-2">
                                        @if ($item->nama_rank === 'Bronze')
                                            <i class="fas fa-medal text-lg text-[#CD7F32]"></i>
                                        @elseif($item->nama_rank === 'Silver')
                                            <i class="fas fa-medal text-lg text-[#D8D8D8]"></i>
                                        @else
                                            <i class="fas fa-medal text-lg text-[#E5B80B]"></i>
                                        @endif
                                        <span class="text-sm">{{ $item->nama_rank }} :</span>
                                    </div>
                                    <span class="text-sm">{{ $item->harga_per_soal }} / Soal</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            </main>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif
