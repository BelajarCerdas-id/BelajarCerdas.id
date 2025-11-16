@include('components/sidebar_beranda', ['headerSideNav' => 'English Zone'])

@if (Auth::user()->role === 'Siswa')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <div id="dynamic-modal-container-materi"></div>

            <div class="bg-[--color-second] w-full h-20 shadow-lg rounded-t-xl flex items-center pl-10 mb-4">
                <div class="text-white font-bold flex items-center gap-3">
                    <span class="text-xl">English Zone</span>
                </div>
            </div>

            <div class="flex mt-12">
                <div class="w-full hover:bg-gray-100" onclick="contentMateri()">
                    <input type="radio" class="hidden" name="radio" id="radio1" checked>
                    <div class="checked-timeline">
                        <label for="radio1" class="cursor-pointer">
                            <span class="text-md flex justify-center relative top-1">Materi</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
                <div class="w-full hover:bg-gray-100" onclick="contentAttendanceHistory()">
                    <input type="radio" class="hidden" name="radio" id="radio2">
                    <div class="checked-timeline">
                        <label for="radio2" class="cursor-pointer">
                            <span class="text-md flex justify-center relative top-1">Kehadiran</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="py-6 relative overflow-hidden">
                <div class="w-full h-auto" id="content-materi">
                    <!--- filter by level --->
                    <div id="container-dropdown-filter-level" class="mr-4">
                        <!-- show data in ajax -->
                    </div>

                    <div id="container-materi-student" data-level-id="{{ $levelIds }}">
                        <div id="grid-list-materi" class="container-accordion mb-8">
                                <!-- show data in ajax -->
                        </div>
                    </div>

                    <div id="container-worksheet-quiz-student">
                        <!-- show data in ajax -->
                    </div>

                    <div id="empty-message-materi" class="w-full h-96 hidden">
                        <span class="w-full h-full flex items-center justify-center">
                            Tidak ada materi yang terdaftar.
                        </span>
                    </div>
                </div>

                <div class="w-full h-auto hidden" id="content-attendance-history">
                    history
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

<script src="{{ asset('js/Features/english-zone/student/paginate-english-zone-materi.js') }}"></script> <!--- paginate materi ---->
<script src="{{ asset('js/Features/english-zone/student/toggle-content-materi-attendance-history.js') }}"></script> <!--- toggle content materi, attendance history ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/accordion-content.js') }}"></script> <!--- accordion template script ---->