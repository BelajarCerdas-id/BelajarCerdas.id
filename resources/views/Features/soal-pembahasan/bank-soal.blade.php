@include('components/sidebar_beranda', ['headerSideNav' => 'Bank Soal'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <main>
                <section class="bg-white shadow-lg border h-96 rounded-lg">
                    <!--- button bulkupload soal pembahasan --->
                    <div class="flex justify-end py-10 px-6">
                        <button
                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold h-8 px-6 rounded-lg shadow-md transition-all text-sm flex gap-2 items-center justify-center"
                            onclick="my_modal_1.showModal()">
                            <i class="fa-solid fa-circle-plus"></i>
                            Bulk Upload Soal
                        </button>
                    </div>

                    <!--- modal bulkupload soal pembahasan --->
                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white w-max">
                            <span class="text-md flex justify-center font-bold opacity-70">Upload Soal</span>
                            <form action="{{ route('syllabus.bulkupload.sub-bab') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="w-full mt-8">
                                    <div class="w-full h-auto">
                                        <div class="text-xs mt-1">
                                            <span>Maksimum ukuran file 10MB. <br> File dapat dalam format .docx.</span>
                                        </div>
                                        <div class="upload-icon">
                                            <div class="flex flex-col max-w-[260px]">
                                                <div id="wordPreview" class="max-w-[280px] cursor-pointer mt-4">
                                                    <div id="wordPreviewContainer-bulkUpload-word"
                                                        class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                        <div class="flex items-center">
                                                            <img id="logo-bulkUpload-word" class="w-[56px] h-max">
                                                            <div class="mt-2 leading-5">
                                                                <span id="textPreview-bulkUpload-word"
                                                                    class="font-bold text-sm"></span><br>
                                                                <span id="textSize-bulkUpload-word"
                                                                    class="text-xs"></span>
                                                                <span id="textCircle-bulkUpload-word"
                                                                    class="relative top-[-2px] text-[5px]"></span>
                                                                <span id="textPages-bulkUpload-word"
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
                                        <label for="file-bulkUpload-word"
                                            class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            <span>Upload File</span>
                                        </label>
                                        <input id="file-bulkUpload-word" name="bulkUpload-soal-pembahasan"
                                            class="hidden" onchange="previewWord(event, 'bulkUpload-word')"
                                            type="file" accept=".docx">
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
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/upload-word.js') }}"></script>
