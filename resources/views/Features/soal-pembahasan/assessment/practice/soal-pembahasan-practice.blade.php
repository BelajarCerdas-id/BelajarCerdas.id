@include('components/sidebar_beranda', ['headerSideNav' => 'Latihan'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section>
                    <div class="h-full bg-white shadow-lg border pb-4 mb-8">
                        <!--- Content Top --->
                        <div class="grid grid-cols-2 md:grid-cols-3 w-full md:w-max p-4 gap-4">
                            <!--- Jenis Soal --->
                            <div class="w-full md:w-max">
                                <div
                                    class="border border-gray-400 py-[4px] w-full md:w-32 text-sm text-center font-bold opacity-70">
                                    Jenis Soal</div>
                                <div
                                    class="border border-gray-400 py-[4px] w-full md:w-32 text-sm text-center font-bold opacity-70">
                                    Latihan</div>
                            </div>

                            <!--- Kelas --->
                            <div class="w-full md:w-max">
                                <div
                                    class="border border-gray-400 py-[4px] w-full md:w-32 text-sm text-center font-bold opacity-70">
                                    Kelas</div>
                                <div
                                    class="border border-gray-400 py-[4px] w-full md:w-32 text-sm text-center font-bold opacity-70">
                                    {{ $kelas }}
                                </div>
                            </div>

                            <!--- mapel --->
                            <div class="w-full md:w-max col-span-2 md:col-span-1">
                                <div
                                    class="border border-gray-400 py-[4px] w-full md:w-32 text-sm text-center font-bold opacity-70">
                                    Mata Pelajaran
                                </div>
                                <div
                                    class="border border-gray-400 py-[4px] w-full md:w-32 text-sm text-center font-bold opacity-70">
                                    {{ $mata_pelajaran }}
                                </div>
                            </div>
                        </div>

                        <!--- Bab --->
                        <div class="w-full p-4">
                            <div
                                class="border border-gray-400 py-[4px] w-full text-sm text-center font-bold opacity-70">
                                Bab
                            </div>
                            <div class="border border-gray-400 py-[4px] w-full text-sm outline-none px-2">
                                {{ $getBabName->nama_bab }}
                            </div>
                        </div>
                        <!--- Sub Bab --->
                        <div class="w-full p-4">
                            <div
                                class="border border-gray-400 py-[4px] w-full text-sm text-center font-bold opacity-70">
                                Sub Bab
                            </div>
                            <div class="border border-gray-400 py-[4px] w-full text-sm outline-none px-2">
                                {{ $getSubBabName->sub_bab }}
                            </div>
                        </div>
                    </div>

                    <!--- Content Bottom --->
                    <div id="practice-questions-form" data-sub-bab-id="{{ $sub_bab_id }}">
                        <!--- Form practice in ajax --->
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

<script src="{{ asset('js/Features/soal-pembahasan/assessment/practice-question.js') }}"></script> <!--- content form action practice question ---->
