@include('components/sidebar_beranda', [
    'headerSideNav' => 'Quiz',
    'linkBackButton' => route('EZ.student.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <div id="container-list-quiz" data-level-id="{{ $levelId }}">
                <div id="grid-list-quiz" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <!-- show cards in ajax -->
                </div>
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/Features/english-zone/student/english-zone-quiz-detail.js') }}"></script> <!--- quiz detail (latihan, ujian) ---->