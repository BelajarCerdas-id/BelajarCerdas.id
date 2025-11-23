@include('components/sidebar_beranda', [
    'headerSideNav' => 'Exam',
    'linkBackButton' => route('EZ.student.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section>
                    <!--- Content Top --->
                    <div class="grid grid-cols-2 w-full p-4 gap-4 bg-white rounded-lg shadow-lg border mb-8">
                        <!--- Jenis Soal --->
                        <div class="w-full">
                            <div
                                class="border border-gray-400 py-[4px] text-sm text-center font-bold opacity-70">
                                Jenis Soal
                            </div>
                            <div
                                class="border border-gray-400 py-[4px] text-sm text-center font-bold opacity-70">
                                Ujian
                            </div>
                        </div>
                        <div class="w-full">
                            <div
                                class="border border-gray-400 py-[4px] text-xs md:text-sm text-center font-bold opacity-70">
                                Nilai Kamu
                            </div>
                            <div id="score-exam"
                                class="border border-gray-400 py-[4px] text-xs md:text-sm text-center font-bold opacity-70">
                                -
                            </div>
                        </div>
                        <div class="w-full">
                            <div
                                class="border border-gray-400 py-[4px] text-sm text-center font-bold opacity-70">
                                Level
                            </div>
                            <div
                                class="border border-gray-400 py-[4px] text-sm text-center font-bold opacity-70">
                                {{ $levelName ?? '-' }}
                            </div>
                        </div>
                        <div class="w-full">
                            <div
                                class="border border-gray-400 py-[4px] text-sm text-center font-bold opacity-70">
                                Sesi
                            </div>
                            <div
                                class="border border-gray-400 py-[4px] text-sm text-center font-bold opacity-70 px-2">
                                {{ $sessionName ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <!--- Content Bottom --->
                    <div id="exam-questions-form" data-level-id="{{ $levelId }}" data-session-id="{{ $sessionId }}">
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

<script src="{{ asset('js/Features/english-zone/student/english-zone-student-exam-TOEP.js') }}"></script> <!--- content form action practice question ---->
