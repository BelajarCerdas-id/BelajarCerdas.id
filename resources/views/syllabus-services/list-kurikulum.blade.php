@include('components/sidebar_beranda', ['headerSideNav' => 'Kurikulum'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-insert-data-kurikulum'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-data-kurikulum'),
                ])
            @endif

            @if (session('success-import-data-sub-bab'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-import-data-sub-bab'),
                ])
            @endif

            <!--- alert nya menggunakan dari response json --->
            <div id="alert-success-insert-data-curiculum"></div>
            <div id="alert-success-update-data-curiculum"></div>
            <div id="alert-success-delete-data-curiculum"></div>
            <div id="alert-success-import-syllabus"></div>

            <main>
                <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                    <!---- BulkUpload Button  ---->
                    <div class="flex justify-end mb-10 lg:mb-0">
                        <button
                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold h-8 px-6 rounded-lg shadow-md transition-all text-sm flex gap-2 items-center justify-center"
                            onclick="my_modal_4.showModal()">
                            <i class="fa-solid fa-circle-plus"></i>
                            Bulk Upload
                        </button>
                    </div>
                    <!---- Form input kurikulum  ---->
                    <form action="{{ route('kurikulum.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
                            <div class="w-full">
                                <label class="text-sm">Nama Kurikulum<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <div class="flex relative max-w-lg mt-2">
                                    <div class="flex gap-2 w-full">
                                        <input type="text" name="nama_kurikulum"
                                            class="w-full bg-white shadow-lg h-11 border-gray-200 border-[2px] outline-none rounded-full text-xs px-2
                                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]
                                            {{ $errors->has('nama_kurikulum') && session('formError') === 'create' ? 'border-[1px] border-red-400' : '' }}"
                                            value="{{ $errors->has('nama_kurikulum') && session('formError') === 'create' ? old('nama_kurikulum') : '' }}"
                                            placeholder="Masukkan Nama Kurikulum">
                                        <button
                                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                            Tambah
                                        </button>
                                    </div>
                                </div>
                                @if ($errors->has('nama_kurikulum') && session('formError') === 'create')
                                    <span
                                        class="text-red-500 font-bold text-xs pt-2">{{ $errors->first('nama_kurikulum') }}</span>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="border-b-2 border-gray-200 mt-4"></div>

                    <!---- Table list data kurikulum  ---->
                    <div class="overflow-x-auto mt-8 pb-24">
                        <table id="tableSyllabusCuriculum" class="table w-full border-collapse border border-gray-300">
                            <thead class="thead-table-syllabus-curiculum hidden">
                                <tr>
                                    <th class="border border-gray-300 w-[80%]">
                                        Kurikulum
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Detail
                                    </th>
                                    <th class="!text-center border border-gray-300">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tableListSyllabusCuriculum">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container-syllabus-curiculum flex justify-center my-4 sm:my-0"></div>

                    <div id="emptyMessageSyllabusCuriculum" class="w-full h-96 hidden">
                        <span class="w-full h-full flex items-center justify-center">
                            Tidak ada Kurikulum.
                        </span>
                    </div>

                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white w-max">
                            <form id="curiculumForm">
                                <span class="text-xl font-bold flex justify-center">Edit Kurikulum</span>

                                <div class="mt-4 w-80">
                                    <label class="text-sm">Nama Kurikulum</label>
                                    <input type="text" id="nama_kurikulum" name="nama_kurikulum"
                                        class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2"
                                        value="" placeholder="Masukkan Nama Kurikulum">
                                    <span id="error-nama-kurikulum" class="text-red-500 text-xs mt-1 font-bold"></span>
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
                    <!---- modal history kurikulum  ---->
                    <dialog id="my_modal_2" class="modal">
                        <div class="modal-box bg-white text-center">
                            <span class="text-2xl">History Kurikulum</span>
                            <div class="flex items-center justify-between mt-6">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-circle-user text-5xl"></i>
                                    <div class="flex flex-col text-start">
                                        <span id="text-nama_lengkap"></span>
                                        <span id="text-status" class="text-sm"></span>
                                        <span id="text-updated_at" class="text-xs leading-6"></span>
                                    </div>
                                </div>
                                <div class="">
                                    <span class="text-[#4189e0] text-sm">Publisher</span>
                                </div>
                            </div>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>

                    <!---- modal delete bab  ---->
                    <dialog id="my_modal_3" class="modal">
                        <div class="modal-box bg-white">
                            <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                            <p class="py-4">Semua yang berkaitan dengan kurikulum ini akan dihapus secara
                                permanen.
                                Apakah kamu
                                yakin
                                ingin menghapus kurikulum ini?</p>
                            <div class="modal-action">
                                <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                                <form id="deleteCuriculumForm">
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

                    <!---- modal BulkUpload  ---->
                    <dialog id="my_modal_4" class="modal">
                        <div class="modal-box bg-white w-max">

                            <div class="flex justify-center font-bold opacity-70">
                                <span class="">Upload Syllabus</span>
                                <sup class="text-red-500 pl-1 pt-4 text-md">&#42;</sup>
                            </div>

                            <form id="bulkUpload-syllabus-form" enctype="multipart/form-data">
                                <div class="w-full mt-4">
                                    <div class="w-full h-auto">

                                        <!--- show bulkUpload word errors --->
                                        <div id="error-bulkUpload-excel" class="my-4 max-h-42 overflow-y-auto"></div>

                                        <div class="text-xs mt-1">
                                            <span>Maksimum ukuran file 10MB. <br> File dapat dalam format .xlsx.</span>
                                        </div>
                                        <div class="upload-icon">
                                            <div class="flex flex-col max-w-[260px]">
                                                <div id="excelPreview" class="max-w-[280px] cursor-pointer mt-4">
                                                    <div id="excelPreviewContainer-bulkUpload-excel"
                                                        class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                        <div class="flex items-center">
                                                            <img id="pdfLogo-bulkUpload-excel" class="w-[56px] h-max">
                                                            <div class="mt-2 leading-5">
                                                                <span id="textPreview-bulkUpload-excel"
                                                                    class="font-bold text-sm"></span><br>
                                                                <span id="textSize-bulkUpload-excel"
                                                                    class="text-xs"></span>
                                                                <span id="textCircle-bulkUpload-excel"
                                                                    class="relative top-[-2px] text-[5px]"></span>
                                                                <span id="textPages-bulkUpload-excel"
                                                                    class="text-xs"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="content-upload w-[385px] h-9 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6 mb-2">
                                        <label for="bulkUpload-excel"
                                            class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            <span>Upload File</span>
                                        </label>
                                        <input id="bulkUpload-excel" name="bulkUpload-syllabus" class="hidden"
                                            onchange="previewExcel(event, 'bulkUpload-excel')" type="file"
                                            accept=".xlsx">
                                    </div>
                                </div>
                                <!-- Tombol Kirim -->
                                <div class="flex justify-end mt-8">
                                    <button
                                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all outline-none">
                                        Kirim
                                    </button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif


<script src="{{ asset('js/syllabus-services/paginate-syllabus-kurikulum-ajax.js') }}"></script> <!--- paginate kurikulum ---->
<script src="{{ asset('js/upload-excel.js') }}"></script> <!--- show excel ---->
<script src="{{ asset('js/syllabus-services/form-action-bulkUpload-syllabus.js') }}"></script> <!--- form action bulkUpload syllabus ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/syllabus-services/list-kurikulum-listener.js') }}"></script> <!--- pusher listener list kurikulum ---->
