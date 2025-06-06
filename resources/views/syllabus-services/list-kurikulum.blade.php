@include('components/sidebar_beranda', ['headerSideNav' => 'Kurikulum'])
@extends('components/sidebar_beranda_mobile')

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            @if (session('success-insert-data-kurikulum'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-insert-data-kurikulum'),
                ])
            @endif
            <!--- alert nya menggunakan dari response json --->
            <div id="alert-success-update-data-curiculum"></div>
            <div id="alert-success-delete-data-curiculum"></div>

            @if (session('success-import-data-sub-bab'))
                @include('components.alert.success-insert-data', [
                    'message' => session('success-import-data-sub-bab'),
                ])
            @endif
            <main>
                <section class="bg-white shadow-lg p-6 rounded-lg border-gray-200 border-[1px]">
                    <!---- BulkUpload Button  ---->
                    <div class="flex justify-end mb-10 lg:mb-0">
                        <button
                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold h-8 px-6 rounded-lg shadow-md transition-all text-sm flex gap-2 items-center justify-center"
                            onclick="my_modal_4_.showModal()">
                            <i class="fa-solid fa-circle-plus"></i>
                            Bulk Upload
                        </button>
                    </div>
                    <!---- Form input kurikulum  ---->
                    <form action="{{ route('kurikulum.store') }}" method="POST">
                        @csrf
                        <label class="text-sm">Nama Kurikulum</label>
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
                    <dialog id="my_modal_4_" class="modal">
                        <div class="modal-box bg-white w-max">
                            <span class="text-md flex justify-center font-bold opacity-70">Upload Syllabus</span>
                            <form action="{{ route('syllabus.bulkupload.sub-bab') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="w-full mt-8">
                                    <div class="w-full h-auto">
                                        <div class="text-xs mt-1">
                                            <span>Maksimum ukuran file 10MB. <br> File dapat dalam format .xlsx.</span>
                                        </div>
                                        <div class="upload-icon">
                                            <div class="flex flex-col max-w-[260px]">
                                                <div id="excelPreview" class="max-w-[280px] cursor-pointer mt-4">
                                                    <div id="excelPreviewContainer-civitas-data-sekolah"
                                                        class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                        <div class="flex items-center">
                                                            <img id="pdfLogo-civitas-data-sekolah"
                                                                class="w-[56px] h-max">
                                                            <div class="mt-2 leading-5">
                                                                <span id="textPreview-civitas-data-sekolah"
                                                                    class="font-bold text-sm"></span><br>
                                                                <span id="textSize-civitas-data-sekolah"
                                                                    class="text-xs"></span>
                                                                <span id="textCircle-civitas-data-sekolah"
                                                                    class="relative top-[-2px] text-[5px]"></span>
                                                                <span id="textPages-civitas-data-sekolah"
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
                                        <label for="file-upload-civitas-data-sekolah"
                                            class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            <span>Upload File</span>
                                        </label>
                                        <input id="file-upload-civitas-data-sekolah" name="file" class="hidden"
                                            onchange="previewExcel(event, 'civitas-data-sekolah')" type="file"
                                            accept=".xlsx, .xls, .csv">
                                    </div>
                                    @if (session('formError') === 'import-sub-bab')
                                        <div
                                            class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-2 h-max max-h-96 overflow-y-auto">
                                            <p class="font-bold">Terjadi kesalahan dalam import data:</p>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>- {{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
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

<script src="{{ asset('js/syllabus-services/paginate-syllabus-kurikulum-ajax.js') }}"></script>
<script src="{{ asset('js/upload-excel.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.Echo.channel('syllabus')
            .listen('.syllabus.crud', (event) => {
                fetchFilteredDataSyllabusCuriculum();
            });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            document.getElementById('alertSuccess').remove();
        }, 3000);

        document.getElementById('btnClose').addEventListener('click', function() {
            document.getElementById('alertSuccess').remove();
        })
    });
</script>

<script>
    function historyCuriculum(element) {
        const modal = document.getElementById('my_modal_2');
        const namaLengkap = element.getAttribute('data-nama_lengkap');
        const status = element.getAttribute('data-status');
        const updatedAt = element.getAttribute('data-updated_at');

        document.getElementById('text-nama_lengkap').innerText = namaLengkap;
        document.getElementById('text-status').innerText = status;
        document.getElementById('text-updated_at').innerText = updatedAt;

        modal.showModal();
    }

    function closeModal() {
        const closeModal = document.getElementById('my_modal_3');
        closeModal.close();
    }
</script>


@if (session('formErrorId'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modalId = "my_modal_1_" + {{ session('formErrorId') }};
            let modal = document.getElementById(modalId);
            if (modal) {
                modal.showModal();
            }
        });
    </script>
@endif

@if (session('formError') === 'import-sub-bab')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalId = 'my_modal_4_';
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.showModal();
            }
        })
    </script>
@endif


<!---- buat hapus border dan text error ketika after validasi ------>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek semua inputan dan hapus error message ketika user mengetik
        document.querySelectorAll('input, select, textarea').forEach(function(el) {
            el.addEventListener('input', function() {
                // Hapus error class
                el.classList.remove('border-red-400');
                const errorMessage = el.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('text-red-500')) {
                    errorMessage.textContent = '';
                }
            });
        });
    });
</script>
