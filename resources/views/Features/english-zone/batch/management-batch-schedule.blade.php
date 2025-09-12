@include('components/sidebar_beranda', [
    'headerSideNav' => 'Batch Schedule',
    'linkBackButton' => route('EZ.managementBatches.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <!--- alert succes after success insert batch ----->
            <div id="alert-success-insert-batch-schedule"></div>

            <!--- alert succes after success update batch ----->
            <div id="alert-success-update-batch-schedule"></div>

            <!--- alert succes after success update batch ----->
            <div id="alert-success-delete-batch-schedule"></div>

            <main class="bg-white shadow-lg border h-max rounded-lg">
                <section class="border-b p-6">
                    <!-- Batch Name -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Batch</label>
                        <div
                            class="w-full flex items-center border bg-white shadow-lg h-12 rounded-md px-4 cursor-default">
                            <span class="text-sm">{{ $batch_name ?? '' }}</span>
                        </div>
                    </div>

                    <div id="accordion-container" data-batch-id="{{ $batch_id }}"
                        data-batch-name="{{ $batch_name }}">
                        <!-- Accordion Groups (DATABASE) -->
                        <div id="accordion-groups">
                            <div id="grid-list-accordion-groups">
                                <!-- show data in ajax -->
                            </div>
                        </div>

                        <!-- Accordion New Groups -->
                        <div id="accordion-new-groups">
                            <div id="accordion-new-groups-container">
                                <!-- show data in ajax -->
                            </div>
                        </div>
                    </div>

                </section>

                <!--- modal edit batch schedule  ---->
                <dialog id="my_modal_1" class="modal">
                    <div class="modal-box bg-white w-max">
                        <form id="edit-batch-schedule-form">
                            <span class="text-xl font-bold flex justify-center">Edit Schedule</span>

                            <div class="mt-8 w-80">
                                <!-- Day Of Week -->
                                <div class="flex flex-col mt-8">
                                    <h4 class="text-sm font-medium mb-2">Day Of Week</h4>
                                    <select id="day_of_week_id" name="day_of_week" class="select select-bordered">
                                        <option value="" class="hidden">Pilih Hari</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Minggu">Minggu</option>
                                    </select>
                                    <span id=""
                                        class="error-day_of_week text-red-500 font-bold text-xs pt-2"></span>
                                </div>

                                <!-- Start Time -->
                                <div class="flex flex-col mt-4">
                                    <h4 class="text-sm font-medium mb-2">Start Time</h4>
                                    <input type="time" id="start_time_id" name="start_time" value=""
                                        class="input input-bordered" />
                                    <span id=""
                                        class="error-start_time text-red-500 font-bold text-xs pt-2"></span>
                                </div>

                                <!-- End Time -->
                                <div class="flex flex-col mt-4">
                                    <h4 class="text-sm font-medium mb-2">End Time</h4>
                                    <input type="time" id="end_time_id" name="end_time" value=""
                                        class="input input-bordered" />
                                    <span id=""
                                        class="error-end_time text-red-500 font-bold text-xs pt-2"></span>
                                </div>
                            </div>

                            <div class="flex justify-end mt-8">
                                <button
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    <form method="dialog" class="modal-backdrop">
                        <button>Close</button>
                    </form>
                </dialog>

                <!---- modal delete batch schedule  ---->
                <dialog id="my_modal_2" class="modal">
                    <div class="modal-box bg-white">
                        <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                        <p class="py-4">Jadwal yang sudah dihapus tidak dapat dikembalikan.
                            Apakah kamu
                            yakin
                            ingin menghapus jadwal ini?</p>
                        <div class="modal-action">
                            <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                            <form id="delete-batch-schedule-form">
                                <button class="btn btn-error text-white">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button>close</button>
                    </form>
                </dialog>

                <!--- modal insert batch schedule  ---->
                <dialog id="my_modal_3" class="modal">
                    <div class="modal-box bg-white w-max">
                        <form id="insert-schedule-form" data-batch-id="{{ $batch_id }}"
                            data-batch-name="{{ $batch_name }}">
                            <span class="text-xl font-bold flex justify-center">Tambah Jadwal</span>

                            <input type="hidden" id="batch_schedule_group" name="batch_schedule_group" value="">
                            <input type="hidden" id="schedule_time_group" name="schedule_time_group" value="">
                            <div class="mt-8 w-80">
                                <!-- Day Of Week -->
                                <div class="flex flex-col mt-8">
                                    <h4 class="text-sm font-medium mb-2">Day Of Week</h4>
                                    <select id="day_of_week_id" name="day_of_week[]" class="select select-bordered">
                                        <option value="" class="hidden">Pilih Hari</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Minggu">Minggu</option>
                                    </select>
                                    <span id=""
                                        class="error-day_of_week-ins text-red-500 font-bold text-xs pt-2"></span>
                                </div>

                                <!-- Start Time -->
                                <div class="flex flex-col mt-4">
                                    <h4 class="text-sm font-medium mb-2">Start Time</h4>
                                    <input type="time" id="start_time_id" name="start_time[]" value=""
                                        class="input input-bordered" />
                                    <span id=""
                                        class="error-start_time-ins text-red-500 font-bold text-xs pt-2"></span>
                                </div>

                                <!-- End Time -->
                                <div class="flex flex-col mt-4">
                                    <h4 class="text-sm font-medium mb-2">End Time</h4>
                                    <input type="time" id="end_time_id" name="end_time[]" value=""
                                        class="input input-bordered" />
                                    <span id=""
                                        class="error-end_time-ins text-red-500 font-bold text-xs pt-2"></span>
                                </div>
                            </div>

                            <div class="flex justify-end mt-8">
                                <button type="button"
                                    class="submit-button bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    <form method="dialog" class="modal-backdrop">
                        <button>Close</button>
                    </form>
                </dialog>
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

<script src="{{ asset('js/Features/english-zone/batch/management-batches-schedule.js') }}"></script> <!--- management batch schedule ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->

<script src="{{ asset('js/accordion-batch-schedule.js') }}"></script> <!-- accordion batch-schedule script -->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-batch-schedule.js') }}"></script> <!--- pusher listener pada saat insert batch ---->
