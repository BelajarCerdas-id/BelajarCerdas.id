@include('components/sidebar_beranda', [
    'headerSideNav' => 'Quiz',
    'linkBackButton' => route('EZ.quizDetail.view', [$levelId]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <!--- alert succes after success submit answer ----->
            <div id="alert-success-submit-answer"></div>

            <main>
                <div class="w-full flex justify-end mb-4">
                    <div class="flex w-full md:w-max gap-4">

                        <div class="w-full md:w-max">
                            <div
                                class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                Waktu
                            </div>
                            <div id="timer-exam"
                                class="border border-gray-400 py-[4px] w-full md:w-32 text-xs md:text-sm text-center font-bold opacity-70">
                                -
                            </div>
                        </div>

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
                <section id="container-quiz-writing-exam-test" data-level-id="{{ $levelId }}">
                    <!-- show content in ajax -->
                </section>
                <div class="pagination-container-question-writing-exam-test"></div>
            </main>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/Features/english-zone/student/quiz/english-zone-writing-exam-test.js') }}"></script> <!--- quiz writing exam test ---->