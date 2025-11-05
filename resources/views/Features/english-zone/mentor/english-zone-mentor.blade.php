@include('components/sidebar_beranda', ['headerSideNav' => 'English Zone'])

@if (Auth::user()->role === 'Mentor')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <div class="flex mt-4">
                <div class="w-full hover:bg-gray-100" onclick="contentNonSchoolPartner()">
                    <input type="radio" class="hidden" name="radio" id="radio1" checked>
                    <div class="checked-timeline">
                        <label for="radio1" class="cursor-pointer">
                            <span class="text-md flex justify-center relative top-1">Non School Partner</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
                <div class="w-full hover:bg-gray-100" onclick="contentSchoolPartner()">
                    <input type="radio" class="hidden" name="radio" id="radio2">
                    <div class="checked-timeline">
                        <label for="radio2" class="cursor-pointer">
                            <span class="text-md flex justify-center relative top-1">School Partner</span>
                            <div class="w-full border-b-[1px] border-gray-200 h-2"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="py-6 relative overflow-hidden">
                <div class="w-full h-auto" id="content-non-school-partner">
                    <div class="overflow-x-auto mt-4 pb-52">
                        <table class="table" id="table-management-student-batch-non-school-partner">
                            <thead class="thead-table-management-student-batch-non-school-partner hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Langganan</th>
                                    <th class="th-table text-black opacity-70">Level</th>
                                    <th class="th-table text-black opacity-70">Batch</th>
                                    <th class="th-table text-black opacity-70">Hari</th>
                                    <th class="th-table text-black opacity-70">Jam</th>
                                    <th class="th-table text-black opacity-70">Masa Aktif</th>
                                    <th class="th-table text-black opacity-70">Peserta</th>
                                    <th class="th-table text-black opacity-70">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-table-management-student-batch-non-school-partner">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-management-student-batch-non-school-partner flex justify-center my-4 sm:my-0">
                        </div>

                        <div id="empty-message-management-student-batch-non-school-partner" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar student batch non school partner.
                            </span>
                        </div>
                    </div>
                </div>
                <div id="content-school-partner" class="w-full h-auto hidden">
                    <div class="mx-4">
                        <label class="input input-bordered flex items-center gap-2 w-max mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-100" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z" />
                            </svg>
                                <input type="search" class="search_school_partner grow text-sm placeholder-black" autocomplete="OFF"
                                placeholder="Cari sekolah..." />
                        </label>
                    </div>

                    <div class="overflow-x-auto mt-4 pb-52">
                        <table class="table" id="table-management-student-batch-school-partner">
                            <thead class="thead-table-management-student-batch-school-partner hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Sekolah</th>
                                    <th class="th-table text-black opacity-70">Langganan</th>
                                    <th class="th-table text-black opacity-70">Level</th>
                                    <th class="th-table text-black opacity-70">Batch</th>
                                    <th class="th-table text-black opacity-70">Hari</th>
                                    <th class="th-table text-black opacity-70">Jam</th>
                                    <th class="th-table text-black opacity-70">Masa Aktif</th>
                                    <th class="th-table text-black opacity-70">Peserta</th>
                                    <th class="th-table text-black opacity-70">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-table-management-student-batch-school-partner">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-management-student-batch-school-partner flex justify-center my-4 sm:my-0">
                        </div>

                        <div id="empty-message-management-student-batch-school-partner" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar student batch school partner.
                            </span>
                        </div>
                    </div>
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
    src="{{ asset('js/Features/english-zone/mentor-student-batch/content-mentor-student-batch-toggle.js') }}">
</script> <!--- content mentor student batch toggle ---->

<script src="{{ asset('js/Features/english-zone/mentor-student-batch/paginate-mentor-student-batch-non-school-partner.js') }}"></script>

<script src="{{ asset('js/Features/english-zone/mentor-student-batch/paginate-mentor-student-batch-school-partner.js') }}"></script>