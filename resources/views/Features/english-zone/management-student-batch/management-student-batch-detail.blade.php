@include('components/sidebar_beranda', [
    'headerSideNav' => 'Student Batch Detail',
    'linkBackButton' => route('EZ.managementStudentBatch.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">

            <div id="alert-success-reschedule-student-batch"></div>

            <div id="alert-success-refund-student-batch"></div>

            <div id="student-batch-detail-identity" class="mb-10">
                <!---- shwo data in ajax ---->
            </div>

            <div class="overflow-x-auto mt-4" id="container-management-student-batch-detail"  
                data-feature-variant-id="{{ $featureVariantId }}" data-level-id="{{ $levelId }}" data-batch-id="{{ $batchId }}" data-batch-schedule-groups="{{ $batchScheduleGroups }}"
                data-batch-schedule-ids="{{ $batchScheduleIds }}" data-student-id="{{ $studentIds }}" data-school-partner-id="{{ $schoolPartnerId }}">
                <table class="table" id="table-management-student-batch">
                    <thead class="thead-table-management-student-batch hidden">
                        <tr>
                            <th class="th-table text-black opacity-70">No</th>
                            <th class="th-table text-black opacity-70">Nama Siswa</th>
                            <th class="th-table text-black opacity-70">No.HP</th>
                            <th class="th-table text-black opacity-70">Email</th>
                            <th class="th-table text-black opacity-70" colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-table-management-student-batch">
                        <!-- show data in ajax -->
                    </tbody>
                </table>

                <div class="pagination-container-management-student-batch flex justify-center my-4 sm:my-0">
                </div>

                <div id="empty-message-management-student-batch" class="w-full h-96 hidden">
                    <span class="w-full h-full flex items-center justify-center">
                        Tidak ada daftar student batch detail.
                    </span>
                </div>
            </div>

            <!---- modal edit reschedule  ---->
            <dialog id="my_modal_1" class="modal">
                <div class="modal-box bg-white w-max">

                    <!-- untuk menghilangkan focus input type pada saat open modal  --->
                    <div tabindex="-1"></div> <!-- Tambahkan ini -->

                    <form id="reschedule-student-batch-form" autocomplete="OFF">
                        <span class="text-xl font-bold flex justify-center">Reschedule</span>

                        <input type="hidden" id="input-batch-schedule-group-ids" name="batch_schedule_group">
                        <input type="hidden" id="input-batch-schedule-ids" name="batch_schedule_id">
                        <input type="hidden" id="input-student-batch-ids" name="student_batch_id">
                        <input type="hidden" id="input-feature-variant-id" name="feature_variant_id">
                        <input type="hidden" id="input-level-id" name="level_id">
                        <input type="hidden" id="input-school-partner-id" name="school_partner_id">
                        <input type="hidden" id="input-transaction-source" name="transaction_source">

                        <!--- Link Zoom--->
                        <div class="flex flex-col mt-4 w-96">
                            <label class="text-sm font-bold opacity-70">
                                Batch
                                <sup class="text-red-500">&#42;</sup>
                            </label>
                            <select id="batch_id" name="batch_id" class="select select-bordered">
                                <option value="" class="hidden">Pilih Batch</option>
                                @foreach ($getBatch as $item)
                                    <option value="{{ $item->id }}">{{ $item->batch_name }}</option>
                                @endforeach
                            </select>
                            <span id="error-batch_id" class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                        </div>

                        <!-- Meeting ID -->
                        <div class="flex flex-col mt-4">
                            <label class="text-sm font-bold opacity-70">
                                Hari
                                <sup class="text-red-500">&#42;</sup>
                            </label>
                            <select id="day_of_week_id" name="day_of_week_id"
                                class="select select-bordered opacity-50 !cursor-default" disabled>
                                <option value="" class="hidden">Pilih Hari</option>
                            </select>
                            <span id="error-day_of_week_id" class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                        </div>

                        <!-- Passcode -->
                        <div class="flex flex-col mt-4">
                            <label class="text-sm font-bold opacity-70">
                                Jam
                                <sup class="text-red-500">&#42;</sup>
                            </label>
                            <select id="hours_id" name="hours_id"
                                class="select select-bordered opacity-50 !cursor-default" disabled>
                                <option value="" class="hidden">Pilih Hari</option>
                            </select>
                            <span id="error-hours_id" class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-8">
                            <button type="button" id="submit-button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>

                <!---- modal refund batch user  ---->
                <dialog id="my_modal_2" class="modal">
                    <div class="modal-box bg-white">
                        <h3 class="font-bold text-lg text-red-600">Konfirmasi Refund</h3>
                        <p class="py-4">Masa aktif paket pada batch user yang di refund akan secara otomatis di non-aktifkan dan tidak dapat diaktifkan kembali, 
                            pastikan kamu telah melakukan konfirmasi dan melakukan refund (pengembalian uang) kepada user yang bersangkutan.
                            Apakah kamu yakin
                            ingin refund batch user ini?</p>
                        <div class="modal-action">
                            <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                            <form id="refund-batch-user-form">
                                <button class="btn btn-error text-white">
                                    Ya, Refund
                                </button>
                            </form>
                        </div>
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button>close</button>
                    </form>
                </dialog>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<!--- jika schoolPartnerId ada maka jalankan paginate management student batch detail school partner ---->
@if ($schoolPartnerId)
    <script
        src="{{ asset('js/Features/english-zone/management-student-batch/paginate-student-batch-detail-school-partner.js') }}">
    </script> <!--- paginate management student batch detail ---->
@else
    <script
        src="{{ asset('js/Features/english-zone/management-student-batch/paginate-student-batch-detail-non-school-partner.js') }}">
    </script> <!--- paginate management student batch detail ---->
@endif

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-student-batch-detail-reschedule.js') }}"></script> <!--- pusher listener pada saat reschedule student batch detail ----->
<script src="{{ asset('js/pusher-listener/english-zone/management-student-batch-detail-refund.js') }}"></script> <!--- pusher listener pada saat refund student batch detail ----->