@include('components/sidebar_beranda', ['headerSideNav' => 'Ujian'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section>
                    <div class="h-full bg-white shadow-lg border p-4 mb-8">
                        <!--- Content Top --->
                        <div class="flex flex-col xl:flex-row xl:justify-between w-full gap-6">
                            <!--- left side --->
                            <div class="flex w-full md:w-max gap-4">
                                <!--- Jenis Soal --->
                                <div class="w-full md:w-max">
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        Jenis Soal</div>
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        Ujian</div>
                                </div>

                                <!--- Kelas --->
                                <div class="w-full md:w-max">
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        Kelas</div>
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        {{ $kelas }}
                                    </div>
                                </div>

                                <!--- mapel --->
                                <div class="w-full md:w-max col-span-2 md:col-span-1">
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        Mata Pelajaran
                                    </div>
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        {{ $mata_pelajaran }}
                                    </div>
                                </div>
                            </div>

                            <!--- right side --->
                            <div class="flex w-full md:w-max gap-4">
                                <!--- Jenis Soal --->
                                <div class="w-full md:w-max">
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        Waktu Tersisa
                                    </div>
                                    <div id="timer-exam"
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        -
                                    </div>
                                </div>

                                <!--- Kelas --->
                                <div class="w-full md:w-max">
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        Nilai Kamu
                                    </div>
                                    <div id="score-exam"
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        -
                                    </div>
                                </div>

                                <!--- mapel --->
                                <div class="w-full md:w-max col-span-2 md:col-span-1">
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        Durasi Jawab
                                    </div>
                                    <div id="timer-duration"
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        -
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--- Bab --->
                        <div class="w-full pt-4">
                            <div
                                class="border border-gray-400 py-[4px] w-full text-xs md:text-sm text-center font-bold opacity-70">
                                Bab
                            </div>
                            <div class="border border-gray-400 py-[4px] w-full text-xs md:text-sm outline-none px-2">
                                {{ $getBabName->nama_bab }}
                            </div>
                        </div>
                    </div>

                    <!--- Content Bottom --->
                    <div id="exam-questions-form" data-bab-id="{{ $bab_id }}"></div>
                    <!--- Form exam in ajax --->
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

<script src="{{ asset('js/Features/soal-pembahasan/assessment/exam-question.js') }}"></script> <!--- content form action exam question ---->
