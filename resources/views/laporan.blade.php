<x-sidebar_beranda :user="session('user')"></x-sidebar_beranda>
@extends('components/sidebar_beranda_mobile') <!-- Menggunakan layout dengan modal -->

@if (isset($user))
    @if ($user->status === 'Siswa')
    @elseif ($user->status === 'Mentor')
        <div class="home-beranda">
            <div class="content-beranda">
                <header class="text-2xl mb-8 font-bold">Laporan Mentor</header>
                <div id="slider">
                    <header class="text-xl font-bold flex flex-start pt-8 px-2">Tanya</header>
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
                                            <i class="fas fa-medal text-5xl text-[#C0C0C0] mb-2"></i>
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
                                            <i class="fas fa-medal text-5xl text-[#FFD700] mb-2"></i>
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
                    <li class="flex justify-evenly gap-4 mt-4">
                        <div class="text-center">
                            <span class="text-sm">Soal Diterima</span><br>
                            <i class="fas fa-circle-check text-md text-success"></i>
                            <span class="text-md">
                                {{ isset($dataAccept[$user->email]) ? $dataAccept[$user->email]->count() : 0 }}
                            </span>
                        </div>
                        <div class="text-center">
                            <span class="text-sm">Soal Ditolak</span><br>
                            <i class="fas fa-circle-xmark text-md text-error"></i>
                            <span class="text-md">
                                {{ isset($dataReject[$user->email]) ? $dataReject[$user->email]->count() : 0 }}
                            </span>
                        </div>
                        <div class="text-center">
                            <span class="text-sm">Accepted</span><br>
                            <i class="fas fa-thumbs-up text-md text-success">
                            </i>
                            <span class="text-md">
                                {{ isset($validatedMentorAccepted[$user->email]) ? $validatedMentorAccepted[$user->email]->count() : 0 }}
                            </span>
                        </div>
                        <div class="text-center">
                            <span class="text-sm">Rejected</span><br>
                            <i class="fas fa-thumbs-down text-md text-error"></i>
                            <span class="text-md">
                                {{ isset($validatedMentorRejected[$user->email]) ? $validatedMentorRejected[$user->email]->count() : 0 }}
                            </span>
                        </div>
                    </li>
                    <div class="">
                        <div class="">
                            <header class="flex mt-8 px-2">Syarat & Ketentuan :</header>
                            <ul class="flex flex-col items-start px-4">
                                <li>
                                    <i class="fas fa-circle text-[8px] text-[#824D74]"></i>
                                    <span class="text-sm">Proses pembayaran akan dilakukan minimal 5 hari kerja.</span>
                                </li>
                                <li>
                                    <i class="fas fa-circle text-[8px] text-[#824D74]"></i>
                                    <span class="text-sm">Pembayaran dilakukan melalui E-Wallet (Dana, Ovo,
                                        Gopay).</span>
                                </li>
                                <li>
                                    <i class="fas fa-circle text-[8px] text-[#824D74]"></i>
                                    <span class="text-sm">Lorem Ipsum</span>
                                </li>
                                <li>
                                    <i class="fas fa-circle text-[8px] text-[#824D74]"></i>
                                    <span class="text-sm">Lorem Ipsum</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($user->status === 'Murid')

    @elseif ($user->status === 'Guru')

    @elseif ($user->status === 'Admin')

    @elseif ($user->status === 'Wakil Kepala Sekolah')

    @elseif ($user->status === 'Kepala Sekolah')

    @elseif ($user->status === 'Administrator')

    @elseif ($user->status === 'Team Leader')
        <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
            <div class="content-beranda">
                <header class="text-2xl mb-8 font-bold">List Mentor</header>
                <div class="grid grid-cols-4 gap-8">
                    @foreach ($getData as $item)
                        <a href="{{ route('laporan.edit', $item->id) }}">
                            <div class="bg-white flex items-center gap-2 pl-6 rounded-xl shadow-lg">
                                <i class="fas fa-circle-user text-4xl"></i>
                                <div class="flex flex-col">
                                    <span class="text-sm leading-6">
                                        Nama:
                                        {{ $item->nama_lengkap }}
                                    </span>
                                    <span class="text-xs leading-6">
                                        Sekolah:
                                        {{ $item->sekolah }}
                                    </span>
                                    <span class="text-xs leading-6">
                                        total tanya:
                                        {{ isset($countData[$item->email]) ? $countData[$item->email] : 0 }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="flex flex-col min-h-screen items-center justify-center">
            <p>ALERT SEMENTARA</p>
            <p>You do not have access to this pages.</p>
        </div>
    @endif
@else
    <p>You are not logged in.</p>
@endif
