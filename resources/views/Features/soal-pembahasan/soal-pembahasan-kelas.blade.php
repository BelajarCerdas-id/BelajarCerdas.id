@include('components/sidebar_beranda', ['headerSideNav' => 'Soal Pembahasan'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section>
                    <div class="flex flex-col xl:flex-row gap-6">
                        <!-- Sidebar -->
                        <div class="w-full xl:w-64 p-4 space-y-2 bg-white shadow-lg border rounded-md h-max py-4">
                            <h2 class="text-sm font-bold mb-4 flex items-center gap-2">
                                <i class="fas fa-bars"></i>
                                {{ Auth::user()->Profile->Fase->nama_fase ?? '' }}
                            </h2>
                            <ul class="space-y-1 text-sm">
                                @foreach ($getKelasByFase as $item)
                                    <li class="list-class-item">
                                        <a href="{{ route('soalPembahasanMapel.view', [$item->kelas, $item->id]) }}"
                                            class="link-href-class flex items-center justify-between gap-2 hover:bg-gray-200 py-2 rounded cursor-pointer px-4 mb-2">
                                            <span class="font-bold opacity-70 text-xs">{{ $item->kelas }}</span>
                                            <i class="fa-solid fa-chevron-right text-xs"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                        </div>

                        <!-- Main Content -->
                        <div class="flex-1 overflow-auto bg-white shadow-lg border rounded-md">
                            <!-- Header -->
                            <div class="p-4 rounded-lg mb-6">
                                <p class="text-lg font-bold opacity-70">List Kelas</p>
                            </div>

                            <!-- Grid -->
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 border-t">
                                @foreach ($getKelasByFase as $item)
                                    <a href="{{ route('soalPembahasanMapel.view', [$item->kelas, $item->id]) }}">
                                        <li
                                            class="flex items-center justify-between gap-2 hover:bg-gray-200 py-2 rounded cursor-pointer px-4 mb-2">
                                            <span class="font-bold opacity-70 text-sm">{{ $item->kelas }}</span>
                                            <i class="fa-solid fa-chevron-right text-xs"></i>
                                        </li>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
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

<script src="{{ asset('js/Features/soal-pembahasan/components/checked-class-option.js') }}"></script> <!--- checked class option ---->
