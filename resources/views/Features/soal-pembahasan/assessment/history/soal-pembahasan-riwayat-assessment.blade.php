@include('components/sidebar_beranda', [
    'headerSideNav' => 'Riwayat',
    'linkBackButton' => route('soalPembahasanKelas.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <main>
                {{-- <section>
                    @foreach ($getQuestions as $questions => $value)
                        {{ $questions }}
                        @foreach ($value as $item)
                            <div class="">
                                {{ $item->id }}
                            </div>
                        @endforeach
                    @endforeach
                </section> --}}
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
                                        Jenis Soal
                                    </div>
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        Ujian
                                    </div>
                                </div>

                                <!--- Kelas --->
                                <div class="w-full md:w-max">
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        Kelas
                                    </div>
                                    <div
                                        class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                        {{ $kelas ?? '-' }}
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
                                        {{ $mata_pelajaran ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <!--- right side --->
                            @if ($tipe_soal === 'Ujian')
                                <div class="flex w-full md:w-max gap-4">
                                    <!--- Kelas --->
                                    <div class="w-full md:w-max">
                                        <div
                                            class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                            Nilai Kamu
                                        </div>
                                        <div id="score-exam"
                                            class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                            {{ $countScore ?? '-' }}
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
                                            {{ $getDurationExam->exam_answer_duration ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            @endif
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

                        @if ($tipe_soal === 'Latihan')
                            <!--- Bab --->
                            <div class="w-full pt-4">
                                <div
                                    class="border border-gray-400 py-[4px] w-full text-xs md:text-sm text-center font-bold opacity-70">
                                    Sub Bab
                                </div>
                                <div
                                    class="border border-gray-400 py-[4px] w-full text-xs md:text-sm outline-none px-2">
                                    {{ $getSubBabName->sub_bab }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!--- Content Bottom --->
                    <div id="history-questions-assessment-answered" data-materi-id="{{ $materi_id }}"
                        data-tipe-soal-id="{{ $tipe_soal }}" data-date="{{ $date }}"
                        data-kelas-id="{{ $kelas }}" data-mapel-id="{{ $mata_pelajaran }}">
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

<script src="{{ asset('js/Features/soal-pembahasan/riwayat-assessment/history-questions-assessment-answered.js') }}">
</script> <!--- paginate history asesmen ---->
