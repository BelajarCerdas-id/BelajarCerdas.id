@include('components/sidebar_beranda', ['headerSideNav' => 'Rank'])

@if (Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section>
                    <!---- Current Rank ---->
                    <div class="w-full h-max flex flex-col gap-6 bg-white shadow-lg rounded-lg p-4 border">
                        <span class="text-lg font-bold opacity-70">Rank Saat Ini</span>

                        <div class="flex flex-col items-center gap-2">
                            @if ($dataTanyaRankProgressDiterima >= 200 && $dataTanyaRankProgressApproved >= 270)
                                <span class="text-xl font-bold opacity-70">Gold</span>
                                <i class="fas fa-medal text-6xl text-[#E5B80B] mb-2"></i>
                            @elseif($dataTanyaRankProgressDiterima >= 100 && $dataTanyaRankProgressApproved >= 150)
                                <span class="text-xl font-bold opacity-70">Silver</span>
                                <i class="fas fa-medal text-6xl text-[#D8D8D8] mb-2"></i>
                            @elseif($dataTanyaRankProgressDiterima >= 43 && $dataTanyaRankProgressApproved >= 30)
                                <span class="text-xl font-bold opacity-70">Bronze</span>
                                <i class="fas fa-medal text-6xl text-[#CD7F32] mb-2"></i>
                            @else
                                <span class="text-xl font-bold opacity-70">Tidak ada rank saat ini</span>
                                <i class="fas fa-circle-question text-6xl text-gray-200 mb-2"></i>
                            @endif
                        </div>

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
                        <input type="radio" name="slider" id="slide4">
                        <div id="slides">
                            <div id="overflow">
                                <div class="inner">
                                    <div class="slide slide_1">
                                        <div class="slide-content">
                                            <div class="flex flex-col w-full h-full text-center">
                                                <header class="text-center text-xl mb-3">Bronze</header>
                                                <i class="fas fa-medal text-5xl text-[#CD7F32] mb-2"></i>
                                                <span class="mb-2 text-sm">Required :</span>
                                                <footer class="flex flex-col leading-6">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <i class="fas fa-circle-check text-md text-success"> </i>
                                                        <span> 50 </span>
                                                    </div>
                                                    <div class="flex items-center justify-center gap-1">
                                                        <i class="fas fa-thumbs-up text-md text-success">
                                                        </i>
                                                        <span> 30 </span>
                                                    </div>
                                                </footer>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slide slide_2">
                                        <div class="slide-content">
                                            <div class="flex flex-col w-full h-full text-center">
                                                <header class="text-center text-xl mb-3">Silver</header>
                                                <i class="fas fa-medal text-5xl text-[#D8D8D8] mb-2"></i>
                                                <span class="mb-2 text-sm">Required :</span>
                                                <footer class="flex flex-col leading-6">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <i class="fas fa-circle-check text-md text-success"> </i>
                                                        <span> 50 </span>
                                                    </div>
                                                    <div class="flex items-center justify-center gap-1">
                                                        <i class="fas fa-thumbs-up text-md text-success">
                                                        </i>
                                                        <span> 30 </span>
                                                    </div>
                                                </footer>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slide slide_3">
                                        <div class="slide-content">
                                            <div class="flex flex-col w-full h-full text-center">
                                                <header class="text-center text-xl mb-3">Gold</header>
                                                <i class="fas fa-medal text-5xl text-[#E5B80B] mb-2"></i>
                                                <span class="mb-2 text-sm">Required :</span>
                                                <footer class="flex flex-col leading-6">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <i class="fas fa-circle-check text-md text-success"> </i>
                                                        <span> 50 </span>
                                                    </div>
                                                    <div class="flex items-center justify-center gap-1">
                                                        <i class="fas fa-thumbs-up text-md text-success">
                                                        </i>
                                                        <span> 30 </span>
                                                    </div>
                                                </footer>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slide slide_4">
                                        <div class="slide-content">
                                            <div class="flex flex-col w-full h-full text-center">
                                                <header class="text-center text-xl mb-3">Platinum</header>
                                                <i class="fas fa-medal text-5xl text-[#E5E4E2] mb-2"></i>
                                                <span class="mb-2 text-sm">Required :</span>
                                                <footer class="flex flex-col leading-6">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <i class="fas fa-circle-check text-md text-success"> </i>
                                                        <span> 50 </span>
                                                    </div>
                                                    <div class="flex items-center justify-center gap-1">
                                                        <i class="fas fa-thumbs-up text-md text-success">
                                                        </i>
                                                        <span> 30 </span>
                                                    </div>
                                                </footer>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="controls">
                            <label for="slide1"></label>
                            <label for="slide2"></label>
                            <label for="slide3"></label>
                            <label for="slide4"></label>
                        </div>
                    </div>
                </section>
                {{-- <div class="">
                    <div class="">
                        <header class="flex mt-8 px-2">Syarat & Ketentuan :</header>
                        <ul class="flex flex-col items-start px-4">
                            <li class="flex items-center gap-1 mb-1">
                                <i class="fas fa-circle text-[8px] text-[#824D74]"></i>
                                <span class="text-sm">Proses pembayaran akan dilakukan minimal 5 hari
                                    kerja.</span>
                            </li>
                            <li class="flex items-center gap-1 mb-1">
                                <i class="fas fa-circle text-[8px] text-[#824D74]"></i>
                                <span class="text-sm">Pembayaran dilakukan melalui E-Wallet (Dana, Ovo,
                                    Gopay).</span>
                            </li>
                            <li class="flex items-center gap-1 mb-1">
                                <i class="fas fa-circle text-[8px] text-[#824D74]"></i>
                                <span class="text-sm">Pembayaran dilakukan setiap 30 soal Accepted.</span>
                            </li>
                            <li class="px-3.5">
                                <span class="text-sm flex flex-start"><a class="pr-3">Bronze</a> :
                                    Rp500/soal</span>
                                <span class="text-sm flex flex-start"><a class="pr-[22px]">Silver</a> :
                                    Rp1000/soal</span>
                                <span class="text-sm flex flex-start"><a class="pr-7">Gold</a> :
                                    Rp1500/soal</span>
                                <span class="text-sm flex flex-start">Platinum : Rp2000/soal</span>
                            </li>
                        </ul>
                    </div>
                </div> --}}
            </main>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif
