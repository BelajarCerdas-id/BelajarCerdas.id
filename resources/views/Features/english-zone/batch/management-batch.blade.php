@include('components/sidebar_beranda', ['headerSideNav' => 'Management Batches'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <!--- alert succes after success insert batch ----->
            <div id="alert-success-insert-batch"></div>

            <!--- alert succes after success update batch ----->
            <div id="alert-success-update-batch"></div>

            <main class="bg-white shadow-lg border h-max rounded-lg">
                <section class="border-b">
                    <form id="management-batch-form" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 py-10 px-6">
                            <!--- Batch Name--->
                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Batch<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="batch_name" id="batch_name"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    data-old-batch_name="{{ old('batch_name') }}">
                                    <option value="" class="hidden">Pilih Batch</option>
                                    @foreach (range(1, 12) as $index)
                                        <option value="Batch {{ $index }}"
                                            {{ old('batch_name') == 'Batch ' . $index ? 'selected' : '' }}>
                                            Batch {{ $index }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="error-batch_name" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <!--- Start Day --->
                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Hari<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="start_day" id="start_day"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    data-old-start_day="{{ old('start_day') }}">
                                    <option value="" class="hidden">Pilih Hari</option>
                                    @foreach (range(1, 31) as $index)
                                        <option value="{{ sprintf('%02d', $index) }}">
                                            {{ sprintf('%02d', $index) }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="error-start_day" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <!--- Start Month --->
                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Bulan<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="start_month" id="start_month"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    data-old-start_month="{{ old('start_month') }}">
                                    <option value="" class="hidden">Pilih Bulan</option>
                                    @foreach (range(1, 12) as $index)
                                        <option value="{{ sprintf('%02d', $index) }}">
                                            {{ sprintf('%02d', $index) }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="error-start_month" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <!--- Max Capacity --->
                            <div class="flex flex-col">
                                <label class="mb-2 text-sm">Kapasitas User<sup
                                        class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="max_capacity" id="max_capacity"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    data-old-max_capacity="{{ old('max_capacity') }}">
                                    <option value="" class="hidden">Pilih Jumlah Kapasitas</option>
                                    <option value="10" {{ old('max_capacity') == 10 ? 'selected' : '' }}>
                                        10
                                    </option>
                                    <option value="20" {{ old('max_capacity') == 20 ? 'selected' : '' }}>
                                        20
                                    </option>
                                    <option value="30" {{ old('max_capacity') == 30 ? 'selected' : '' }}>
                                        30
                                    </option>
                                </select>
                                <span id="error-max_capacity" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <!--- button add batch --->
                            <div class="flex items-center w-full mt-6">
                                <button type="button" id="submit-button"
                                    class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold w-full h-10 px-6 rounded-lg shadow-md transition-all text-sm flex gap-2 items-center justify-center">
                                    <i class="fa-solid fa-circle-plus"></i>
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </section>

                <section class="py-6">
                    <span class="text-lg font-bold px-4 opacity-70">BATCH LIST</span>

                    <!--- table batch list --->
                    <div class="overflow-x-auto m-4 pb-10">
                        <table class="table" id="table-management-batch">
                            <thead class="thead-table-management-batch hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Batch</th>
                                    <th class="th-table text-black opacity-70">Start Batch</th>
                                    <th class="th-table text-black opacity-70">Kapasitas User</th>
                                    <th class="th-table text-black opacity-70">Detail</th>
                                    <th class="th-table text-black opacity-70">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table-list-management-batch">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>

                        <div class="pagination-container-management-batch flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-management-batch" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar batch list.
                            </span>
                        </div>
                    </div>
                </section>

                <!---- modal edit batch  ---->
                <dialog id="my_modal_1" class="modal">
                    <div class="modal-box bg-white w-max">

                        <!-- untuk menghilangkan focus input type pada saat open modal  --->
                        <div tabindex="-1"></div> <!-- Tambahkan ini -->

                        <form id="edit-batch-form">
                            <span class="text-xl font-bold flex justify-center">Edit Batch</span>

                            <!--- Batch Name--->
                            <div class="flex flex-col mt-4 w-96">
                                <label class="mb-2 text-sm">Batch<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="batch_name" id="batch_name_id"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[1px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    data-old-batch_name="{{ old('batch_name') }}">
                                    <option value="" class="hidden">Pilih Batch</option>
                                    @foreach (range(1, 12) as $index)
                                        <option value="Batch {{ $index }}"
                                            {{ old('batch_name') == 'Batch ' . $index ? 'selected' : '' }}>
                                            Batch {{ $index }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="error-batch_name" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <!--- Start Day --->
                            <div class="flex flex-col mt-4 w-96">
                                <label class="mb-2 text-sm">Hari<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="start_day" id="start_day_id"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[1px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    data-old-start_day="{{ old('start_day') }}">
                                    <option value="" class="hidden">Pilih Hari</option>
                                    @foreach (range(1, 31) as $index)
                                        <option value="{{ sprintf('%02d', $index) }}">
                                            {{ sprintf('%02d', $index) }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="error-start_day" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <!--- Start Month --->
                            <div class="flex flex-col mt-4 w-96">
                                <label class="mb-2 text-sm">Bulan<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="start_month" id="start_month_id"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[1px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    data-old-start_mont="{{ old('start_month') }}">
                                    <option value="" class="hidden">Pilih Bulan</option>
                                    @foreach (range(1, 12) as $index)
                                        <option value="{{ sprintf('%02d', $index) }}">
                                            {{ sprintf('%02d', $index) }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="error-start_month" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

                            <!--- Max Capacity --->
                            <div class="flex flex-col mt-4 w-96">
                                <label class="mb-2 text-sm">Kapasitas Batch<sup
                                        class="text-red-500 pl-1">&#42;</sup></label>
                                <select name="max_capacity" id="max_capacity_id"
                                    class="w-full bg-white shadow-lg h-12 text-sm border-gray-200 border-[1px] outline-none rounded-md px-2 focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue] cursor-pointer"
                                    data-old-max_capacity="{{ old('max_capacity') }}">
                                    <option value="" class="hidden">Pilih Jumlah Kapasitas</option>
                                    <option value="10" {{ old('max_capacity') == 10 ? 'selected' : '' }}>
                                        10
                                    </option>
                                    <option value="20" {{ old('max_capacity') == 20 ? 'selected' : '' }}>
                                        20
                                    </option>
                                    <option value="30" {{ old('max_capacity') == 30 ? 'selected' : '' }}>
                                        30
                                    </option>
                                </select>
                                <span id="error-max_capacity" class="text-red-500 font-bold text-xs pt-2"></span>
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
            </main>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/Features/english-zone/batch/management-batches.js') }}"></script> <!--- management batch ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-batch.js') }}"></script> <!--- pusher listener pada saat insert batch ---->
