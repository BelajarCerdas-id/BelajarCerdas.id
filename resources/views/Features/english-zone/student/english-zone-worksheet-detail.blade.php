@include('components/sidebar_beranda', [
    'headerSideNav' => 'Worksheet',
    'linkBackButton' => route('EZ.student.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <div id="dynamic-modal-container-materi"></div>

            <div id="container-worksheet-detail" data-level-id={{ $levelId }}>
                <div id="grid-list-worksheet" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                    <!-- show data in ajax -->
                </div>
            </div>

            <div id="empty-message-materi" class="w-full h-96 hidden">
                <span class="w-full h-full flex items-center justify-center">
                    Tidak ada worksheet yang terdaftar pada level ini.
                </span>
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/Features/english-zone/student/paginate-worksheet-detail.js') }}"></script> <!--- paginate worksheet ---->