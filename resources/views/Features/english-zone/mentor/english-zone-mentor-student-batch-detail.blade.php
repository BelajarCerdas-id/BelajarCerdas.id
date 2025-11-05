@include('components/sidebar_beranda', [
    'headerSideNav' => 'Student Batch Detail',
    'linkBackButton' => route('EZ.mentor.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">

            <div id="student-batch-detail-identity" class="mb-10">
                <!---- shwo data in ajax ---->
            </div>

            <div class="flex mt-4">
                <div class="w-full hover:bg-gray-100" onclick="contentMateri()">
                    <input type="radio" class="hidden" name="radio" id="radio1" checked>
                    <div class="checked-timeline">
                        <label for="radio1" class="cursor-pointer">
                            <span class="text-md flex justify-center relative top-1">Materi</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
                <div class="w-full hover:bg-gray-100" onclick="contentStudentList()">
                    <input type="radio" class="hidden" name="radio" id="radio2">
                    <div class="checked-timeline">
                        <label for="radio2" class="cursor-pointer">
                            <span class="text-md flex justify-center relative top-1">Daftar Siswa</span>
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

                    <div id="container-management-student-batch-detail-materi" data-level-id="{{ $levelId }}" data-student-id="{{ $studentIds }}">
                        <div id="grid-list-materi" class="container-accordion mb-8">
                            <!-- show data in ajax -->
                        </div>
                    </div>

                    <div id="empty-message-materi" class="w-full h-96 hidden">
                        <span class="w-full h-full flex items-center justify-center">
                            Tidak ada materi yang terdaftar.
                        </span>
                    </div>
                </div>
                
                <div id="content-student-list" class="w-full h-auto hidden">
                    <div class="overflow-x-auto" id="container-management-student-batch-detail"  
                        data-feature-variant-id="{{ $featureVariantId }}" data-level-id="{{ $levelId }}" data-batch-id="{{ $batchId }}" data-batch-schedule-groups="{{ $batchScheduleGroups }}"
                        data-batch-schedule-ids="{{ $batchScheduleIds }}" data-student-id="{{ $studentIds }}">
                        <table class="table" id="table-management-student-batch">
                            <thead class="thead-table-management-student-batch hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Nama Siswa</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-table-management-student-batch">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-management-student-batch flex justify-center my-4 sm:my-0">
                        </div>

                    </div>
                </div>
                
                <div id="empty-message-management-student-batch" class="w-full h-96 hidden">
                    <span class="w-full h-full flex items-center justify-center">
                        Tidak ada daftar student batch detail.
                    </span>
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

<script
    src="{{ asset('js/Features/english-zone/mentor-student-batch/paginate-mentor-student-batch-detail.js') }}">
</script> <!--- paginate mentor student batch detail ---->

<script
    src="{{ asset('js/Features/english-zone/mentor-student-batch/paginate-mentor-student-batch-detail-materi.js') }}">
</script> <!--- paginate mentor student batch detail materi ---->

<script
    src="{{ asset('js/Features/english-zone/mentor-student-batch/content-mentor-student-batch-detail-toggle.js') }}">
</script> <!--- content mentor student batch detail toggle ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/accordion-content.js') }}"></script> <!--- accordion template script ---->