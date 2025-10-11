@include('components/sidebar_beranda', [
    'headerSideNav' => 'User Subscription',
    'linkBackButton' => route('schoolSubscription.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda mt-[120px]">
            <main>
                <section>
                    <!---- SCHOOL IDENTITY ---->
                    <div id="school-identity" class="mb-10">
                        <!-- show data in ajax -->
                    </div>

                    <span class="text-lg font-bold opacity-70">LIST USER SCHOOL PARTNER</span>

                    <div class="my-8 flex flex-col md:flex-row md:items-center md:justify-between gap-8">
                        <!--- search bar --->
                        <label class="input input-bordered flex items-center gap-2 w-66 md:w-max">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z" />
                            </svg>
                            <input id="search_student" type="search" class="grow text-sm"
                                placeholder="Cari siswa..." />
                        </label>

                        <!--- dropdown access control --->
                        <details class="dropdown dropdown-end">
                            <summary tabindex="0"
                                class="btn m-1 font-bold border-none outline-none bg-[#4189E0] text-white hover:bg-[#4189E0]">
                                Kontrol Akses Fitur Semua Siswa
                            </summary>

                            <ul tabindex="0" id="dropdown-features-access-control"
                                class="dropdown-content menu bg-base-100 rounded-box z-1 w-full p-2 shadow-sm">
                                <!--- show list in ajax --->
                            </ul>
                        </details>
                    </div>

                    <!---- table list user school partner subscription ---->
                    <div id="container-user-school-partner-list" class="overflow-x-auto"
                        data-school-id="{{ $schoolId }}" data-features-order="{{ $countFeatures->values() }}">
                        <table class="table" id="table-user-school-partner-list">
                            <thead class="thead-table-user-school-partner-list hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70" rowspan="2">No</th>
                                    <th class="th-table text-black opacity-70" rowspan="2">Nama Siswa</th>
                                    <th class="th-table text-black opacity-70" rowspan="2">Fase</th>
                                    <th class="th-table text-black opacity-70" rowspan="2">Kelas</th>
                                    <th class="th-table text-black opacity-70"
                                        colspan="{{ $countFeatures->count() * 2 ?? 0 }}">
                                        Features
                                    </th>
                                </tr>
                                <tr>
                                    @foreach ($countFeatures as $fitur)
                                        <th class="border border-gray-300 text-center" colspan="2">
                                            {{ $fitur }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="tbody-user-school-partner-list">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>

                        <div class="pagination-container-user-school-partner-list flex justify-center my-4 sm:my-0">
                        </div>

                        <div id="empty-message-user-school-partner-list" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada siswa yang terdaftar berlangganan pada sekolah ini.
                            </span>
                        </div>
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

<script src="{{ asset('js/school-partner/paginate-list-user-school-partner.js') }}"></script> <!--- form action school partner ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/school-partner/list-school-partner-user-subscription-listener.js') }}">
</script> <!--- pusher listener list school partner user subscription ---->
