@include('components/sidebar_beranda', ['headerSideNav' => 'Mentor Schedule'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <!--- alert succes after success insert batch ----->
            <div id="alert-success-insert-batch"></div>

            <!--- alert succes after success update batch ----->
            <div id="alert-success-update-batch"></div>

            <main class="bg-white shadow-lg border h-max rounded-lg">
                <section class="border-b">
                    <div class="p-6 space-y-6">
                        <h2 class="text-xl font-bold opacity-70">Assign Mentor ke Jadwal Batch</h2>

                        <div class="my-8 flex flex-col md:flex-row md:items-center md:justify-between gap-8">
                            <!--- search bar --->
                            <label class="input input-bordered flex items-center gap-2 w-66 md:w-max">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z" />
                                </svg>
                                <input id="search_mentor" type="search" class="grow text-sm"
                                    placeholder="Cari Mentor..." />
                            </label>

                            <select id="dropdown-filter-batch" class="select border-2 border-gray-200 w-66 md:w-max">
                                <option value="" class="hidden">Filtered By Batch</option>
                                @foreach ($getBatch as $item)
                                    <option value="{{ $item->batch_name }}">{{ $item->display_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!--- table mentor schedule list --->
                        <div class="overflow-x-auto my-4 pb-10">
                            <table class="table" id="table-management-mentor-schedule">
                                <thead class="thead-table-management-mentor-schedule hidden">
                                    <!-- show data in ajax -->
                                </thead>
                                <tbody id="table-list-management-mentor-schedule">
                                    <!-- show data in ajax -->
                                </tbody>
                            </table>

                            <div
                                class="pagination-container-management-mentor-schedule flex justify-center my-4 sm:my-0">
                            </div>

                            <div id="empty-message-management-mentor-schedule" class="w-full h-96 hidden">
                                <span class="w-full h-full flex items-center justify-center">
                                    Tidak ada mentor yang terdaftar pada fitur English Zone.
                                </span>
                            </div>

                            <div id="empty-message-schedule" class="w-full h-96 hidden">
                                <span class="w-full h-full flex items-center justify-center">
                                    Tidak ada schedule pada batch ini.
                                </span>
                            </div>
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

<script src="{{ asset('js/Features/english-zone/mentor-schedule/management-mentor-schedule.js') }}"></script> <!--- management mentor schedule ---->


<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-mentor-schedule.js') }}"></script>
