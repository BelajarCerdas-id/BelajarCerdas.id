@include('components/sidebar_beranda', [
    'headerSideNav' => 'Quiz',
    'linkBackButton' => route('EZ.quizDetail.view', [$levelId]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section id="container-quiz-listening-practice-test" data-level-id="{{ $levelId }}">
                    <!-- show content in ajax -->
                </section>
                <div class="pagination-container-question-listening-practice-test"></div>
            </main>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/Features/english-zone/student/quiz/english-zone-listening-practice-test.js') }}"></script> <!--- quiz listening practice test ---->