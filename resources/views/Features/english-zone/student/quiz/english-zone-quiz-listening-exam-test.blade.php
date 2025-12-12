@include('components/sidebar_beranda', [
    'headerSideNav' => 'Quiz',
    'linkBackButton' => route('EZ.quizDetail.view', [$levelId]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <div class="w-full flex flex-col items-end">
                    <div id="container-score-exam" class="w-max hidden">
                        <div
                            class="w-full border border-gray-400 py-[4px] text-xs md:text-sm text-center font-bold opacity-70 px-4">
                            Score Passage
                        </div>
                        <div id="score-exam"
                            class="w-full border border-gray-400 py-[4px] text-xs md:text-sm text-center font-bold opacity-70">
                            -
                        </div>
                    </div>
                </div>
                <section id="container-quiz-listening-exam-test" data-level-id="{{ $levelId }}">
                    <!-- show content in ajax -->
                </section>
                <div class="pagination-container-question-listening-exam-test"></div>
            </main>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/Features/english-zone/student/quiz/english-zone-listening-exam-test.js') }}"></script> <!--- quiz listening exam test ---->